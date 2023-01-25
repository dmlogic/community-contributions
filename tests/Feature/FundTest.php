<?php

namespace Tests\Feature;

use App\Models\Fund;
use Tests\FeatureTest;
use Inertia\Testing\AssertableInertia;

class FundTest extends FeatureTest
{
    public function test_non_admin_cannot_access(): void
    {
        $this->actingAs($this->supplierUser())
             ->get(route('fund.index'))
             ->assertForbidden();
    }

    public function test_funds_are_listed(): void
    {
        Fund::factory()->count(4)->create();

        $this->actingAs($this->adminUser())
            ->get(route('fund.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('funds')
                ->count('funds', 4)
                ->has('funds.0.id')
                ->has('funds.0.name')
                ->has('funds.0.description')
            );
    }

    public function test_fund_is_shown(): void
    {
        $fund = Fund::factory()->create();

        $this->actingAs($this->adminUser())
            ->get(route('fund.show', $fund->id))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('fund')
                ->where('fund.name', $fund->name)
            );
    }

    public function test_fund_is_created(): void
    {
        $fund = Fund::factory()->make();

        $this->actingAs($this->adminUser())
            ->post(route('fund.store'), $fund->toArray())
            ->assertRedirectToRoute('fund.index');

        $this->assertDatabaseHas('funds', [
            'name' => $fund->name,
            'description' => $fund->description,
        ]);
    }

    public function test_fund_is_updated(): void
    {
        $fund = Fund::factory()->create();
        $fund->name = 'Edited name';

        $this->actingAs($this->adminUser())
             ->patch(route('fund.update', $fund->id), $fund->toArray());

        $this->assertDatabaseHas('funds', [
            'id' => $fund->id,
            'name' => $fund->name,
        ]);
    }

    public function test_fund_can_be_deleted(): void
    {
        $fund = Fund::factory()->create();
        $this->actingAs($this->adminUser())
             ->delete(route('fund.destroy', $fund->id));

        $this->assertDatabaseMissing('funds', ['id', $fund->id]);
    }
}
