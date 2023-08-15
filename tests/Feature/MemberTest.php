<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\User;
use App\Models\Member;
use Tests\FeatureTest;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia;

class MemberTest extends FeatureTest
{
    public function test_non_admin_cannot_access(): void
    {
        $this->actingAs($this->supplierUser())
            ->get(route('member.index'))
            ->assertForbidden();
    }

    public function test_members_are_listed(): void
    {
        $this->actingAs($this->adminUser())
            ->get(route('member.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('members')
                ->count('members', 4)
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
            ->get(route('member.show', $member->id))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('member')
                ->where('member.name', $member->name)
                ->where('member.property.address', $member->property->address)
            );
    }

    public function test_member_form_is_shown(): void
    {
        $member = Member::first();

        $this->actingAs($this->adminUser())
            ->get(route('member.edit', $member->id))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('member')
                ->where('member.name', $member->name)
                ->where('member.property.address', $member->property->address)
            );
        $this->get(route('member.create'))
            ->assertOk();
    }

    public function test_member_is_created(): void
    {
        $member = User::factory()->make();
        $this->actingAs($this->adminUser());
        $response = $this->actingAs($this->adminUser())
            ->post(route('member.store'), [
                'name' => $member->name,
                'email' => $member->email,
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => $member->email,
            'name' => $member->name,
        ]);
    }

    public function test_member_is_updated(): void
    {
        $member = User::factory()->create();
        $member->name = fake()->name();

        $formData = [
            'name' => $member->name,
            'email' => $member->email,
            'role_id' => [Roles::RESIDENT->value, ROLES::SUPPLIER->value],
        ];

        $response = $this->actingAs($this->adminUser())
            ->patch(route('member.update', $member->id), $formData)
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'name' => $member->name,
        ]);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $member->id,
            'role_id' => Roles::RESIDENT->value,
        ]);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $member->id,
            'role_id' => Roles::SUPPLIER->value,
        ]);
    }

    public function test_member_can_be_deleted(): void
    {
        DB::table('properties')->delete();
        $member = User::factory()->create();

        $this->actingAs($this->adminUser())
            ->delete(route('member.destroy', $member->id));

        $this->assertSoftDeleted($member);
    }

    public function test_member_cannot_be_deleted_if_occupied(): void
    {
        $member = User::factory()->create();
        $property = Property::factory()->create(['user_id' => $member->id]);
        $this->actingAs($this->adminUser())
            ->delete(route('member.destroy', $member->id))
            ->assertInvalid();
    }
}
