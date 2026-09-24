<?php

namespace App\Services;

use App\Models\AssessmentAnswer;
use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\AssessmentSubmission;
use App\Models\Participant;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class AssessmentService
{
    protected ScoringService $scoringService;
    protected ResultService $resultService;

    public function __construct(ScoringService $scoringService, ResultService $resultService)
    {
        $this->scoringService = $scoringService;
        $this->resultService = $resultService;
    }

    /**
     * Validate participant eligibility before starting an assessment.
     */
    public function validateEligibility(string $participantCode, string $periodCode, string $type): array
    {
        $participant = Participant::where('participant_code', $participantCode)->first();
        if (!$participant) {
            return ['eligible' => false, 'message' => 'Kode Peserta tidak ditemukan dalam database.'];
        }

        if ($participant->status !== 'active') {
            return ['eligible' => false, 'message' => 'Status kepesertaan Anda tidak aktif. Silakan hubungi admin.'];
        }

        $period = AssessmentPeriod::where('period_code', $periodCode)->first();
        if (!$period) {
            return ['eligible' => false, 'message' => 'Periode assessment tidak ditemukan.'];
        }

        if ($type === 'pretest' && !$period->isPretestActive()) {
            return ['eligible' => false, 'message' => 'Periode Pretest belum dibuka atau telah berakhir.'];
        }

        if ($type === 'posttest' && !$period->isPosttestActive()) {
            return ['eligible' => false, 'message' => 'Periode Posttest belum dibuka atau telah berakhir.'];
        }

        // Anti-Duplicate Check
        $existing = AssessmentSubmission::where('period_id', $period->id)
            ->where('participant_id', $participant->id)
            ->where('submission_type', $type)
            ->where('status', 'submitted')
            ->first();

        if ($existing) {
            return ['eligible' => false, 'message' => "Anda sudah menyelesaikan " . strtoupper($type) . " untuk periode ini."];
        }

        // For posttest, verify pretest completion
        if ($type === 'posttest') {
            $pretest = AssessmentSubmission::where('period_id', $period->id)
                ->where('participant_id', $participant->id)
                ->where('submission_type', 'pretest')
                ->where('status', 'submitted')
                ->first();

            if (!$pretest) {
                return ['eligible' => false, 'message' => 'Anda harus mengisi Pretest terlebih dahulu sebelum mengikuti Posttest.'];
            }
        }

        return [
            'eligible' => true,
            'participant' => $participant,
            'period' => $period,
        ];
    }

    /**
     * Submit answers and finalize assessment transactionally.
     */
    public function submitAssessment(
        AssessmentPeriod $period,
        Participant $participant,
        string $type,
        array $answersData,
        string $ipAddress = '127.0.0.1'
    ): AssessmentResult {
        return DB::transaction(function () use ($period, $participant, $type, $answersData, $ipAddress) {
            // Re-check anti duplicate inside transaction
            $existing = AssessmentSubmission::where('period_id', $period->id)
                ->where('participant_id', $participant->id)
                ->where('submission_type', $type)
                ->where('status', 'submitted')
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw new Exception("Submission sudah disubmit dan bersifat immutable.");
            }

            $submissionCode = 'SUB-' . strtoupper(Str::random(10));

            $submission = AssessmentSubmission::create([
                'period_id' => $period->id,
                'participant_id' => $participant->id,
                'submission_type' => $type,
                'submission_code' => $submissionCode,
                'status' => 'submitted',
                'started_at' => now(),
                'submitted_at' => now(),
                'ip_address' => $ipAddress,
            ]);

            $instrument = $period->instrument;
            $scoringRule = $instrument->scoringRules;

            foreach ($answersData as $questionId => $val) {
                $question = Question::find($questionId);
                if (!$question) continue;

                $optionId = null;
                $rawValue = 0;

                $valStr = (string) $val;
                if (str_starts_with($valStr, 'opt_')) {
                    $optId = (int) str_replace('opt_', '', $valStr);
                    $option = QuestionOption::find($optId);
                    if ($option) {
                        $optionId = $option->id;
                        $rawValue = $option->option_value;
                    }
                } elseif (is_numeric($val)) {
                    $option = QuestionOption::find((int)$val);
                    if ($option) {
                        $optionId = $option->id;
                        $rawValue = $option->option_value;
                    } else {
                        $rawValue = (int) $val;
                    }
                } else {
                    $rawValue = (int) $val;
                }

                $calculatedScore = $this->scoringService->calculateQuestionScore($question, $rawValue, $scoringRule);

                AssessmentAnswer::create([
                    'submission_id' => $submission->id,
                    'question_id' => $question->id,
                    'question_option_id' => $optionId,
                    'raw_value' => $rawValue,
                    'calculated_score' => $calculatedScore,
                ]);
            }

            $result = $this->resultService->generateResult($submission);

            AuditLogService::log(
                action: 'submit_' . $type,
                module: 'Assessment',
                recordType: 'AssessmentSubmission',
                recordId: (string) $submission->id,
                changes: [
                    'participant_code' => $participant->participant_code,
                    'period_code' => $period->period_code,
                    'total_score' => $result->total_score,
                    'percentage' => $result->percentage,
                ]
            );

            return $result;
        });
    }
}
