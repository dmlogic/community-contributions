<?php

namespace App\Events;

use App\Models\Campaign;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CampaignRequestsGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Campaign $campaign;
    public Collection $requests;

    public function __construct(Campaign $campaign, Collection $requests)
    {
        $this->campaign = $campaign;
        $this->requests = $requests;
    }

}
