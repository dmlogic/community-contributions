<?php

namespace App\Http\Requests;

use App\Models\Ledger;
use App\Enums\LedgerTypes;
use App\Models\CampaignRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Events\CampaignContributionCreated;
use Illuminate\Foundation\Http\FormRequest;

class LedgerCreateRequest extends FormRequest
{
    public $campaignRequest;

    public function createLedgerEntry()
    {
        if ($this->input('request_id')) {
            $this->campaignRequest = CampaignRequest::findorFail($this->input('request_id'));
        }

        $data = $this->validator->safe()->except(['request_id']);
        if ($this->user()->isAdmin()) {
            $data['verified_at'] = now();
        }
        $ledger = Ledger::create($data);

        if ($this->campaignRequest) {
            CampaignContributionCreated::dispatch($this->campaignRequest, $ledger);
        }
    }

    public function rules(): array
    {
        return [
            'type' => ['required', $this->allowedTypes()],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'integer'],
            'fund_id' => ['required', 'exists:funds,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'request_id' => ['nullable', 'exists:campaign_requests,id'],
            'created_at' => ['date'],
        ];
    }

    protected function prepareForValidation()
    {
        if (! $this->query('request_id')) {
            return;
        }
        $this->merge([
            'request_id' => $this->query('request_id'),
        ]);
    }

    private function allowedTypes()
    {
        if ($this->user()->isAdmin()) {
            return new Enum(LedgerTypes::class);
        }

        return Rule::in(LedgerTypes::RESIDENT_OFFLINE->value);
    }
}
