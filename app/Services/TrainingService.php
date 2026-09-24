<?php

namespace App\Services;

use App\Models\TrainingBatch;
use App\Models\TrainingProgram;
use App\Models\TrainingRegistration;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class TrainingService
{
    public function registerParticipant(
        TrainingProgram $training,
        TrainingBatch $batch,
        array $data,
        ?User $user = null
    ): TrainingRegistration {
        return DB::transaction(function () use ($training, $batch, $data, $user) {
            // Lock batch for update to check quota
            $batchLocked = TrainingBatch::where('id', $batch->id)->lockForUpdate()->first();

            $currentRegistrations = TrainingRegistration::where('batch_id', $batch->id)
                ->whereIn('registration_status', ['approved', 'completed'])
                ->count();

            if ($currentRegistrations >= $batchLocked->quota) {
                throw new Exception("Kuota pendaftaran untuk angkatan ini telah penuh.");
            }

            $regNumber = 'TRG-' . strtoupper(Str::random(8));

            $registration = TrainingRegistration::create([
                'registration_number' => $regNumber,
                'training_id' => $training->id,
                'batch_id' => $batch->id,
                'user_id' => $user?->id,
                'participant_name' => $data['participant_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'institution' => $data['institution'] ?? null,
                'price' => $training->price,
                'payment_status' => $training->price > 0 ? 'pending' : 'verified',
                'registration_status' => 'pending',
                'admin_notes' => null,
            ]);

            AuditLogService::log(
                action: 'create_registration',
                module: 'Training',
                recordType: 'TrainingRegistration',
                recordId: (string) $registration->id,
                changes: [
                    'registration_number' => $regNumber,
                    'training' => $training->title,
                    'batch' => $batch->batch_name,
                ]
            );

            return $registration;
        });
    }
}
