<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminConsultationController extends Controller
{
    public function index(Request $request)
    {
        $query = Consultation::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('consultation_number', 'like', '%' . $request->q . '%')
                  ->orWhere('institution', 'like', '%' . $request->q . '%');
        }

        $consultations = $query->latest()->paginate(15);

        return view('admin.consultations.index', compact('consultations'));
    }

    public function updateStatus(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,scheduled,completed,cancelled'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $consultation->update($validated);

        AuditLogService::log(
            action: 'update_consultation_status',
            module: 'Consultation',
            recordType: 'Consultation',
            recordId: (string) $consultation->id,
            changes: $validated
        );

        return back()->with('success', 'Status permintaan konsultasi berhasil diperbarui.');
    }
}
