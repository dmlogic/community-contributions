<?php

namespace App\Models;

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
        $user = User::newUser($this->name, $this->email);

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
}
