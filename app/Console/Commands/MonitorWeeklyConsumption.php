<?php

namespace App\Console\Commands;

use App\Models\Connection;
use App\Models\User;
use App\Notifications\WeekConsumptionChanged;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class MonitorWeeklyConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monitor-weekly-consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the last two full weeks of consumption and notifies when changed drastically';

    /**
     * The amount of difference in percentage
     *
     * @var int
     */
    protected int $differ = 10;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Get the last two full weeks (from Monday to Sunday)
        $startOfWeek = Carbon::now()->startOfWeek()->startOfDay()->subWeek();
        $endOfWeek = Carbon::now()->endOfWeek()->endOfDay()->subWeek();
        $startOfPrevWeek = $startOfWeek->copy()->subWeek();
        $endOfPrevWeek = $endOfWeek->copy()->subWeek();

        $connections = Connection::with('meterReadings')->get();

        foreach ($connections as $connection) {
            $WeekTotal = $connection->dailyCosts()
                                 ->whereBetween('timestamp', [$startOfWeek, $endOfWeek])
                                 ->sum('kwh_used');

            $prevWeekTotal = $connection->dailyCosts()
                                 ->whereBetween('timestamp', [$startOfPrevWeek, $endOfPrevWeek])
                                 ->sum('kwh_used');

            if($prevWeekTotal > 0) {
                $diffInPercentages = (($WeekTotal - $prevWeekTotal) / $prevWeekTotal) * 100;

                // when the difference is to great, notify the user
                if($diffInPercentages > $this->differ) {

                    // Since there is no implementation for users and ean yet, get the testUser
                    $user = User::find(1)->first();

                    Notification::send($user, new WeekConsumptionChanged($connection));
                }
            }
        }
    }
}
