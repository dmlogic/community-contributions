<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignRequest extends Model
{
    protected $table = 'campaign_requests';

    protected $guarded = [];

    protected $appends = ['value'];

    protected $casts = [
        'amount' => 'integer',
        'notified_at' => 'datetime',
    ];

    // ------------------------------------------------------------------------
    // Custom attributes

    public function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return new Money($attributes['amount']);
            }
        );
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
