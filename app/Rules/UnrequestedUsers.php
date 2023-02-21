<?php

namespace App\Rules;

class UnrequestedUsers extends RequestedUsers
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __invoke($attribute, $value, $fail): void
    {
        if ($this->countRequestedUsers($value)) {
            $fail('One or members already has a request');
        }
    }
}
