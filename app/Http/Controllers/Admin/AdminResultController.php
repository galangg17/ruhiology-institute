<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\AssessmentSubmission;
use App\Models\Institution;
use App\Models\Program;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminResultController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessmentSubmission::where('status', 'submitted')
            ->with([
                'participant.institution',
                'participant.program',
                'period.instrument',
                'result.dimensionResults.dimension'
            ]);

        if ($request->filled('institution_id')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('institution_id', $request->institution_id);
            });
        }

        if ($request->filled('program_id')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('program_id', $request->program_id);
            });
        }

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }

        if ($request->filled('submission_type')) {
            $query->where('submission_type', $request->submission_type);
        }

        if ($request->filled('q')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('participant_code', 'like', '%' . $request->q . '%');
            });
        }

        $submissions = $query->latest('submitted_at')->paginate(15);

        $institutions = Institution::all();
        $programs = Program::all();
        $periods = AssessmentPeriod::all();

        return view('admin.results.index', compact('submissions', 'institutions', 'programs', 'periods'));
    }

    public function show(AssessmentSubmission $submission)
    {
        $submission->load([
            'participant.institution',
            'period.program',
            'period.instrument',
            'result.dimensionResults.dimension',
            'answers.question.dimension',
            'answers.option'
        ]);

        // Pre/Post Comparison object if exists
        $pretestSubmission = null;
        $posttestSubmission = null;

        if ($submission->submission_type === 'posttest') {
            $posttestSubmission = $submission;
            $pretestSubmission = AssessmentSubmission::where('period_id', $submission->period_id)
                ->where('participant_id', $submission->participant_id)
                ->where('submission_type', 'pretest')
                ->where('status', 'submitted')
                ->with('result.dimensionResults.dimension')
                ->first();
        } elseif ($submission->submission_type === 'pretest') {
            $pretestSubmission = $submission;
            $posttestSubmission = AssessmentSubmission::where('period_id', $submission->period_id)
                ->where('participant_id', $submission->participant_id)
                ->where('submission_type', 'posttest')
                ->where('status', 'submitted')
                ->with('result.dimensionResults.dimension')
                ->first();
        }

        return view('admin.results.show', compact('submission', 'pretestSubmission', 'posttestSubmission'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $query = AssessmentSubmission::where('status', 'submitted')
            ->with([
                'participant.institution',
                'participant.program',
                'period.instrument',
                'result'
            ]);

        if ($request->filled('institution_id')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('institution_id', $request->institution_id);
            });
        }

        if ($request->filled('program_id')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('program_id', $request->program_id);
            });
        }

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }

        $submissions = $query->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Laporan_Asesmen_RQ_" . date('Y-m-d_H-i-s') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($submissions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Submission Code',
                'Participant Code',
                'Participant Name',
                'Email',
                'Institution',
                'Program',
                'Assessment Type',
                'Total Score',
                'Max Score',
                'Percentage (%)',
                'Pre-Post Delta',
                'Interpretation',
                'Submitted At'
            ]);

            foreach ($submissions as $sub) {
                fputcsv($file, [
                    $sub->submission_code,
                    $sub->participant->participant_code,
                    $sub->participant->name,
                    $sub->participant->email,
                    $sub->participant->institution->name ?? 'N/A',
                    $sub->participant->program->name ?? 'N/A',
                    strtoupper($sub->submission_type),
                    $sub->result->total_score ?? 0,
                    $sub->result->max_score ?? 0,
                    $sub->result->percentage ?? 0,
                    $sub->result->pre_post_diff ?? '-',
                    $sub->result->overall_interpretation ?? '-',
                    $sub->submitted_at ? $sub->submitted_at->format('Y-m-d H:i:s') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
