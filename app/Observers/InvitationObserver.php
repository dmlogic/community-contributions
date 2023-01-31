<?php

namespace App\Observers;

use App\Enums\Roles;
use Ramsey\Uuid\Uuid;
use App\Models\Invitation;

class InvitationObserver
{
    public function creating(Invitation $invitation): void
    {
        if (null === $invitation->code) {
            $invitation->code = (string) Uuid::uuid4();
        }
        if (null === $invitation->role_id) {
            $invitation->role_id = Roles::RESIDENT->value;
        }
    }
}
