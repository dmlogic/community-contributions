<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getAddressAttribute(): string
    {
        return implode(' ', array_filter($this->only('number', 'street')));
    }

    public function resident()
    {
        return $this->belongsTo(User::class);
    }
}
