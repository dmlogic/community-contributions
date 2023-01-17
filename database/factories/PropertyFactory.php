<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => fake()->uuid(),
            'street' => config('community.address.street', fake()->streetAddress()),
            'town' => config('community.address.town', fake()->city()),
            'postcode' => config('community.address.postcode', fake()->postcode()),
        ];
    }
}
