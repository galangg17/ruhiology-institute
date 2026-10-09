<?php

namespace Database\Seeders;

use App\Models\AssessmentAnswer;
use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\AssessmentSubmission;
use App\Models\Country;
use App\Models\Instrument;
use App\Models\Occupation;
use App\Models\Participant;
use App\Models\Province;
use App\Models\Question;
use App\Models\Regency;
use App\Models\School;
use App\Models\University;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyMandiriSeeder extends Seeder
{
    public function run(): void
    {
        $instrument = Instrument::where('code', 'RQI-20')->first() ?? Instrument::first();
        if (!$instrument) {
            $this->command->error('Instrument RQI-20 tidak ditemukan. Jalankan MasterDataSeeder terlebih dahulu.');
            return;
        }

        $questions = Question::where('instrument_id', $instrument->id)
            ->with(['dimension', 'options'])
            ->orderBy('order')
            ->get();

        if ($questions->isEmpty()) {
            $this->command->error('Tidak ada soal ditemukan.');
            return;
        }

        $indonesia = Country::where('code', 'ID')->first();
        $provinces = Province::take(10)->get();
        $universities = University::take(5)->get();
        $schools = School::take(5)->get();
        $occupations = Occupation::take(5)->get();

        $dummyParticipantsData = [
            ['name' => 'Ahmad Rizky Pratama', 'gender' => 'L', 'category' => 'Mahasiswa', 'email' => 'rizky.pratama@gmail.com', 'phone' => '081274550101'],
            ['name' => 'Siti Nurhaliza', 'gender' => 'P', 'category' => 'Mahasiswa', 'email' => 'siti.nurhaliza@uinjambi.ac.id', 'phone' => '085266110202'],
            ['name' => 'Budi Santoso', 'gender' => 'L', 'category' => 'Umum', 'email' => 'budi.santoso@yahoo.com', 'phone' => '081377880303'],
            ['name' => 'Dewi Anggraini', 'gender' => 'P', 'category' => 'Pelajar', 'email' => 'dewi.anggraini@sman1jambi.sch.id', 'phone' => '089612340404'],
            ['name' => 'Muhammad Taufik', 'gender' => 'L', 'category' => 'Umum', 'email' => 'taufik.m@gmail.com', 'phone' => '082188990505'],
            ['name' => 'Rina Indriani', 'gender' => 'P', 'category' => 'Mahasiswa', 'email' => 'rina.indriani@unja.ac.id', 'phone' => '085377110606'],
            ['name' => 'Fajar Kurniawan', 'gender' => 'L', 'category' => 'Pelajar', 'email' => 'fajar.kurniawan@gmail.com', 'phone' => '081299000707'],
            ['name' => 'Nadia Putri Utami', 'gender' => 'P', 'category' => 'Umum', 'email' => 'nadia.putri@outlook.com', 'phone' => '081355440808'],
            ['name' => 'Hendra Wijaya', 'gender' => 'L', 'category' => 'Umum', 'email' => 'hendra.wijaya@gmail.com', 'phone' => '082266550909'],
            ['name' => 'Anisa Rahmawati', 'gender' => 'P', 'category' => 'Mahasiswa', 'email' => 'anisa.rahma@uinjambi.ac.id', 'phone' => '085211221010'],
            ['name' => 'Dedi Iskandar', 'gender' => 'L', 'category' => 'Umum', 'email' => 'dedi.iskandar@gmail.com', 'phone' => '081233441111'],
            ['name' => 'Maya Sari', 'gender' => 'P', 'category' => 'Pelajar', 'email' => 'maya.sari@gmail.com', 'phone' => '081388771212'],
            ['name' => 'Reza Pahlevi', 'gender' => 'L', 'category' => 'Mahasiswa', 'email' => 'reza.pahlevi@unja.ac.id', 'phone' => '085344551313'],
            ['name' => 'Fitriani Hidayah', 'gender' => 'P', 'category' => 'Umum', 'email' => 'fitriani.hidayah@gmail.com', 'phone' => '082177881414'],
            ['name' => 'Agus Setiawan', 'gender' => 'L', 'category' => 'Umum', 'email' => 'agus.setiawan@gmail.com', 'phone' => '081266551515'],
        ];

        $reflections = [
            'Asesmen ini sangat membuka mata saya mengenai pentingnya menjaga keseimbangan antara kesibukan harian dengan ketenangan jiwa (God Spot).',
            'Saya merasa terdorong untuk lebih konsisten dalam ibadah dan menjaga kejujuran batin di tengah godaan gosip dan media sosial.',
            'Hasil asesmen sangat sesuai dengan apa yang saya rasakan. Nilai WHO-5 saya menunjukkan perlunya lebih banyak waktu jeda dan istirahat berkualitas.',
            'Sangat bermanfaat! Saya menyadari bahwa nilai diri tidak ditentukan oleh validasi sosial, melainkan kebersihan hati dan kedekatan dengan Tuhan.',
            'Panduan level yang diberikan memberikan motivasi nyata untuk terus meningkatkan kualitas akhlak dan integritas dalam pekerjaan saya.',
        ];

        $period = AssessmentPeriod::first();

        foreach ($dummyParticipantsData as $idx => $pData) {
            $province = $provinces->isNotEmpty() ? $provinces->random() : null;
            $regency = $province ? Regency::where('province_id', $province->id)->first() : null;
            $university = ($pData['category'] === 'Mahasiswa' && $universities->isNotEmpty()) ? $universities->random() : null;
            $school = ($pData['category'] === 'Pelajar' && $schools->isNotEmpty()) ? $schools->random() : null;
            $occupation = ($pData['category'] === 'Umum' && $occupations->isNotEmpty()) ? $occupations->random() : null;

            $assessmentCode = Participant::generateUniqueAssessmentCode();

            $participant = Participant::create([
                'event_id' => null, // Peserta Mandiri (Tanpa Event)
                'access_type' => 'PUBLIC',
                'participant_code' => 'P-MANDIRI-' . strtoupper(Str::random(6)),
                'assessment_code' => $assessmentCode,
                'name' => $pData['name'],
                'gender' => $pData['gender'],
                'category' => $pData['category'],
                'email' => $pData['email'],
                'phone' => $pData['phone'],
                'country_id' => $indonesia?->id,
                'province_id' => $province?->id,
                'regency_id' => $regency?->id,
                'university_id' => $university?->id,
                'school_id' => $school?->id,
                'occupation_id' => $occupation?->id,
                'status' => 'completed',
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            $submissionCode = 'SUB-MND-' . strtoupper(Str::random(8));
            $startedAt = now()->subDays(rand(1, 30))->subMinutes(rand(15, 30));
            $submittedAt = (clone $startedAt)->addMinutes(rand(8, 20));

            $submission = AssessmentSubmission::create([
                'event_id' => null,
                'access_type' => 'PUBLIC',
                'sub_category' => 'PUBLIC_SELF_ASSESSMENT',
                'period_id' => $period?->id,
                'participant_id' => $participant->id,
                'submission_type' => 'mandiri',
                'submission_code' => $submissionCode,
                'status' => 'completed',
                'started_at' => $startedAt,
                'submitted_at' => $submittedAt,
                'ip_address' => '127.0.0.1',
            ]);

            $totalScore = 0;
            $rqiSum = 0;
            $rqiCount = 0;
            $whoSum = 0;
            $whoCount = 0;
            $dimScores = [];

            foreach ($questions as $question) {
                // Bias towards higher/realistic scores (3, 4, 5)
                $score = rand(3, 5);
                if (rand(1, 10) <= 2) {
                    $score = rand(2, 3);
                }

                $option = $question->options->where('option_value', $score)->first() ?? $question->options->first();

                AssessmentAnswer::create([
                    'submission_id' => $submission->id,
                    'question_id' => $question->id,
                    'question_option_id' => $option?->id,
                    'score' => $score,
                ]);

                $totalScore += $score;
                $dimCode = $question->dimension?->code ?? 'DIM-1';
                $dimScores[$dimCode] = ($dimScores[$dimCode] ?? 0) + $score;

                if (str_contains($dimCode, 'WHO')) {
                    $whoSum += $score;
                    $whoCount++;
                } else {
                    $rqiSum += $score;
                    $rqiCount++;
                }
            }

            $whoRawScore = $whoSum;
            $whoPercentage = ($whoCount > 0) ? round(($whoSum / ($whoCount * 5)) * 100, 1) : 0;
            
            $whoNote = 'Kesejahteraan mental baik.';
            if ($whoPercentage < 50) {
                $whoNote = 'Skrining WHO-5 menunjukkan perlunya perhatian ekstra terhadap kelelahan mental & manajemen stres.';
            } elseif ($whoPercentage >= 75) {
                $whoNote = 'Tingkat kesejahteraan mental dan antusiasme harian sangat optimal.';
            }

            $levelName = 'Level 3: Developing Soul (Jiwa Berproses Stabil)';
            if ($totalScore >= 75) {
                $levelName = 'Level 5: Enlightened Soul (Jiwa Terpancar Sempurna)';
            } elseif ($totalScore >= 62) {
                $levelName = 'Level 4: Mindful Youth (Jiwa Tenang & Terjaga)';
            } elseif ($totalScore >= 45) {
                $levelName = 'Level 3: Developing Soul (Jiwa Berproses Stabil)';
            } else {
                $levelName = 'Level 2: Awakening Pilgrim (Jiwa Mulai Tergugah)';
            }

            AssessmentResult::create([
                'submission_id' => $submission->id,
                'total_score' => $totalScore,
                'rqi_score' => $rqiSum,
                'category_name' => $levelName,
                'max_score' => 100,
                'percentage' => round(($totalScore / 100) * 100, 1),
                'overall_interpretation' => 'Peserta mandiri telah menyelesaikan asesmen RQI-20 dengan tingkat partisipasi aktif.',
                'who5_raw_score' => $whoRawScore,
                'who5_percentage' => $whoPercentage,
                'who5_screening_note' => $whoNote,
                'dimension_scores' => $dimScores,
                'reflection_text' => $reflections[array_rand($reflections)],
                'scoring_version' => '1.0',
            ]);
        }
    }
}
