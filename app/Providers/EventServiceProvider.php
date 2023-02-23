<?php

namespace App\Providers;

use App\Models\Ledger;
use App\Models\Invitation;
use App\Observers\LedgerObserver;
use App\Observers\InvitationObserver;
use Illuminate\Auth\Events\Registered;
use App\Events\CampaignRequestsGenerated;
use App\Events\CampaignRemindersGenerated;
use App\Listeners\LogCampaignContribution;
use App\Events\CampaignContributionCreated;
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
            SendFundingRequestNotifications::class,
        ],
        CampaignRemindersGenerated::class => [
            SendFundingReminderNotifications::class,
        ],
        CampaignContributionCreated::class => [
            LogCampaignContribution::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Invitation::observe(InvitationObserver::class);
        Ledger::observe(LedgerObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
