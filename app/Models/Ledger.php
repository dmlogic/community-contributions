<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' =>  'decimal:2'
    ];
}
