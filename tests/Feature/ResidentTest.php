<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\FeatureTest;
use App\Models\Property;
use App\Models\Resident;
use Inertia\Testing\AssertableInertia;


class ResidentTest extends FeatureTest
{
    public function test_non_admin_cannot_access(): void
    {
        $this->actingAs( User::where('name', '=', 'Pothole fairy')->first() )
             ->get(route('resident.index'))
             ->assertForbidden();
    }

    public function test_residents_are_listed(): void
    {
        $this->actingAs($this->adminUser())
            ->get(route('resident.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('residents')
                ->count('residents',4)
                ->has('residents.0.name')
                ->has('residents.0.email')
                ->has('residents.0.id')
                ->has('residents.0.property.address')
            );
    }

    public function test_resident_is_shown(): void
    {
        $resident = Resident::first();

        $this->actingAs($this->adminUser())
            ->get(route('resident.show',$resident->id))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('resident')
                ->where('resident.name', $resident->name)
                ->where('resident.property.address', $resident->property->address)
            );
    }

    public function test_resident_is_updated(): void
    {
        $resident = User::factory()->create();
        $resident->name = fake()->name();


        $this->actingAs($this->adminUser())
             ->patch( route('resident.update', $resident->id), $resident->only(['name', 'email']))

             ;

        $this->assertDatabaseHas('users',[
            'id' => $resident->id,
            'name' => $resident->name,
        ]);

    }

    public function test_resident_is_created(): void
    {
        $this->markTestIncomplete();
        $house = Property::factory()->make();

        $this->actingAs($this->adminUser())
            ->post( route('resident.store'), $house->only('number', 'street', 'town', 'postcode') )
            ->assertRedirectToRoute('resident.index');

        $this->assertDatabaseHas('properties', [
            'number' => $house->number,
        ]);
    }


    public function test_resident_can_be_deleted(): void
    {
        $this->markTestIncomplete();
        $house = Property::factory()->create();
        $this->actingAs($this->adminUser())
             ->delete( route('resident.destroy', $house->id) );

        $this->assertDatabaseMissing('properties', ['id', $house->id]);
    }

    public function test_resident_cannot_be_deleted_if_occupied(): void
    {
        $this->markTestIncomplete();
        $user = User::first();
        $house = Property::factory()->create(['user_id' => $user->id]);
        $this->actingAs($this->adminUser())
             ->delete( route('resident.destroy', $house->id) )
             ->assertInvalid('user_id');
    }

    public function test_forms_contain_expected_data()
    {
        $this->markTestIncomplete();
        $response = $this->actingAs($this->adminUser())
            ->get(route('resident.create'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('property')
                ->has('residents')
                ->missing('resident.id')
        );

        $house = Property::first();
        $this->get(route('resident.show', $house->id))
            ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('property')
            ->has('residents')
            ->has('resident.id')
        );
    }
}
