<?php

namespace App\Listeners;

use App\Notifications\FundingReminder;

class SendFundingReminderNotifications extends SendFundingRequestNotifications
{
    public function sendNotifications(): void
    {
        foreach ($this->requests as $request) {
            $this->users[$request->user_id]
                 ->notify(new FundingReminder($request));
        }
    }
}
