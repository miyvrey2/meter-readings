<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReadings extends Model
{
    /** @use HasFactory<\Database\Factories\MeterReadingsFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ean_code',
        'kwh_total',
        'timestamp',
    ];

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class, 'ean_code', 'ean_code');
    }
}
