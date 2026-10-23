<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'training_id',
        'batch_id',
        'user_id',
        'participant_name',
        'email',
        'phone',
        'institution',
        'price',
        'payment_status',
        'registration_status',
        'payment_proof_path',
        'admin_notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function training()
    {
        return $this->belongsTo(TrainingProgram::class, 'training_id');
    }

    public function batch()
    {
        return $this->belongsTo(TrainingBatch::class, 'batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getWhatsappConfirmationUrlAttribute(): string
    {
        // Try training contact person first, or general default admin WA
        $rawPhone = $this->training?->contact_person;
        if (empty($rawPhone)) {
            $rawPhone = '6281234567890'; // default Ruhiology Admin WA
        }

        // Clean phone number to international format
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $trainingTitle = $this->training?->title ?? 'Pelatihan Ruhiology';
        $batchName = $this->batch?->batch_name ?? '-';
        $nominal = 'Rp ' . number_format($this->price, 0, ',', '.');

        $message = "Halo Admin Ruhiology Institute,\n\n"
            . "Saya ingin konfirmasi pendaftaran pelatihan:\n"
            . "• No. Registrasi: *{$this->registration_number}*\n"
            . "• Nama Peserta: *{$this->participant_name}*\n"
            . "• Program: *{$trainingTitle}*\n"
            . "• Batch/Jadwal: *{$batchName}*\n"
            . "• Investasi: *{$nominal}*\n\n"
            . "Mohon verifikasi status pendaftaran saya. Terima kasih.";

        return 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($message);
    }
}
