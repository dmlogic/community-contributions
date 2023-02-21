<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['fund'];

    protected $appends = ['target_value', 'raised_value'];

    protected $casts = [
        'target' => 'integer',
        'raised' => 'integer',
        'closed_at' => 'datetime',
    ];

    public function targetValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return (string) new Money($attributes['target'] ?? 0);
            }
        );
    }

    public function raisedValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return (string) new Money($attributes['raised'] ?? 0);
            }
        );
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function fund(): BelongsTo
    {
        return $this->belongsTo(Fund::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(CampaignRequest::class);
    }
}
