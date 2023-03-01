<?php

namespace Tests\Feature;

use App\Enums\LedgerTypes;
use App\Enums\Roles;
use App\Models\CampaignRequest;
use App\Models\User;
use App\Models\Ledger;
use App\Models\Member;
use Tests\FeatureTest;
use App\Models\Property;
use Tests\SeedsCampaigns;
use App\Models\Invitation;
use Inertia\Testing\AssertableInertia;

class DashboardTest extends FeatureTest
{
    use SeedsCampaigns;

    public function setUp(): void
    {
        parent::setUp();
        $this->seedCampaigns();
        $this->actingAs($this->seedData['members'][0]);
    }

    public function test_dash_contains_resident_data(): void
    {
        $this->get(route('dashboard'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('funds')
                ->has('funds.1.campaigns')
                ->has('funds.1.campaigns.0.requests')
                ->where('funds.1.campaigns.0.requests.0.amount', 50)
            );
    }

    public function test_dash_contains_admin_data(): void
    {
        $ledger = Ledger::create([
            'fund_id' => $this->seedData['fund']->id,
            'user_id' => $this->seedData['members'][0]->id,
            'created_by' => $this->seedData['members'][0]->id,
            'type' => LedgerTypes::RESIDENT_OFFLINE->value,
            'verified_at' => null,
            'amount' => 50
        ]);

        CampaignRequest::where('user_id','=',$this->seedData['members'][0]->id)->update(['ledger_id' => $ledger->id]);

        $this->actingAs($this->adminUser())->get(route('dashboard'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('reconcile.0.name')
        );
        ;
    }
}
