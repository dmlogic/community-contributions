<?php

namespace Tests\Feature;

use Tests\FeatureTest;
use Inertia\Testing\Assert;
use Inertia\Testing\AssertableInertia;


class PropertyTest extends FeatureTest
{
    public function test_non_admin_cannot_access(): void
    {
        $this->markTestIncomplete();
    }

    public function test_property_can_be_listed(): void
    {
        $response = $this
            ->actingAs($this->adminUser())
            ->get('/property')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('property')
                ->count('property',4)
                ->has('property.0.number')
                ->has('property.0.street')
                ->has('property.0.resident.id')
                ->has('property.0.resident.name')
                ->has('property.0.resident.email')
            );
    }

    public function test_property_is_displayed(): void
    {
        $this->markTestIncomplete();
    }

    public function test_property_can_be_created(): void
    {
        $this->markTestIncomplete();
    }

    public function test_property_can_be_updated(): void
    {
        $this->markTestIncomplete();
    }

    public function test_property_can_be_deleted(): void
    {
        $this->markTestIncomplete();
    }

    public function test_property_cannot_be_deleted_if_occupied(): void
    {
        $this->markTestIncomplete();
    }
}
