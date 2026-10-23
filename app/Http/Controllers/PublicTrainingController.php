<?php

namespace App\Http\Controllers;

use App\Models\TrainingBatch;
use App\Models\TrainingProgram;
use App\Models\TrainingRegistration;
use App\Services\AuditLogService;
use App\Services\TrainingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class PublicTrainingController extends Controller
{
    protected TrainingService $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    public function index(Request $request)
    {
        $query = TrainingProgram::where('status', 'published')
            ->with(['batches' => function ($q) {
                $q->where('status', 'open')->orderBy('start_date', 'asc');
            }]);

        // Filter: Category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter: Mode (online/offline)
        if ($request->filled('mode')) {
            if ($request->mode === 'online') {
                $query->where('is_online', true);
            } elseif ($request->mode === 'offline') {
                $query->where('is_online', false);
            }
        }

        // Search: Title or Trainer
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('trainer', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $trainings = $query->latest()->get();

        // Get distinct categories for filter buttons
        $categories = TrainingProgram::where('status', 'published')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('public.training.index', compact('trainings', 'categories'));
    }

    public function show(string $slug)
    {
        $training = TrainingProgram::where('slug', $slug)
            ->with(['batches' => function ($q) {
                $q->orderBy('start_date', 'asc');
            }])
            ->firstOrFail();

        // Check active batches
        $openBatches = $training->batches->filter(fn($b) => $b->isOpen());

        return view('public.training.show', compact('training', 'openBatches'));
    }

    public function register(Request $request, string $slug)
    {
        $training = TrainingProgram::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'batch_id' => ['required', 'exists:training_batches,id'],
            'participant_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'institution' => ['nullable', 'string', 'max:255'],
        ], [
            'batch_id.required' => 'Silakan pilih jadwal / angkatan pelatihan.',
            'participant_name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
        ]);

        $batch = TrainingBatch::where('training_id', $training->id)->findOrFail($validated['batch_id']);

        if (!$batch->isOpen()) {
            return back()->with('error', 'Mohon maaf, pendaftaran untuk angkatan ini sedang tidak dibuka.')->withInput();
        }

        if ($batch->remaining_quota <= 0) {
            return back()->with('error', 'Mohon maaf, kuota peserta untuk angkatan ini sudah penuh.')->withInput();
        }

        try {
            $registration = $this->trainingService->registerParticipant(
                $training,
                $batch,
                $validated,
                Auth::user()
            );

            return redirect()->route('training.invoice', $registration->registration_number)
                ->with('success', "Pendaftaran Anda berhasil! Silakan selesaikan proses konfirmasi dan pembayaran berikut.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function invoice(string $registrationNumber)
    {
        $registration = TrainingRegistration::where('registration_number', $registrationNumber)
            ->with(['training', 'batch'])
            ->firstOrFail();

        return view('public.training.invoice', compact('registration'));
    }

    public function uploadProof(Request $request, string $registrationNumber)
    {
        $registration = TrainingRegistration::where('registration_number', $registrationNumber)->firstOrFail();

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ], [
            'payment_proof.required' => 'Silakan pilih berkas bukti transfer.',
            'payment_proof.mimes' => 'Format file yang didukung: JPG, PNG, atau PDF.',
            'payment_proof.max' => 'Ukuran file bukti transfer maksimal 5 MB.',
        ]);

        $file = $request->file('payment_proof');
        $filename = 'proof_' . $registration->registration_number . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = public_path('uploads/training_proofs');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $file->move($path, $filename);

        $registration->update([
            'payment_proof_path' => asset('uploads/training_proofs/' . $filename),
            'payment_status' => 'pending', // Waiting for admin confirmation
        ]);

        AuditLogService::log(
            action: 'upload_payment_proof',
            module: 'Training',
            recordType: 'TrainingRegistration',
            recordId: (string) $registration->id,
            changes: [
                'participant_name' => $registration->participant_name,
                'registration_number' => $registration->registration_number,
            ]
        );

        return back()->with('success', 'Bukti transfer berhasil diunggah! Tim Admin Ruhiology akan memverifikasi dalam waktu maksimal 1x24 jam.');
    }
}
