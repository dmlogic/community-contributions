<?php

namespace App\Events;

use App\Models\Ledger;
use App\Models\CampaignRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CampaignContributionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CampaignRequest $campaignReqest;

    public Ledger $ledgerEntry;

    public function __construct(CampaignRequest $campaignReqest, Ledger $ledgerEntry)
    {
        $this->campaignReqest = $campaignReqest;
        $this->ledgerEntry = $ledgerEntry;
    }
}
