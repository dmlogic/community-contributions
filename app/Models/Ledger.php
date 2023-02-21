<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = [
        'value',
    ];

    public static function forFund(int $fundId, ?string $filter): Builder
    {
        $query = Ledger::with('user')
                     ->where('fund_id', '=', $fundId)
                     ->latest();
        if ($filter === 'unverified') {
            $query->whereNull('verified_at');
        }

        return $query;
    }

    // ------------------------------------------------------------------------
    // Custom attributes

    public function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return (string) new Money($attributes['amount'] ?? 0);
            }
        );
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function fund(): BelongsTo
    {
        return $this->belongsTo(Fund::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
