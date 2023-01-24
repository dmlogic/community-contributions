<?php

namespace App\Models;

use App\Models\Fund;
use App\Models\CampaignRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['fund'];
    protected $appends = ['target_value', 'raised_value'];

    protected $casts = [
        'target' =>  'integer',
        'raised' =>  'integer',
        'closed_at' => 'datetime',
    ];

    public function targetValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return new Money($attributes['target']);
            }
        );
    }

    public function raisedValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return new Money($attributes['raised']);
            }
        );
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }

    public function requests()
    {
        return $this->hasMany(CampaignRequest::class);
    }
}
