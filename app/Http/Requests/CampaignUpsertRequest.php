<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignUpsertRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => 'required',
            'fund_id' => ['required', 'exists:funds,id'],
            'target' => ['nullable', 'integer'],
            'raised' => ['nullable', 'integer'],
            'closed_at' => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
