<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institution_id',
        'program_id',
        'user_id',
        'participant_code',
        'assessment_code',
        'name',
        'birth_date',
        'category',
        'email',
        'phone',
        'batch',
        'gender',
        'country_id',
        'province_id',
        'regency_id',
        'school_id',
        'university_id',
        'faculty_id',
        'study_program_id',
        'school_level',
        'school_class',
        'semester',
        'entry_year',
        'occupation_id',
        'occupation_custom',
        'occupation',
        'status',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function occupationModel()
    {
        return $this->belongsTo(Occupation::class, 'occupation_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    public static function generateUniqueAssessmentCode(): string
    {
        do {
            $code = 'RQI-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (static::where('assessment_code', $code)->exists());

        return $code;
    }
}
