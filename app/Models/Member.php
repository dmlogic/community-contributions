<?php

namespace App\Models;

use App\Enums\Roles;
use Illuminate\Database\Eloquent\Builder;

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

    public static function residents()
    {
        return static::select('id', 'name', 'email' )
                     ->whereHas('roles', function (Builder $query) {
                        $query->where('role_id', '=', Roles::RESIDENT->value);
                    })->get();
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function property()
    {
        return $this->belongsTo(Property::class, 'id', 'user_id');
    }
}
