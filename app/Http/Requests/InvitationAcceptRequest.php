<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvitationAcceptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'confirmed'],
        ];
    }

}
