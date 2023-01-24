<?php

namespace App\Providers;

use App\Models\Ledger;
use App\Models\Invitation;
use App\Observers\LedgerObserver;
use App\Observers\InvitationObserver;
use Illuminate\Auth\Events\Registered;
use App\Events\CampaignRequestsGenerated;
use App\Events\CampaignRemindersGenerated;
use App\Listeners\SendFundingRequestNotifications;
use App\Listeners\SendFundingReminderNotifications;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CampaignRequestsGenerated::class => [
            SendFundingRequestNotifications::class
        ],
        CampaignRemindersGenerated::class => [
            SendFundingReminderNotifications::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Invitation::observe(InvitationObserver::class);
        Ledger::observe(LedgerObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
