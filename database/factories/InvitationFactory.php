<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvitationFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => fake()->uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
