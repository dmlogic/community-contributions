<?php

namespace Database\Seeders;

use App\Models\Ledger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LedgerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ledger')->delete();

        $i = 200;
        $userIds = [1, 3, 4];
        while ($i > 0) {
            shuffle($userIds);
            Ledger::factory()->create(
                [
                    'fund_id' => 1,
                    'verified_at' => now(),
                    'user_id' => current($userIds),
                    'amount' => rand(5000, 10000),
                    'created_at' => now()->subMinutes(rand(0, 6000)),
                ]
            );
            $i--;
        }
    }
}
