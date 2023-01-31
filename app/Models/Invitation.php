<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('code', $value)->firstOrFail();
    }

    // ------------------------------------------------------------------------
    // Domain actions

    public function convertToUser(?string $password): User
    {
        $user = User::newUser($this->name, $this->email, $password);

        if ($this->role_id) {
            $user->roles()->attach($this->role_id);
        }

        if ($this->property_id && $property = Property::find($this->property_id)) {
            $property->user_id = $user->id;
            $property->save();
        }

        $this->delete();

        return $user;
    }

    // ------------------------------------------------------------------------
    // Relationships

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'id', 'user_id');
    }
}
