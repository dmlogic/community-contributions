<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use DmLogic\CommunityContributions\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'street' => config('community.address.street', fake()->streetAddress()),
            'town' => config('community.address.town', fake()->city()),
            'postcode' => config('community.address.postcode', fake()->postcode()),
        ];
    }
}
