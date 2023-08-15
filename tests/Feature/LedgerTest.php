<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\Fund;
use App\Models\User;
use App\Models\Ledger;
use Tests\FeatureTest;
use Tests\SeedsCampaigns;
use App\Enums\LedgerTypes;
use App\Models\CampaignRequest;
use Inertia\Testing\AssertableInertia;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OfflinePaymentVerified;
use Illuminate\Testing\Fluent\AssertableJson;

class LedgerTest extends FeatureTest
{
    use SeedsCampaigns;

    public function test_pagination_endpoint()
    {
        $this->actingAs($this->adminUser());

        $response = $this->getJson('ledger?fund_id=1&page=1');
        $response->assertJson(fn (AssertableJson $json) => $json->has('ledgers.current_page')
            ->has('ledgers.data')
            ->has('ledgers.next_page_url')
        );

        $response = $this->getJson('ledger?fund_id=1&page=1&filter=unverified');
        $response->assertJson(fn (AssertableJson $json) => $json->has('ledgers.data')
        );
    }

    public function test_ledger_form_renders()
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('ledger.create', ['fund_id' => 1]))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('fund')
                ->has('residents')
            );
    }

    public function test_admin_can_add_any_valid_type(): void
    {
        $this->actingAs($this->adminUser());

        $fund = Fund::factory()->create();
        $balance = $fund->balance;

        foreach (LedgerTypes::cases() as $type) {
            $amount = rand(-21, 101);
            $ledger = Ledger::factory()->make([
                'type' => $type->value,
                'amount' => $amount,
                'user_id' => 1,
                'fund_id' => $fund->id,
            ]);

            $balance += $amount;
            $this->post(route('ledger.store'), $ledger->toArray());
            $createdLedger = Ledger::orderBy('id', 'desc')->first();
            $this->assertSame($createdLedger->type, $ledger->type);
            $this->assertSame($createdLedger->description, $ledger->description);
            $this->assertNotNull($createdLedger->verified_at);
            $this->assertDatabaseHas('funds', ['id' => $fund->id, 'balance' => $balance]);
        }
    }

    public function test_resident_can_only_add_certain_types(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Roles::RESIDENT->value);
        $fund = Fund::factory()->create();
        $this->actingAs($user);

        // Allowed
        $ledger = Ledger::factory()->make([
            'type' => LedgerTypes::RESIDENT_OFFLINE->value,
            'amount' => 100,
            'fund_id' => $fund->id,
        ]);
        $this->post(route('ledger.store'), $ledger->toArray())
            ->assertSessionHas('success');

        $this->assertDatabaseHas('ledger', [
            'user_id' => $user->id,
            'created_by' => $user->id,
            'amount' => 100,
            'fund_id' => $fund->id,
            'verified_at' => null,
        ]);

        // Not allowed
        $ledger->type = LedgerTypes::ADMIN_ADJUSTMENT->value;
        $this->post(route('ledger.store'), $ledger->toArray())
            ->assertInvalid();
    }

    public function test_resident_can_contribute_to_a_specific_request(): void
    {
        $this->seedCampaigns();
        $this->actingAs($this->seedData['members'][0]);

        $fundRequest = CampaignRequest::where('user_id', $this->seedData['members'][0]->id)->first();
        $this->post(route('ledger.store', ['request_id' => $fundRequest->id]), [
            'type' => LedgerTypes::RESIDENT_OFFLINE->value,
            'fund_id' => $this->seedData['fund']->id,
            'amount' => 100,
        ])
            ->assertSessionHas('success');

        $createdLedger = Ledger::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('campaign_requests', ['id' => $fundRequest->id, 'ledger_id' => $createdLedger->id]);
    }

    public function test_admin_can_delete_entries(): void
    {
        $fund = Fund::factory()->create();
        $ledger = Ledger::factory()->create(['fund_id' => $fund->id, 'amount' => 101]);
        $this->actingAs($this->adminUser());
        $this->assertDatabaseHas('funds', ['id' => $fund->id, 'balance' => 101]);
        $this->delete(route('ledger.destroy', $ledger->id))
            ->assertValid();
        $this->assertDatabaseHas('funds', ['id' => $fund->id, 'balance' => 0]);
    }

    public function test_deleting_unverified_entries_does_not_update_fund(): void
    {
        $fund = Fund::factory()->create(['balance' => 20]);
        $ledger = Ledger::factory()->create(['fund_id' => $fund->id, 'type' => LedgerTypes::RESIDENT_OFFLINE->name]);
        $this->actingAs($this->adminUser());
        $this->assertDatabaseHas('funds', ['id' => $fund->id, 'balance' => 20]);
        $this->delete(route('ledger.destroy', $ledger->id))
            ->assertValid();
        $this->assertDatabaseHas('funds', ['id' => $fund->id, 'balance' => 20]);
    }

    public function test_user_cannot_delete_entries(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Roles::RESIDENT->value);
        $fund = Fund::factory()->create();
        $this->actingAs($user);
        $ledger = Ledger::factory()->create(['fund_id' => $fund->id, 'amount' => 101]);
        $response = $this->delete(route('ledger.destroy', $ledger->id))
            ->assertForbidden();
    }

    public function test_unverfied_entries_can_be_verified(): void
    {
        Notification::fake();
        $this->seedCampaigns();
        $ledger = Ledger::factory()->create([
            'fund_id' => $this->seedData['fund']->id,
            'user_id' => $this->seedData['members'][0]->id,
            'amount' => 101,
            'type' => LedgerTypes::RESIDENT_OFFLINE->value,
        ]);
        CampaignRequest::where('user_id', '=', $this->seedData['members'][0]->id)->update(['ledger_id' => $ledger->id]);
        $this->actingAs($this->adminUser());
        $this->patch(route('ledger.verify', $ledger->id))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('funds', ['id' => $this->seedData['fund']->id, 'balance' => 101]);
        Notification::assertSentTo(
            [$this->seedData['members'][0]], OfflinePaymentVerified::class
        );
    }

    public function test_verfied_entries_cannot_be_verified(): void
    {
        $this->actingAs($this->adminUser());
        $fund = Fund::factory()->create();
        $ledger = Ledger::factory()->create(['fund_id' => $fund->id, 'type' => LedgerTypes::RESIDENT_OFFLINE->value, 'verified_at' => now()]);
        $this->patch(route('ledger.verify', $ledger->id))
            ->assertInvalid();
    }
}
