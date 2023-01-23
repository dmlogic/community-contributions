<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(),
            'target' => 5000,
            'raised' => 0
        ];
    }
}
