<?php

namespace App\Http\Requests;

use App\Rules\UnpaidUsers;
use App\Rules\RequestedUsers;
use App\Models\CampaignRequest;
use App\Rules\UnrequestedUsers;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Validation\InvokableRule;

class CampaignMembersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'integer',
            'members' => ['array', 'exists:users,id', $this->getMemberRule()],
        ];
    }

    public function createModels(int $campaignId): Collection
    {
        $requestCollection = new Collection;
        foreach($this->validated('members') as $userId) {
            $requestCollection->push(
                CampaignRequest::create([
                    'amount' => $this->validated('amount'),
                    'campaign_id' => $campaignId,
                    'user_id' => $userId,
                ])
            );
        }
        return $requestCollection;
    }

    public function deleteModels($campaignId)
    {
        CampaignRequest::whereIn('user_id', $this->validated('members'))
                       ->where('campaign_id', $campaignId)
                       ->delete();
    }

    public function getModelsToBeReminded(int $campaignId): Collection
    {
        return CampaignRequest::whereNull('ledger_id')
                       ->where('campaign_id', $campaignId)
                       ->whereIn('user_id', $this->validated('members'))
                       ->get();
    }

    public function getMemberRule(): InvokableRule
    {
        switch(Route::currentRouteName()) {
            case 'campaign.new-request':
                return new UnrequestedUsers;
            case 'campaign.remind-request':
                return new RequestedUsers;
            case 'campaign.delete-request':
                return new UnpaidUsers;
        }
    }
}
