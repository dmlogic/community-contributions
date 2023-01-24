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
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount');
            $table->foreignIdFor(Campaign::class)->onDelete('cascade');
            $table->foreignIdFor(User::class)->onDelete('cascade');;
            $table->foreignIdFor(Ledger::class)->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_requests');
    }
};
