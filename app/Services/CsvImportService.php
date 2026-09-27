<?php

namespace App\Services;

use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\AssessmentSubmission;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Province;
use App\Models\Regency;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CsvImportService
{
    /**
     * Import assessment submissions from CSV string or file path.
     * 
     * @param string $csvContent Raw CSV text content
     * @return array Summary of import results (imported, skipped, total)
     */
    public static function importFromCsvContent(string $csvContent): array
    {
        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($csvContent)));
        if (count($lines) <= 1) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'error' => 'File CSV kosong atau format tidak valid.'];
        }

        // Clean UTF-8 BOM if present
        $headerLine = preg_replace('/[\x{EF}\x{BB}\x{BF}]/u', '', array_shift($lines));
        $headers = str_getcsv($headerLine);

        // Normalize header names
        $headerMap = [];
        foreach ($headers as $index => $colName) {
            $cleaned = trim($colName, " \t\n\r\0\x0B\"'");
            $headerMap[$cleaned] = $index;
        }

        $importedCount = 0;
        $skippedCount = 0;
        $totalRows = count($lines);

        $period = AssessmentPeriod::where('status', 'active')->first() ?? AssessmentPeriod::first();
        $periodId = $period ? $period->id : 1;

        // Pre-cache Provinces & Regencies for fast fuzzy lookup
        $provinces = Province::all();
        $regencies = Regency::all();

        DB::beginTransaction();
        try {
            foreach ($lines as $line) {
                if (trim($line) === '') {
                    continue;
                }

                $row = str_getcsv($line);
                if (count($row) < 3) {
                    continue; // Skip malformed line
                }

                $getValue = function($key) use ($row, $headerMap) {
                    if (isset($headerMap[$key]) && isset($row[$headerMap[$key]])) {
                        return trim($row[$headerMap[$key]]);
                    }
                    return null;
                };

                $submissionCode = $getValue('Kode Assessment');
                if (!$submissionCode) {
                    // Fallback to second column if header map missing
                    $submissionCode = isset($row[1]) ? trim($row[1]) : null;
                }

                if (!$submissionCode || !Str::startsWith($submissionCode, 'SUB-')) {
                    continue;
                }

                // DEDUPLICATION: Skip if submission code already exists in DB
                if (AssessmentSubmission::where('submission_code', $submissionCode)->exists()) {
                    $skippedCount++;
                    continue;
                }

                // Parse Submitted At
                $submittedAtRaw = $getValue('Tanggal Submit');
                try {
                    $submittedAt = $submittedAtRaw ? Carbon::parse($submittedAtRaw) : now();
                } catch (\Exception $e) {
                    $submittedAt = now();
                }

                // Participant Name & Category
                $name = $getValue('Nama Peserta') ?: 'Anonim';
                if ($name === '-' || strtolower($name) === 'anonim') {
                    $name = 'Anonim';
                }

                $category = $getValue('Kategori Peserta') ?: 'Umum';
                if ($category === '-') $category = 'Umum';

                $subCategory = $getValue('Sub-Kategori / Kelas / Kelompok');
                if ($subCategory === '-') $subCategory = null;

                $eventName = $getValue('Event / Kegiatan');
                $accessType = 'public';
                $eventId = null;

                if ($eventName && !in_array(strtolower($eventName), ['mandiri publik', 'umum', '-'])) {
                    $accessType = 'event';
                    $event = Event::firstOrCreate(
                        ['title' => $eventName],
                        [
                            'event_code' => 'EVT-' . strtoupper(Str::random(6)),
                            'category' => $category,
                            'status' => 'active',
                            'start_date' => now(),
                        ]
                    );
                    $eventId = $event->id;
                }

                // Province & Regency Matching
                $provName = $getValue('Provinsi');
                $provinceId = null;
                if ($provName && $provName !== '-') {
                    $provMatch = $provinces->first(function($p) use ($provName) {
                        return stripos($p->name, $provName) !== false || stripos($provName, $p->name) !== false;
                    });
                    if ($provMatch) {
                        $provinceId = $provMatch->id;
                    }
                }

                $regName = $getValue('Kabupaten/Kota');
                $regencyId = null;
                if ($regName && $regName !== '-') {
                    $regMatch = $regencies->first(function($r) use ($regName, $provinceId) {
                        $matchName = stripos($r->name, $regName) !== false || stripos($regName, $r->name) !== false;
                        return $provinceId ? ($matchName && $r->province_id == $provinceId) : $matchName;
                    });
                    if ($regMatch) {
                        $regencyId = $regMatch->id;
                    }
                }

                // Institution / School / University / Job info
                $instInfo = $getValue('Sekolah / Kampus / Pekerjaan');
                $schoolCustom = null;
                $universityCustom = null;
                $occupation = null;

                if ($instInfo && $instInfo !== '-') {
                    if (in_array(strtolower($category), ['pelajar', 'siswa']) || Str::contains(strtolower($instInfo), ['sma', 'smk', 'ma', 'sederajat', 'sd', 'smp'])) {
                        $schoolCustom = $instInfo;
                    } elseif (Str::contains(strtolower($instInfo), ['uin', 'univ', 'universitas', 'stkip', 'stain', 'stit', 'stikp', 'kampus'])) {
                        $universityCustom = $instInfo;
                    } else {
                        $occupation = $instInfo;
                    }
                }

                // Resolve or Create Participant
                $participant = Participant::where('name', $name)
                    ->where(function($q) use ($schoolCustom, $universityCustom, $provinceId) {
                        if ($schoolCustom) $q->orWhere('school_custom', $schoolCustom);
                        if ($universityCustom) $q->orWhere('university_custom', $universityCustom);
                        if ($provinceId) $q->orWhere('province_id', $provinceId);
                    })
                    ->first();

                // Submission Type
                $typeRaw = strtoupper($getValue('Tipe Sesi') ?: 'PRETEST');
                $submissionType = in_array($typeRaw, ['PRETEST', 'POSTTEST']) ? $typeRaw : 'PRETEST';

                // Check if participant already has a submission of this type in this period
                if ($participant) {
                    $existingSub = AssessmentSubmission::where('period_id', $periodId)
                        ->where('participant_id', $participant->id)
                        ->where('submission_type', $submissionType)
                        ->first();
                    if ($existingSub) {
                        // Create a separate participant record for multiple test sessions by same name
                        $participant = null;
                    }
                }

                if (!$participant) {
                    $slugName = Str::slug($name);
                    $dummyEmail = ($slugName ?: 'participant') . '.' . strtolower(Str::random(5)) . '@participant.ruhiology.id';

                    $participant = Participant::create([
                        'event_id' => $eventId,
                        'access_type' => $accessType,
                        'participant_code' => 'PAR-' . strtoupper(Str::random(8)),
                        'assessment_code' => $submissionCode,
                        'name' => $name,
                        'email' => $dummyEmail,
                        'category' => $category,
                        'sub_category' => $subCategory,
                        'province_id' => $provinceId,
                        'regency_id' => $regencyId,
                        'school_custom' => $schoolCustom,
                        'university_custom' => $universityCustom,
                        'occupation' => $occupation,
                        'batch' => 'Angkatan 2026',
                        'status' => 'active',
                    ]);
                }

                // Create Assessment Submission
                $submission = AssessmentSubmission::create([
                    'event_id' => $eventId,
                    'access_type' => $accessType,
                    'sub_category' => $subCategory,
                    'period_id' => $periodId,
                    'participant_id' => $participant->id,
                    'submission_type' => $submissionType,
                    'submission_code' => $submissionCode,
                    'status' => 'completed',
                    'started_at' => $submittedAt->copy()->subMinutes(15),
                    'submitted_at' => $submittedAt,
                ]);

                // Create Assessment Result
                $rqiScoreRaw = $getValue('Skor RQI (0-100)');
                $rqiScore = (float) str_replace(',', '.', $rqiScoreRaw ?: '0');

                $categoryName = $getValue('Kategori RQI') ?: 'Uncategorized';

                $who5PctRaw = $getValue('Skor WHO-5 (%)');
                $who5Pct = (float) str_replace(['%', ','], ['', '.'], $who5PctRaw ?: '0');
                $who5RawScore = (int) round(($who5Pct / 100) * 25);

                $who5Status = $getValue('Status WHO-5') ?: 'Kesejahteraan Baik';

                AssessmentResult::create([
                    'submission_id' => $submission->id,
                    'total_score' => $rqiScore,
                    'rqi_score' => $rqiScore,
                    'category_name' => $categoryName,
                    'max_score' => 100,
                    'percentage' => $rqiScore,
                    'overall_interpretation' => $categoryName,
                    'who5_raw_score' => $who5RawScore,
                    'who5_percentage' => $who5Pct,
                    'who5_screening_note' => $who5Status,
                    'scoring_version' => 'v1.0',
                ]);

                $importedCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'imported' => $importedCount,
                'skipped' => $skippedCount,
                'total' => $totalRows,
                'error' => 'Gagal mengimpor data CSV: ' . $e->getMessage(),
            ];
        }

        return [
            'imported' => $importedCount,
            'skipped' => $skippedCount,
            'total' => $totalRows,
            'error' => null,
        ];
    }
}
