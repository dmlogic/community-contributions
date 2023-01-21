<?php

namespace App\Observers;

use App\Models\Ledger;
use App\Enums\LedgerTypes;
use App\Concerns\UpdatesFundBalance;

class LedgerObserver
{
    use UpdatesFundBalance;

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



}
