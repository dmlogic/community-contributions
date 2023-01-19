<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FundFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
        ];
    }
}
