<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['address'];

    // ------------------------------------------------------------------------
    // Helpers

    public static function listData(): Collection
    {
        return Property::with('member')
                    ->select('id', 'number', 'street', 'user_id')
                    ->orderBy('number')
                    ->get();
    }

    public static function defaultData(): Property
    {
        return new Property([
            'number' => '',
            'street' => config('app.default_address.street'),
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

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')
                    ->select('id', 'name', 'email');
    }
}
