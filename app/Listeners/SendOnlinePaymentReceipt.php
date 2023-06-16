<?php

namespace App\Listeners;

use App\Models\Ledger;
use App\Events\OnlinePaymentReceived;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\OnlinePaymentReceipt;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOnlinePaymentReceipt implements ShouldQueue
{
    use InteractsWithQueue;

    public Ledger $ledger;

    public function handle(OnlinePaymentReceived $event): void
    {
        $this->ledger = $event->ledger;
        $this->ledger->user->notify(new OnlinePaymentReceipt($this->ledger));
    }

}
