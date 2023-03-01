<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Ledger;
use App\Models\Member;
use App\Enums\LedgerTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\LedgerCreateRequest;
use Illuminate\Validation\ValidationException;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'ledgers' => Ledger::forFund($request->fund_id, $request->filter)
                               ->simplePaginate(20)
                               ->appends(['fund_id' => $request->fund_id, 'filter' => $request->filter]),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Fund/LedgerForm', [
            'residents' => Member::residents(),
            'fund' => Fund::findOrFail($request->fund_id)->only(['id', 'name']),
            'requestId' => (int) $request->request_id,
            'amount' => (int) $request->amount,
            'userId' => (int) $request->user_id,
            'type' => $request->user_id ? LedgerTypes::RESIDENT_OFFLINE->value : LedgerTypes::ADMIN_ADJUSTMENT->value,
            'created' => now()->subDays(1)->format('Y-m-d H:i'),
            'description' => $request->type === LedgerTypes::RESIDENT_OFFLINE->value ? 'Reconciled from bank statement' : '',
        ]);
    }

    public function store(LedgerCreateRequest $request): RedirectResponse
    {
        $request->createLedgerEntry();

        if ($request->campaignRequest) {
            return Redirect::route('campaign.show', $request->campaignRequest->campaign_id)
                       ->with('success', 'Campaign total updated');
        }

        return Redirect::route('fund.show', $request->fund_id)
                       ->with('success', 'Fund value updated');
    }

    public function verify(Ledger $ledger): RedirectResponse
    {
        if ($ledger->verified_at) {
            throw ValidationException::withMessages(['ledger' => 'Entry is already verified']);
        }

        $ledger->verified_at = now();
        $ledger->save();

        return Redirect::route('fund.show', [$ledger->fund_id])
                       ->with('success', 'Fund value updated');
    }

    public function destroy(Ledger $ledger): RedirectResponse
    {
        $ledger->delete();

        return Redirect::route('fund.show', [$ledger->fund_id])
                       ->with('success', 'Fund value updated');
    }
}
