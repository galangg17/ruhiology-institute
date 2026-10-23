<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'access_type',
        'institution_id',
        'program_id',
        'user_id',
        'participant_code',
        'assessment_code',
        'name',
        'birth_date',
        'category',
        'sub_category',
        'email',
        'phone',
        'phone_country_code',
        'batch',
        'gender',
        'country_id',
        'country_code',
        'country_name',
        'state_custom',
        'city_custom',
        'province_id',
        'regency_id',
        'school_id',
        'school_custom',
        'university_id',
        'university_custom',
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

    public function setSchoolCustomAttribute($value)
    {
        $this->attributes['school_custom'] = $value ? strtoupper(trim(preg_replace('/\s+/', ' ', $value))) : null;
    }

    public function setUniversityCustomAttribute($value)
    {
        $this->attributes['university_custom'] = $value ? strtoupper(trim(preg_replace('/\s+/', ' ', $value))) : null;
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

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

    public function getResolvedCategoryAttribute(): string
    {
        $isEn = app()->getLocale() === 'en';
        $rawCat = $this->event?->target_category ?: ($this->category ?: 'UMUM');
        $catUpper = strtoupper(trim((string) $rawCat));

        $labels = [
            'GURU' => $isEn ? 'Teacher / Educator' : 'Guru / Pendidik',
            'DOSEN' => $isEn ? 'Lecturer / Academician' : 'Dosen / Akademisi',
            'MURID' => $isEn ? 'Student (K-12)' : 'Siswa / Pelajar',
            'PELAJAR' => $isEn ? 'Student (K-12)' : 'Siswa / Pelajar',
            'MAHASISWA' => $isEn ? 'University Student' : 'Mahasiswa',
            'ASN' => $isEn ? 'Civil Servant (ASN)' : 'Aparatur Sipil Negara (ASN)',
            'APH' => $isEn ? 'Law Enforcement Officer' : 'Aparat Penegak Hukum (APH)',
            'TENAGA KEPENDIDIKAN' => $isEn ? 'Educational Staff (Tendik)' : 'Tenaga Kependidikan (Tendik)',
            'WARGA BINAAN' => $isEn ? 'Correctional Facility Inmate' : 'Warga Binaan Pemasyarakatan',
            'UMUM' => $isEn ? 'General Public / Professional' : 'Umum / Profesional',
        ];

        return $labels[$catUpper] ?? ucwords(strtolower((string) $rawCat));
    }

    public function getResolvedInstitutionAttribute(): ?string
    {
        $inst = null;

        if ($this->event && !empty(trim((string) $this->event->institution_name))) {
            $inst = trim((string) $this->event->institution_name);
        }

        if (!$inst) {
            $inst = $this->school_custom
                ?: $this->university_custom
                ?: $this->occupation_custom
                ?: $this->school?->name
                ?: $this->university?->name
                ?: null;
        }

        if (!$inst && !empty($this->sub_category) && str_contains($this->sub_category, '—')) {
            $parts = explode('—', $this->sub_category);
            $afterDash = trim((string) end($parts));
            if (!empty($afterDash)) {
                $inst = $afterDash;
            }
        }

        $genericLevels = [
            'sma / sederajat', 'smp / sederajat', 'sd / sederajat', 'tk / paud',
            'perguruan tinggi', 'd3', 'd4', 's1', 's2', 's3',
            'sma', 'smk', 'ma', 'smp', 'mts', 'sd', 'mi', 'umum', 'mandiri',
            'tidak ada', '-', 'none', 'n/a', 'belum bekerja', 'belum / tidak bekerja'
        ];
        if ($inst && in_array(strtolower(trim((string) $inst)), $genericLevels)) {
            $inst = null;
        }

        if ($inst && !empty($this->school_class) && !str_contains($inst, $this->school_class)) {
            $inst .= " ({$this->school_class})";
        }

        return $inst;
    }
}
