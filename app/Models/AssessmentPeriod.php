<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'instrument_id',
        'title',
        'period_code',
        'pretest_start',
        'pretest_end',
        'posttest_start',
        'posttest_end',
        'status',
    ];

    protected $casts = [
        'pretest_start' => 'datetime',
        'pretest_end' => 'datetime',
        'posttest_start' => 'datetime',
        'posttest_end' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssessmentSubmission::class, 'period_id');
    }

    public function isPretestActive(): bool
    {
        if ($this->status !== 'active') return false;
        $now = now();
        if ($this->pretest_start && $now->lt($this->pretest_start)) return false;
        if ($this->pretest_end && $now->gt($this->pretest_end)) return false;
        return true;
    }

    public function isPosttestActive(): bool
    {
        if ($this->status !== 'active') return false;
        $now = now();
        if ($this->posttest_start && $now->lt($this->posttest_start)) return false;
        if ($this->posttest_end && $now->gt($this->posttest_end)) return false;
        return true;
    }
}
