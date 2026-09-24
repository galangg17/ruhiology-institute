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
        'description',
        'version',
        'instructions',
        'status',
    ];

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
