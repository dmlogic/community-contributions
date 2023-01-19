<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ledger extends Model
{
    use HasFactory;

    protected $table = 'ledger';
    protected $guarded = [];

    protected $casts = [
        'amount' =>  'decimal:2'
    ];
}
