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
        'question_text_en',
        'type',
        'scoring_direction',
        'order',
        'status',
    ];

    public function getLocalizedTextAttribute(): string
    {
        if (app()->getLocale() === 'en') {
            if (!empty($this->question_text_en)) {
                return $this->question_text_en;
            }
            
            $fallback = \App\Services\TranslationService::getQuestionEnglishFallback($this);
            if (!empty($fallback)) {
                return $fallback;
            }
        }
        return $this->question_text ?? '';
    }

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
