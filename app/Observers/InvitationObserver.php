<?php

namespace App\Observers;

use Ramsey\Uuid\Uuid;
use App\Models\Invitation;

class InvitationObserver
{
    public function creating(Invitation $invitation): void
    {
        if(null !== $invitation->code ) {
            return;
        }
        $invitation->code = (string) Uuid::uuid4();

    }
}
