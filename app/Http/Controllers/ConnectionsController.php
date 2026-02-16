<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConnectionsReadingsRequest;
use App\Models\Ean;
use App\Models\MeterReadings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConnectionsController extends Controller
{
    public function readings(StoreConnectionsReadingsRequest $request, Ean $ean): jsonResponse
    {
        $data = $request->validated();

        $meterReadings = MeterReadings::create([
            'ean_code' => $ean->code,
            'kwh_total' => $data['kwh_total'],
            'timestamp' => $data['timestamp'],
        ]);

        if($meterReadings->id) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failed']);
    }
}
