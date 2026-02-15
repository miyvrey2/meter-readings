<?php

namespace Database\Factories;

use App\Models\Ean;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyCosts>
 */
class DailyCostsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ean_code' => Ean::factory(),
            'kwh_used' => $this->faker->randomFloat(2, 1000, 2000),
            'cost_in_euro' => $this->faker->randomFloat(2, 1000, 2000)
        ];
    }
}
