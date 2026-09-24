<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingBatch;
use App\Models\TrainingProgram;
use App\Models\TrainingRegistration;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminTrainingController extends Controller
{
    public function index()
    {
        $trainings = TrainingProgram::withCount(['batches', 'registrations'])->latest()->paginate(10);
        return view('admin.training.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.training.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string'],
            'trainer' => ['required', 'string'],
            'duration' => ['required', 'string'],
            'location' => ['required', 'string'],
            'is_online' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        $training = TrainingProgram::create($validated);

        AuditLogService::log(
            action: 'create_training',
            module: 'Training',
            recordType: 'TrainingProgram',
            recordId: (string) $training->id,
            changes: $validated
        );

        return redirect()->route('admin.training.index')->with('success', 'Program pelatihan berhasil dibuat.');
    }

    public function storeBatch(Request $request, TrainingProgram $training)
    {
        $validated = $request->validate([
            'batch_name' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'registration_open' => ['required', 'date'],
            'registration_close' => ['required', 'date'],
            'quota' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:open,closed,completed'],
        ]);

        $validated['training_id'] = $training->id;
        TrainingBatch::create($validated);

        return back()->with('success', 'Angkatan pelatihan berhasil ditambahkan.');
    }

    public function registrations(Request $request)
    {
        $query = TrainingRegistration::with(['training', 'batch']);

        if ($request->filled('registration_status')) {
            $query->where('registration_status', $request->registration_status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $registrations = $query->latest()->paginate(15);

        return view('admin.training.registrations', compact('registrations'));
    }

    public function updateRegistrationStatus(Request $request, TrainingRegistration $registration)
    {
        $validated = $request->validate([
            'registration_status' => ['required', 'in:pending,approved,rejected,completed,cancelled'],
            'payment_status' => ['required', 'in:pending,verified,rejected'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $registration->update($validated);

        AuditLogService::log(
            action: 'update_registration_status',
            module: 'Training',
            recordType: 'TrainingRegistration',
            recordId: (string) $registration->id,
            changes: $validated
        );

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }
}
