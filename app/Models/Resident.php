<?php

namespace App\Models;


class Resident extends User
{
    protected $table = 'users';

    // ------------------------------------------------------------------------
    // Helpers

    public static function residentData()
    {
        return static::select('id', 'name', 'email' )
                     ->get();
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function property()
    {
        return $this->belongsTo(Property::class, 'id', 'user_id');
    }
}
