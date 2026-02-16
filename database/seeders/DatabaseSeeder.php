<?php

namespace Database\Seeders;

use App\Models\DailyCosts;
use App\Models\Ean;
use App\Models\MeterReadings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $eans = Ean::factory(10)->create();

        $amount_of_days = 14;

        foreach ($eans as $ean) {
            // Set a base for the current kwh
            $kwh_total = fake()->randomFloat(2, 1000, 2000);
            $kwh_increase = fake()->numberBetween(5,30) * $amount_of_days;

            // Create meter_readings for a day, add in sequence (15 min) kwh and update timestamp
            MeterReadings::factory()
                 ->count($amount_of_days * 24 * 4)
                 ->sequence(function (Sequence $sequence) use ($kwh_total, $kwh_increase, $amount_of_days) {
                        return [
                            'kwh_total' => ($kwh_total + $sequence->index * ($kwh_increase / ($amount_of_days * 24 * 4))),
                            'timestamp' => now()->startOfDay()->addMinutes($sequence->index * 15)
                        ];
                 })
                 ->recycle($ean)
                 ->create();

            DailyCosts::factory(1)->recycle($ean)->create(
                [
                    'timestamp' => now()->endOfDay(),
                    'kwh_used' => $kwh_increase,
                    'cost_in_euro' => $ean->cost_per_kwh_in_euro * $kwh_increase,
                ]
            );
        }
    }
}
