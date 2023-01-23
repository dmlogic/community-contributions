<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;
use App\Models\Campaign;
use App\Models\CampaignRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CampaignUpsertRequest;
use App\Http\Requests\CampaignMembersRequest;
use Illuminate\Validation\ValidationException;

class CampaignController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('Campaign/List', [
            'campaigns' => Campaign::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Campaign/Form', [
            'campaign' => new Campaign()
        ]);
    }

    public function store(CampaignUpsertRequest $request): RedirectResponse
    {
        $campaign = Campaign::create($request->validated());
        return Redirect::route('campaign.show', ['campaign' => $campaign->id])
                       ->with('success', 'Campaign created');
    }

    public function show(Campaign $campaign): Response
    {
        return Inertia::render('Campaign/View', [
            'campaign' => $campaign->load('requests'),
            'residents' => Member::residents()
        ]);
    }

    public function edit(Campaign $campaign): Response
    {
        return Inertia::render('Campaign/Form', [
            'campaign' => $campaign
        ]);
    }

    public function update(CampaignUpsertRequest $request, Campaign $campaign): RedirectResponse
    {
        $campaign->fill($request->validated());
        $campaign->save();
        return Redirect::route('campaign.index')
                       ->with('success', 'Campaign updated');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        if($campaign->requests()->whereNotNull('ledger_id')->count()) {
            throw ValidationException::withMessages(['campaign' => 'Campaign has payment activity']);
        }
        $campaign->delete();
        return Redirect::route('campaign.index')
                       ->with('success', 'Campaign deleted');
    }

    public function newRequest(CampaignMembersRequest $request, Campaign $campaign )
    {
        foreach($request->validated('members') as $userId) {
            CampaignRequest::create([
                'campaign_id' => $campaign->id,
                'user_id' => $userId,
            ]);
            // @todo send notification
        }
        return Redirect::route('campaign.index')
                       ->with('success', 'Requests created');
    }

    public function remindRequest(CampaignMembersRequest $request, Campaign $campaign )
    {
        foreach($request->validated('members') as $userId) {
            // @todo send notification
        }
        return Redirect::route('campaign.index')
                       ->with('success', 'Requests created');
    }

    public function deleteRequest(CampaignMembersRequest $request, Campaign $campaign )
    {
        CampaignRequest::whereIn('user_id', $request->validated('members'))
                       ->where('campaign_id', $campaign->id)
                       ->delete();

        return Redirect::route('campaign.index')
                       ->with('success', 'Requests deleted');
    }
}
