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
            $publicInstruments = Instrument::where('status', 'active')
                ->where('access_type', 'public')
                ->withCount('questions')
                ->get();

            $activePeriods = AssessmentPeriod::where('status', 'active')
                ->whereHas('instrument', function ($q) {
                    $q->where('access_type', 'public');
                })
                ->with(['program.institution', 'instrument'])
                ->get();

            $activeEvents = \App\Models\Event::where('status', 'active')
                ->where('access_type', 'EVENT_PROGRAM')
                ->orderBy('title', 'asc')
                ->get();

            $provinces = \App\Models\Province::where('status', 'active')
                ->orderBy('name', 'asc')
                ->get(['id', 'code', 'name']);

            $regenciesMap = \App\Models\Regency::where('status', 'active')
                ->orderBy('name', 'asc')
                ->get(['id', 'province_id', 'name', 'type'])
                ->groupBy('province_id');
        } catch (\Throwable $e) {
            $publicInstruments = collect([]);
            $activePeriods = collect([]);
            $activeEvents = collect([]);
            $provinces = collect([]);
            $regenciesMap = collect([]);
        }

        $defaultPeriod = $activePeriods->first();

        return view('public.assessment.index', compact('activePeriods', 'defaultPeriod', 'activeEvents', 'publicInstruments', 'provinces', 'regenciesMap'));
    }

    /**
     * Handle participant registration and generate random secure Assessment Code.
     */
    public function register(Request $request)
    {
        if (!empty($request->input('event_code'))) {
            $eventModel = \App\Models\Event::where('event_code', strtoupper(trim($request->input('event_code'))))->first();
            if ($eventModel) {
                $defaultProvId = $eventModel->province_id ?? \App\Models\Province::where('status', 'active')->first()?->id ?? 1;
                $defaultRegId = $eventModel->regency_id ?? \App\Models\Regency::where('province_id', $defaultProvId)->first()?->id ?? 1;

                if (empty($request->input('province_id'))) {
                    $request->merge(['province_id' => $defaultProvId]);
                }
                if (empty($request->input('regency_id'))) {
                    $request->merge(['regency_id' => $defaultRegId]);
                }
                if (empty($request->input('category'))) {
                    $request->merge(['category' => $eventModel->target_category ?? 'Pelajar']);
                }
            }
        }

        if (empty($request->input('province_id'))) {
            $defaultProvId = \App\Models\Province::where('status', 'active')->first()?->id ?? 1;
            $request->merge(['province_id' => $defaultProvId]);
        }
        if (empty($request->input('regency_id'))) {
            $defaultRegId = \App\Models\Regency::where('province_id', $request->input('province_id'))->first()?->id ?? 1;
            $request->merge(['regency_id' => $defaultRegId]);
        }

        if ($request->input('category') === 'Mahasiswa') {
            $request->merge(['category' => 'Mahasiswa/i']);
        }
        if ($request->input('category') === 'Mandiri') {
            $request->merge(['category' => 'Umum']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['nullable', 'in:Laki-laki,Perempuan'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'category' => ['required', 'in:Pelajar,Mahasiswa/i,Umum,Mahasiswa,Mandiri'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'province_id' => ['required', 'exists:provinces,id'],
            'regency_id' => ['required', 'exists:regencies,id'],
            
            // Pelajar fields
            'school_level' => ['required_if:category,Pelajar', 'nullable', 'string'],
            'school_class' => ['nullable', 'string', 'max:100'],
            'school_custom' => ['nullable', 'string', 'max:255'],
            'school_name' => ['nullable', 'string', 'max:255'],
            
            // Mahasiswa/i fields
            'university_id' => ['nullable'],
            'university_custom' => ['nullable', 'string', 'max:255'],
            'university_name' => ['nullable', 'string', 'max:255'],
            'faculty_id' => ['nullable'],
            'study_program_id' => ['nullable'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:14'],
            'entry_year' => ['nullable', 'string'],

            // Umum fields
            'occupation_id' => ['nullable'],
            'occupation_custom' => ['nullable', 'string', 'max:255'],
            
            'sub_category' => ['nullable', 'string', 'max:255'],
            'event_code' => ['nullable', 'string'],
            'period_code' => ['nullable', 'string'],
        ]);

        $eventId = null;
        $accessType = 'PUBLIC_SELF';

        if (!empty($validated['event_code'])) {
            $event = \App\Models\Event::where('event_code', strtoupper(trim($validated['event_code'])))->first();
            if ($event) {
                $eventId = $event->id;
                $accessType = $event->access_type ?? 'EVENT_PROGRAM';
            }
        }

        $periodCode = $validated['period_code'] ?? 'RQI-PERIOD-2026';

        if (!empty($eventId) && isset($event) && $event->instrument_id) {
            $period = AssessmentPeriod::where('instrument_id', $event->instrument_id)->where('status', 'active')->first();
            if (!$period) {
                $period = AssessmentPeriod::create([
                    'program_id' => $event->program_id ?? 1,
                    'instrument_id' => $event->instrument_id,
                    'title' => 'Periode Event ' . $event->title,
                    'period_code' => 'RQI-PER-' . $event->event_code,
                    'status' => 'active',
                ]);
            }
        } elseif (!empty($request->input('instrument_id'))) {
            $targetInstId = (int) $request->input('instrument_id');
            $period = AssessmentPeriod::where('instrument_id', $targetInstId)->where('status', 'active')->first();
            if (!$period) {
                $targetInst = Instrument::find($targetInstId);
                $period = AssessmentPeriod::create([
                    'program_id' => 1,
                    'instrument_id' => $targetInstId,
                    'title' => 'Periode Asesmen ' . ($targetInst->name ?? 'Publik'),
                    'period_code' => 'PER-PUB-' . strtoupper(Str::random(6)),
                    'status' => 'active',
                ]);
            }
        } else {
            $period = AssessmentPeriod::where('period_code', $periodCode)->first() 
                ?? AssessmentPeriod::where('status', 'active')->first();

            if (!$period) {
                $period = AssessmentPeriod::create([
                    'program_id' => 1,
                    'instrument_id' => 1,
                    'title' => 'Default Periode Asesmen 2026',
                    'period_code' => 'RQI-PERIOD-2026',
                    'status' => 'active',
                ]);
            }
        }

        $assessmentCode = Participant::generateUniqueAssessmentCode();
        $participantCode = 'PAR-' . strtoupper(Str::random(8));

        $email = !empty($validated['email']) ? $validated['email'] : ('participant.' . strtolower(Str::random(6)) . '@ruhiologyinstitute.com');
        if (Auth::check() && Auth::user()->email && empty($validated['email'])) {
            $email = Auth::user()->email;
        }

        $countryId = $validated['country_id'] ?? 1;

        $participant = Participant::create([
            'event_id' => $eventId,
            'access_type' => $accessType,
            'participant_code' => $participantCode,
            'assessment_code' => $assessmentCode,
            'user_id' => Auth::id(),
            'name' => trim($validated['name']),
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'] ?? 'Laki-laki',
            'phone' => $validated['phone'] ?? null,
            'category' => $validated['category'],
            'sub_category' => $validated['sub_category'] ?? null,
            'email' => $email,
            'country_id' => $countryId,
            'province_id' => $validated['province_id'],
            'regency_id' => $validated['regency_id'],
            'school_id' => null,
            'school_custom' => $validated['school_custom'] ?? $validated['school_name'] ?? null,
            'university_id' => null,
            'university_custom' => $validated['university_custom'] ?? $validated['university_name'] ?? null,
            'faculty_id' => $validated['faculty_id'] ?? null,
            'study_program_id' => $validated['study_program_id'] ?? null,
            'school_level' => $validated['school_level'] ?? null,
            'school_class' => $validated['school_class'] ?? null,
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

        // Load all active questions for the period's instrument
        $allQuestions = Question::where('instrument_id', $period->instrument_id)
            ->where('status', 'active')
            ->with(['options' => function ($q) { $q->orderBy('order', 'asc'); }, 'dimension'])
            ->orderBy('order', 'asc')
            ->get();

        $rqiQuestions = $allQuestions->filter(function ($q) {
            $dimCode = strtolower($q->dimension->code ?? '');
            $dimName = strtolower($q->dimension->name ?? '');
            return !str_contains($dimCode, 'who') && !str_contains($dimName, 'who');
        })->values();

        $who5Questions = $allQuestions->filter(function ($q) {
            $dimCode = strtolower($q->dimension->code ?? '');
            $dimName = strtolower($q->dimension->name ?? '');
            return str_contains($dimCode, 'who') || str_contains($dimName, 'who');
        })->values();

        // Robust fallback if who5Questions is empty: fetch active questions from WHO dimension
        if ($who5Questions->isEmpty()) {
            $who5Questions = Question::whereHas('dimension', function ($q) {
                $q->where('code', 'like', '%who%')
                  ->orWhere('name', 'like', '%who%');
            })
            ->where('status', 'active')
            ->with(['options' => function ($q) { $q->orderBy('order', 'asc'); }, 'dimension'])
            ->orderBy('order', 'asc')
            ->get();
        }

        // Robust fallback if rqiQuestions is empty: fetch active questions from RQI dimensions
        if ($rqiQuestions->isEmpty()) {
            $rqiQuestions = Question::whereHas('dimension', function ($q) {
                $q->where('code', 'not like', '%who%')
                  ->where('name', 'not like', '%who%');
            })
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

        $provinces = \App\Models\Province::where('status', 'active')->orderBy('name', 'asc')->get(['id', 'code', 'name']);
        $regenciesMap = \App\Models\Regency::where('status', 'active')->orderBy('name', 'asc')->get(['id', 'province_id', 'name', 'type'])->groupBy('province_id');

        return view('public.assessment.result', compact('submission', 'preSubmission', 'postSubmission', 'provinces', 'regenciesMap'));
    }

    /**
     * Update participant's region (Province & Regency) from the result page.
     */
    public function updateRegion(Request $request, string $submissionCode)
    {
        $validated = $request->validate([
            'province_id' => ['required', 'exists:provinces,id'],
            'regency_id' => ['required', 'exists:regencies,id'],
        ]);

        $submission = AssessmentSubmission::where('submission_code', $submissionCode)->firstOrFail();
        $participant = $submission->participant;
        
        $participant->update([
            'province_id' => $validated['province_id'],
            'regency_id' => $validated['regency_id'],
        ]);

        $regency = \App\Models\Regency::find($validated['regency_id']);
        $province = \App\Models\Province::find($validated['province_id']);

        $regencyStr = $regency?->formatted_name ?? '';
        $provinceStr = $province?->name ?? '';

        return back()->with('success', "Wilayah asal berhasil diperbarui menjadi {$regencyStr}, {$provinceStr}.");
    }

    /**
     * Official Digital Certificate View (A4 Landscape Print/PDF).
     */
    public function certificate(string $submissionCode)
    {
        $submission = AssessmentSubmission::where('submission_code', $submissionCode)
            ->with([
                'participant.country',
                'participant.province',
                'participant.regency',
                'participant.school',
                'participant.university',
                'period.program',
                'period.instrument',
                'result.dimensionResults.dimension'
            ])
            ->firstOrFail();

        // Auto-generate result if missing
        if (!$submission->result) {
            try {
                $resultService = app(\App\Services\ResultService::class);
                $resultService->generateResult($submission);
                $submission->load('result.dimensionResults.dimension');
            } catch (\Exception $e) {
                // Ignore fallback exception
            }
        }

        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();

        return view('public.assessment.certificate', compact('submission', 'settings'));
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

    /**
     * Verify event code and return event details (target category, title, institution).
     */
    public function verifyEventCode(Request $request)
    {
        $code = strtoupper(trim($request->input('event_code', '')));

        if (empty($code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode event wajib diisi.'
            ], 422);
        }

        $event = \App\Models\Event::where('event_code', $code)
            ->where('status', 'active')
            ->first();

        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode Event "' . $code . '" tidak valid atau tidak ditemukan. Silakan periksa kembali kode yang Anda masukkan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'event' => [
                'id' => $event->id,
                'event_code' => $event->event_code,
                'title' => $event->title,
                'institution_name' => $event->institution_name,
                'target_category' => $event->target_category,
                'group_label' => $event->group_label,
                'custom_subcategories' => $event->custom_subcategories,
                'province_id' => $event->province_id,
                'regency_id' => $event->regency_id,
            ]
        ]);
    }
}
