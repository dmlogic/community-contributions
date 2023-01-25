<?php

namespace App\Policies;

use App\Concerns\DefinesRoleAbilities;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization, DefinesRoleAbilities;
}
