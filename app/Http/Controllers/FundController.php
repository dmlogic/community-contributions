<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Http\Requests\FundRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class FundController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Fund/List', [
            'funds' => Fund::all(),
        ]);
    }

    public function show(Request $request, Fund $fund): Response
    {
        return Inertia::render('Fund/View', [
            'fund' => $fund,
            'ledgers' => Ledger::forFund($fund->id, $request->filter)
                               ->simplePaginate(20)
                               ->withPath(route('ledger.index',['fund_id' => $fund->id, 'filter' => $request->filter]))
        ]);
    }

    public function edit(Fund $fund): Response
    {
        return Inertia::render('Fund/Form', [
            'fund' => $fund,
        ]);
    }

    public function create(): Response
    {
        return $this->edit(new Fund);
    }

    public function store(FundRequest $request): RedirectResponse
    {
        Fund::create($request->validated());

        return Redirect::route('fund.index')
                       ->with('success', 'Fund created');
    }

    public function update(FundRequest $request, Fund $fund): RedirectResponse
    {
        $fund->fill($request->validated())->save();

        return Redirect::route('fund.index')
                       ->with('success', 'Fund updated');
    }

    public function destroy(Fund $fund): RedirectResponse
    {
        $fund->delete();

        return Redirect::route('fund.index')
                       ->with('success', 'Fund deleted');
    }
}
