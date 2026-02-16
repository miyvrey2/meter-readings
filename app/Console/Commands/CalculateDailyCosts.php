<?php

namespace App\Console\Commands;

use App\Models\DailyCosts;
use App\Models\Ean;
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
        $eans = Ean::all();

        foreach ($eans as $ean) {

            // find all meter-readings of today
            $readings = $ean->meterReadings()
                            ->whereBetween('timestamp', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                            ->orderBy('timestamp')
                            ->get();

            // Subtract the begin kwh of the end to get the kwh_used
            $kwh_used = ((float) $readings->last()->kwh_total - (float) $readings->first()->kwh_total) ?? 0.0;

            // Store the dailyCost into the database
            DailyCosts::create([
                'ean_code' => $ean->code,
                'kwh_used' => $kwh_used,
                'cost_in_euro' => $ean->cost_per_kwh_in_euro * $kwh_used,
                'timestamp' => Carbon::now()->startOfDay(),
            ]);
        }
    }
}
