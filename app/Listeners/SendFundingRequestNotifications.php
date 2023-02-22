<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Campaign;
use App\Notifications\FundingRequest;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CampaignRequestsGenerated;
use App\Models\CampaignRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;

class SendFundingRequestNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public Campaign $campaign;

    public Collection $requests;

    public Collection $users;

    public function handle(CampaignRequestsGenerated $event): void
    {
        $this->campaign = $event->campaign;
        $this->requests = $event->requests;
        $this->eagerLoadUsers();
        $this->sendNotifications();
        $this->updateNotificationDate();
    }

    public function updateNotificationDate()
    {
        CampaignRequest::whereIn('id', $this->requests->pluck('id'))->update(['notified_at' => now()]);
    }

    public function sendNotifications(): void
    {
        foreach ($this->requests as $request) {
            $this->users[$request->user_id]
                 ->notify(new FundingRequest($request));
        }
    }

    public function eagerLoadUsers(): void
    {
        $this->users = User::whereIn('id', $this->requests->pluck('user_id'))
                    ->get()
                    ->keyBy('id');
    }
}
