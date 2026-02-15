<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCosts extends Model
{
    /** @use HasFactory<\Database\Factories\DailyCostsFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ean_code',
        'kwh_used'
    ];

    public function ean(): BelongsTo
    {
        return $this->belongsTo(Ean::class, 'ean_code', 'code');
    }
}
