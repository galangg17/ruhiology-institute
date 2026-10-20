<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'option_text',
        'option_text_en',
        'option_value',
        'order',
    ];

    public function getLocalizedTextAttribute(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->option_text_en)) {
            return $this->option_text_en;
        }
        return $this->option_text ?? '';
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
