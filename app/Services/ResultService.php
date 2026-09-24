<?php

namespace App\Services;

use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\AssessmentSubmission;
use App\Models\ResultDimension;

class ResultService
{
    protected ScoringService $scoringService;
    protected RqiScoringEngine $rqiScoringEngine;

    public function __construct(ScoringService $scoringService, RqiScoringEngine $rqiScoringEngine)
    {
        $this->scoringService = $scoringService;
        $this->rqiScoringEngine = $rqiScoringEngine;
    }

    /**
     * Compute and store assessment result for a submission.
     */
    public function generateResult(AssessmentSubmission $submission): AssessmentResult
    {
        $submission->load(['answers.question.dimension', 'period.instrument.scoringRules', 'period.instrument.dimensions']);
        $instrument = $submission->period->instrument;
        $scoringRule = $instrument->scoringRules;

        $totalScore = 0;
        $maxScore = 0;
        $dimensionScores = [];

        $who5RawScore = 0;
        $hasWho5 = false;

        // Group answers by dimension
        foreach ($submission->answers as $answer) {
            $question = $answer->question;
            if (!$question) continue;

            $dimensionId = $question->dimension_id;

            if ($question->instrument_id != $instrument->id) {
                // WHO-5 item
                $hasWho5 = true;
                $val = (int) $answer->raw_value;
                $who5ItemScore = match($val) {
                    5 => 5,
                    4 => 4,
                    3 => 3,
                    2 => 1,
                    1 => 0,
                    default => max(0, min(5, $val)),
                };
                $who5RawScore += $who5ItemScore;
                continue;
            }

            if (!isset($dimensionScores[$dimensionId])) {
                $dimensionScores[$dimensionId] = [
                    'dimension_id' => $dimensionId,
                    'score' => 0,
                    'max_score' => 0,
                ];
            }

            $optionMax = $scoringRule?->scale_max ?? 5;

            $dimensionScores[$dimensionId]['score'] += $answer->calculated_score;
            $dimensionScores[$dimensionId]['max_score'] += $optionMax;

            $totalScore += $answer->calculated_score;
            $maxScore += $optionMax;
        }

        $maxScore = $maxScore > 0 ? $maxScore : 75;
        $overallPercentage = round(($totalScore / $maxScore) * 100, 2);
        $rqiCat = $this->rqiScoringEngine->getCategoryLevel($totalScore);
        $overallInterpretation = $rqiCat['description'];

        // WHO-5 calculations
        $who5Data = null;
        if ($hasWho5) {
            $who5Data = $this->rqiScoringEngine->calculateWho5Score($who5RawScore);
        }

        // Check if posttest to calculate pre/post diff
        $prePostDiff = null;
        if ($submission->submission_type === 'posttest') {
            $pretestSubmission = AssessmentSubmission::where('period_id', $submission->period_id)
                ->where('participant_id', $submission->participant_id)
                ->where('submission_type', 'pretest')
                ->where('status', 'submitted')
                ->first();

            if ($pretestSubmission && $pretestSubmission->result) {
                $prePostDiff = round($totalScore - ($pretestSubmission->result->rqi_score ?? $pretestSubmission->result->total_score), 2);
            }
        }

        // Create Snapshot
        $snapshot = [
            'instrument_code' => $instrument->code,
            'version' => $instrument->version,
            'total_score' => $totalScore,
            'category' => $rqiCat,
            'who5' => $who5Data,
            'dimension_scores' => $dimensionScores,
        ];

        $result = AssessmentResult::create([
            'submission_id' => $submission->id,
            'total_score' => $totalScore,
            'rqi_score' => $totalScore,
            'category_name' => $rqiCat['name'],
            'max_score' => $maxScore,
            'percentage' => $overallPercentage,
            'overall_interpretation' => $overallInterpretation,
            'who5_raw_score' => $who5Data ? $who5Data['raw_score'] : null,
            'who5_percentage' => $who5Data ? $who5Data['percentage'] : null,
            'who5_screening_note' => $who5Data ? $who5Data['screening_note'] : null,
            'pre_post_diff' => $prePostDiff,
            'scoring_version' => $instrument->version ?? '1.0',
            'snapshot_data' => $snapshot,
        ]);

        foreach ($dimensionScores as $dimData) {
            $dimPct = $dimData['max_score'] > 0 ? round(($dimData['score'] / $dimData['max_score']) * 100, 2) : 0;
            $dimInterp = $this->scoringService->getInterpretation($dimPct, $scoringRule);

            ResultDimension::create([
                'result_id' => $result->id,
                'dimension_id' => $dimData['dimension_id'],
                'score' => $dimData['score'],
                'max_score' => $dimData['max_score'],
                'percentage' => $dimPct,
                'interpretation' => $dimInterp,
            ]);
        }

        return $result;
    }
}
