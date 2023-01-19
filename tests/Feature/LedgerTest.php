<?php

namespace Tests\Feature;

use App\Enums\Entry;
use App\Models\Fund;
use App\Models\Ledger;
use Tests\FeatureTest;
use Inertia\Testing\AssertableInertia;


class LedgerTest extends FeatureTest
{

    public function test_admin_can_add_any_valid_type(): void
    {
        $this->actingAs($this->adminUser());

        $fund = Fund::factory()->create();
        $balance = $fund->balance;

        foreach (Entry::cases() as $type) {
            $amount = rand(-21, 101);
            $ledger = Ledger::factory()->make([
                'type' => $type->value,
                'amount' => $amount,
                'user_id' => 1,
                'fund_id' => $fund->id
            ]);

            $balance += $amount;

            $this->post(route('ledger.store'),$ledger->toArray());
            $this->assertDatabaseHas('ledger',$ledger->only(['type','description','amount']));
            $this->assertDatabaseHas('funds',['id' => $fund->id, 'balance' => $balance]);
            // see fund balance update
        }

    }

    public function test_resident_can_only_add_certain_types()
    {
        $this->markTestIncomplete();
        // see fund balance update
    }

    public function test_admin_can_delete_entries()
    {
        $this->markTestIncomplete();
        // see fund balance update
    }





}
