<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CampaignRequest extends Model
{
    protected $table = 'campaign_requests';

    protected $guarded = [];

    protected $appends = ['value'];

    protected $casts = [
        'amount' => 'integer',
        'notified_at' => 'datetime',
    ];

    public static function loadFromHttpRequest(Request $request): ?CampaignRequest
    {
        if(!$request->input('request_id')) {
            return null;
        }
        $record = static::with('campaign')->find($request->input('request_id'));
        // This prevents us loading a request for someone else, whilst not throwing
        // and exception for a missing request
        if($record && $record->user_id !== $request->user()->id) {
            throw new ModelNotFoundException;
        }
        return $record;
    }

    // ------------------------------------------------------------------------
    // Custom attributes

    public function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return (string) new Money($attributes['amount']);
            }
        );
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function ledger(): BelongsTo
    {
        return $this->belongsTo(Ledger::class);
    }
}
