<?php

namespace App\Http\Controllers;

use App\Models\AssessmentPeriod;
use App\Models\AssessmentSubmission;
use App\Models\AssessmentResult;
use App\Models\Participant;
use App\Models\Instrument;
use App\Models\Question;
use App\Services\AssessmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;

class PublicAssessmentController extends Controller
{
    protected AssessmentService $assessmentService;

    public function __construct(AssessmentService $assessmentService)
    {
        $this->assessmentService = $assessmentService;
    }

    public function index()
    {
        try {
            $activePeriods = AssessmentPeriod::where('status', 'active')
                ->with(['program.institution', 'instrument'])
                ->get();
        } catch (\Throwable $e) {
            $activePeriods = collect([]);
        }

        $defaultPeriod = $activePeriods->first();

        return view('public.assessment.index', compact('activePeriods', 'defaultPeriod'));
    }

    /**
     * Handle participant registration and generate random secure Assessment Code.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'category' => ['required', 'in:Pelajar,Mahasiswa/i,Umum'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'province_id' => ['required', 'exists:provinces,id'],
            'regency_id' => ['required', 'exists:regencies,id'],
            
            // Pelajar fields
            'school_level' => ['required_if:category,Pelajar', 'nullable', 'string'],
            
            // Mahasiswa/i fields
            'university_id' => ['required_if:category,Mahasiswa/i', 'nullable', 'exists:universities,id'],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'study_program_id' => ['nullable', 'exists:study_programs,id'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:14'],
            'entry_year' => ['nullable', 'string'],

            // Umum fields
            'occupation_id' => ['nullable', 'exists:occupations,id'],
            'occupation_custom' => ['nullable', 'string', 'max:255'],
            
            'period_code' => ['nullable', 'string'],
        ]);

        $periodCode = $validated['period_code'] ?? 'RQI-PERIOD-2026';
        $period = AssessmentPeriod::where('period_code', $periodCode)->first() 
            ?? AssessmentPeriod::where('status', 'active')->firstOrFail();

        $assessmentCode = Participant::generateUniqueAssessmentCode();
        $participantCode = 'PAR-' . strtoupper(Str::random(8));

        $email = 'participant.' . strtolower(Str::random(6)) . '@ruhiologyinstitute.com';
        if (Auth::check() && Auth::user()->email) {
            $email = Auth::user()->email;
        }

        $countryId = $validated['country_id'] ?? 1;

        $participant = Participant::create([
            'participant_code' => $participantCode,
            'assessment_code' => $assessmentCode,
            'user_id' => Auth::id(),
            'name' => trim($validated['name']),
            'birth_date' => $validated['birth_date'],
            'category' => $validated['category'],
            'email' => $email,
            'country_id' => $countryId,
            'province_id' => $validated['province_id'],
            'regency_id' => $validated['regency_id'],
            'school_id' => null,
            'university_id' => $validated['university_id'] ?? null,
            'faculty_id' => $validated['faculty_id'] ?? null,
            'study_program_id' => $validated['study_program_id'] ?? null,
            'school_level' => $validated['school_level'] ?? null,
            'school_class' => null,
            'semester' => $validated['semester'] ?? null,
            'entry_year' => $validated['entry_year'] ?? null,
            'occupation_id' => $validated['occupation_id'] ?? null,
            'occupation_custom' => $validated['occupation_custom'] ?? null,
            'status' => 'active',
        ]);

        // Auto intake session for Pre-test
        session([
            'assessment_intake' => [
                'participant_id' => $participant->id,
                'period_id' => $period->id,
                'type' => 'pretest',
            ]
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'assessment_code' => $participant->assessment_code,
                'participant_code' => $participant->participant_code,
                'name' => $participant->name,
                'take_url' => route('assessment.take', [
                    'period_code' => $period->period_code,
                    'type' => 'pretest'
                ])
            ]);
        }

        return redirect()->route('assessment.take', [
            'period_code' => $period->period_code,
            'type' => 'pretest'
        ])->with('assessment_code', $participant->assessment_code);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'participant_code' => ['required', 'string'],
            'period_code' => ['required', 'string'],
            'type' => ['required', 'in:pretest,posttest'],
        ]);

        $code = trim($validated['participant_code']);
        
        // Lookup by assessment_code OR participant_code
        $participant = Participant::where('assessment_code', $code)
            ->orWhere('participant_code', $code)
            ->first();

        if (!$participant) {
            return back()->with('error', 'Kode Assessment / Peserta tidak ditemukan dalam database.')->withInput();
        }

        $check = $this->assessmentService->validateEligibility(
            $participant->participant_code,
            $validated['period_code'],
            $validated['type']
        );

        if (!$check['eligible']) {
            return back()->with('error', $check['message'])->withInput();
        }

        $period = $check['period'];
        $type = $validated['type'];

        session([
            'assessment_intake' => [
                'participant_id' => $participant->id,
                'period_id' => $period->id,
                'type' => $type,
            ]
        ]);

        return redirect()->route('assessment.take', [
            'period_code' => $period->period_code,
            'type' => $type,
        ]);
    }

    public function take(string $periodCode, string $type)
    {
        $intake = session('assessment_intake');
        if (!$intake) {
            return redirect()->route('assessment.index')->with('error', 'Sesi asesmen Anda telah habis. Silakan masukkan Kode Assessment Anda kembali.');
        }

        $period = AssessmentPeriod::where('period_code', $periodCode)
            ->with(['instrument.questions.options', 'instrument.questions.dimension'])
            ->firstOrFail();

        if ($period->id !== $intake['period_id'] || $type !== $intake['type']) {
            return redirect()->route('assessment.index')->with('error', 'Sesi asesmen tidak cocok.');
        }

        $participant = Participant::findOrFail($intake['participant_id']);

        // Load RQI-30M questions (30 items) + WHO-5 questions (5 items)
        $rqiQuestions = Question::where('instrument_id', $period->instrument_id)
            ->where('status', 'active')
            ->with(['options' => function ($q) { $q->orderBy('order', 'asc'); }, 'dimension'])
            ->orderBy('order', 'asc')
            ->get();

        $who5Instrument = Instrument::where('code', 'WHO-5')->first();
        $who5Questions = collect();
        if ($who5Instrument) {
            $who5Questions = Question::where('instrument_id', $who5Instrument->id)
                ->where('status', 'active')
                ->with(['options' => function ($q) { $q->orderBy('order', 'asc'); }, 'dimension'])
                ->orderBy('order', 'asc')
                ->get();
        }

        return view('public.assessment.take', compact('period', 'participant', 'type', 'rqiQuestions', 'who5Questions'));
    }

    public function submit(Request $request, string $periodCode, string $type)
    {
        $intake = session('assessment_intake');
        if (!$intake) {
            return redirect()->route('assessment.index')->with('error', 'Sesi asesmen telah kedaluwarsa.');
        }

        $period = AssessmentPeriod::where('period_code', $periodCode)->firstOrFail();
        $participant = Participant::findOrFail($intake['participant_id']);

        $answers = $request->input('answers', []);
        if (empty($answers)) {
            return back()->with('error', 'Mohon isi seluruh pertanyaan yang tersedia.');
        }

        try {
            $result = $this->assessmentService->submitAssessment(
                $period,
                $participant,
                $type,
                $answers,
                $request->ip()
            );

            session()->forget('assessment_intake');

            return redirect()->route('assessment.result', [
                'submission_code' => $result->submission->submission_code
            ])->with('success', 'Asesmen ' . strtoupper($type) . ' berhasil disubmit!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function result(string $submissionCode)
    {
        $submission = AssessmentSubmission::where('submission_code', $submissionCode)
            ->with([
                'participant.country',
                'participant.province',
                'participant.regency',
                'participant.school',
                'participant.university',
                'participant.faculty',
                'participant.studyProgram',
                'period.program',
                'period.instrument',
                'result.dimensionResults.dimension',
                'answers.question.dimension'
            ])
            ->firstOrFail();

        // Check if pretest/posttest comparison available
        $preSubmission = null;
        $postSubmission = null;

        if ($submission->submission_type === 'posttest') {
            $postSubmission = $submission;
            $preSubmission = AssessmentSubmission::where('period_id', $submission->period_id)
                ->where('participant_id', $submission->participant_id)
                ->where('submission_type', 'pretest')
                ->where('status', 'submitted')
                ->with(['result.dimensionResults.dimension'])
                ->first();
        } else {
            $preSubmission = $submission;
            $postSubmission = AssessmentSubmission::where('period_id', $submission->period_id)
                ->where('participant_id', $submission->participant_id)
                ->where('submission_type', 'posttest')
                ->where('status', 'submitted')
                ->with(['result.dimensionResults.dimension'])
                ->first();
        }

        return view('public.assessment.result', compact('submission', 'preSubmission', 'postSubmission'));
    }

    /**
     * Verification & Score Lookup via Assessment Code + Birth Date.
     */
    public function checkScore(Request $request)
    {
        $validated = $request->validate([
            'assessment_code' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
        ]);

        $code = trim($validated['assessment_code']);
        $participant = Participant::where('assessment_code', $code)
            ->whereDate('birth_date', $validated['birth_date'])
            ->first();

        if (!$participant) {
            return back()->with('error', 'Kode Assessment dan Tanggal Lahir tidak cocok atau tidak ditemukan.')->withInput();
        }

        $latestSubmission = AssessmentSubmission::where('participant_id', $participant->id)
            ->where('status', 'submitted')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestSubmission) {
            return back()->with('error', 'Peserta belum menyelesaikan tes asesmen.');
        }

        return redirect()->route('assessment.result', [
            'submission_code' => $latestSubmission->submission_code
        ]);
    }

    /**
     * Submit personal reflection after reviewing results.
     */
    public function submitReflection(Request $request, string $submissionCode)
    {
        $validated = $request->validate([
            'reflection_text' => ['required', 'string', 'max:1000'],
        ]);

        $submission = AssessmentSubmission::where('submission_code', $submissionCode)->firstOrFail();
        
        if ($submission->result) {
            $submission->result->update([
                'reflection_text' => trim($validated['reflection_text'])
            ]);
        }

        return back()->with('success', 'Tekad perubahan batin & refleksi Anda berhasil disimpan.');
    }
}
