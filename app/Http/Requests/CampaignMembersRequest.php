<?php

namespace App\Http\Requests;

use App\Rules\UnpaidUsers;
use App\Rules\RequestedUsers;
use App\Rules\UnrequestedUsers;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class CampaignMembersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'members.*' => 'exists:users,id',
            'members' => ['array', $this->getMemberRule()],
        ];
    }

    public function getMemberRule()
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
