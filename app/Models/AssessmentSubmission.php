<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'participant_id',
        'submission_type',
        'submission_code',
        'status',
        'started_at',
        'submitted_at',
        'ip_address',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function period()
    {
        return $this->belongsTo(AssessmentPeriod::class, 'period_id');
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'submission_id');
    }

    public function result()
    {
        return $this->hasOne(AssessmentResult::class, 'submission_id');
    }
}
