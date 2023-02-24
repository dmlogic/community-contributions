<?php

namespace App\Http\Requests;

use App\Models\Ledger;
use App\Enums\LedgerTypes;
use App\Models\CampaignRequest;
use App\Events\CampaignContributionCreated;
use Illuminate\Foundation\Http\FormRequest;

class WebhookRequest extends FormRequest
{
    public function processWebhook()
    {
        $requestId = $this->validated('data.object.metadata.request_id');

        $ledger = Ledger::create([
            'fund_id' => $this->validated('data.object.metadata.fund_id'),
            'user_id' => $this->validated('data.object.metadata.user_id'),
            'created_by' => $this->validated('data.object.metadata.user_id'),
            'type' => $requestId ? LedgerTypes::RESIDENT_REQUEST->value : LedgerTypes::RESIDENT_ADDITIONAL->value,
            'description' => 'Online payment',
            'amount' => $this->validated('data.object.amount_total'),
            'verified_at' => now(),
        ]);

        if ($requestId && $campaignRequest = CampaignRequest::find($requestId)) {
            CampaignContributionCreated::dispatch($campaignRequest, $ledger);
        }
    }

    public function rules(): array
    {
        return [
            'data.object.customer_email' => ['required', 'exists:users,email'],
            'data.object.amount_total' => ['required', 'integer', 'min:100'],
            'data.object.metadata.user_id' => ['required', 'exists:users,id'],
            'data.object.metadata.fund_id' => ['required', 'exists:funds,id'],
            'data.object.metadata.request_id' => ['nullable', 'exists:campaign_requests,id'],
        ];
    }
}
