<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\Fund;
use App\Models\User;
use App\Models\Campaign;
use App\Models\CampaignRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignSeeder extends Seeder
{
    public function run(): array
    {
        $data['fund'] = Fund::factory()->create();
        $data['campaign'] = Campaign::factory()->create(['fund_id' => $data['fund']->id]);

        $members = User::factory()->count(5)->create();
        DB::table('role_user')->insert([
            ['user_id' => $members[0]->id, 'role_id' => Roles::RESIDENT->value],
            ['user_id' => $members[1]->id, 'role_id' => Roles::RESIDENT->value],
            ['user_id' => $members[2]->id, 'role_id' => Roles::RESIDENT->value],
        ]);
        CampaignRequest::insert([
            ['user_id' => $members[0]->id, 'campaign_id' => $data['campaign']->id, 'amount' => 50],
            ['user_id' => $members[1]->id, 'campaign_id' => $data['campaign']->id, 'amount' => 50],
            ['user_id' => $members[2]->id, 'campaign_id' => $data['campaign']->id, 'amount' => 50],
        ]);
        $data['members'] = $members;

        return $data;
    }
}
