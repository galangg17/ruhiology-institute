<?php

namespace App\Http\Controllers;

use App\Models\TrainingBatch;
use App\Models\TrainingProgram;
use App\Services\TrainingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class PublicTrainingController extends Controller
{
    protected TrainingService $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    public function index()
    {
        $trainings = TrainingProgram::where('status', 'published')
            ->with(['batches' => function ($q) {
                $q->where('status', 'open');
            }])
            ->get();

        return view('public.training.index', compact('trainings'));
    }

    public function show(string $slug)
    {
        $training = TrainingProgram::where('slug', $slug)
            ->with(['batches' => function ($q) {
                $q->where('status', 'open');
            }])
            ->firstOrFail();

        return view('public.training.show', compact('training'));
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
        ]);

        $batch = TrainingBatch::findOrFail($validated['batch_id']);

        try {
            $registration = $this->trainingService->registerParticipant(
                $training,
                $batch,
                $validated,
                Auth::user()
            );

            return redirect()->route('training.show', $slug)->with(
                'success',
                "Pendaftaran berhasil! Nomor Registrasi Anda: {$registration->registration_number}. Petunjuk pembayaran dan konfirmasi telah dicatat."
            );
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
