<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['address'];

    // ------------------------------------------------------------------------
    // Helpers

    public static function listData()
    {
        return static::with('member')
                    ->select('id', 'number', 'street', 'user_id')
                    ->orderBy('number')
                    ->get();
    }

    public static function defaultData()
    {
        return new static([
            'number' => '',
            'street' => config('app.default_address.steet'),
            'town' => config('app.default_address.town'),
            'postcode' => config('app.default_address.postcode'),
        ]);
    }

    // ------------------------------------------------------------------------
    // Custom attributes

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

    // ------------------------------------------------------------------------
    // Relationships

    public function member()
    {
        return $this->belongsTo(User::class, 'user_id')
                    ->select('id', 'name', 'email');
    }
}
