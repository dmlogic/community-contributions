<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Invitation;
use App\Notifications\InviteMember;
use Illuminate\Foundation\Http\FormRequest;

class InvitationCreateRequest extends FormRequest
{
    public function createInvitation()
    {
        $this->destroyMatches();
        $invite = Invitation::create(
            $this->validated()
        );

        $tmpUser = new User(['name' => $invite->name, 'email' => $invite->email]);

        $tmpUser->notify(new InviteMember($invite));
    }

    /**
     * Destroying any colliding invitations removes the need to manage them
     */
    private function destroyMatches()
    {
        Invitation::where('email', '=', $this->input('email'))
            ->delete();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:App\Models\User,email'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'property_id' => ['nullable', 'exists:properties,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email' => 'There is already a member with this email address',
        ];
    }
}
