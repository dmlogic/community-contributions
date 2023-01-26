<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\FeatureTest;
use App\Models\Property;
use Inertia\Testing\AssertableInertia;

class PropertyTest extends FeatureTest
{
    public function test_non_admin_cannot_access(): void
    {
        $this->actingAs($this->supplierUser())
             ->get(route('property.index'))
             ->assertForbidden();
    }

    public function test_properties_are_listed(): void
    {
        $this->actingAs($this->adminUser())
            ->get(route('property.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('properties')
                ->count('properties', 4)
                ->has('properties.0.number')
                ->has('properties.0.street')
                ->has('properties.0.member.id')
                ->has('properties.0.member.name')
                ->has('properties.0.member.email')
            );
    }

    public function test_property_is_shown(): void
    {
        $house = Property::first();

        $this->actingAs($this->adminUser())
            ->get(route('property.show', $house->id))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('property')
                ->where('property.address', $house->address)
                ->where('property.member.name', $house->member->name)
            );
    }

    public function test_property_is_created(): void
    {
        $house = Property::factory()->make();

        $this->actingAs($this->adminUser())
            ->post(route('property.store'), $house->only('number', 'street', 'town', 'postcode'))
            ->assertRedirectToRoute('property.index');

        $this->assertDatabaseHas('properties', [
            'number' => $house->number,
        ]);
    }

    public function test_property_is_updated(): void
    {
        $member = User::factory()->create();
        $house = Property::first();
        $house->street = fake()->streetAddress();
        $house->user_id = $member->id;

        $this->actingAs($this->adminUser())
             ->patch(route('property.update', $house->id), $house->only('number', 'street', 'town', 'postcode', 'user_id'));

        $this->assertDatabaseHas('properties', [
            'id' => $house->id,
            'street' => $house->street,
            'user_id' => $member->id,
        ]);
    }

    public function test_property_can_be_deleted(): void
    {
        $house = Property::factory()->create();
        $this->actingAs($this->adminUser())
             ->delete(route('property.destroy', $house->id));

        $this->assertDatabaseMissing('properties', ['id', $house->id]);
    }

    public function test_property_cannot_be_deleted_if_occupied(): void
    {
        $user = User::first();
        $house = Property::factory()->create(['user_id' => $user->id]);
        $this->actingAs($this->adminUser())
             ->delete(route('property.destroy', $house->id))
             ->assertInvalid('user_id');
    }

    public function test_forms_contain_expected_data(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('property.create'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('property')
                ->has('members')
                ->missing('property.id')
            );

        $house = Property::first();
        $this->get(route('property.show', $house->id))
            ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('property')
            ->has('members')
            ->has('property.id')
            );
    }
}
