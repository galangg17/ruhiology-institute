<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'access_type',
        'sub_category',
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

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

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

    public function getResolvedEventAttribute()
    {
        if ($this->relationLoaded('event') && $this->event) {
            return $this->event;
        }
        if ($this->relationLoaded('participant') && $this->participant && $this->participant->relationLoaded('event') && $this->participant->event) {
            return $this->participant->event;
        }
        if ($this->event_id) {
            return Event::find($this->event_id);
        }
        if ($this->participant && $this->participant->event_id) {
            return Event::find($this->participant->event_id);
        }
        if ($this->period) {
            $pCode = $this->period->period_code ?? '';
            if (str_starts_with($pCode, 'RQI-PER-')) {
                $evtCode = str_replace('RQI-PER-', '', $pCode);
                return Event::where('event_code', $evtCode)->first();
            }
        }
        return null;
    }

    public function getResolvedCategoryAttribute(): string
    {
        $isEn = app()->getLocale() === 'en';
        $event = $this->resolved_event;
        $rawCat = $event?->target_category ?: ($this->participant?->category ?: 'UMUM');
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
        $event = $this->resolved_event;
        $participant = $this->participant;
        $inst = null;

        // 1. From Event institution_name (official event institution set by admin)
        if ($event && !empty(trim((string) $event->institution_name))) {
            $inst = trim((string) $event->institution_name);
        }

        // 2. From Participant custom fields (self assessment or event fallback)
        if (!$inst && $participant) {
            $inst = $participant->school_custom
                ?: $participant->university_custom
                ?: $participant->occupation_custom
                ?: $participant->school?->name
                ?: $participant->university?->name
                ?: null;
        }

        // 3. Extract from sub_category if formatted as "Level — Institution"
        if (!$inst && $participant && !empty($participant->sub_category)) {
            if (str_contains($participant->sub_category, '—')) {
                $parts = explode('—', $participant->sub_category);
                $afterDash = trim((string) end($parts));
                if (!empty($afterDash)) {
                    $inst = $afterDash;
                }
            }
        }

        // 4. Reject generic level names
        $genericLevels = [
            'sma / sederajat', 'smp / sederajat', 'sd / sederajat', 'tk / paud',
            'perguruan tinggi', 'd3', 'd4', 's1', 's2', 's3',
            'sma', 'smk', 'ma', 'smp', 'mts', 'sd', 'mi', 'umum', 'mandiri'
        ];
        if ($inst && in_array(strtolower(trim((string) $inst)), $genericLevels)) {
            $inst = null;
        }

        // 5. Append school class / group if available and meaningful
        $classOrGroup = $participant?->school_class;
        if ($inst && !empty($classOrGroup) && !str_contains($inst, $classOrGroup)) {
            $inst .= " ({$classOrGroup})";
        }

        return $inst;
    }

    public function getResolvedDomicileAttribute(): string
    {
        $p = $this->participant;
        if (!$p) return '';
        $reg = $p->regency?->name ?? '';
        $prov = $p->province?->name ?? '';
        if ($reg && $prov) return "{$reg}, {$prov}";
        if ($reg) return $reg;
        if ($prov) return $prov;
        if ($p->city_custom && $p->state_custom) return "{$p->city_custom}, {$p->state_custom}";
        return $p->city_custom ?: ($p->state_custom ?: ($p->country_name ?: ''));
    }
}
