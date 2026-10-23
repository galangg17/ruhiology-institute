<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'syllabus',
        'facilities',
        'target_audience',
        'category',
        'image',
        'trainer',
        'trainer_bio',
        'duration',
        'location',
        'contact_person',
        'is_online',
        'price',
        'quota',
        'status',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function batches()
    {
        return $this->hasMany(TrainingBatch::class, 'training_id');
    }

    public function registrations()
    {
        return $this->hasMany(TrainingRegistration::class, 'training_id');
    }

    public function getParsedSyllabusAttribute(): array
    {
        if (empty($this->syllabus)) {
            return [];
        }
        $decoded = json_decode($this->syllabus, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        // Fallback: split by lines
        return array_values(array_filter(array_map('trim', explode("\n", $this->syllabus))));
    }

    public function getParsedFacilitiesAttribute(): array
    {
        if (empty($this->facilities)) {
            return [
                'E-Sertifikat Resmi Ber-SK Ruhiology Institute',
                'Modul Pelatihan & Handbook Komprehensif',
                'Akses Rekaman Sesi & Materi Presentasi',
                'Grup Diskusi & Jejaring Praktisi / Fasilitator',
                'Konsultasi Pasca Pelatihan dengan Tim Ahli'
            ];
        }
        $decoded = json_decode($this->facilities, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return array_values(array_filter(array_map('trim', explode("\n", $this->facilities))));
    }
}
