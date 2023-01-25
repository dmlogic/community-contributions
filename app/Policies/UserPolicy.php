<?php

namespace App\Policies;

use App\Concerns\DefinesRoleAbilities;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, DefinesRoleAbilities;
}
