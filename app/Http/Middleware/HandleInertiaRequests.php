<?php

namespace App\Http\Middleware;

use App\Models\User;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'app_name' => config('app.name'),
            'nav' => $this->navForUser($request->user()),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }

    public function navForUser(?User $user): ?array
    {
        if(!$user) {
            return null;
        }
        return [
            ['href' => route('dashboard'), 'label' => 'Dashboard', 'icon' => 'table-columns' ],
            ['href' => route('campaign.index'), 'label' => 'Campaigns', 'icon' => 'sack-dollar' ],
            ['href' => route('member.index'), 'label' => 'Members', 'icon' => 'users' ],
            ['href' => route('property.index'), 'label' => 'Properties', 'icon' => 'map-location' ],
            ['href' => route('fund.index'), 'label' => 'Funds', 'icon' => 'building-columns' ],
        ];
    }
}
