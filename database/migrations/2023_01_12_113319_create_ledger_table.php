<?php

use App\Models\Fund;
use App\Models\User;
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
        Schema::create('ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Fund::class);
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(User::class, 'created_by');
            $table->string('type');
            $table->text('description')->nullable();
            $table->integer('amount')->default(0)->comment('amount in pence');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger');
    }
};
