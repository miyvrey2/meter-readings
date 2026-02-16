<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Run the price calculator daily at the end of the day
Schedule::command('app:calculate-daily-costs')->dailyAt('23:59');
