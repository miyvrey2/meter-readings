<?php

namespace Database\Seeders;

use App\Models\DailyCosts;
use App\Models\Connection;
use App\Models\MeterReadings;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create random connections
        $connections = Connection::factory(100)->create();

        $amount_of_days = 28;
        $amount_of_inserts_per_day = 96;

        foreach ($connections as $connection) {

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
                $meterRows = [];

                for ($j = 0; $j < $amount_of_inserts_per_day; $j++) {
                    $meterRows[] = [
                        'ean_code' => $connection->ean_code,
                        'kwh_total' => (($kwh_total + $kwh_increase * $i) + $j * ($kwh_increase / ($i * $amount_of_inserts_per_day))),
                        'timestamp' => now()->subDays($amount_of_days)
                                            ->startOfDay()
                                            ->addDays($i)
                                            ->addMinutes($j * (1440 / $amount_of_inserts_per_day)),
                    ];
                }
                MeterReadings::insert($meterRows);

                // Calculate and insert the dailyCosts
                DailyCosts::insert([
                    'ean_code' => $connection->ean_code,
                    'timestamp' => now()->subDays($amount_of_days)->endOfDay()->addDays($i),
                    'kwh_used' => $kwh_increase,
                    'cost_in_euro' => $connection->cost_per_kwh_in_euro * $kwh_increase,
                ]);
            }
        }

        // Set our test user to send notifications to
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
