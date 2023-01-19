<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ------------------------------------------------------------------------
    // Domain actions

    public function convertToUser(): User
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => now(),
            'password' => Hash::make( Str::random(40)),
        ]);

        if($this->role_id) {
            $user->roles()->attach($this->role_id);
        }

        if($this->property_id && $property = Property::find($this->property_id)) {
            $property->user_id = $user->id;
            $property->save();
        }

        $this->delete();
        return $user;
    }

    // ------------------------------------------------------------------------
    // Events

    protected static function booted()
    {
        static::creating(function ($invitation) {
            if(empty($invitation->code)) {
                $invitation->code = (string) Uuid::uuid4();
            }
            return $invitation;
        });
    }
}
