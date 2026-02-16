<?php

namespace Database\Seeders;

use App\Models\DailyCosts;
use App\Models\Ean;
use App\Models\MeterReadings;
use App\Models\User;
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

        $amount_of_days = 28;

        foreach ($eans as $ean) {

            // Set a base for the current kwh
            $kwh_total = fake()->randomFloat(2, 1000, 2000);
            $kwh_increase = fake()->numberBetween(5,30);

            // Loop though the n of days
            for($i = 1; $i <= $amount_of_days; $i++) {

                // Set the second week a higher usage
                if($i % 7 === 0) {
                    $kwh_increase *= 1.15;
                }

                // Create meter_readings for a day, add in sequence (15 min) kwh and update timestamp
                MeterReadings::factory()
                         ->count(24 * 4)
                         ->sequence(function (Sequence $sequence) use ($kwh_total, $kwh_increase, $amount_of_days, $i) {
                             return [
                                 'kwh_total' => (($kwh_total + $kwh_increase * $i) + $sequence->index * ($kwh_increase / ($i * 24 * 4))),
                                 'timestamp' => now()->subDays($amount_of_days)->startOfDay()->addDays($i)->addMinutes($sequence->index * 15)
                             ];
                         })
                         ->recycle($ean)
                         ->create();

                // Calculate and insert the dailyCosts
                DailyCosts::factory(1)->recycle($ean)->create(
                    [
                        'timestamp' => now()->subDays($amount_of_days)->endOfDay()->addDays($i),
                        'kwh_used' => $kwh_increase,
                        'cost_in_euro' => $ean->cost_per_kwh_in_euro * $kwh_increase,
                    ]
                );
            }
        }

        // Set our test user to send notifications to
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
