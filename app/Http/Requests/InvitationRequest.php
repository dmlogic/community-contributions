<?php

namespace App\Http\Requests;

use App\Models\Invitation;
use Illuminate\Foundation\Http\FormRequest;

class InvitationRequest extends FormRequest
{
    public function createInvitation(): Invitation
    {
        $this->destroyMatches();
        $member = Invitation::create(
            $this->validated()
        );
        return $member;
    }

    /**
     * Destroying any colliding invitations removes
     * the need to manage them
     */
    private function destroyMatches()
    {
        Invitation::where('email','='. $this->input('email'))
                  ->delete();
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string', 'max:255'],
            'email' => ['required','email', 'max:255', 'unique:App\Models\User,email'],
            'role_id' => ['nullable','exists:roles,id'],
            'property_id' => ['nullable','exists:properties,id'],
        ];
    }
}
