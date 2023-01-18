<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ------------------------------------------------------------------------
    // Helpers

    public static function residentData()
    {
        return static::select('id', 'name', 'email' )
                     ->get();
    }

    public function isAdmin(): bool
    {
        return $this->roles
                ->where('name', Role::ROLE_ADMIN)
                ->isNotEmpty();
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
