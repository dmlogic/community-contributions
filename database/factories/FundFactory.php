<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
        ];
    }
}
