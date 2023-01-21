<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\Fund;
use App\Models\User;
use App\Models\Ledger;
use Tests\FeatureTest;
use App\Enums\LedgerTypes;

class LedgerTest extends FeatureTest
{

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
                'fund_id' => $fund->id
            ]);


            $this->post(route('ledger.store'),$ledger->toArray());
            $createdLedger = Ledger::orderBy('id', 'desc')->first();
            $this->assertSame($createdLedger->type, $ledger->type);
            $this->assertSame($createdLedger->description, $ledger->description);
            if($type === LedgerTypes::RESIDENT_OFFLINE) {
                $this->assertNull($createdLedger->verified_at);
            } else {
                $this->assertNotNull($createdLedger->verified_at);
                $balance += $amount;
            }
            $this->assertDatabaseHas('funds',['id' => $fund->id, 'balance' => $balance]);
        }
    }

    public function test_resident_can_only_add_certain_types()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Roles::RESIDENT->value);
        $fund = Fund::factory()->create();
        $this->actingAs($user);

        // Allowed
        $ledger = Ledger::factory()->make([
            'type' => LedgerTypes::RESIDENT_OFFLINE->value,
            'amount' => 100,
            'user_id' => $user->id,
            'fund_id' => $fund->id
        ]);
        $this->post(route('ledger.store'),$ledger->toArray())
                ->assertSessionHas('success');

        // Not allowed
        $ledger->type = LedgerTypes::ADMIN_ADJUSTMENT->value;
        $this->post(route('ledger.store'),$ledger->toArray())
                 ->assertInvalid();
    }

    public function test_admin_can_delete_entries()
    {
        $fund = Fund::factory()->create();
        $ledger = Ledger::factory()->create(['fund_id' => $fund->id, 'amount' => 101]);
        $this->actingAs($this->adminUser());
        $this->assertDatabaseHas('funds', ['id' => $fund->id, 'balance' => 101]);
        $this->delete(route('ledger.destroy', $ledger->id))
             ->assertValid();
        $this->assertDatabaseHas('funds', ['id' => $fund->id, 'balance' => 0]);
    }

    public function test_user_cannot_delete_entries()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Roles::RESIDENT->value);
        $fund = Fund::factory()->create();
        $this->actingAs($user);
        $ledger = Ledger::factory()->create(['fund_id' => $fund->id, 'amount' => 101]);
        $response = $this->delete(route('ledger.destroy', $ledger->id))
                        ->assertForbidden();
    }
}
