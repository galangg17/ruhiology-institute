<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentSubmission;
use App\Models\Institution;
use App\Models\Participant;
use App\Models\Program;
use App\Models\Province;
use App\Models\Regency;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminParticipantController extends Controller
{
    public function index(Request $request)
    {
        // Global Statistics Bar
        $stats = [
            'total_participants' => Participant::count(),
            'public_participants' => Participant::where(function($w) {
                $w->where('access_type', 'public')->orWhereNull('event_id');
            })->count(),
            'event_participants' => Participant::where(function($w) {
                $w->where('access_type', 'event_only')->orWhereNotNull('event_id');
            })->count(),
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
            'submissions.period.instrument',
            'submissions.result'
        ]);

        if ($request->filled('access_type')) {
            if ($request->access_type === 'public') {
                $query->where(function($w) {
                    $w->where('access_type', 'public')->orWhereNull('event_id');
                });
            } elseif ($request->access_type === 'event_only') {
                $query->where(function($w) {
                    $w->where('access_type', 'event_only')->orWhereNotNull('event_id');
                });
            }
        }

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
        $regencies = $request->filled('province_id')
            ? Regency::where('province_id', $request->province_id)->orderBy('name')->get()
            : collect([]);
        $regenciesMap = Regency::where('status', 'active')->orderBy('name')->get(['id', 'province_id', 'name', 'type'])->groupBy('province_id');

        return view('admin.participants.index', compact('participants', 'institutions', 'programs', 'provinces', 'regencies', 'regenciesMap', 'stats'));
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
            'submissions.period.instrument',
            'submissions.result'
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
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        $headerLine = fgets($handle);
        if (!$headerLine) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong.');
        }

        $headerLine = preg_replace('/[\x{EF}\x{BB}\x{BF}]/u', '', $headerLine);
        $header = str_getcsv($headerLine);

        $headerMap = [];
        foreach ($header as $index => $colName) {
            $cleaned = strtolower(trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $colName)));
            $headerMap[$cleaned] = $index;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        $provinces = Province::all();
        $regencies = Regency::all();

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) continue;

            $getValue = function(...$keys) use ($row, $headerMap) {
                foreach ($keys as $k) {
                    $kClean = strtolower(trim($k));
                    if (isset($headerMap[$kClean]) && isset($row[$headerMap[$kClean]])) {
                        $val = trim($row[$headerMap[$kClean]]);
                        if ($val !== '' && $val !== '-') return $val;
                    }
                }
                return null;
            };

            $partCode = $getValue('kode peserta', 'participant_code');
            $assCode = $getValue('kode asesmen', 'kode assessment', 'assessment_code');
            $name = $getValue('nama lengkap', 'nama peserta', 'nama', 'name');
            $email = $getValue('email');
            $phone = $getValue('telepon', 'phone', 'hp');
            $gender = $getValue('jenis kelamin', 'gender');
            $provName = $getValue('provinsi', 'province');
            $regName = $getValue('kabupaten / kota', 'kabupaten/kota', 'kota', 'regency');
            $schoolOrUni = $getValue('sekolah / kampus', 'sekolah', 'kampus', 'institusi asal');
            $batch = $getValue('angkatan / batch', 'batch', 'angkatan') ?? 'Angkatan ' . date('Y');
            $status = strtolower($getValue('status') ?? 'active');

            if (!$name && !$email && !$partCode && !$assCode) {
                $skipped++;
                continue;
            }

            $provinceId = null;
            if ($provName) {
                $provMatch = $provinces->first(function($p) use ($provName) {
                    return stripos($p->name, $provName) !== false || stripos($provName, $p->name) !== false;
                });
                if ($provMatch) $provinceId = $provMatch->id;
            }

            $regencyId = null;
            if ($regName) {
                $regMatch = $regencies->first(function($r) use ($regName, $provinceId) {
                    $matchName = stripos($r->name, $regName) !== false || stripos($regName, $r->name) !== false;
                    return $provinceId ? ($matchName && $r->province_id == $provinceId) : $matchName;
                });
                if ($regMatch) $regencyId = $regMatch->id;
            }

            $participant = null;
            if ($partCode) {
                $participant = Participant::where('participant_code', $partCode)->first();
            }
            if (!$participant && $assCode) {
                $participant = Participant::where('assessment_code', $assCode)->first();
            }
            if (!$participant && $email) {
                $participant = Participant::where('email', $email)->first();
            }
            if (!$participant && $name) {
                $participant = Participant::where('name', $name)->first();
            }

            $schoolCustom = null;
            $universityCustom = null;
            if ($schoolOrUni) {
                if (\Illuminate\Support\Str::contains(strtolower($schoolOrUni), ['uin', 'univ', 'universitas', 'stkip', 'stain', 'stit', 'stikp', 'kampus', 'iaic'])) {
                    $universityCustom = $schoolOrUni;
                } else {
                    $schoolCustom = $schoolOrUni;
                }
            }

            if ($participant) {
                $updateData = [];
                if ($name) $updateData['name'] = $name;
                if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) $updateData['email'] = $email;
                if ($phone) $updateData['phone'] = $phone;
                if ($gender) $updateData['gender'] = $gender;
                if ($provinceId) $updateData['province_id'] = $provinceId;
                if ($regencyId) $updateData['regency_id'] = $regencyId;
                if ($schoolCustom) $updateData['school_custom'] = $schoolCustom;
                if ($universityCustom) $updateData['university_custom'] = $universityCustom;
                if ($batch) $updateData['batch'] = $batch;
                if (in_array($status, ['active', 'inactive'])) $updateData['status'] = $status;

                $participant->update($updateData);
                $updated++;
            } else {
                if (!$name) {
                    $skipped++;
                    continue;
                }
                $dummyEmail = ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) ? $email : (\Illuminate\Support\Str::slug($name) . '.' . strtolower(\Illuminate\Support\Str::random(5)) . '@participant.ruhiology.id');
                Participant::create([
                    'institution_id' => Institution::first()?->id ?? 1,
                    'program_id' => Program::first()?->id ?? 1,
                    'participant_code' => $partCode ?: ('PST-' . date('Ym') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)),
                    'assessment_code' => $assCode ?: Participant::generateUniqueAssessmentCode(),
                    'name' => $name,
                    'email' => $dummyEmail,
                    'phone' => $phone,
                    'gender' => $gender ?: 'Laki-laki',
                    'province_id' => $provinceId,
                    'regency_id' => $regencyId,
                    'school_custom' => $schoolCustom,
                    'university_custom' => $universityCustom,
                    'batch' => $batch,
                    'status' => in_array($status, ['active', 'inactive']) ? $status : 'active',
                ]);
                $created++;
            }
        }

        fclose($handle);

        AuditLogService::log(
            action: 'import_update',
            module: 'Participants',
            recordType: 'Participant',
            recordId: 'batch_mass_update',
            changes: ['updated' => $updated, 'created' => $created, 'skipped' => $skipped]
        );

        return back()->with('success', "Proses Impor/Update Data Peserta Berhasil: {$updated} data peserta diperbarui (termasuk Kota/Provinsi), {$created} peserta baru ditambahkan, {$skipped} dilewati.");
    }

    public function importServerCsv(Request $request)
    {
        $request->validate([
            'csv_file' => ['nullable', 'file', 'mimes:csv,txt,excel', 'max:10240'],
            'csv_text' => ['nullable', 'string'],
        ]);

        $csvContent = null;
        if ($request->hasFile('csv_file')) {
            $csvContent = file_get_contents($request->file('csv_file')->getRealPath());
        } elseif ($request->filled('csv_text')) {
            $csvContent = $request->csv_text;
        }

        if (!$csvContent) {
            return back()->with('error', 'Silakan pilih file CSV atau tempelkan teks CSV.');
        }

        $result = \App\Services\CsvImportService::importFromCsvContent($csvContent);

        if (!empty($result['error'])) {
            return back()->with('error', $result['error']);
        }

        AuditLogService::log(
            action: 'import_server_csv',
            module: 'Participants',
            recordType: 'AssessmentSubmission',
            recordId: 'batch_server_import',
            changes: ['imported' => $result['imported'], 'skipped' => $result['skipped'], 'total' => $result['total']]
        );

        return back()->with('success', "Impor data asesmen server berhasil: {$result['imported']} data baru ditambahkan, {$result['skipped']} data duplikat dilewati.");
    }
}
