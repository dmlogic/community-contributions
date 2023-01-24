<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\InvokableRule;

class RequestedUsers implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail): void
    {
        if($this->countRequestedUsers($value) !== count($value)) {
            $fail('One or members does not have a request');
        }
    }

    public function countRequestedUsers($userIds, $withLedgerEntry = false): int
    {
        $query = DB::table('campaign_requests')
                    ->where('campaign_id','=',request()->route('campaign')->id)
                    ->whereIn('user_id', $userIds);

        if($withLedgerEntry) {
            $query->whereNotNull('ledger_id');
        }

        return (int) $query->count();
    }
}
