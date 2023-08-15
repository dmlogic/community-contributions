<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Ledger;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Profile/Statement', [
            'payments' => Ledger::statementForUser($request->user())->get(),
        ]);
    }
}
