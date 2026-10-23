<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFormResponseAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'field_id',
        'answer_value',
    ];

    public function response()
    {
        return $this->belongsTo(CustomFormResponse::class, 'response_id');
    }

    public function field()
    {
        return $this->belongsTo(CustomFormField::class, 'field_id');
    }

    public function getFormattedAnswerAttribute(): string
    {
        if (empty($this->answer_value)) {
            return '-';
        }

        // Try decoding if JSON array (e.g. for checkboxes)
        $decoded = json_decode($this->answer_value, true);
        if (is_array($decoded)) {
            return implode(', ', $decoded);
        }

        return (string) $this->answer_value;
    }
}
