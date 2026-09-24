<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_id',
        'scale_min',
        'scale_max',
        'reverse_mapping',
        'interpretation_ranges',
    ];

    protected $casts = [
        'reverse_mapping' => 'array',
        'interpretation_ranges' => 'array',
    ];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
