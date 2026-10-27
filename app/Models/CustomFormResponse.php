<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomFormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_form_id',
        'response_code',
        'ip_address',
        'user_agent',
        'submitted_at',
        'certificate_number',
        'participant_name_cache',
        'participant_phone_cache',
        'participant_inst_cache',
        'certificate_issued_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
    ];

    public function form()
    {
        return $this->belongsTo(CustomForm::class, 'custom_form_id');
    }

    public function answers()
    {
        return $this->hasMany(CustomFormResponseAnswer::class, 'response_id');
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'RSP-' . strtoupper(Str::random(8));
        } while (static::where('response_code', $code)->exists());

        return $code;
    }

    public function getParticipantNameAttribute(): string
    {
        if (!empty($this->participant_name_cache)) {
            return $this->participant_name_cache;
        }

        // Try mapped field
        if ($this->form && $this->form->certificate_name_field_id) {
            $ans = $this->answers->firstWhere('field_id', $this->form->certificate_name_field_id);
            if ($ans && !empty(trim($ans->answer_value))) {
                return trim($ans->answer_value);
            }
        }

        // Fallback: search answer where field label contains 'nama'
        foreach ($this->answers as $a) {
            $label = strtolower($a->field?->label ?? '');
            if (str_contains($label, 'nama')) {
                return trim((string)$a->answer_value);
            }
        }

        return 'Peserta Kegiatan';
    }

    public function getParticipantPhoneAttribute(): ?string
    {
        if (!empty($this->participant_phone_cache)) {
            return $this->participant_phone_cache;
        }

        if ($this->form && $this->form->certificate_phone_field_id) {
            $ans = $this->answers->firstWhere('field_id', $this->form->certificate_phone_field_id);
            if ($ans && !empty(trim($ans->answer_value))) {
                return trim($ans->answer_value);
            }
        }

        foreach ($this->answers as $a) {
            $label = strtolower($a->field?->label ?? '');
            if (str_contains($label, 'wa') || str_contains($label, 'whatsapp') || str_contains($label, 'hp') || str_contains($label, 'telepon') || str_contains($label, 'phone')) {
                return trim((string)$a->answer_value);
            }
        }

        return null;
    }

    public function getParticipantInstitutionAttribute(): ?string
    {
        if (!empty($this->participant_inst_cache)) {
            return $this->participant_inst_cache;
        }

        if ($this->form && $this->form->certificate_inst_field_id) {
            $ans = $this->answers->firstWhere('field_id', $this->form->certificate_inst_field_id);
            if ($ans && !empty(trim($ans->answer_value))) {
                return trim($ans->answer_value);
            }
        }

        foreach ($this->answers as $a) {
            $label = strtolower($a->field?->label ?? '');
            if (str_contains($label, 'instansi') || str_contains($label, 'sekolah') || str_contains($label, 'lembaga') || str_contains($label, 'kampus') || str_contains($label, 'universitas')) {
                return trim((string)$a->answer_value);
            }
        }

        return null;
    }

    public function ensureCertificateNumber(): string
    {
        if (!empty($this->certificate_number)) {
            return $this->certificate_number;
        }

        $form = $this->form;
        if (!$form) {
            return 'CERT-' . $this->response_code;
        }

        // Count responses in this form created before this one
        $seq = $form->responses()->where('id', '<=', $this->id)->count();
        if ($seq < 1) $seq = 1;

        $certNo = $form->generateCertificateNumber($seq);

        $this->update([
            'certificate_number' => $certNo,
            'certificate_issued_at' => now(),
            'participant_name_cache' => $this->participant_name,
            'participant_phone_cache' => $this->participant_phone,
            'participant_inst_cache' => $this->participant_institution,
        ]);

        return $certNo;
    }

    public function getCertificateUrlAttribute(): string
    {
        return route('public.form.view_certificate', [
            'slug' => $this->form?->slug ?? 'event',
            'response_code' => $this->response_code
        ]);
    }
}
