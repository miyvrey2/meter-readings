<?php


// Run the price calculator daily at the end of the day
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:calculate-daily-costs')->dailyAt('23:59');
