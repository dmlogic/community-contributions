<?php

namespace App\Http\Middleware;

use App\Models\User;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;

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
    public function version(Request $request): ?string
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
            'can' => [
                'admin' => $request->user()?->isAdmin(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'status' => $request->session()->get('success'),
        ]);
    }

    public function navForUser(?User $user): ?array
    {
        if (!$user) {
            return null;
        }
        $nav = [
            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'gauge-simple-high'],
            ['route' => 'statement', 'label' => 'Payment history', 'icon' => 'scale-balanced'],

        ];
        if ($user->isAdmin()) {
            $nav[] = ['route' => 'campaign.index', 'label' => 'Campaigns', 'icon' => 'sack-dollar'];
            $nav[] = ['route' => 'fund.index', 'label' => 'Funds', 'icon' => 'building-columns'];
            $nav[] = ['route' => 'member.index', 'label' => 'Members', 'icon' => 'users'];
            $nav[] = ['route' => 'property.index', 'label' => 'Properties', 'icon' => 'map-location'];
        }

        return $nav;
    }
}
