<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Instrument;
use App\Models\Program;
use App\Models\AssessmentSubmission;
use App\Models\Province;
use App\Models\Regency;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));
        $status = $request->query('status', '');

        $query = Event::with(['program', 'instrument', 'province', 'regency'])
            ->withCount(['participants', 'submissions'])
            ->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('event_code', 'like', "%{$search}%")
                  ->orWhere('institution_name', 'like', "%{$search}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $events = $query->paginate(12);

        $stats = [
            'total_events' => Event::count(),
            'active_events' => Event::where('status', 'active')->count(),
            'event_participants' => \App\Models\Participant::whereNotNull('event_id')->count(),
            'event_submissions' => AssessmentSubmission::whereNotNull('event_id')->count(),
            'total_institutions' => Event::whereNotNull('institution_name')->where('institution_name', '!=', '')->distinct('institution_name')->count('institution_name'),
        ];

        $programs = Program::where('status', 'active')->get();
        $instruments = Instrument::where('status', 'active')->get();
        $provinces = Province::orderBy('name', 'asc')->get();
        $regenciesMap = Regency::where('status', 'active')->orderBy('name', 'asc')->get(['id', 'province_id', 'name', 'type'])->groupBy('province_id');
        $categories = \App\Models\ParticipantCategory::getAllActive();

        return view('admin.events.index', compact('events', 'programs', 'instruments', 'provinces', 'regenciesMap', 'categories', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'event_code' => ['nullable', 'string', 'max:50', 'unique:events,event_code'],
            'access_type' => ['required', 'in:EVENT_PROGRAM,PUBLIC_SELF'],
            'assessment_type' => ['required', 'in:single,prepost'],
            'target_category' => ['nullable', 'string', 'max:100'],
            'group_label' => ['nullable', 'string', 'max:100'],
            'custom_subcategories' => ['nullable'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'regency_id' => ['nullable', 'exists:regencies,id'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'instrument_id' => ['nullable', 'exists:instruments,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'pretest_start' => ['nullable', 'date'],
            'pretest_end' => ['nullable', 'date'],
            'posttest_start' => ['nullable', 'date'],
            'posttest_end' => ['nullable', 'date'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,active,completed,archived'],
            'description' => ['nullable', 'string'],
        ]);

        if (empty($validated['event_code'])) {
            $prefix = $validated['access_type'] === 'PUBLIC_SELF' ? 'PUB' : 'EVT';
            $validated['event_code'] = 'RQ-' . $prefix . '-' . strtoupper(Str::random(6));
        } else {
            $validated['event_code'] = strtoupper(Str::slug($validated['event_code'], '-'));
        }

        // Process custom_subcategories input if string
        if (isset($validated['custom_subcategories']) && is_string($validated['custom_subcategories'])) {
            $items = array_map('trim', explode(',', $validated['custom_subcategories']));
            $validated['custom_subcategories'] = array_values(array_filter($items));
        }

        $event = Event::create($validated);

        AuditLogService::log(
            action: 'create_event',
            module: 'Event',
            recordType: 'Event',
            recordId: (string) $event->id,
            changes: $validated
        );

        return back()->with('success', 'Event / Kegiatan Asesmen "' . $event->title . '" berhasil ditambahkan dengan Kode: ' . $event->event_code);
    }

    public function show(Event $event)
    {
        $event->load(['program', 'instrument', 'province', 'regency']);

        $eventId = $event->id;
        $eventCode = $event->event_code;

        $submissions = AssessmentSubmission::where(function ($q) use ($eventId, $eventCode) {
            $q->where('event_id', $eventId)
              ->orWhereHas('participant', function ($pq) use ($eventId, $eventCode) {
                  $pq->where('event_id', $eventId)
                     ->orWhere('sub_category', 'like', "%{$eventCode}%");
              });
        })
        ->with(['participant.province', 'participant.regency', 'result'])
        ->latest()
        ->paginate(15);

        $totalSubmissions = AssessmentSubmission::where(function ($q) use ($eventId, $eventCode) {
            $q->where('event_id', $eventId)
              ->orWhereHas('participant', function ($pq) use ($eventId, $eventCode) {
                  $pq->where('event_id', $eventId);
              });
        })->count();

        $completedSubmissions = AssessmentSubmission::where(function ($q) use ($eventId) {
            $q->where('event_id', $eventId)
              ->orWhereHas('participant', fn($pq) => $pq->where('event_id', $eventId));
        })->where('status', 'submitted')->count();

        // Analytics aggregation
        $results = \App\Models\AssessmentResult::whereHas('submission', function ($q) use ($eventId) {
            $q->where('event_id', $eventId)
              ->orWhereHas('participant', fn($pq) => $pq->where('event_id', $eventId));
        })->get();

        $avgRqi = $results->count() > 0 ? round($results->avg('rqi_score'), 1) : 0;
        $avgWho5 = $results->count() > 0 ? round($results->avg('who5_percentage'), 1) : 0;

        $who5SehatCount = $results->filter(fn($r) => $r->who5_percentage >= 50)->count();
        $who5PerluSkriningCount = $results->filter(fn($r) => $r->who5_percentage < 50)->count();

        $instruments = Instrument::where('status', 'active')->get();
        $provinces = Province::orderBy('name', 'asc')->get();
        $regenciesMap = Regency::where('status', 'active')->orderBy('name', 'asc')->get(['id', 'province_id', 'name', 'type'])->groupBy('province_id');
        $categories = \App\Models\ParticipantCategory::getAllActive();

        return view('admin.events.show', compact(
            'event',
            'submissions',
            'totalSubmissions',
            'completedSubmissions',
            'avgRqi',
            'avgWho5',
            'who5SehatCount',
            'who5PerluSkriningCount',
            'instruments',
            'provinces',
            'regenciesMap',
            'categories'
        ));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'event_code' => ['nullable', 'string', 'max:50', 'unique:events,event_code,' . $event->id],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'instrument_id' => ['nullable', 'exists:instruments,id'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'target_category' => ['nullable', 'string', 'max:100'],
            'group_label' => ['nullable', 'string', 'max:100'],
            'custom_subcategories' => ['nullable'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'regency_id' => ['nullable', 'exists:regencies,id'],
            'access_type' => ['nullable', 'in:EVENT_PROGRAM,PUBLIC_SELF'],
            'assessment_type' => ['required', 'in:single,prepost'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'pretest_start' => ['nullable', 'date'],
            'pretest_end' => ['nullable', 'date'],
            'posttest_start' => ['nullable', 'date'],
            'posttest_end' => ['nullable', 'date'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,active,completed,archived'],
            'description' => ['nullable', 'string'],
        ]);

        if (!empty($validated['event_code'])) {
            $validated['event_code'] = strtoupper(Str::slug($validated['event_code'], '-'));
        }

        if (isset($validated['custom_subcategories'])) {
            if (is_string($validated['custom_subcategories'])) {
                $items = array_map('trim', explode(',', $validated['custom_subcategories']));
                $validated['custom_subcategories'] = array_values(array_filter($items));
            } elseif (is_array($validated['custom_subcategories'])) {
                $validated['custom_subcategories'] = array_values(array_filter(array_map('trim', $validated['custom_subcategories'])));
            }
        }

        $event->update($validated);

        if (!empty($validated['instrument_id'])) {
            \App\Models\AssessmentPeriod::where('period_code', 'RQI-PER-' . $event->event_code)
                ->orWhere('title', 'like', '%' . $event->title . '%')
                ->update(['instrument_id' => $validated['instrument_id']]);
        }

        AuditLogService::log(
            action: 'update_event',
            module: 'Event',
            recordType: 'Event',
            recordId: (string) $event->id,
            changes: $validated
        );

        return back()->with('success', 'Data Event "' . $event->title . '" berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }

    /**
     * 1-Click Mass Region Update for all participants of this Event.
     */
    public function bulkUpdateRegion(Request $request, Event $event)
    {
        $validated = $request->validate([
            'province_id' => ['required', 'exists:provinces,id'],
            'regency_id' => ['required', 'exists:regencies,id'],
            'only_from_regency_id' => ['nullable', 'integer'],
        ]);

        $eventId = $event->id;
        $eventCode = $event->event_code;

        // Query participants associated with this event
        $query = \App\Models\Participant::where(function ($q) use ($eventId, $eventCode) {
            $q->where('event_id', $eventId)
              ->orWhere('sub_category', 'like', "%{$eventCode}%")
              ->orWhereHas('submissions', function ($sq) use ($eventId) {
                  $sq->where('event_id', $eventId);
              });
        });

        if (!empty($validated['only_from_regency_id'])) {
            $query->where('regency_id', $validated['only_from_regency_id']);
        }

        $count = $query->count();

        $query->update([
            'province_id' => $validated['province_id'],
            'regency_id' => $validated['regency_id'],
        ]);

        $regency = Regency::find($validated['regency_id']);
        $province = Province::find($validated['province_id']);
        $regencyStr = $regency?->formatted_name ?? '';

        AuditLogService::log(
            action: 'bulk_update_region',
            module: 'Event',
            recordType: 'Event',
            recordId: (string) $event->id,
            changes: ['count' => $count, 'province_id' => $validated['province_id'], 'regency_id' => $validated['regency_id']]
        );

        return back()->with('success', "Berhasil memperbarui wilayah asal {$count} peserta pada Event \"{$event->title}\" menjadi {$regencyStr}, {$province?->name}.");
    }

    /**
     * Export CSV of participants for this specific Event.
     */
    public function exportCsv(Event $event)
    {
        $eventId = $event->id;
        $eventCode = $event->event_code;

        $participants = \App\Models\Participant::where(function ($q) use ($eventId, $eventCode) {
            $q->where('event_id', $eventId)
              ->orWhere('sub_category', 'like', "%{$eventCode}%")
              ->orWhereHas('submissions', function ($sq) use ($eventId) {
                  $sq->where('event_id', $eventId);
              });
        })->with(['province', 'regency', 'school', 'university'])->get();

        $filename = 'peserta_event_' . Str::slug($event->event_code) . '_' . date('Y-m-d') . '.csv';

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
                'Sub Kategori / Sesi',
                'Status'
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
                    $p->sub_category ?? '-',
                    strtoupper($p->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import / Mass Update CSV of participants for this specific Event.
     */
    public function importCsv(Request $request, Event $event)
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
                $participant = \App\Models\Participant::where('participant_code', $partCode)->first();
            }
            if (!$participant && $assCode) {
                $participant = \App\Models\Participant::where('assessment_code', $assCode)->first();
            }
            if (!$participant && $email) {
                $participant = \App\Models\Participant::where('email', $email)->first();
            }
            if (!$participant && $name) {
                $participant = \App\Models\Participant::where('name', $name)->first();
            }

            $schoolCustom = null;
            $universityCustom = null;
            if ($schoolOrUni) {
                if (Str::contains(strtolower($schoolOrUni), ['uin', 'univ', 'universitas', 'stkip', 'stain', 'stit', 'stikp', 'kampus', 'iaic'])) {
                    $universityCustom = $schoolOrUni;
                } else {
                    $schoolCustom = $schoolOrUni;
                }
            }

            if ($participant) {
                $updateData = ['event_id' => $event->id];
                if ($name) $updateData['name'] = $name;
                if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) $updateData['email'] = $email;
                if ($phone) $updateData['phone'] = $phone;
                if ($gender) $updateData['gender'] = $gender;
                if ($provinceId) $updateData['province_id'] = $provinceId;
                if ($regencyId) $updateData['regency_id'] = $regencyId;
                if ($schoolCustom) $updateData['school_custom'] = $schoolCustom;
                if ($universityCustom) $updateData['university_custom'] = $universityCustom;

                $participant->update($updateData);
                $updated++;
            } else {
                if (!$name) {
                    $skipped++;
                    continue;
                }
                $dummyEmail = ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) ? $email : (Str::slug($name) . '.' . strtolower(Str::random(5)) . '@participant.ruhiology.id');
                \App\Models\Participant::create([
                    'event_id' => $event->id,
                    'access_type' => 'event',
                    'participant_code' => $partCode ?: ('PST-' . date('Ym') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)),
                    'assessment_code' => $assCode ?: \App\Models\Participant::generateUniqueAssessmentCode(),
                    'name' => $name,
                    'email' => $dummyEmail,
                    'phone' => $phone,
                    'gender' => $gender ?: 'Laki-laki',
                    'category' => $event->target_category ?? 'Pelajar',
                    'province_id' => $provinceId,
                    'regency_id' => $regencyId,
                    'school_custom' => $schoolCustom,
                    'university_custom' => $universityCustom,
                    'batch' => 'Angkatan ' . date('Y'),
                    'status' => 'active',
                ]);
                $created++;
            }
        }

        fclose($handle);

        AuditLogService::log(
            action: 'import_update_event_participants',
            module: 'Event',
            recordType: 'Event',
            recordId: (string) $event->id,
            changes: ['updated' => $updated, 'created' => $created, 'skipped' => $skipped]
        );

        return back()->with('success', "Proses Impor/Update Peserta Event \"{$event->title}\" Berhasil: {$updated} peserta diperbarui, {$created} peserta baru ditambahkan.");
    }
}
