<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResidentUpdateRequest extends FormRequest
{
    public function formData(): array
    {
        return $this->safe([
            'name',
            'email',
        ]);
    }
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->route('resident')->id)],
        ];
    }
}
