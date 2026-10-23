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
            'syllabus' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'target_audience' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'trainer' => ['required', 'string'],
            'trainer_bio' => ['nullable', 'string'],
            'duration' => ['required', 'string'],
            'location' => ['required', 'string'],
            'contact_person' => ['nullable', 'string'],
            'is_online' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,published,archived'],
            'image' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'training_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/trainings');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
            $validated['image'] = asset('images/trainings/' . $filename);
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        unset($validated['image_file']);
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

    public function update(Request $request, TrainingProgram $training)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'syllabus' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'target_audience' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'trainer' => ['required', 'string'],
            'trainer_bio' => ['nullable', 'string'],
            'duration' => ['required', 'string'],
            'location' => ['required', 'string'],
            'contact_person' => ['nullable', 'string'],
            'is_online' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,published,archived'],
            'image' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'training_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/trainings');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
            $validated['image'] = asset('images/trainings/' . $filename);
        }

        unset($validated['image_file']);
        $training->update($validated);

        AuditLogService::log(
            action: 'update_training',
            module: 'Training',
            recordType: 'TrainingProgram',
            recordId: (string) $training->id,
            changes: $validated
        );

        return back()->with('success', 'Program pelatihan berhasil diperbarui.');
    }

    public function destroy(TrainingProgram $training)
    {
        AuditLogService::log(
            action: 'delete_training',
            module: 'Training',
            recordType: 'TrainingProgram',
            recordId: (string) $training->id,
            changes: ['title' => $training->title]
        );

        $training->delete();
        return back()->with('success', 'Program pelatihan berhasil dihapus.');
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

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('registration_number', 'like', "%{$q}%")
                  ->orWhere('participant_name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('institution', 'like', "%{$q}%");
            });
        }

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('registration_status')) {
            $query->where('registration_status', $request->registration_status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $registrations = $query->latest()->paginate(15)->withQueryString();
        $trainings = TrainingProgram::orderBy('title')->get();

        return view('admin.training.registrations', compact('registrations', 'trainings'));
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

    public function exportCsv(Request $request)
    {
        $query = TrainingRegistration::with(['training', 'batch']);

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('registration_number', 'like', "%{$q}%")
                  ->orWhere('participant_name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('institution', 'like', "%{$q}%");
            });
        }

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('registration_status')) {
            $query->where('registration_status', $request->registration_status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $registrations = $query->latest()->get();
        $filename = 'Pendaftar_Pelatihan_Ruhiology_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel

            fputcsv($file, [
                'No',
                'No. Registrasi',
                'Nama Peserta',
                'Email',
                'WhatsApp',
                'Institusi / Lembaga',
                'Program Pelatihan',
                'Angkatan / Batch',
                'Jadwal Pelaksanaan',
                'Biaya (Rp)',
                'Status Pembayaran',
                'Status Pendaftaran',
                'Link Bukti Transfer',
                'Catatan Admin',
                'Waktu Registrasi'
            ]);

            $no = 1;
            foreach ($registrations as $r) {
                fputcsv($file, [
                    $no++,
                    $r->registration_number,
                    $r->participant_name,
                    $r->email,
                    $r->phone,
                    $r->institution ?? '-',
                    $r->training?->title ?? '-',
                    $r->batch?->batch_name ?? '-',
                    $r->batch?->start_date ? $r->batch->start_date->format('d/m/Y') : '-',
                    number_format($r->price, 0, ',', '.'),
                    strtoupper($r->payment_status),
                    strtoupper($r->registration_status),
                    $r->payment_proof_path ?? '-',
                    $r->admin_notes ?? '-',
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
