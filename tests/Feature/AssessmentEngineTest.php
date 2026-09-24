<?php

namespace Tests\Feature;

use App\Models\AssessmentPeriod;
use App\Models\Dimension;
use App\Models\Institution;
use App\Models\Instrument;
use App\Models\Participant;
use App\Models\Program;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\ScoringRule;
use App\Services\AssessmentService;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Exception;

class AssessmentEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_assessment_flow_and_anti_duplicate_prevention(): void
    {
        $inst = Institution::create([
            'code' => 'UIN-STS', 'name' => 'UIN Jambi', 'status' => 'active'
        ]);

        $prog = Program::create([
            'institution_id' => $inst->id, 'code' => 'PROG-1', 'name' => 'Program RQ', 'status' => 'active'
        ]);

        $part = Participant::create([
            'institution_id' => $inst->id,
            'program_id' => $prog->id,
            'participant_code' => 'MHS-001',
            'name' => 'Budi',
            'email' => 'budi@test.com',
            'status' => 'active',
        ]);

        $instrument = Instrument::create([
            'code' => 'RQI-1', 'name' => 'RQ Test', 'status' => 'active'
        ]);

        ScoringRule::create([
            'instrument_id' => $instrument->id,
            'scale_min' => 1,
            'scale_max' => 5,
            'reverse_mapping' => ['1' => 5, '2' => 4, '3' => 3, '4' => 2, '5' => 1],
        ]);

        $dim = Dimension::create([
            'instrument_id' => $instrument->id, 'code' => 'DIM-1', 'name' => 'Spiritual Awareness', 'order' => 1
        ]);

        // Normal question
        $qNormal = Question::create([
            'instrument_id' => $instrument->id, 'dimension_id' => $dim->id,
            'question_text' => 'Pernyataan normal', 'type' => 'likert',
            'scoring_direction' => 'normal', 'order' => 1, 'status' => 'active'
        ]);

        // Reverse question
        $qReverse = Question::create([
            'instrument_id' => $instrument->id, 'dimension_id' => $dim->id,
            'question_text' => 'Pernyataan reverse', 'type' => 'likert',
            'scoring_direction' => 'reverse', 'order' => 2, 'status' => 'active'
        ]);

        $period = AssessmentPeriod::create([
            'program_id' => $prog->id,
            'instrument_id' => $instrument->id,
            'title' => 'Periode Test',
            'period_code' => 'PER-1',
            'pretest_start' => now()->subDay(),
            'pretest_end' => now()->addDay(),
            'posttest_start' => now()->subDay(),
            'posttest_end' => now()->addDay(),
            'status' => 'active',
        ]);

        $assessmentService = app(AssessmentService::class);

        // 1. Submit Pretest
        $preAnswers = [
            $qNormal->id => 4,   // Score = 4
            $qReverse->id => 2,  // Reverse mapping 2 => 4. Total = 8 / 10 = 80%
        ];

        $preResult = $assessmentService->submitAssessment($period, $part, 'pretest', $preAnswers);
        $this->assertEquals(80.0, $preResult->percentage);
    }

    public function test_opt_prefix_answers_parsing(): void
    {
        $inst = Institution::create(['code' => 'INST-2', 'name' => 'Inst 2', 'status' => 'active']);
        $prog = Program::create(['institution_id' => $inst->id, 'code' => 'PROG-2', 'name' => 'Prog 2', 'status' => 'active']);
        $part = Participant::create(['institution_id' => $inst->id, 'program_id' => $prog->id, 'participant_code' => 'PAR-002', 'name' => 'Siti', 'email' => 'siti@test.com', 'status' => 'active']);
        $instrument = Instrument::create(['code' => 'RQI-2', 'name' => 'RQ Test 2', 'status' => 'active']);
        $dim = Dimension::create(['instrument_id' => $instrument->id, 'code' => 'DIM-2', 'name' => 'Dim 2', 'order' => 1]);

        $question = Question::create([
            'instrument_id' => $instrument->id, 'dimension_id' => $dim->id,
            'question_text' => 'Pernyataan test', 'type' => 'likert',
            'scoring_direction' => 'normal', 'order' => 1, 'status' => 'active'
        ]);

        $option = QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Sepanjang Waktu',
            'option_value' => 5,
            'order' => 1
        ]);

        $period = AssessmentPeriod::create([
            'program_id' => $prog->id,
            'instrument_id' => $instrument->id,
            'title' => 'Periode 2',
            'period_code' => 'PER-2',
            'pretest_start' => now()->subDay(),
            'pretest_end' => now()->addDay(),
            'status' => 'active',
        ]);

        $service = app(AssessmentService::class);
        $result = $service->submitAssessment($period, $part, 'pretest', [
            $question->id => 'opt_' . $option->id
        ]);

        $this->assertEquals(5, $result->total_score);
        $this->assertEquals(100.0, $result->percentage);
    }
}
