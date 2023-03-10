<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => fake()->uuid(),
            'street' => config('app.default_address.street', fake()->streetAddress()),
            'town' => config('app.default_address.town', fake()->city()),
            'postcode' => config('app.default_address.postcode', fake()->postcode()),
        ];
    }
}
