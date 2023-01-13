<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Notifications\Notifiable;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function isAdmin(): bool
    {
        return $this->roles
                ->where('name', Role::ROLE_ADMIN)
                ->isNotEmpty();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
