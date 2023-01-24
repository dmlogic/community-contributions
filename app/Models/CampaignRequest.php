<?php

namespace App\Models;

use App\Models\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
