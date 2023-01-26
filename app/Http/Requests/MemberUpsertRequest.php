<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MemberUpsertRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', $this->getEmailRule()],
            'role_id' => ['array', 'exists:roles,id'],
        ];
    }

    public function getEmailRule()
    {
        if ($this->isMethod('PATCH')) {
            return Rule::unique(User::class)->ignore($this->route('member')->id);
        }

        return Rule::unique(User::class);
    }
}
