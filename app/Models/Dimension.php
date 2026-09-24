<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_id',
        'code',
        'name',
        'description',
        'order',
    ];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class)->orderBy('order');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }
}
