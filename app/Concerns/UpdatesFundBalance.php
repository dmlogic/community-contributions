<?php

namespace App\Concerns;

use App\Models\Fund;

trait UpdatesFundBalance
{
    private function updateFund(Fund $fund, int $adjustment): void
    {
        $fund->balance += $adjustment;
        $fund->save();
    }
}
