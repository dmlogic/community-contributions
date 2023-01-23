<?php

namespace App\Models;

use App\Models\Fund;
use App\Models\CampaignRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['fund'];

    protected $casts = [
        'target' =>  'integer',
        'raised' =>  'integer',
        'closed_at' => 'datetime',
    ];

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
