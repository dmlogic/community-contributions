<?php

use App\Models\User;
use App\Models\Ledger;
use App\Models\Campaign;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount');
            $table->foreignIdFor(Campaign::class);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Ledger::class)->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_requests');
    }
};
