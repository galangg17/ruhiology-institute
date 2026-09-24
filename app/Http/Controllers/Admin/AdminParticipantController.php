<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Participant;
use App\Models\Program;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = Participant::query()->with(['institution', 'program']);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('participant_code', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }
        if ($request->filled('batch')) {
            $query->where('batch', $request->batch);
        }

        $participants = $query->withCount('submissions')->latest()->paginate(15);

        $institutions = Institution::all();
        $programs = Program::all();

        return view('admin.participants.index', compact('participants', 'institutions', 'programs'));
    }

    public function create()
    {
        $institutions = Institution::where('status', 'active')->get();
        $programs = Program::where('status', 'active')->get();
        return view('admin.participants.create', compact('institutions', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'participant_code' => ['required', 'string', 'unique:participants,participant_code'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string'],
            'batch' => ['required', 'string'],
            'gender' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $participant = Participant::create($validated);

        AuditLogService::log(
            action: 'create',
            module: 'Participants',
            recordType: 'Participant',
            recordId: (string) $participant->id,
            changes: $validated
        );

        return redirect()->route('admin.participants.index')->with('success', 'Peserta berhasil terdaftar.');
    }

    public function edit(Participant $participant)
    {
        $institutions = Institution::all();
        $programs = Program::all();
        return view('admin.participants.edit', compact('participant', 'institutions', 'programs'));
    }

    public function update(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'participant_code' => ['required', 'string', 'unique:participants,participant_code,' . $participant->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string'],
            'batch' => ['required', 'string'],
            'gender' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $participant->update($validated);

        AuditLogService::log(
            action: 'update',
            module: 'Participants',
            recordType: 'Participant',
            recordId: (string) $participant->id,
            changes: $validated
        );

        return redirect()->route('admin.participants.index')->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroy(Participant $participant)
    {
        AuditLogService::log(
            action: 'delete',
            module: 'Participants',
            recordType: 'Participant',
            recordId: (string) $participant->id,
            changes: ['name' => $participant->name, 'code' => $participant->participant_code]
        );

        $participant->delete();
        return redirect()->route('admin.participants.index')->with('success', 'Peserta berhasil dihapus.');
    }
}
