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
        'institution_name',
        'program_id',
        'instrument_id',
        'start_date',
        'end_date',
        'quota',
        'status',
        'description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
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
}
