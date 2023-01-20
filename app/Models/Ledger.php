<?php

namespace App\Models;

use App\Models\Fund;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ledger extends Model
{
    use HasFactory;

    protected $table = 'ledger';
    protected $guarded = [];

    protected $casts = [
        'amount' =>  'integer'
    ];

    // ------------------------------------------------------------------------
    // Relationships

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }
}
