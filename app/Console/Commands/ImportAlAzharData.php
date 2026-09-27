<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Participant;
use App\Models\AssessmentSubmission;
use App\Models\AssessmentResult;
use App\Services\RqiScoringEngine;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportAlAzharData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-alazhar {--file= : Optional path to custom CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import and link Al-Azhar Jambi participants and submissions to target Event';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Al-Azhar Jambi data import process...');

        $customFile = $this->option('file');
        $csvPath = $customFile ?: database_path('data/al_azhar_data.csv');

        if (!file_exists($csvPath)) {
            $this->error("File CSV tidak ditemukan pada path: {$csvPath}");
            return 1;
        }

        // Find or create Al Azhar Jambi Event
        $event = Event::where('institution_name', 'like', '%Al Azhar%')
            ->orWhere('event_code', 'CAHAYAKU')
            ->first();

        if (!$event) {
            $this->info('Event Al Azhar Jambi belum ada, membuat Event baru...');
            $event = Event::create([
                'title' => 'Ruhiologi Asesment',
                'event_code' => 'CAHAYAKU',
                'institution_name' => 'Al Azhar Jambi',
                'access_type' => 'EVENT_PROGRAM',
                'target_category' => 'Pelajar',
                'status' => 'completed',
                'start_date' => '2026-09-24',
                'assessment_type' => 'single',
                'instrument_id' => 1,
            ]);
        }

        $this->info("Target Event: [ID: {$event->id}] {$event->title} - {$event->institution_name}");

        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle);

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($header) === count($row)) {
                $rows[] = array_combine($header, $row);
            }
        }
        fclose($handle);

        $scoringEngine = new RqiScoringEngine();
        $updatedCount = 0;
        $createdCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();

        try {
            $bar = $this->output->createProgressBar(count($rows));
            $bar->start();

            foreach ($rows as $r) {
                $name = trim($r['Participant Name'] ?? '');
                $code = trim($r['Submission Code'] ?? '');
                $partCode = trim($r['Participant Code'] ?? '');
                $email = trim($r['Email'] ?? '');
                $type = strtoupper(trim($r['Assessment Type'] ?? 'PRETEST'));
                $totalScore = (float) ($r['Total Score'] ?? 0);
                $maxScore = (float) ($r['Max Score'] ?? 75);
                $pct = (float) ($r['Percentage (%)'] ?? 0);
                $interpretation = trim($r['Interpretation'] ?? '');
                $submittedAtRaw = trim($r['Submitted At'] ?? '');

                // Skip sample/dummy test rows
                if (in_array(strtolower($name), ['ahmad fauzi', 'siti nurhaliza', 'budi santoso', 'sdgfsg', 'nazari', 'test', 'testing'])) {
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }

                try {
                    $submittedAt = $submittedAtRaw ? Carbon::parse($submittedAtRaw) : now();
                } catch (\Exception $e) {
                    $submittedAt = now();
                }

                $catLevel = $scoringEngine->getCategoryLevel($totalScore);
                $categoryName = $catLevel['name'];

                $participant = Participant::where('name', $name)->first();

                if ($participant) {
                    $participant->update([
                        'event_id' => $event->id,
                        'access_type' => 'EVENT_PROGRAM',
                        'school_custom' => $event->institution_name ?? 'Al Azhar Jambi',
                    ]);

                    $submission = AssessmentSubmission::where('participant_id', $participant->id)->first();
                    if ($submission) {
                        $submission->update([
                            'event_id' => $event->id,
                            'access_type' => 'EVENT_PROGRAM',
                            'submission_code' => $code ?: $submission->submission_code,
                            'submitted_at' => $submittedAt,
                        ]);
                    } else {
                        $submission = AssessmentSubmission::create([
                            'event_id' => $event->id,
                            'access_type' => 'EVENT_PROGRAM',
                            'period_id' => 1,
                            'participant_id' => $participant->id,
                            'submission_type' => $type,
                            'submission_code' => $code ?: 'SUB-' . strtoupper(Str::random(10)),
                            'status' => 'completed',
                            'started_at' => $submittedAt->copy()->subMinutes(15),
                            'submitted_at' => $submittedAt,
                        ]);
                    }

                    $result = AssessmentResult::where('submission_id', $submission->id)->first();
                    if ($result) {
                        $result->update([
                            'total_score' => $totalScore,
                            'max_score' => $maxScore,
                            'percentage' => $pct,
                            'rqi_score' => $totalScore,
                            'overall_interpretation' => $interpretation ?: $result->overall_interpretation,
                            'category_name' => $categoryName,
                        ]);
                    } else {
                        AssessmentResult::create([
                            'submission_id' => $submission->id,
                            'total_score' => $totalScore,
                            'max_score' => $maxScore,
                            'percentage' => $pct,
                            'rqi_score' => $totalScore,
                            'overall_interpretation' => $interpretation ?: $categoryName,
                            'category_name' => $categoryName,
                            'scoring_version' => '1.0',
                        ]);
                    }

                    $updatedCount++;
                } else {
                    $participant = Participant::create([
                        'event_id' => $event->id,
                        'access_type' => 'EVENT_PROGRAM',
                        'participant_code' => $partCode ?: 'PAR-' . strtoupper(Str::random(8)),
                        'assessment_code' => $code,
                        'name' => $name,
                        'email' => $email ?: Str::slug($name) . '@participant.ruhiology.id',
                        'category' => 'Pelajar',
                        'school_custom' => $event->institution_name ?? 'Al Azhar Jambi',
                        'batch' => 'Angkatan 2026',
                        'status' => 'active',
                    ]);

                    $submission = AssessmentSubmission::create([
                        'event_id' => $event->id,
                        'access_type' => 'EVENT_PROGRAM',
                        'period_id' => 1,
                        'participant_id' => $participant->id,
                        'submission_type' => $type,
                        'submission_code' => $code ?: 'SUB-' . strtoupper(Str::random(10)),
                        'status' => 'completed',
                        'started_at' => $submittedAt->copy()->subMinutes(15),
                        'submitted_at' => $submittedAt,
                    ]);

                    AssessmentResult::create([
                        'submission_id' => $submission->id,
                        'total_score' => $totalScore,
                        'max_score' => $maxScore,
                        'percentage' => $pct,
                        'rqi_score' => $totalScore,
                        'overall_interpretation' => $interpretation ?: $categoryName,
                        'category_name' => $categoryName,
                        'scoring_version' => '1.0',
                    ]);

                    $createdCount++;
                }

                $bar->advance();
            }

            DB::commit();
            $bar->finish();
            $this->newLine(2);

            $this->info("✅ Impor Sukses Berhasil!");
            $this->table(
                ['Kategori', 'Jumlah'],
                [
                    ['Peserta Diperbarui', $updatedCount],
                    ['Peserta Baru Dibuat', $createdCount],
                    ['Baris Sampel/Dummy Diabaikan', $skippedCount],
                    ['Total Diproses', $updatedCount + $createdCount],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal mengimpor data: " . $e->getMessage());
            return 1;
        }
    }
}
