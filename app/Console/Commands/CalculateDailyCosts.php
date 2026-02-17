<?php

namespace App\Console\Commands;

use App\Models\DailyCosts;
use App\Models\Connection;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateDailyCosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-daily-costs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the costs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $connections = Connection::with('meterReadings')->get();

        foreach ($connections as $connection) {

            // find all meter-readings of today
            $readings = $connection->meterReadings()
                            ->whereDate('timestamp', Carbon::now())
                            ->get();

            // Subtract the first kwh reading record of the last to get the kwh_used
            $kwh_used = ((float) $readings->last()->kwh_total - (float) $readings->first()->kwh_total) ?? 0.0;

            // Store the dailyCost into the database
            DailyCosts::create([
                'ean_code' => $connection->ean_code,
                'kwh_used' => $kwh_used,
                'cost_in_euro' => $connection->cost_per_kwh_in_euro * $kwh_used,
                'timestamp' => Carbon::now()->startOfDay(),
            ]);
        }
    }
}
