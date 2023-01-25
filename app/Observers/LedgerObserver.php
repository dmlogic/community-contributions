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
        if ($ledger->type !== LedgerTypes::RESIDENT_OFFLINE->value) {
            $ledger->verified_at = now();
        }

        if (request()->user() && ! request()->user()->isAdmin()) {
            $ledger->user_id = request()->user()->id;
        }

        // Attribute a ledger entry the current user,
        // fallback to the primary user
        if (! $ledger->created_by) {
            $ledger->created_by = auth()->user()?->id ?? 1;
        }
    }

    public function created(Ledger $ledger): void
    {
        if (! $ledger->verified_at) {
            return;
        }
        $this->updateFund($ledger->fund, $ledger->amount);
    }

    public function deleting(Ledger $ledger): void
    {
        if (! $ledger->verified_at) {
            return;
        }
        $this->updateFund($ledger->fund, ($ledger->amount * -1));
    }
}
