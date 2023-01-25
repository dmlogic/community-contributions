<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\Fund;
use App\Models\User;
use Tests\FeatureTest;
use App\Models\Campaign;
use Tests\SeedsCampaigns;
use App\Models\CampaignRequest;
use Illuminate\Support\Facades\DB;
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
                        ->has('campaign.requests')
                        ->has('campaign.requests.0.user_id')
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

    public function test_can_delete_member_requests_from_campaign(): void
    {
        $this->delete(route('campaign.delete-request', $this->seedData['campaign']->id), [
            'members' => [$this->seedData['members'][1]->id, $this->seedData['members'][2]->id],
        ])
        ->assertSessionHas('success');

        $this->assertEquals(1, CampaignRequest::where('campaign_id', $this->seedData['campaign']->id)->count());
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

    // ------------------------------------------------------------------------

    protected function seedCampaign()
    {
        $this->seedData['fund'] = Fund::factory()->create();
        $this->seedData['campaign'] = Campaign::factory()->create(['fund_id' => $this->seedData['fund']->id]);

        $members = User::factory()->count(5)->create();
        DB::table('role_user')->insert([
            ['user_id' => $members[0]->id, 'role_id' => Roles::RESIDENT->value],
            ['user_id' => $members[1]->id, 'role_id' => Roles::RESIDENT->value],
            ['user_id' => $members[2]->id, 'role_id' => Roles::RESIDENT->value],
        ]);
        CampaignRequest::insert([
            ['user_id' => $members[0]->id, 'campaign_id' => $this->seedData['campaign']->id, 'amount' => 50],
            ['user_id' => $members[1]->id, 'campaign_id' => $this->seedData['campaign']->id, 'amount' => 50],
            ['user_id' => $members[2]->id, 'campaign_id' => $this->seedData['campaign']->id, 'amount' => 50],
        ]);
        $this->seedData['members'] = $members;
    }
}
