<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ean extends Model
{
    /** @use HasFactory<\Database\Factories\EanFactory> */
    use HasFactory;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'street',
        'house_number',
        'house_number_addition',
        'city',
        'country',
        'network_operator',
        'cost_per_kwh_in_euro',
    ];

    public function meterReadings(): HasMany
    {
        return $this->hasMany(MeterReadings::class, 'ean_code', 'code');
    }

    public function dailyCosts(): HasMany
    {
        return $this->hasMany(DailyCosts::class, 'ean_code', 'code');
    }
}
