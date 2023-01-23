<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CampaignUpsertRequest;

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
        $campaign->delete();
        return Redirect::route('campaign.index')
                       ->with('success', 'Campaign deleted');
    }
}
