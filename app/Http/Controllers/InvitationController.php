<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Invitation;
use App\Events\InvitationCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\InvitationRequest;
use Illuminate\Support\Facades\Redirect;

class InvitationController extends Controller
{
    public function create()
    {
        return 'todo';
    }

    public function store(InvitationRequest $request): RedirectResponse
    {
        $invitation = $request->createInvitation();
        InvitationCreated::dispatch($invitation);

        return Redirect::route('member.index')
                       ->with('success', 'Member invitation sent');
    }

    /**
     * @route invitation.confirm
     */
    public function confirm(Invitation $invitation): Response
    {
        return Inertia::render('Member/Invitation', [
            'invitation' => $invitation,
        ]);
    }

    /**
     * @route invitation.process
     */
    public function process(Invitation $invitation)
    {
        $user = $invitation->convertToUser();
        Auth::login($user);

        return Redirect::route('dashboard')
                       ->with('welcome', 'Welcome to '.config('app.name'));
    }
}
