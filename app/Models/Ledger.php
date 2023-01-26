<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ledger extends Model
{
    use HasFactory;

    protected $table = 'ledger';

    protected $guarded = [];

    protected $casts = [
        'amount' => 'integer',
        'verified_at' => 'datetime',
    ];

    // ------------------------------------------------------------------------
    // Relationships

    public function fund(): BelongsTo
    {
        return $this->belongsTo(Fund::class);
    }
}
