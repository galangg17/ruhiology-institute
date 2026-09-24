<?php

namespace Tests\Feature;

use App\Models\TrainingBatch;
use App\Models\TrainingProgram;
use App\Services\TrainingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_can_register_for_training(): void
    {
        $training = TrainingProgram::create([
            'title' => 'Training RQ',
            'slug' => 'training-rq',
            'description' => 'Pelatihan RQ',
            'category' => 'Sertifikasi',
            'trainer' => 'Prof. Iskandar',
            'duration' => '2 Hari',
            'location' => 'Online',
            'is_online' => true,
            'price' => 500000,
            'quota' => 30,
            'status' => 'published',
        ]);

        $batch = TrainingBatch::create([
            'training_id' => $training->id,
            'batch_name' => 'Batch 1',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(6),
            'registration_open' => now()->subDay(),
            'registration_close' => now()->addDays(4),
            'quota' => 30,
            'status' => 'open',
        ]);

        $trainingService = app(TrainingService::class);
        $registration = $trainingService->registerParticipant($training, $batch, [
            'participant_name' => 'Ahmad',
            'email' => 'ahmad@test.com',
            'phone' => '08123456789',
        ]);

        $this->assertDatabaseHas('training_registrations', [
            'id' => $registration->id,
            'participant_name' => 'Ahmad',
            'payment_status' => 'pending',
        ]);
    }
}
