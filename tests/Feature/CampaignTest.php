<?php

namespace Tests\Feature;

use App\Models\Ledger;
use Tests\FeatureTest;
use App\Models\Campaign;
use Tests\SeedsCampaigns;
use App\Models\CampaignRequest;
use App\Notifications\FundingRequest;
use App\Notifications\FundingReminder;
use Inertia\Testing\AssertableInertia;
use Illuminate\Support\Facades\Notification;

class CampaignTest extends FeatureTest
{
    use SeedsCampaigns;

    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->adminUser());
        $this->seedCampaigns();
    }

    public function test_non_admin_cannot_access(): void
    {
        $this->actingAs($this->supplierUser())
             ->get(route('campaign.index'))
             ->assertForbidden();
    }

    public function test_campaigns_are_listed(): void
    {
        $response = $this->get(route('campaign.index'))

            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('campaigns')
                ->count('campaigns', 1)
                ->where('campaigns.0.description', $this->seedData['campaign']->description)
                ->where('campaigns.0.fund.name', $this->seedData['campaign']->fund->name)
            );
    }

    public function test_campaign_view_includes_requests_and_residents(): void
    {
        $response = $this->get(route('campaign.show', $this->seedData['campaign']->id))
                        ->assertInertia(fn (AssertableInertia $page) => $page
                        ->has('campaign')
                        ->has('campaign.fund')
                        ->has('requests')
                        ->has('requests.0.user_id')
                        ->has('residents')
                        ->has('residents.0.name')
                        );
    }

    public function test_can_create_campaign(): void
    {
        $campaign = Campaign::factory()->make(['fund_id' => $this->seedData['fund']->id]);
        $response = $this->post(route('campaign.store'), $campaign->toArray());
        $created = Campaign::whereDescription($campaign->description)->first();
        $response->assertRedirectToRoute('campaign.show', [$created->id]);
    }

    public function test_can_update_campaign(): void
    {
        $this->seedData['campaign']->description = 'edited';
        $response = $this->patch(route('campaign.update', $this->seedData['campaign']->id),
            $this->seedData['campaign']->toArray()
        )
        ->assertSessionHas('success');

        $this->assertDatabaseHas('campaigns', ['id' => $this->seedData['campaign']->id, 'description' => 'edited']);
    }

    public function test_can_add_member_requests_to_campaign(): void
    {
        Notification::fake();

        $this->post(route('campaign.new-request', $this->seedData['campaign']->id), [
            'amount' => 50,
            'members' => [$this->seedData['members'][3]->id, $this->seedData['members'][4]->id],
        ])
            ->assertSessionHas('success');

        Notification::assertSentTo(
            [$this->seedData['members'][3], $this->seedData['members'][4]], FundingRequest::class
        );
        Notification::assertNotSentTo(
            [$this->seedData['members'][1]], FundingRequest::class
        );
    }

    public function test_cannot_add_a_member_to_a_campaign_twice(): void
    {
        $this->post(route('campaign.new-request', $this->seedData['campaign']->id), [
            'amount' => 50,
            'members' => [$this->seedData['members'][1]->id],
        ])
        ->assertInvalid();
    }

    public function test_can_resend_notifications(): void
    {
        Notification::fake();

        $this->post(route('campaign.remind-request', $this->seedData['campaign']->id), [
            'members' => [$this->seedData['members'][1]->id, $this->seedData['members'][2]->id],
        ])
            ->assertSessionHas('success');

        Notification::assertSentTo(
            [$this->seedData['members'][1], $this->seedData['members'][2]], FundingReminder::class
        );
        Notification::assertNotSentTo(
            [$this->seedData['members'][3]], FundingReminder::class
        );
    }

    public function test_cannot_remind_members_without_requests(): void
    {
        CampaignRequest::where('user_id', $this->seedData['members'][1]->id)->delete();
        $this->post(route('campaign.remind-request', $this->seedData['campaign']->id), [
            'members' => [$this->seedData['members'][1]->id],
        ])
        ->assertInvalid();
    }

    public function test_can_delete_member_requests_from_campaign(): void
    {
        $this->delete(route('campaign.delete-request', $this->seedData['campaign']->id), [
            'members' => [$this->seedData['members'][1]->id, $this->seedData['members'][2]->id],
        ])
        ->assertSessionHas('success');

        $this->assertEquals(1, CampaignRequest::where('campaign_id', $this->seedData['campaign']->id)->count());
    }

    public function test_cannot_delete_paid_requests()
    {
        $request = CampaignRequest::first();
        $ledger = Ledger::factory()->create([
            'user_id' => $request->user_id,
            'fund_id' => $this->seedData['fund']->id,
            'verified_at' => now(),
        ]);
        $request->ledger_id = $ledger->id;
        $request->save();
        $this->delete(route('campaign.delete-request', $this->seedData['campaign']->id), [
            'members' => [$request->user_id],
        ])
        ->assertInvalid();
    }

    public function test_cannot_delete_campaign_with_activity()
    {
        $request = CampaignRequest::first();
        $request->ledger_id = 1;
        $request->save();
        $this->delete(route('campaign.destroy', $this->seedData['campaign']->id))
             ->assertInvalid();
    }

    public function test_can_delete_campaign(): void
    {
        $this->delete(route('campaign.destroy', $this->seedData['campaign']->id))
             ->assertSessionHas('success');
        $this->assertDatabaseMissing('campaigns', ['id' => $this->seedData['campaign']->id]);
    }

    public function test_can_close_campaign(): void
    {
        $this->patch(route('campaign.close', $this->seedData['campaign']->id))
             ->assertSessionHas('success');
        $this->assertDatabaseMissing('campaigns', ['id' => $this->seedData['campaign']->id, 'closed_at' => null]);
    }

    public function test_forms_render()
    {
        $this->get(route('campaign.create'))
             ->assertOk();

        $this->get(route('campaign.edit', $this->seedData['campaign']->id))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('campaign.description', $this->seedData['campaign']->description)
            );
    }
}
