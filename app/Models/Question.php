<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_id',
        'dimension_id',
        'indicator_id',
        'question_text',
        'type',
        'scoring_direction',
        'order',
        'status',
    ];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }
}
