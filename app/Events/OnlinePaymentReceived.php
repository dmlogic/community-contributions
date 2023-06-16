<?php

namespace App\Events;

use App\Models\Ledger;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OnlinePaymentReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Ledger $ledger;

    public function __construct(Ledger $ledger)
    {
        $this->ledger = $ledger;
    }
}
