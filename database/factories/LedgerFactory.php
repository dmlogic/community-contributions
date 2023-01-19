<?php

namespace Database\Factories;

use App\Enums\Entry;
use Illuminate\Database\Eloquent\Factories\Factory;

class LedgerFactory extends Factory
{
    public function definition()
    {
        return [
            'type' => Entry::RESIDENT_REQUEST->name,
            'description' => fake()->sentence(),
            'amount' => 100
        ];
    }
}
