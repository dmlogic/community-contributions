<?php

namespace Tests\Feature;

use App\Models\Ledger;
use Tests\FeatureTest;
use Tests\SeedsCampaigns;
use App\Enums\LedgerTypes;
use App\Models\CampaignRequest;

class SendReconcileAlertCommandTest extends FeatureTest
{
    use SeedsCampaigns;

    public function setUp(): void
    {
        parent::setUp();
        $this->seedCampaigns();
    }

    public function test_command_runs(): void
    {
        $this->artisan('reconcile:alert')->assertSuccessful();

        $ledger = Ledger::factory()->create(['fund_id' => $this->seedData['fund']->id, 'type' => LedgerTypes::RESIDENT_OFFLINE->value, 'verified_at' => null]);
        CampaignRequest::latest()->update(['ledger_id' => $ledger->id]);
        $this->artisan('reconcile:alert')->assertSuccessful();
    }
}
