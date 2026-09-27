<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentSubmission;
use App\Models\Institution;
use App\Models\Participant;
use App\Models\Program;
use App\Models\Province;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminParticipantController extends Controller
{
    public function index(Request $request)
    {
        // Global Statistics Bar
        $stats = [
            'total_participants' => Participant::count(),
            'active_participants' => Participant::where('status', 'active')->count(),
            'total_provinces' => Participant::whereNotNull('province_id')->distinct('province_id')->count(),
            'total_submissions' => AssessmentSubmission::count(),
        ];

        $query = Participant::query()->with([
            'institution',
            'program',
            'province',
            'regency',
            'school',
            'university',
            'submissions.instrument'
        ]);

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('participant_code', 'like', "%{$q}%")
                  ->orWhere('assessment_code', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('school_custom', 'like', "%{$q}%")
                  ->orWhere('university_custom', 'like', "%{$q}%");
            });
        }

        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('batch')) {
            $query->where('batch', 'like', '%' . $request->batch . '%');
        }

        $participants = $query->withCount('submissions')->latest()->paginate(15)->withQueryString();

        $institutions = Institution::all();
        $programs = Program::all();
        $provinces = Province::orderBy('name')->get();

        return view('admin.participants.index', compact('participants', 'institutions', 'programs', 'provinces', 'stats'));
    }

    public function show(Participant $participant)
    {
        $participant->load([
            'institution',
            'program',
            'province',
            'regency',
            'school',
            'university',
            'submissions.period',
            'submissions.instrument'
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'participant' => $participant,
            ]);
        }

        return view('admin.participants.show', compact('participant'));
    }

    public function create()
    {
        $institutions = Institution::where('status', 'active')->get();
        $programs = Program::where('status', 'active')->get();
        $provinces = Province::orderBy('name')->get();
        return view('admin.participants.create', compact('institutions', 'programs', 'provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'regency_id' => ['nullable', 'exists:regencies,id'],
            'school_custom' => ['nullable', 'string', 'max:255'],
            'university_custom' => ['nullable', 'string', 'max:255'],
            'participant_code' => ['required', 'string', 'unique:participants,participant_code'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string'],
            'batch' => ['required', 'string'],
            'gender' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (empty($validated['assessment_code'])) {
            $validated['assessment_code'] = Participant::generateUniqueAssessmentCode();
        }

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
        $provinces = Province::orderBy('name')->get();
        return view('admin.participants.edit', compact('participant', 'institutions', 'programs', 'provinces'));
    }

    public function update(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'regency_id' => ['nullable', 'exists:regencies,id'],
            'school_custom' => ['nullable', 'string', 'max:255'],
            'university_custom' => ['nullable', 'string', 'max:255'],
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

    public function exportCsv(Request $request)
    {
        $query = Participant::query()->with([
            'institution',
            'program',
            'province',
            'regency',
            'school',
            'university'
        ])->withCount('submissions');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('participant_code', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
            });
        }
        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $participants = $query->latest()->get();

        $filename = 'data_peserta_ruhiologi_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Kode Peserta',
                'Kode Asesmen',
                'Nama Lengkap',
                'Email',
                'Telepon',
                'Jenis Kelamin',
                'Provinsi',
                'Kabupaten / Kota',
                'Sekolah / Kampus',
                'Institusi',
                'Program',
                'Angkatan / Batch',
                'Jumlah Sesi Test',
                'Status',
                'Tanggal Terdaftar'
            ]);

            foreach ($participants as $p) {
                $schoolOrUni = $p->school_custom ?: ($p->university_custom ?: ($p->school?->name ?: ($p->university?->name ?: '-')));
                fputcsv($file, [
                    $p->participant_code,
                    $p->assessment_code ?? '-',
                    $p->name,
                    $p->email,
                    $p->phone ?? '-',
                    $p->gender ?? '-',
                    $p->province?->name ?? '-',
                    $p->regency?->name ?? '-',
                    $schoolOrUni,
                    $p->institution?->name ?? '-',
                    $p->program?->name ?? '-',
                    $p->batch,
                    $p->submissions_count,
                    strtoupper($p->status),
                    $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplate()
    {
        $filename = 'template_import_peserta_ruhiologi.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'participant_code',
                'name',
                'email',
                'phone',
                'gender',
                'batch',
                'institution_id',
                'program_id',
                'school_custom',
                'university_custom',
                'status'
            ]);

            fputcsv($file, [
                'MHS-2026-001',
                'Ahmad Fauzi',
                'fauzi@example.com',
                '081234567890',
                'Laki-laki',
                'Angkatan 2026',
                '1',
                '1',
                'SMAN 1 Jambi',
                'UIN STS Jambi',
                'active'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong.');
        }

        $cleanHeader = array_map(function ($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $h)));
        }, $header);

        $created = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) continue;

            $data = [];
            foreach ($cleanHeader as $index => $key) {
                $data[$key] = isset($row[$index]) ? trim($row[$index]) : null;
            }

            $code = $data['participant_code'] ?? ('PST-' . date('Ym') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT));
            $name = $data['name'] ?? null;
            $email = $data['email'] ?? null;

            if (!$name || !$email) {
                $skipped++;
                continue;
            }

            if (Participant::where('participant_code', $code)->orWhere('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            Participant::create([
                'institution_id' => !empty($data['institution_id']) ? (int)$data['institution_id'] : (Institution::first()?->id ?? 1),
                'program_id' => !empty($data['program_id']) ? (int)$data['program_id'] : (Program::first()?->id ?? 1),
                'participant_code' => $code,
                'assessment_code' => Participant::generateUniqueAssessmentCode(),
                'name' => $name,
                'email' => $email,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? 'Laki-laki',
                'batch' => $data['batch'] ?? 'Angkatan ' . date('Y'),
                'school_custom' => $data['school_custom'] ?? null,
                'university_custom' => $data['university_custom'] ?? null,
                'status' => in_array($data['status'] ?? '', ['active', 'inactive']) ? $data['status'] : 'active',
            ]);

            $created++;
        }

        fclose($handle);

        AuditLogService::log(
            action: 'import',
            module: 'Participants',
            recordType: 'Participant',
            recordId: 'batch_import',
            changes: ['created' => $created, 'skipped' => $skipped]
        );

        return back()->with('success', "Import Data Peserta berhasil: {$created} peserta ditambahkan, {$skipped} dilewati/duplikat.");
    }
}
