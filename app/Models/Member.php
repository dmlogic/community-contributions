<?php

namespace App\Models;

use App\Enums\Roles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends User
{
    protected $table = 'users';

    // ------------------------------------------------------------------------
    // Helpers

    public static function residents(): Collection
    {
        return static::select('id', 'name', 'email')
            ->whereHas('roles', function (Builder $query) {
                $query->where('role_id', '=', Roles::RESIDENT->value);
            })->get();
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'id', 'user_id');
    }
}
