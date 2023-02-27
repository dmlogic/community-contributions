<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use App\Events\CampaignContributionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCampaignContribution implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(CampaignContributionCreated $event): void
    {
        $event->campaignReqest->update(['ledger_id' => $event->ledgerEntry->id]);
        if ($event->ledgerEntry->verified_at) {
            $event->campaignReqest->campaign->increment('raised', $event->ledgerEntry->amount);
        }
    }
}
