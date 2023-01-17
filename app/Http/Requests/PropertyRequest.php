<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{
    public function propertyData(): array
    {
        return $this->safe([
            'number',
            'street',
            'town',
            'postcode',
            'user_id',
        ]);
    }

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'number' => 'required',
            'street' => 'required',
            'town' => 'required',
            'postcode' => 'required',
            'user_id' => 'nullable|exists:users,id'
        ];
    }
}
