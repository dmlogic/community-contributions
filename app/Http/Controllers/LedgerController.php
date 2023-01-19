<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Http\Requests\LedgerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class LedgerController extends Controller
{
    public function store(LedgerRequest $request): RedirectResponse
    {
        Ledger::create($request->validated());
        return Redirect::route('fund.index')
                       ->with('success', 'Fund value updated');
    }

    public function destory(Ledger $ledger)
    {
        $ledger->delete();
        return Redirect::route('fund.index')
                       ->with('success', 'Fund value updated');
    }

}
