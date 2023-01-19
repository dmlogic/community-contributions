<?php

namespace App\Models;

class Member extends User
{
    protected $table = 'users';

    // ------------------------------------------------------------------------
    // Helpers

    public static function memberData()
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
