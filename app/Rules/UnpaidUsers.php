<?php

namespace App\Rules;

class UnpaidUsers extends RequestedUsers
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
        if($this->countRequestedUsers($value, true)) {
            $fail('One or members has paid this request and cannot be deleted');
        }
    }
}
