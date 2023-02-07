<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Concerns\UpdatesFundBalance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\LedgerCreateRequest;
use Illuminate\Validation\ValidationException;

class LedgerController extends Controller
{
    use UpdatesFundBalance;

    public function index(Request $request)
    {
        return response()->json([
            'ledgers' => Ledger::forFund($request->fund_id)
                               ->simplePaginate(20)
                               ->appends(['fund_id' => $request->fund_id])
        ]);
    }

    public function store(LedgerCreateRequest $request): RedirectResponse
    {
        $request->createLedgerEntry();

        return $this->done();
    }

    public function verify(Ledger $ledger): RedirectResponse
    {
        if ($ledger->verified_at) {
            throw ValidationException::withMessages(['ledger' => 'Entry is already verified']);
        }

        $ledger->verified_at = now();
        $ledger->save();
        $this->updateFund($ledger->fund, $ledger->amount);

        return $this->done();
    }

    public function destroy(Ledger $ledger): RedirectResponse
    {
        $ledger->delete();

        return $this->done();
    }

    private function done(): RedirectResponse
    {
        return Redirect::route('fund.index')
                       ->with('success', 'Fund value updated');
    }
}
