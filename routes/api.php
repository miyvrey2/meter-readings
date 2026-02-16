<?php

use App\Http\Controllers\ConnectionsController;
use Illuminate\Support\Facades\Route;

Route::post('connections/{ean}/readings', [ConnectionsController::class, 'readings']);
