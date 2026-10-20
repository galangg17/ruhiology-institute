<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instrument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'description',
        'description_en',
        'version',
        'instructions',
        'status',
        'access_type',
    ];

    public function getLocalizedNameAttribute(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $this->name ?? '';
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        return $this->description ?? '';
    }

    public function dimensions()
    {
        return $this->hasMany(Dimension::class)->orderBy('order');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function scoringRules()
    {
        return $this->hasOne(ScoringRule::class);
    }

    public function periods()
    {
        return $this->hasMany(AssessmentPeriod::class);
    }
}
