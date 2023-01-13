<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_RESIDENT = 'resident';
    public const ROLE_MAINTAINER = 'maintainer';

    protected $guarded = [];
}
