<?php

namespace Database\Factories;

use App\Enums\LedgerTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class LedgerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => LedgerTypes::RESIDENT_REQUEST->name,
            'description' => fake()->sentence(),
            'amount' => 100,
        ];
    }
}
