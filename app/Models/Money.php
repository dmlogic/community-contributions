<?php

namespace App\Models;

class Money
{
    private string $amount;

    private string $currency;

    public function __construct($amount)
    {
        $this->amount = number_format(($amount / 100), 2);
        $this->currency = config('app.currency', '£');
    }

    public function __toString()
    {
        return $this->currency.$this->amount;
    }
}
