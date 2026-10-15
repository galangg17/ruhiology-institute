<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\AssessmentSubmission;
use App\Models\Dimension;
use App\Models\Event;
use App\Models\Institution;
use App\Models\Program;
use App\Models\Province;
use App\Models\Question;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $pretestSubmission = null;
        $posttestSubmission = null;

        if ($submission->participant_id && $submission->period_id) {
            $pretestSubmission = AssessmentSubmission::where('participant_id', $submission->participant_id)
                ->where('period_id', $submission->period_id)
                ->whereIn('submission_type', ['pretest', 'PRETEST'])
                ->with('result')
                ->first();

            $posttestSubmission = AssessmentSubmission::where('participant_id', $submission->participant_id)
                ->where('period_id', $submission->period_id)
                ->whereIn('submission_type', ['posttest', 'POSTTEST'])
                ->with('result')
                ->first();
        }

        return view('admin.results.show', compact('submission', 'pretestSubmission', 'posttestSubmission'));
    }

    public function destroy(AssessmentSubmission $submission)
    {
        $participantName = $submission->participant ? $submission->participant->name : 'Peserta';

        \App\Services\AuditLogService::log(
            action: 'delete_submission',
            module: 'Assessment',
            recordType: 'AssessmentSubmission',
            recordId: (string) $submission->id,
            changes: ['submission_code' => $submission->submission_code, 'participant_name' => $participantName]
        );

        if ($submission->result) {
            $submission->result->dimensionResults()->delete();
            $submission->result->delete();
        }
        $submission->answers()->delete();

        $participantId = $submission->participant_id;

        $submission->delete();

        if ($participantId) {
            $otherCount = AssessmentSubmission::where('participant_id', $participantId)->count();
            if ($otherCount === 0) {
                \App\Models\Participant::where('id', $participantId)->delete();
            }
        }

        return back()->with('success', 'Data peserta "' . $participantName . '" berhasil dihapus.');
    }

    public function exportCsv(Request $request)
    {
        $dimensions = Dimension::whereIn('id', [1, 2, 3, 4, 5, 6])->orderBy('id')->get();
        if ($dimensions->isEmpty()) {
            $dimensions = Dimension::orderBy('code')->get();
        }

        $questions = Question::with('dimension')->orderBy('order')->orderBy('id')->get();

        $filename = 'Rekap_Lengkap_Hasil_Asesmen_Ruhiology_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($request, $dimensions, $questions) {
            @set_time_limit(300);
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header Row
            $header = [
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
                'Tipe Sesi',
                
                // Skor & Level RQI
                'Skor RQI (0-100)',
                'Kategori Level RQI',
                
                // Skor & Level WHO-5
                'Raw Score WHO-5 (0-25)',
                'Persentase WHO-5 (%)',
                'Kategori Level WHO-5',
                'Status Diagnostik WHO-5',
            ];

            // Dimensi RQI & WHO-5
            foreach ($dimensions as $dim) {
                $code = $dim->code;
                $dimName = $dim->name;
                $header[] = "[{$code}] {$dimName} (Skor)";
                $header[] = "[{$code}] {$dimName} (%)";
            }

            // Seluruh Butir Soal Instrumen (Q1-Q15 RQI & Q16-Q20 WHO-5)
            foreach ($questions as $idx => $q) {
                $qNum = $idx + 1;
                $dimCode = $q->dimension?->code ?? 'DIM';
                $isWho = ($q->dimension_id == 6 || str_contains($dimCode, 'WHO'));
                $tag = $isWho ? 'WHO5' : 'RQI';
                $shortText = Str::limit(strip_tags($q->question_text), 35);
                $header[] = "Q{$qNum} [{$tag}-{$dimCode}] Jawaban Dipilih ({$shortText})";
                $header[] = "Q{$qNum} [{$tag}-{$dimCode}] Skor Poin";
            }

            fputcsv($file, $header);

            $index = 0;
            $this->buildFilterQuery($request)
                ->with([
                    'participant.province',
                    'participant.regency',
                    'participant.school',
                    'participant.university',
                    'event',
                    'result.dimensionResults',
                    'answers.option'
                ])
                ->latest('submitted_at')
                ->chunk(150, function ($submissions) use ($file, &$index, $dimensions, $questions) {
                    foreach ($submissions as $sub) {
                        $index++;
                        $p = $sub->participant;
                        $res = $sub->result;
                        $institutionDetail = $this->getInstitutionDetail($p);

                        // WHO-5 Resolution
                        $who5Raw = $res?->who5_raw_score ?? 0;
                        $who5Pct = $res?->who5_percentage ?? ($who5Raw > 0 ? round(($who5Raw / 25) * 100) : null);
                        $who5Level = null;
                        if ($res && !empty($res->snapshot_data['who5']['name'])) {
                            $who5Level = $res->snapshot_data['who5']['name'];
                        } elseif ($who5Pct !== null) {
                            if ($who5Pct >= 80) $who5Level = 'Level 5: Thriving & Energetic (Sangat Sejahtera)';
                            elseif ($who5Pct >= 64) $who5Level = 'Level 4: Good Vibe & Fresh (Sejahtera)';
                            elseif ($who5Pct >= 44) $who5Level = 'Level 3: Moderate Well-Being (Cukup Stabil)';
                            elseif ($who5Pct >= 24) $who5Level = 'Level 2: Low Energy (Perlu Perhatian)';
                            else $who5Level = 'Level 1: Mental Exhausted (Perlu Pendampingan)';
                        }

                        $who5Status = '-';
                        if ($who5Pct !== null) {
                            $who5Status = $who5Pct >= 50 ? 'Sehat (Kesejahteraan Baik)' : 'Indikasi Perlu Skrining';
                        }

                        $dimResults = $res ? $res->dimensionResults->keyBy('dimension_id') : collect();
                        $subAnswers = $sub->answers->keyBy('question_id');

                        $row = [
                            $index,
                            $sub->submission_code,
                            $sub->submitted_at ? $sub->submitted_at->format('Y-m-d H:i') : '-',
                            $p?->name ?? 'Anonim',
                            $p?->category ?? 'Mandiri',
                            $p?->sub_category ?? '-',
                            $sub->event->title ?? ($sub->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Umum'),
                            $p?->province->name ?? '-',
                            $p?->regency->name ?? '-',
                            $institutionDetail,
                            strtoupper($sub->submission_type ?? 'MANDIRI'),
                            
                            // RQI
                            $res->rqi_score ?? '-',
                            $res->category_name ?? '-',
                            
                            // WHO-5
                            $who5Raw > 0 ? $who5Raw : ($res && $res->who5_raw_score !== null ? $res->who5_raw_score : '-'),
                            $who5Pct !== null ? $who5Pct . '%' : '-',
                            $who5Level ?? '-',
                            $who5Status,
                        ];

                        // Dimensions
                        foreach ($dimensions as $dim) {
                            $dRes = $dimResults->get($dim->id);
                            $row[] = $dRes ? $dRes->score : '-';
                            $row[] = $dRes ? (number_format($dRes->percentage, 1) . '%') : '-';
                        }

                        // Questions
                        foreach ($questions as $q) {
                            $ans = $subAnswers->get($q->id);
                            $row[] = $ans ? ($ans->option?->option_text ?? '-') : '-';
                            $row[] = $ans ? $ans->calculated_score : '-';
                        }

                        fputcsv($file, $row);
                    }
                    fflush($file);
                });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export individual assessment dimension breakdown to Excel CSV
     */
    public function exportSubmissionDimensions(AssessmentSubmission $submission)
    {
        $submission->loadMissing([
            'participant.province',
            'participant.regency',
            'participant.school',
            'participant.university',
            'event',
            'result.dimensionResults.dimension'
        ]);

        $p = $submission->participant;
        $res = $submission->result;
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $p?->name ?? 'Peserta');
        $filename = 'Capaian_Dimensi_' . $submission->submission_code . '_' . $cleanName . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($submission, $p, $res) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // Metadata Section
            fputcsv($file, ['LAPORAN CAPAIAN PER DIMENSI RUHIOLOGI']);
            fputcsv($file, ['Nama Peserta', $p?->name ?? 'Anonim']);
            fputcsv($file, ['Kode Asesmen', $submission->submission_code]);
            fputcsv($file, ['Kategori Peserta', $p?->category ?? 'Mandiri']);
            fputcsv($file, ['Sub Kategori / Kelas', $p?->sub_category ?? '-']);
            fputcsv($file, ['Event / Kegiatan', $submission->event?->title ?? ($submission->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Umum')]);
            fputcsv($file, ['Institusi / Pekerjaan', $this->getInstitutionDetail($p)]);
            fputcsv($file, ['Tanggal & Waktu Submit', $submission->submitted_at ? $submission->submitted_at->format('Y-m-d H:i:s') : '-']);
            fputcsv($file, ['Skor Total RQI (0-100)', $res?->rqi_score ?? '-']);
            fputcsv($file, ['Kategori RQI', $res?->category_name ?? '-']);
            fputcsv($file, ['Skor WHO-5 (%)', ($res && $res->who5_percentage !== null) ? $res->who5_percentage . '%' : '-']);
            fputcsv($file, ['Status Kesejahteraan (WHO-5)', ($res && $res->who5_percentage !== null) ? ($res->who5_percentage >= 50 ? 'Kesejahteraan Mental Terpenuhi' : 'Perlu Pendampingan / Skrining') : '-']);
            fputcsv($file, []);

            // Table Header
            fputcsv($file, [
                'No',
                'Kode Dimensi',
                'Nama Dimensi Ruhiologi',
                'Skor Diperoleh',
                'Skor Maksimum',
                'Persentase Capaian (%)',
                'Interpretasi / Kategori'
            ]);

            $dimResults = $res?->dimensionResults ?? collect();
            foreach ($dimResults as $idx => $d) {
                fputcsv($file, [
                    $idx + 1,
                    $d->dimension?->code ?? '-',
                    $d->dimension?->name ?? '-',
                    $d->score,
                    $d->max_score,
                    number_format($d->percentage, 1) . '%',
                    $d->interpretation ?? '-'
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export individual assessment item-by-item question answers to Excel CSV
     */
    public function exportSubmissionAnswers(AssessmentSubmission $submission)
    {
        $submission->loadMissing([
            'participant.province',
            'participant.regency',
            'event',
            'result',
            'answers.question.dimension',
            'answers.option'
        ]);

        $p = $submission->participant;
        $res = $submission->result;
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $p?->name ?? 'Peserta');
        $filename = 'Jawaban_Butir_Soal_' . $submission->submission_code . '_' . $cleanName . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($submission, $p, $res) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // Metadata Section
            fputcsv($file, ['LEMBAR JAWABAN PER BUTIR SOAL (RAW & CALCULATED SCORE)']);
            fputcsv($file, ['Nama Peserta', $p?->name ?? 'Anonim']);
            fputcsv($file, ['Kode Asesmen', $submission->submission_code]);
            fputcsv($file, ['Kategori Peserta', $p?->category ?? 'Mandiri']);
            fputcsv($file, ['Sub Kategori / Kelas', $p?->sub_category ?? '-']);
            fputcsv($file, ['Event / Kegiatan', $submission->event?->title ?? ($submission->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Umum')]);
            fputcsv($file, ['Tanggal & Waktu Submit', $submission->submitted_at ? $submission->submitted_at->format('Y-m-d H:i:s') : '-']);
            fputcsv($file, ['Total Butir Soal Dijawab', count($submission->answers)]);
            fputcsv($file, ['Skor Total RQI (0-100)', $res?->rqi_score ?? '-']);
            fputcsv($file, ['Skor WHO-5 (%)', ($res && $res->who5_percentage !== null) ? $res->who5_percentage . '%' : '-']);
            fputcsv($file, []);

            // Table Header
            fputcsv($file, [
                'No',
                'Kode Dimensi',
                'Nama Dimensi',
                'Teks Pertanyaan / Soal Asesmen',
                'Arah Penilaian',
                'Jawaban Peserta (Opsi Dipilih)',
                'Nilai Mentah (Raw Score)',
                'Skor Terhitung (Calculated Score)'
            ]);

            foreach ($submission->answers as $idx => $ans) {
                fputcsv($file, [
                    $idx + 1,
                    $ans->question?->dimension?->code ?? '-',
                    $ans->question?->dimension?->name ?? '-',
                    $ans->question?->question_text ?? '-',
                    $ans->question?->scoring_direction === 'reverse' ? 'Reverse (Negatif)' : 'Normal (Positif)',
                    $ans->option?->option_text ?? '-',
                    $ans->raw_value,
                    $ans->calculated_score
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export aggregate cohort dimension matrix to Excel CSV
     */
    public function exportDimensionsMatrix(Request $request)
    {
        $dimensions = Dimension::orderBy('code')->get();
        $filename = 'Rekap_Capaian_Dimensi_Ruhiologi_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($request, $dimensions) {
            @set_time_limit(300);
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // CSV Header Row
            $header = [
                'No',
                'Kode Assessment',
                'Tanggal Submit',
                'Nama Peserta',
                'Kategori Peserta',
                'Sub-Kategori / Kelas',
                'Event / Kegiatan',
                'Provinsi',
                'Kabupaten/Kota',
                'Institusi / Pekerjaan',
                'Total Skor RQI (0-100)',
                'Kategori RQI',
                'Skor WHO-5 (%)',
                'Status WHO-5',
                'Tipe Sesi'
            ];

            foreach ($dimensions as $dim) {
                $code = $dim->code;
                $header[] = "[{$code}] {$dim->name} (Skor)";
                $header[] = "[{$code}] {$dim->name} (Maks)";
                $header[] = "[{$code}] {$dim->name} (%)";
                $header[] = "[{$code}] {$dim->name} (Interpretasi)";
            }

            fputcsv($file, $header);

            $index = 0;
            $this->buildFilterQuery($request)
                ->with(['result.dimensionResults.dimension'])
                ->latest('submitted_at')
                ->chunk(200, function ($submissions) use ($file, &$index, $dimensions) {
                    foreach ($submissions as $sub) {
                        $index++;
                        $p = $sub->participant;
                        $res = $sub->result;
                        $institutionDetail = $this->getInstitutionDetail($p);

                        $who5Status = '-';
                        if ($res && $res->who5_percentage !== null) {
                            $who5Status = $res->who5_percentage >= 50 ? 'Kesejahteraan Baik' : 'Indikasi Perlu Skrining';
                        }

                        $dimResults = $res ? $res->dimensionResults->keyBy('dimension_id') : collect();

                        $row = [
                            $index,
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
                            strtoupper($sub->submission_type ?? 'MANDIRI')
                        ];

                        foreach ($dimensions as $dim) {
                            $dRes = $dimResults->get($dim->id);
                            if ($dRes) {
                                $row[] = $dRes->score;
                                $row[] = $dRes->max_score;
                                $row[] = number_format($dRes->percentage, 1) . '%';
                                $row[] = $dRes->interpretation ?? '-';
                            } else {
                                $row[] = '-';
                                $row[] = '-';
                                $row[] = '-';
                                $row[] = '-';
                            }
                        }

                        fputcsv($file, $row);
                    }
                    fflush($file);
                });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export aggregate cohort item response matrix (raw & calculated score) to Excel CSV
     */
    public function exportAnswersMatrix(Request $request)
    {
        $questions = Question::with('dimension')->orderBy('order')->orderBy('id')->get();
        $filename = 'Rekap_Jawaban_Butir_Soal_Raw_Calc_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($request, $questions) {
            @set_time_limit(300);
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // CSV Header Row
            $header = [
                'No',
                'Kode Assessment',
                'Tanggal Submit',
                'Nama Peserta',
                'Kategori Peserta',
                'Sub-Kategori / Kelas',
                'Event / Kegiatan',
                'Total Skor RQI',
                'Kategori RQI',
                'Skor WHO-5 (%)',
                'Status WHO-5'
            ];

            foreach ($questions as $idx => $q) {
                $qNum = $idx + 1;
                $dimCode = $q->dimension?->code ?? 'DIM';
                $shortText = Str::limit(strip_tags($q->question_text), 30);
                $header[] = "Q{$qNum} [{$dimCode}] Pilihan ({$shortText})";
                $header[] = "Q{$qNum} [{$dimCode}] Raw";
                $header[] = "Q{$qNum} [{$dimCode}] Calc";
            }

            fputcsv($file, $header);

            $index = 0;
            $this->buildFilterQuery($request)
                ->with([
                    'answers.question.dimension',
                    'answers.option',
                    'result'
                ])
                ->latest('submitted_at')
                ->chunk(150, function ($submissions) use ($file, &$index, $questions) {
                    foreach ($submissions as $sub) {
                        $index++;
                        $p = $sub->participant;
                        $res = $sub->result;

                        $who5Status = '-';
                        if ($res && $res->who5_percentage !== null) {
                            $who5Status = $res->who5_percentage >= 50 ? 'Kesejahteraan Baik' : 'Indikasi Perlu Skrining';
                        }

                        $subAnswers = $sub->answers->keyBy('question_id');

                        $row = [
                            $index,
                            $sub->submission_code,
                            $sub->submitted_at ? $sub->submitted_at->format('Y-m-d H:i') : '-',
                            $p?->name ?? 'Anonim',
                            $p?->category ?? 'Mandiri',
                            $p?->sub_category ?? '-',
                            $sub->event->title ?? ($sub->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Umum'),
                            $res->rqi_score ?? '-',
                            $res->category_name ?? '-',
                            $res->who5_percentage !== null ? $res->who5_percentage . '%' : '-',
                            $who5Status
                        ];

                        foreach ($questions as $q) {
                            $ans = $subAnswers->get($q->id);
                            if ($ans) {
                                $row[] = $ans->option?->option_text ?? '-';
                                $row[] = $ans->raw_value;
                                $row[] = $ans->calculated_score;
                            } else {
                                $row[] = '-';
                                $row[] = '-';
                                $row[] = '-';
                            }
                        }

                        fputcsv($file, $row);
                    }
                    fflush($file);
                });

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

        $levelCounts = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];

        foreach ($results as $res) {
            $score = (float) ($res->rqi_score ?? 0);
            if ($score >= 67) {
                $levelCounts[5]++;
            } elseif ($score >= 51) {
                $levelCounts[4]++;
            } elseif ($score >= 34) {
                $levelCounts[3]++;
            } elseif ($score >= 17) {
                $levelCounts[2]++;
            } else {
                $levelCounts[1]++;
            }
        }

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
            'levelCounts',
            'request'
        ));
    }

    private function getInstitutionDetail($p): string
    {
        if (!$p) {
            return '-';
        }

        if ($p->category === 'Pelajar') {
            $schoolName = $p->school_custom ?: ($p->school->name ?? '');
            return trim(($p->school_level ? $p->school_level . ' ' : '') . $schoolName) ?: '-';
        } elseif ($p->category === 'Mahasiswa/i') {
            return $p->university_custom ?: ($p->university->name ?? '-');
        } else {
            return $p->occupation_custom ?: ($p->occupation ?: 'Personal / Mandiri');
        }
    }

    private function buildFilterQuery(Request $request)
    {
        $query = AssessmentSubmission::where(function ($q) {
                $q->whereIn('status', ['submitted', 'completed', 'SUBMITTED', 'COMPLETED'])
                  ->orWhereNotNull('submitted_at');
            })
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
                    $q->whereNull('event_id')->orWhereIn('access_type', ['PUBLIC_SELF', 'PUBLIC', 'public']);
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
