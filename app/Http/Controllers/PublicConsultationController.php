<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PublicConsultationController extends Controller
{
    public function index()
    {
        return view('public.consultation.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'institution' => ['nullable', 'string', 'max:255'],
            'consultation_type' => ['required', 'string'],
            'preferred_date' => ['required', 'date', 'after:today'],
            'preferred_time' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        $consultationNumber = 'CNS-' . strtoupper(Str::random(8));

        $consultation = Consultation::create([
            'consultation_number' => $consultationNumber,
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'institution' => $validated['institution'] ?? null,
            'consultation_type' => $validated['consultation_type'],
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $validated['preferred_time'],
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        AuditLogService::log(
            action: 'create_consultation_request',
            module: 'Consultation',
            recordType: 'Consultation',
            recordId: (string) $consultation->id
        );

        return redirect()->route('consultation.index')->with(
            'success',
            "Permintaan konsultasi Anda telah berhasil dikirim! Nomor Referensi: {$consultationNumber}. Tim sekretariat kami akan menghubungi Anda melalui WhatsApp/Email."
        );
    }
}
