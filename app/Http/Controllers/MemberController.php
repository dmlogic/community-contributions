<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\MemberUpsertRequest;
use Illuminate\Validation\ValidationException;

class MemberController extends Controller
{
    /**
     * @route member.index
     */
    public function index(): Response
    {
        return Inertia::render('Member/List', [
            'members' => Member::with('property')->get(),
        ]);
    }

    /**
     * @route member.show
     */
    public function show(Member $member): Response
    {
        return Inertia::render('Member/View', [
            'member' => $member->load('property', 'roles'),
        ]);
    }

    /**
     * @route member.edit
     */
    public function edit(Member $member): Response
    {
        return $this->renderMemberForm($member->load('property', 'roles'));
    }

    /**
     * @route member.create
     */
    public function create(): Response
    {
        return $this->renderMemberForm(new Member);
    }

    /**
     * @route member.store
     */
    public function store(MemberUpsertRequest $request)
    {
        $member = User::newUser($request->validated('name'), $request->validated('email'));
        $member->roles()->sync($request->validated('role_id'));

        return Redirect::route('member.index')
                       ->with('success', 'Member created');
    }

    /**
     * @route member.update
     */
    public function update(MemberUpsertRequest $request, Member $member): RedirectResponse
    {
        $member->fill($request->safe(['name', 'email']))->save();
        $member->roles()->sync($request->validated('role_id'));

        return Redirect::route('member.index')
                       ->with('success', 'Member updated');
    }

    /**
     * @route member.destroy
     */
    public function destroy(Member $member): RedirectResponse
    {
        if ($member->property) {
            throw ValidationException::withMessages(['user_id' => 'Member is attached to a property']);
        }
        $member->delete();

        return Redirect::route('member.index')
                       ->with('success', 'Member deleted');
    }

    /**
     * Helper to share the creation of a property form
     * Vue will handle logic changes based on presence of member.id
     */
    private function renderMemberForm(Member $member): Response
    {
        return Inertia::render('Member/Form', [
            'member' => $member,
            'properties' => Property::listData(),
        ]);
    }
}
