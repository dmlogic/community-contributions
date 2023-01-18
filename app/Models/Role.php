<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ROLE_ADMIN = 1;
    public const ROLE_RESIDENT = 2;
    public const ROLE_SUPPLIER = 3;

    protected $guarded = [];
}
