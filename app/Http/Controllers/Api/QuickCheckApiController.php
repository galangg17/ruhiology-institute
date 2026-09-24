<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;
use App\Models\AssessmentSubmission;
use App\Models\Participant;
use Illuminate\Http\Request;

class QuickCheckApiController extends Controller
{
    public function check(Request $request)
    {
        $code = trim((string) $request->input('code'));

        if (empty($code)) {
            return response()->json([
                'found' => false,
                'message' => 'Silakan masukkan kode asesmen atau kode peserta.'
            ], 422);
        }

        $codeUpper = strtoupper($code);

        // 1. Search by submission_code
        $submission = AssessmentSubmission::where('submission_code', $codeUpper)
            ->with(['participant.university', 'participant.school', 'period', 'result'])
            ->first();

        $participant = null;

        if ($submission) {
            $participant = $submission->participant;
        } else {
            // 2. Search by assessment_code or participant_code
            $participant = Participant::where('assessment_code', $codeUpper)
                ->orWhere('participant_code', $codeUpper)
                ->with(['university', 'school'])
                ->first();

            if ($participant) {
                $submission = AssessmentSubmission::where('participant_id', $participant->id)
                    ->where('status', 'submitted')
                    ->orderBy('id', 'desc')
                    ->with(['period', 'result'])
                    ->first();
            }
        }

        if (!$participant && !$submission) {
            return response()->json([
                'found' => false,
                'message' => 'Kode "' . e($code) . '" tidak ditemukan dalam sistem database. Silakan periksa kembali kode Anda.'
            ]);
        }

        // Institution / Organization display
        $institutionName = 'Kategori Umum';
        if ($participant) {
            if ($participant->university) {
                $institutionName = $participant->university->name;
            } elseif ($participant->school) {
                $institutionName = $participant->school->name;
            } elseif ($participant->category) {
                $institutionName = 'Kategori ' . $participant->category;
            }
        }

        // Pretest & Posttest Submissions lookup
        $pretest = AssessmentSubmission::where('participant_id', $participant->id)
            ->where('submission_type', 'pretest')
            ->where('status', 'submitted')
            ->orderBy('id', 'desc')
            ->with(['result'])
            ->first();

        $posttest = AssessmentSubmission::where('participant_id', $participant->id)
            ->where('submission_type', 'posttest')
            ->where('status', 'submitted')
            ->orderBy('id', 'desc')
            ->with(['result'])
            ->first();

        $latestSubmission = $posttest ?? $pretest ?? $submission;

        // Period determination
        $period = $latestSubmission?->period 
            ?? AssessmentPeriod::where('status', 'active')->first();

        // Posttest Eligibility logic
        $canTakePosttest = false;
        $posttestStatusMessage = '';
        $posttestBadgeColor = 'slate';

        if ($posttest) {
            $canTakePosttest = false;
            $posttestStatusMessage = 'Sesi Pretest & Posttest Telah Selesai';
            $posttestBadgeColor = 'emerald';
        } elseif ($pretest) {
            if ($period && $period->isPosttestActive()) {
                $canTakePosttest = true;
                $posttestStatusMessage = 'Sesi Posttest Siap Dikerjakan!';
                $posttestBadgeColor = 'amber';
            } else {
                // Allow taking posttest if active period exists
                $activePeriod = AssessmentPeriod::where('status', 'active')->first();
                if ($activePeriod) {
                    $canTakePosttest = true;
                    $period = $activePeriod;
                    $posttestStatusMessage = 'Sesi Posttest Siap Dikerjakan';
                    $posttestBadgeColor = 'amber';
                } else {
                    $canTakePosttest = false;
                    $posttestStatusMessage = 'Pretest Selesai. Periode Posttest Belum Dibuka oleh Institusi.';
                    $posttestBadgeColor = 'slate';
                }
            }
        } else {
            $canTakePosttest = false;
            $posttestStatusMessage = 'Belum Menyelesaikan Sesi Pretest';
            $posttestBadgeColor = 'slate';
        }

        // Prepare Result Data Payload
        $resultPayload = null;
        if ($latestSubmission && $latestSubmission->result) {
            $resultPayload = [
                'submission_code' => $latestSubmission->submission_code,
                'type' => strtoupper($latestSubmission->submission_type),
                'total_score' => number_format((float) $latestSubmission->result->total_score, 1),
                'rq_level_name' => $latestSubmission->result->rq_level_name ?? 'Kategori Ruhiologi',
                'rq_level_code' => $latestSubmission->result->rq_level_code ?? 'RQ-LEVEL',
                'submitted_at' => $latestSubmission->created_at ? $latestSubmission->created_at->format('d M Y, H:i') : '-',
                'result_url' => route('assessment.result', $latestSubmission->submission_code),
            ];
        }

        return response()->json([
            'found' => true,
            'participant' => [
                'name' => $participant->name ?? 'Peserta Assesmen',
                'participant_code' => $participant->participant_code,
                'assessment_code' => $participant->assessment_code,
                'institution' => $institutionName,
                'category' => $participant->category ?? 'Umum',
            ],
            'latest_result' => $resultPayload,
            'period' => $period ? [
                'period_code' => $period->period_code,
                'title' => $period->title,
            ] : null,
            'posttest' => [
                'can_take' => $canTakePosttest,
                'status_message' => $posttestStatusMessage,
                'badge_color' => $posttestBadgeColor,
                'verify_url' => route('assessment.verify'),
            ],
        ]);
    }
}
