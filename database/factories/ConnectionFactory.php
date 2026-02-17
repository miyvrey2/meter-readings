<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Connection>
 */
class ConnectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'ean_code' => "871" . $this->faker->unique()->numerify('###############'),
            'street' => $this->faker->streetName(),
            'house_number' => $this->faker->buildingNumber(),
            'house_number_addition' => $this->faker->buildingNumber(),
            'city' => $this->faker->city(),
            'country' => 'Nederland',
            'network_operator' => $this->faker->company(),
            'cost_per_kwh_in_euro' => $this->faker->randomFloat(2,0.21, 0.25),
        ];
    }
}
