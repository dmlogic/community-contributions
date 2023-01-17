<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['address'];

    public function address(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return implode(' ', array_filter([
                    $attributes['number'],
                    $attributes['street'],
                ]));
            }
        );


    }

    public function resident()
    {
        return $this->belongsTo(User::class, 'user_id')
                    ->select('id', 'name', 'email' );
    }
}
