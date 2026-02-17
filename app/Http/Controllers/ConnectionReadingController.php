<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConnectionsReadingsRequest;
use App\Models\Connection;
use App\Models\MeterReadings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConnectionReadingController extends Controller
{
    public function store(StoreConnectionsReadingsRequest $request, Connection $connection): jsonResponse
    {
        $data = $request->validated();

        $meterReadings = MeterReadings::create([
            'ean_code' => $connection->ean_code,
            'kwh_total' => $data['kwh_total'],
            'timestamp' => $data['timestamp'],
        ]);

        if($meterReadings->id) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failed']);
    }
}
