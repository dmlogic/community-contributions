<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Property;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\InvitationAcceptRequest;
use App\Http\Requests\InvitationCreateRequest;

class InvitationController extends Controller
{
    public function create()
    {
        return Inertia::render('Member/Invitation', [
            'properties' => Property::listData(),
        ]);
    }

    public function store(InvitationCreateRequest $request): RedirectResponse
    {
        $request->createInvitation();

        return Redirect::route('member.index')
            ->with('success', 'Member invitation sent');
    }

    /**
     * @route invitation.confirm
     */
    public function confirm(Invitation $invitation): Response
    {
        return Inertia::render('Member/Accept', [
            'invitation' => $invitation->load('property'),
        ]);
    }

    /**
     * @route invitation.process
     */
    public function process(InvitationAcceptRequest $request, Invitation $invitation)
    {
        $user = $invitation->convertToUser($request->validated('password'));
        Auth::login($user);

        return Redirect::route('dashboard')
            ->with('success', 'Welcome to '.config('app.name'));
    }
}
