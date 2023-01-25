<?php

namespace Tests;

use App\Enums\Roles;
use App\Models\Fund;
use App\Models\User;
use App\Models\Campaign;
use App\Models\CampaignRequest;
use Illuminate\Support\Facades\DB;

trait SeedsCampaigns
{
    public $seedData;

    public function seedCampaigns(): void
    {
        $this->seedData['fund'] = Fund::factory()->create();
        $this->seedData['campaign'] = Campaign::factory()->create(['fund_id' => $this->seedData['fund']->id]);

        $members = User::factory()->count(5)->create();
        DB::table('role_user')->insert([
            ['user_id' => $members[0]->id, 'role_id' => Roles::RESIDENT->value],
            ['user_id' => $members[1]->id, 'role_id' => Roles::RESIDENT->value],
            ['user_id' => $members[2]->id, 'role_id' => Roles::RESIDENT->value],
        ]);
        CampaignRequest::insert([
            ['user_id' => $members[0]->id, 'campaign_id' => $this->seedData['campaign']->id, 'amount' => 50],
            ['user_id' => $members[1]->id, 'campaign_id' => $this->seedData['campaign']->id, 'amount' => 50],
            ['user_id' => $members[2]->id, 'campaign_id' => $this->seedData['campaign']->id, 'amount' => 50],
        ]);
        $this->seedData['members'] = $members;
    }
}
