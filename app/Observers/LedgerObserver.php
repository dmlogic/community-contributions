<?php

namespace App\Observers;

use App\Enums\LedgerTypes;
use App\Models\Fund;
use App\Models\Ledger;

class LedgerObserver
{
    public function creating(Ledger $ledger): void
    {
        if($ledger->type !== LedgerTypes::RESIDENT_OFFLINE->value) {
            $ledger->verified_at = now();
        }
    }

    public function created(Ledger $ledger): void
    {
        if(!$ledger->verified_at) {
            return;
        }
        $this->updateFund($ledger->fund, $ledger->amount);
    }

    public function deleting(Ledger $ledger): void
    {
        if(!$ledger->verified_at) {
            return;
        }
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
