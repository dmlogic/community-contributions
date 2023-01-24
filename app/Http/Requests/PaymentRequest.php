<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function getMetaData(): array
    {
        return [
            'request_id' => $this->validated('request_id'),
            'fund_id' => $this->validated('fund_id'),
        ];
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1'],
            'request_id' => ['nullable', 'exists:campaign_requests, id'],
            'fund_id' => ['exists:funds, id'],
        ];
    }
}
