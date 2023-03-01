<?php

namespace App\Console\Commands;

use App\Enums\Roles;
use App\Models\Fund;
use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\OfflinePayment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;

class SendReconcileAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reconcile:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send admin notifications when funds need reconciling';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (! $this->fundsRequiringReconciliation()) {
            $this->info('no funds affected');

            return;
        }
        foreach ($this->adminUsers() as $admin) {
            $admin->notify(new OfflinePayment);
        }
    }

    public function adminUsers(): Collection
    {
        return User::whereHas('roles', function (Builder $query) {
            $query->where('roles.id', '=', Roles::ADMIN->value);
        })->get();
    }

    public function fundsRequiringReconciliation(): bool
    {
        return (bool) Fund::whereHas('campaigns.requests.ledger', function (Builder $query) {
            $query->whereNull('verified_at');
            // $query->where('created_at', '<=', now()->subDays(1));
        })->count();
    }
}
