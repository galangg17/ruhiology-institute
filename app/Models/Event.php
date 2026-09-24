<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_code',
        'title',
        'access_type',
        'assessment_type',
        'target_category',
        'group_label',
        'custom_subcategories',
        'institution_name',
        'province_id',
        'regency_id',
        'program_id',
        'instrument_id',
        'start_date',
        'end_date',
        'pretest_start',
        'pretest_end',
        'posttest_start',
        'posttest_end',
        'quota',
        'status',
        'description',
    ];

    protected $casts = [
        'custom_subcategories' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'event_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssessmentSubmission::class, 'event_id');
    }

    public function getDirectAccessUrlAttribute(): string
    {
        return url('/assessment?event=' . $this->event_code);
    }

    /**
     * Determine active test phase based on current time
     */
    public function activeTestPhase(): string
    {
        if ($this->assessment_type !== 'prepost') {
            return 'single';
        }

        $now = now();

        if ($this->posttest_start && $this->posttest_end && $now->between($this->posttest_start, $this->posttest_end)) {
            return 'posttest';
        }

        if ($this->pretest_start && $this->pretest_end && $now->between($this->pretest_start, $this->pretest_end)) {
            return 'pretest';
        }

        if ($this->posttest_start && $now->greaterThanOrEqualTo($this->posttest_start)) {
            return 'posttest';
        }

        return 'pretest';
    }
}
