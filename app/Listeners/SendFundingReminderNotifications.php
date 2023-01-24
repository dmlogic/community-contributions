<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Campaign;
use App\Notifications\FundingRequest;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CampaignRequestsGenerated;
use App\Notifications\FundingReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;

class SendFundingReminderNotifications extends SendFundingRequestNotifications
{
    public function sendNotifications(): void
    {
        foreach($this->requests as $request) {
            $this->users[$request->user_id]
                 ->notify(new FundingReminder($request));
        }
    }
}
