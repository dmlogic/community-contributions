<?php

namespace App\Http\Requests;

use App\Enums\LedgerTypes;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class LedgerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', $this->allowedTypes()],
            'description' => ['nullable','string'],
            'amount' => ['required','integer'],
            'fund_id' => ['required','exists:funds,id'],
            'user_id' => ['nullable','exists:users,id']
        ];
    }

    private function allowedTypes()
    {
        if($this->user()->isAdmin()) {
            return new Enum(LedgerTypes::class);
        }
        return Rule::in(LedgerTypes::RESIDENT_OFFLINE->value);
    }
}
