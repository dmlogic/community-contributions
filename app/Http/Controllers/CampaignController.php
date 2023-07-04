<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Events\CampaignRequestsGenerated;
use App\Events\CampaignRemindersGenerated;
use App\Http\Requests\CampaignUpsertRequest;
use App\Http\Requests\CampaignMembersRequest;
use Illuminate\Validation\ValidationException;

class CampaignController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Campaign/List', [
            'campaigns' => Campaign::with('fund')->orderBy('closed_at', 'desc')->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function create(): Response
    {
        return $this->renderForm(new Campaign(['fund_id' => 1]));
    }

    public function store(CampaignUpsertRequest $request): RedirectResponse
    {
        $model = Campaign::create($request->validated());

        return Redirect::route('campaign.show', $model->id)
                       ->with('success', 'Campaign created');
    }

    public function show(Campaign $campaign): Response
    {
        $campaign->load('fund');
        return Inertia::render('Campaign/View', [
            'campaign' => $campaign,
            'requests' => $campaign->requests()
                                    ->orderBy('ledger_id', 'asc')
                                    ->orderBy('updated_at', 'asc')
                                    ->with('ledger')
                                    ->get(),
            'residents' => Member::residents(),
        ]);
    }

    public function edit(Campaign $campaign): Response
    {
        return $this->renderForm($campaign);
    }

    public function update(CampaignUpsertRequest $request, Campaign $campaign): RedirectResponse
    {
        $campaign->fill($request->validated());
        $campaign->save();

        return Redirect::route('campaign.index')
                       ->with('success', 'Campaign updated');
    }

    public function close(Campaign $campaign): RedirectResponse
    {
        $campaign->close();
        return Redirect::route('campaign.index')
                       ->with('success', 'Campaign closed');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        if ($campaign->requests()->whereNotNull('ledger_id')->count()) {
            throw ValidationException::withMessages(['campaign' => 'Campaign has payment activity']);
        }
        $campaign->delete();

        return Redirect::route('campaign.index')
                       ->with('success', 'Campaign deleted');
    }

    public function newRequest(CampaignMembersRequest $request, Campaign $campaign)
    {
        CampaignRequestsGenerated::dispatch($campaign, $request->createModels($campaign->id));

        return Redirect::route('campaign.show', $campaign->id)
                       ->with('success', 'Requests created');
    }

    public function remindRequest(CampaignMembersRequest $request, Campaign $campaign)
    {
        CampaignRemindersGenerated::dispatch($campaign, $request->getModelsToBeReminded($campaign->id));

        return Redirect::route('campaign.show', $campaign->id)
                       ->with('success', 'Requests created');
    }

    public function deleteRequest(CampaignMembersRequest $request, Campaign $campaign)
    {
        $request->deleteModels($campaign->id);

        return Redirect::route('campaign.show', $campaign->id)
                       ->with('success', 'Requests deleted');
    }

    private function renderForm(Campaign $campaign): Response
    {
        return Inertia::render('Campaign/Form', [
            'campaign' => $campaign,
            'funds' => Fund::get(),
        ]);
    }
}
