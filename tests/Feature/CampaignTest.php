<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\Fund;
use App\Models\User;
use Tests\FeatureTest;
use App\Models\Campaign;
use App\Models\CampaignRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia;

class CampaignTest extends FeatureTest
{
    private array $seedData = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->adminUser());
        $this->seedCampaign();
    }

    public function test_non_admin_cannot_access(): void
    {
        $this->actingAs( $this->supplierUser() )
             ->get(route('campaign.index'))
             ->assertForbidden();
    }

    public function test_campaigns_are_listed(): void
    {
        $response = $this->get(route('campaign.index'))

            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('campaigns')
                ->count('campaigns',1)
                ->where('campaigns.0.description', $this->seedData['campaign']->description)
                ->where('campaigns.0.fund.name', $this->seedData['campaign']->fund->name)
            );
    }

    public function test_campaign_view_includes_requests_and_residents(): void
    {
        $response = $this->get(route('campaign.show',$this->seedData['campaign']->id ))
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
        $response = $this->post( route('campaign.store'), $campaign->toArray() );
        $created = Campaign::whereDescription($campaign->description)->first();
        $response->assertRedirectToRoute('campaign.show',[$created->id]);
    }

    public function test_can_add_member_requests_to_campaign(): void
    {
        // see request records created
        // see notifications sent
        $this->markTestIncomplete();
    }

    public function test_can_resend_notifications(): void
    {
        $this->markTestIncomplete();
    }

    public function test_can_delete_member_requests_from_campaign(): void
    {
        $this->markTestIncomplete();
    }

    public function test_can_delete_campaign(): void
    {
        // but not if requests have been fulfilled
        $this->markTestIncomplete();
    }

    private function seedCampaign()
    {
        $this->seedData['fund'] = Fund::factory()->create();
        $this->seedData['campaign'] = Campaign::factory()->create(['fund_id' => $this->seedData['fund']->id]);

        $members = User::factory()->count(3)->create();
        DB::table('role_user')->insert([
            ['user_id' => $members[0]->id, 'role_id' => Roles::RESIDENT->value],
            ['user_id' => $members[1]->id, 'role_id' => Roles::RESIDENT->value],
            ['user_id' => $members[2]->id, 'role_id' => Roles::RESIDENT->value],
        ]);
        CampaignRequest::insert([
            ['user_id' => $members[0]->id, 'campaign_id' => $this->seedData['campaign']->id],
            ['user_id' => $members[1]->id, 'campaign_id' => $this->seedData['campaign']->id],
            ['user_id' => $members[2]->id, 'campaign_id' => $this->seedData['campaign']->id],
        ]);
        $this->seedData['members'] = $members;
    }

}
