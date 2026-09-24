<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\AssessmentSubmission;
use App\Models\Event;
use App\Models\Institution;
use App\Models\Program;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminResultController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildFilterQuery($request);

        $submissions = $query->latest('submitted_at')->paginate(15)->withQueryString();

        $events = Event::where('status', 'active')->orWhere('id', $request->event_id)->get();
        $provinces = Province::where('status', 'active')->orderBy('name')->get();
        $regencies = $request->filled('province_id')
            ? Regency::where('province_id', $request->province_id)->orderBy('name')->get()
            : collect([]);

        // Subcategory list for active event filter
        $subCategories = collect();
        if ($request->filled('event_id') && $request->event_id !== 'PUBLIC_SELF') {
            $selectedEvt = Event::find($request->event_id);
            if ($selectedEvt && !empty($selectedEvt->custom_subcategories)) {
                $subCategories = collect($selectedEvt->custom_subcategories);
            }
        }

        // Calculated stats for current filter
        $allFilteredSubmissions = $this->buildFilterQuery($request)->get();
        $results = $allFilteredSubmissions->pluck('result')->filter();

        $stats = [
            'total' => $allFilteredSubmissions->count(),
            'avg_rqi' => $results->count() > 0 ? round($results->avg('rqi_score'), 1) : 0,
            'avg_who5' => $results->count() > 0 ? round($results->avg('who5_percentage'), 1) : 0,
            'who5_sehat' => $results->filter(fn($r) => $r->who5_percentage >= 50)->count(),
            'who5_skrining' => $results->filter(fn($r) => $r->who5_percentage < 50)->count(),
        ];

        return view('admin.results.index', compact(
            'submissions',
            'events',
            'provinces',
            'regencies',
            'subCategories',
            'stats'
        ));
    }

    public function show(AssessmentSubmission $submission)
    {
        $submission->load([
            'participant.province',
            'participant.regency',
            'participant.school',
            'participant.university',
            'event',
            'period.program',
            'period.instrument',
            'result.dimensionResults.dimension',
            'answers.question.dimension',
            'answers.option'
        ]);

        return view('admin.results.show', compact('submission'));
    }

    public function exportCsv(Request $request)
    {
        $submissions = $this->buildFilterQuery($request)->latest('submitted_at')->get();

        $filename = 'Rekap_Hasil_Asesmen_Ruhiology_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header Row
            fputcsv($file, [
                'No',
                'Kode Assessment',
                'Tanggal Submit',
                'Nama Peserta',
                'Kategori Peserta',
                'Sub-Kategori / Kelas / Kelompok',
                'Event / Kegiatan',
                'Provinsi',
                'Kabupaten/Kota',
                'Sekolah / Kampus / Pekerjaan',
                'Skor RQI (0-100)',
                'Kategori RQI',
                'Skor WHO-5 (%)',
                'Status WHO-5',
                'Tipe Sesi'
            ]);

            foreach ($submissions as $index => $sub) {
                $p = $sub->participant;
                $res = $sub->result;

                $institutionDetail = '-';
                if ($p) {
                    if ($p->category === 'Pelajar') {
                        $institutionDetail = ($p->school_level ? $p->school_level . ' ' : '') . ($p->school->name ?? '');
                    } elseif ($p->category === 'Mahasiswa/i') {
                        $institutionDetail = $p->university->name ?? '-';
                    } else {
                        $institutionDetail = $p->occupation ?? $p->occupation_custom ?? 'Personal / Mandiri';
                    }
                }

                $who5Status = '-';
                if ($res && $res->who5_percentage !== null) {
                    $who5Status = $res->who5_percentage >= 50 ? 'Kesejahteraan Baik' : 'Indikasi Perlu Skrining';
                }

                fputcsv($file, [
                    $index + 1,
                    $sub->submission_code,
                    $sub->submitted_at ? $sub->submitted_at->format('Y-m-d H:i') : '-',
                    $p?->name ?? 'Anonim',
                    $p?->category ?? 'Mandiri',
                    $p?->sub_category ?? '-',
                    $sub->event->title ?? ($sub->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Umum'),
                    $p?->province->name ?? '-',
                    $p?->regency->name ?? '-',
                    $institutionDetail,
                    $res->rqi_score ?? '-',
                    $res->category_name ?? '-',
                    $res->who5_percentage !== null ? $res->who5_percentage . '%' : '-',
                    $who5Status,
                    strtoupper($sub->submission_type)
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $submissions = $this->buildFilterQuery($request)->latest('submitted_at')->get();
        $results = $submissions->pluck('result')->filter();

        $event = $request->filled('event_id') ? Event::find($request->event_id) : null;
        $province = $request->filled('province_id') ? Province::find($request->province_id) : null;
        $regency = $request->filled('regency_id') ? Regency::find($request->regency_id) : null;

        $avgRqi = $results->count() > 0 ? round($results->avg('rqi_score'), 1) : 0;
        $avgWho5 = $results->count() > 0 ? round($results->avg('who5_percentage'), 1) : 0;

        $who5SehatCount = $results->filter(fn($r) => $r->who5_percentage >= 50)->count();
        $who5SkriningCount = $results->filter(fn($r) => $r->who5_percentage < 50)->count();

        return view('admin.results.export_pdf', compact(
            'submissions',
            'results',
            'event',
            'province',
            'regency',
            'avgRqi',
            'avgWho5',
            'who5SehatCount',
            'who5SkriningCount',
            'request'
        ));
    }

    private function buildFilterQuery(Request $request)
    {
        $query = AssessmentSubmission::where('status', 'submitted')
            ->with([
                'participant.province',
                'participant.regency',
                'participant.school',
                'participant.university',
                'event',
                'period.instrument',
                'result'
            ]);

        if ($request->filled('event_id')) {
            if ($request->event_id === 'PUBLIC_SELF') {
                $query->where(function ($q) {
                    $q->whereNull('event_id')->orWhere('access_type', 'PUBLIC_SELF');
                });
            } else {
                $query->where('event_id', $request->event_id);
            }
        }

        if ($request->filled('province_id')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('province_id', $request->province_id);
            });
        }

        if ($request->filled('regency_id')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('regency_id', $request->regency_id);
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        if ($request->filled('sub_category')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('sub_category', $request->sub_category);
            });
        }

        if ($request->filled('who5_status')) {
            if ($request->who5_status === 'sehat') {
                $query->whereHas('result', fn($q) => $q->where('who5_percentage', '>=', 50));
            } elseif ($request->who5_status === 'skrining') {
                $query->whereHas('result', fn($q) => $q->where('who5_percentage', '<', 50));
            }
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('submission_code', 'like', "%{$search}%")
                  ->orWhereHas('participant', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sub_category', 'like', "%{$search}%")
                         ->orWhere('assessment_code', 'like', "%{$search}%");
                  });
            });
        }

        return $query;
    }
}
