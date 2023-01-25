<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fund extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'balance',
    ];

    protected $casts = [
        'balance' => 'integer',
    ];

    protected $appends = [
        'value',
    ];

    // ------------------------------------------------------------------------
    // Custom attributes

    public function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return new Money($attributes['balance'] ?? 0);
            }
        );
    }
}
