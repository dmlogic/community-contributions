<?php

namespace App\Http\Requests;

use App\Enums\Entry;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class LedgerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(Entry::class)],
            'description' => ['nullable','string'],
            'amount' => ['required','decimal:2'],
            'fund_id' => ['required','exists:funds,id'],
            'user_id' => ['nullable','exists:users,id']
        ];
    }

    private function allowedTypes()
    {
        if($this->user()->isAdmin()) {
            return new Enum(Entry::class);
        }
    }
}
