<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Instrument;
use App\Models\Program;
use App\Models\AssessmentSubmission;
use App\Models\Province;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));
        $status = $request->query('status', '');

        $query = Event::with(['program', 'instrument'])
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

        $programs = Program::where('status', 'active')->get();
        $instruments = Instrument::where('status', 'active')->get();

        return view('admin.events.index', compact('events', 'programs', 'instruments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'event_code' => ['nullable', 'string', 'max:50', 'unique:events,event_code'],
            'access_type' => ['required', 'in:EVENT_PROGRAM,PUBLIC_SELF'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'instrument_id' => ['nullable', 'exists:instruments,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
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
        $event->load(['program', 'instrument']);

        $submissions = AssessmentSubmission::where('event_id', $event->id)
            ->with(['participant.province', 'participant.regency', 'result'])
            ->latest()
            ->paginate(15);

        $totalSubmissions = AssessmentSubmission::where('event_id', $event->id)->count();
        $completedSubmissions = AssessmentSubmission::where('event_id', $event->id)->where('status', 'submitted')->count();

        // Analytics aggregation
        $results = \App\Models\AssessmentResult::whereHas('submission', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })->get();

        $avgRqi = $results->count() > 0 ? round($results->avg('rqi_score'), 1) : 0;
        $avgWho5 = $results->count() > 0 ? round($results->avg('who5_percentage'), 1) : 0;

        $who5SehatCount = $results->filter(fn($r) => $r->who5_percentage >= 50)->count();
        $who5PerluSkriningCount = $results->filter(fn($r) => $r->who5_percentage < 50)->count();

        return view('admin.events.show', compact(
            'event',
            'submissions',
            'totalSubmissions',
            'completedSubmissions',
            'avgRqi',
            'avgWho5',
            'who5SehatCount',
            'who5PerluSkriningCount'
        ));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,active,completed,archived'],
            'description' => ['nullable', 'string'],
        ]);

        $event->update($validated);

        return back()->with('success', 'Data Event "' . $event->title . '" berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
