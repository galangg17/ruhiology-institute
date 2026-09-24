<?php

namespace Database\Seeders;

use App\Models\AssessmentPeriod;
use App\Models\Institution;
use App\Models\Participant;
use App\Models\Program;
use App\Models\User;
use App\Services\AssessmentService;
use Illuminate\Database\Seeder;

class ParticipantAndAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $institution = Institution::first();
        $program = Program::first();
        $period = AssessmentPeriod::first();
        $user = User::where('role', 'participant')->first();

        if (!$institution || !$program || !$period) {
            return;
        }

        // Participant 1 (with pretest & posttest)
        $p1 = Participant::create([
            'institution_id' => $institution->id,
            'program_id' => $program->id,
            'user_id' => $user?->id,
            'participant_code' => 'MHS-2026-001',
            'name' => 'Ahmad Fauzi',
            'email' => 'fauzi@uinjambi.ac.id',
            'phone' => '085266123456',
            'batch' => 'Angkatan 2026',
            'gender' => 'Laki-laki',
            'occupation' => 'Mahasiswa',
            'status' => 'active',
        ]);

        // Participant 2 (with pretest only)
        $p2 = Participant::create([
            'institution_id' => $institution->id,
            'program_id' => $program->id,
            'participant_code' => 'MHS-2026-002',
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@uinjambi.ac.id',
            'phone' => '081388776655',
            'batch' => 'Angkatan 2026',
            'gender' => 'Perempuan',
            'occupation' => 'Mahasiswa',
            'status' => 'active',
        ]);

        // Participant 3 (with pretest only)
        $p3 = Participant::create([
            'institution_id' => $institution->id,
            'program_id' => $program->id,
            'participant_code' => 'MHS-2026-003',
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@uinjambi.ac.id',
            'phone' => '082199887766',
            'batch' => 'Angkatan 2026',
            'gender' => 'Laki-laki',
            'occupation' => 'Mahasiswa',
            'status' => 'active',
        ]);

        $assessmentService = app(AssessmentService::class);
        $questions = $period->instrument->questions;

        if ($questions->isEmpty()) return;

        // Pretest answers for P1 (Initial state - e.g. moderate scores)
        $p1PreAnswers = [];
        foreach ($questions as $q) {
            // Give 3 or 4 for normal items, 2 or 3 for reverse items
            $p1PreAnswers[$q->id] = $q->scoring_direction === 'normal' ? 3 : 3;
        }
        $assessmentService->submitAssessment($period, $p1, 'pretest', $p1PreAnswers);

        // Posttest answers for P1 (Post-training state - improved scores)
        $p1PostAnswers = [];
        foreach ($questions as $q) {
            // Give 5 for normal items, 1 or 2 for reverse items (so reverse item score becomes 4 or 5)
            $p1PostAnswers[$q->id] = $q->scoring_direction === 'normal' ? 5 : 1;
        }
        $assessmentService->submitAssessment($period, $p1, 'posttest', $p1PostAnswers);

        // Pretest answers for P2
        $p2PreAnswers = [];
        foreach ($questions as $q) {
            $p2PreAnswers[$q->id] = $q->scoring_direction === 'normal' ? 4 : 2;
        }
        $assessmentService->submitAssessment($period, $p2, 'pretest', $p2PreAnswers);

        // Pretest answers for P3
        $p3PreAnswers = [];
        foreach ($questions as $q) {
            $p3PreAnswers[$q->id] = $q->scoring_direction === 'normal' ? 2 : 4;
        }
        $assessmentService->submitAssessment($period, $p3, 'pretest', $p3PreAnswers);
    }
}
