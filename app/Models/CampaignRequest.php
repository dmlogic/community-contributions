<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignRequest extends Model
{
    protected $table = 'campaign_requests';
    protected $guarded = [];

    protected $casts = [
        'notified_at' => 'datetime',
    ];

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
