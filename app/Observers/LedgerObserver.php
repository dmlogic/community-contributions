<?php

namespace App\Observers;

use App\Models\Fund;
use App\Models\Ledger;

class LedgerObserver
{
    public function created(Ledger $ledger): void
    {
        $this->updateFund($ledger->fund, $ledger->amount);
    }

    public function deleting(Ledger $ledger): void
    {
        $this->updateFund($ledger->fund, ($ledger->amount * -1));
    }

    private function updateFund(Fund $fund, int $adjustment): void
    {
        if(!$fund) {
            return;
        }
        $fund->balance += $adjustment;
        $fund->save();
    }

}
