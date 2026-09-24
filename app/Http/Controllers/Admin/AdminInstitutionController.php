<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminInstitutionController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::query();
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('code', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $institutions = $query->withCount(['programs', 'participants'])->latest()->paginate(10);

        return view('admin.institutions.index', compact('institutions'));
    }

    public function create()
    {
        return view('admin.institutions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'unique:institutions,code'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $institution = Institution::create($validated);

        AuditLogService::log(
            action: 'create',
            module: 'Institutions',
            recordType: 'Institution',
            recordId: (string) $institution->id,
            changes: $validated
        );

        return redirect()->route('admin.institutions.index')->with('success', 'Institusi berhasil ditambahkan.');
    }

    public function edit(Institution $institution)
    {
        return view('admin.institutions.edit', compact('institution'));
    }

    public function update(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'unique:institutions,code,' . $institution->id],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $institution->update($validated);

        AuditLogService::log(
            action: 'update',
            module: 'Institutions',
            recordType: 'Institution',
            recordId: (string) $institution->id,
            changes: $validated
        );

        return redirect()->route('admin.institutions.index')->with('success', 'Data institusi berhasil diperbarui.');
    }

    public function destroy(Institution $institution)
    {
        AuditLogService::log(
            action: 'delete',
            module: 'Institutions',
            recordType: 'Institution',
            recordId: (string) $institution->id,
            changes: ['name' => $institution->name]
        );

        $institution->delete();
        return redirect()->route('admin.institutions.index')->with('success', 'Institusi berhasil dihapus.');
    }
}
