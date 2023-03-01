<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Inertia\Inertia;
use Illuminate\Contracts\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function show()
    {
        return Inertia::render('Dashboard', $this->dashboardData());
    }

    private function dashboardData(): array
    {
        $data = [
            'funds' => Fund::with([
                'campaigns' => function (Builder $query) {
                    $query->whereNull('closed_at');
                },
                'campaigns.requests' => function (Builder $query) {
                    $query->where('user_id', '=', request()->user()->id);
                },
                'campaigns.requests.ledger',
            ])
            ->get(),
            'reconcile' => [],
        ];

        if (! request()->user()->isAdmin()) {
            return $data;
        }

        $data['reconcile'] = Fund::whereHas('campaigns.requests.ledger', function (Builder $query) {
            $query->whereNull('verified_at');
        })->get();

        return $data;
    }
}
