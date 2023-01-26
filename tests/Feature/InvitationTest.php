<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\User;
use App\Models\Member;
use Tests\FeatureTest;
use App\Models\Property;
use App\Models\Invitation;
use App\Events\InvitationCreated;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

class InvitationTest extends FeatureTest
{
    public function test_non_admin_cannot_create_invitations(): void
    {
        $this->actingAs($this->supplierUser())
             ->get(route('member.index'))
             ->assertForbidden();
    }

    public function test_invitation_is_created(): void
    {
        Event::fake([InvitationCreated::class]);

        $property = Property::factory()->create();
        $newbie = [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'role_id' => Roles::RESIDENT->value,
            'property_id' => $property->id,
        ];

        $response = $this->actingAs($this->adminUser())
            ->post(route('invitation.store'), $newbie)
            ->assertRedirectToRoute('member.index');

        $invite = Invitation::where('email', '=', $newbie['email'])->first();
        $this->assertNotEmpty($invite);
        $this->assertNotEmpty($invite->code);

        Event::assertDispatched(InvitationCreated::class);
    }

    public function test_confirm_screen_renders(): void
    {
        $property = Property::factory()->create();
        $invite = Invitation::factory()->create(['role_id' => Roles::RESIDENT->value, 'property_id' => $property->id]);

        $this->assertGuest()
            ->get(route('invitation.confirm', $invite->id))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('invitation.code', $invite->code)
            );
    }

    public function test_accepted_invitation_creates_member(): void
    {
        $property = Property::factory()->create();
        $invite = Invitation::factory()->create(['role_id' => Roles::RESIDENT->value, 'property_id' => $property->id]);

        $this->post(route('invitation.confirm', $invite->id))
                         ->assertRedirectToRoute('dashboard');

        $user = Member::whereEmail($invite->email)->first();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->roles->first()->id, Roles::RESIDENT->value);
        $this->assertEquals($user->property->id, $property->id);
    }
}
