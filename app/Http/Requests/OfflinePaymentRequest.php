<?php

namespace App\Http\Requests;

use App\Models\Ledger;
use App\Enums\LedgerTypes;
use App\Models\CampaignRequest;
use App\Events\CampaignContributionCreated;
use Illuminate\Foundation\Http\FormRequest;

class OfflinePaymentRequest extends FormRequest
{
    public function createLedgerEntry()
    {
        $campaignRequest = CampaignRequest::with('campaign')
            ->where('user_id', '=', $this->user()->id)
            ->findorFail($this->input('request_id'));
        $ledger = Ledger::create([
            'fund_id' => $campaignRequest->campaign->id,
            'user_id' => $campaignRequest->user_id,
            'created_by' => $campaignRequest->user_id,
            'description' => 'Payment made by bank transfer',
            'amount' => $campaignRequest->amount,
            'verified_at' => null,
            'type' => LedgerTypes::RESIDENT_OFFLINE->value,
        ]);

        CampaignContributionCreated::dispatch($campaignRequest, $ledger);
    }

    public function rules(): array
    {
        return [
            'payment_date' => ['required', 'date'],
            'request_id' => ['nullable', 'exists:campaign_requests,id'],
            'fund_id' => ['required', 'exists:funds,id'],
        ];
    }
}
