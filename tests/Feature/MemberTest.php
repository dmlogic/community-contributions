<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\FeatureTest;
use App\Models\Property;
use App\Models\Member;
use Inertia\Testing\AssertableInertia;


class MemberTest extends FeatureTest
{
    public function test_non_admin_cannot_access(): void
    {
        $this->actingAs( $this->supplierUser() )
             ->get(route('member.index'))
             ->assertForbidden();
    }

    public function test_members_are_listed(): void
    {
        $this->actingAs($this->adminUser())
            ->get(route('member.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('members')
                ->count('members',4)
                ->has('members.0.name')
                ->has('members.0.email')
                ->has('members.0.id')
                ->has('members.0.property.address')
            );
    }

    public function test_member_is_shown(): void
    {
        $member = Member::first();

        $this->actingAs($this->adminUser())
            ->get(route('member.show',$member->id))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('member')
                ->where('member.name', $member->name)
                ->where('member.property.address', $member->property->address)
            );
    }

    public function test_member_is_updated(): void
    {
        $member = User::factory()->create();
        $member->name = fake()->name();

        $this->actingAs($this->adminUser())
             ->patch( route('member.update', $member->id), $member->only(['name', 'email']));             ;

        $this->assertDatabaseHas('users',[
            'id' => $member->id,
            'name' => $member->name,
        ]);

        $this->markTestIncomplete('@TODO validate multiple role submissions');
    }

    public function test_member_can_be_deleted(): void
    {
        \DB::table('properties')->delete();
        $member = User::factory()->create();

        $this->actingAs($this->adminUser())
              ->delete( route('member.destroy', $member->id));

        $this->assertSoftDeleted($member);

    }

    public function test_member_cannot_be_deleted_if_occupied(): void
    {
        $member = User::factory()->create();
        $property = Property::factory()->create(['user_id' => $member->id]);
        $this->actingAs($this->adminUser())
              ->delete( route('member.destroy', $member->id))
              ->assertInvalid();
    }
}
