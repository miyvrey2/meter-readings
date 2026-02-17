<?php

use App\Http\Controllers\ConnectionReadingController;
use Illuminate\Support\Facades\Route;

Route::apiResource('connections.readings', ConnectionReadingController::class)->only(['store']);
