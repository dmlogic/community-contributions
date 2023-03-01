<?php

namespace App\Observers;

use App\Models\Fund;
use App\Models\Ledger;
use App\Models\Campaign;
use App\Enums\LedgerTypes;

class LedgerObserver
{
    public function creating(Ledger $ledger): void
    {
        $this->markAsVerified($ledger);

        // A record always belongs to the authenticated user unless
        // otherwise specified by an admin
        if (request()->user() && ! request()->user()->isAdmin()) {
            $ledger->user_id = request()->user()->id;
        }

        // Attribute the creation of a record to the current user,
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

    public function updated(Ledger $ledger)
    {
        // @codeCoverageIgnoreStart
        if ($ledger->getOriginal('verfied_at') || ! $ledger->verified_at) {
            return;
        }
        // @codeCoverageIgnoreEnd
        $this->updateFund($ledger->fund, $ledger->amount);
        if ($ledger->request) {
            $this->updateCampaignTotal($ledger->request->campaign, $ledger->amount);
        }
    }

    public function deleting(Ledger $ledger): void
    {
        if (! $ledger->verified_at) {
            return;
        }
        $this->updateFund($ledger->fund, ($ledger->amount * -1));
    }

    private function markAsVerified(Ledger $ledger): void
    {
        // A record is always considered verified unless it's a
        // resident advising an offline payment has been made
        if (
            $ledger->type !== LedgerTypes::RESIDENT_OFFLINE->value ||
            (auth()->check() && auth()->user()->isAdmin())
        ) {
            $ledger->verified_at = now();
        }
    }

    private function updateFund(Fund $fund, int $adjustment): void
    {
        $fund->balance += $adjustment;
        $fund->save();
    }

    private function updateCampaignTotal(Campaign $campaign, int $amount): void
    {
        $campaign->increment('raised', $amount);
    }
}
