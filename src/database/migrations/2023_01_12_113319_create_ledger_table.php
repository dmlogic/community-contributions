<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fund_id')->references('id')->on('funds');
            $table->unsignedInteger('user_id')->references('id')->on('users')->nullable();
            $table->enum('type', [
                'RESIDENT_REQUEST',
                'RESIDENT_ADDITIONAL',
                'RESIDENT_OTHER',
                'RESIDENT_OFFLINE',
                'ADMIN_RECONCILE_ADJUSTMENT',
                'DISBURSEMENT',
            ]);
            $table->text('description');
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
        Schema::dropIfExists('ledger');
    }
};
