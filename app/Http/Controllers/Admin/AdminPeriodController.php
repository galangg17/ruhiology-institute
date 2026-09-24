<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;
use App\Models\Instrument;
use App\Models\Program;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminPeriodController extends Controller
{
    public function index()
    {
        $periods = AssessmentPeriod::with(['program.institution', 'instrument'])
            ->withCount('submissions')
            ->latest()
            ->paginate(10);

        $programs = Program::where('status', 'active')->get();
        $instruments = Instrument::where('status', 'active')->get();

        return view('admin.periods.index', compact('periods', 'programs', 'instruments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'instrument_id' => ['required', 'exists:instruments,id'],
            'title' => ['required', 'string', 'max:255'],
            'period_code' => ['required', 'string', 'unique:assessment_periods,period_code'],
            'pretest_start' => ['nullable', 'date'],
            'pretest_end' => ['nullable', 'date'],
            'posttest_start' => ['nullable', 'date'],
            'posttest_end' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,active,closed,archived'],
        ]);

        $period = AssessmentPeriod::create($validated);

        AuditLogService::log(
            action: 'create_period',
            module: 'Assessment',
            recordType: 'AssessmentPeriod',
            recordId: (string) $period->id,
            changes: $validated
        );

        return back()->with('success', 'Periode asesmen berhasil ditambahkan.');
    }

    public function update(Request $request, AssessmentPeriod $period)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'pretest_start' => ['nullable', 'date'],
            'pretest_end' => ['nullable', 'date'],
            'posttest_start' => ['nullable', 'date'],
            'posttest_end' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,active,closed,archived'],
        ]);

        $period->update($validated);

        AuditLogService::log(
            action: 'update_period',
            module: 'Assessment',
            recordType: 'AssessmentPeriod',
            recordId: (string) $period->id,
            changes: $validated
        );

        return back()->with('success', 'Periode asesmen berhasil diperbarui.');
    }
}
