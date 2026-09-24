<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Program;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::query()->with('institution');
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('code', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }
        $programs = $query->withCount(['participants', 'periods'])->latest()->paginate(10);
        $institutions = Institution::all();

        return view('admin.programs.index', compact('programs', 'institutions'));
    }

    public function create()
    {
        $institutions = Institution::where('status', 'active')->get();
        return view('admin.programs.create', compact('institutions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'code' => ['required', 'string', 'unique:programs,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:draft,active,completed,archived'],
        ]);

        $program = Program::create($validated);

        AuditLogService::log(
            action: 'create',
            module: 'Programs',
            recordType: 'Program',
            recordId: (string) $program->id,
            changes: $validated
        );

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dibuat.');
    }

    public function edit(Program $program)
    {
        $institutions = Institution::all();
        return view('admin.programs.edit', compact('program', 'institutions'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'code' => ['required', 'string', 'unique:programs,code,' . $program->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:draft,active,completed,archived'],
        ]);

        $program->update($validated);

        AuditLogService::log(
            action: 'update',
            module: 'Programs',
            recordType: 'Program',
            recordId: (string) $program->id,
            changes: $validated
        );

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        AuditLogService::log(
            action: 'delete',
            module: 'Programs',
            recordType: 'Program',
            recordId: (string) $program->id,
            changes: ['name' => $program->name]
        );

        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }
}
