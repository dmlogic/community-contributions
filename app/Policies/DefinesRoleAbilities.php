<?php

namespace App\Policies;

use App\Models\User;

trait DefinesRoleAbilities
{
    public function manage(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin();
    }
}
