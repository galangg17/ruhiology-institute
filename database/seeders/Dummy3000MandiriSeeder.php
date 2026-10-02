<?php

namespace Database\Seeders;

use App\Models\AssessmentPeriod;
use App\Models\Country;
use App\Models\Instrument;
use App\Models\Province;
use App\Models\Question;
use App\Models\Regency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Dummy3000MandiriSeeder extends Seeder
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
            $this->command->error('Tidak ada soal RQI-20.');
            return;
        }

        $period = AssessmentPeriod::first();
        $periodId = $period ? $period->id : 1;

        $indonesia = Country::where('code', 'ID')->first();
        $provinces = Province::with('regencies')->get();
        if ($provinces->isEmpty()) {
            $this->command->error('Master wilayah belum diisi. Jalankan MasterDataSeeder terlebih dahulu.');
            return;
        }

        $firstNamesMale = [
            'Ahmad', 'Muhammad', 'Fauzi', 'Budi', 'Hendri', 'Fajar', 'Dedi', 'Reza', 'Agus', 'Taufik',
            'Rian', 'Fikri', 'Arif', 'Bayu', 'Rizky', 'Ilham', 'Dimas', 'Aditya', 'Farhan', 'Zul',
            'Gading', 'Daarul', 'Leo', 'Imam', 'Riko', 'Afif', 'Alvaroby', 'Andi', 'Fatur', 'Randi',
            'M. Thaha', 'M. Hasbi', 'M. Irwansyah', 'M. Sopan', 'M. Alvin', 'M. Alif', 'M. Daffa'
        ];

        $lastNamesMale = [
            'Pratama', 'Santoso', 'Kurniawan', 'Iskandar', 'Setiawan', 'Hidayat', 'Saputra', 'Wibowo',
            'Nugraha', 'Syahputra', 'Ramadhan', 'Wijaya', 'Fadillah', 'Amir', 'Utama', 'Faivi',
            'Suryono', 'Arrajwa', 'Zarrar', 'Fahrozi', 'Sitorus', 'Harahap', 'Ginting', 'Nasution'
        ];

        $firstNamesFemale = [
            'Putri', 'Puja', 'Aysha', 'Najwa', 'Fadhilah', 'Auliya', 'Husna', 'Indriya', 'Zelica',
            'Winda', 'Maria', 'Zahrotunnisa', 'Nimas', 'Itqiana', 'Lamy', 'Aliyah', 'Aliyya',
            'Astrid', 'Mourin', 'Nur', 'Ericha', 'Sisilla', 'Nia', 'Fidra', 'Rahma', 'Mazaya',
            'Anindya', 'Delinda', 'Ezy', 'Ray', 'Aufa', 'Nur Shafika', 'Ratu', 'Hatsya', 'Rafa',
            'Alifa', 'Azzura', 'Amoza', 'Faneca', 'Nurul', 'Abiyya', 'Rosy', 'Ginasih', 'Aurel'
        ];

        $lastNamesFemale = [
            'Wulandari', 'Wardani', 'Harfiya', 'Prastika', 'Riskha', 'Zuraedha', 'Fauziah', 'Nariya',
            'Az Zahra', 'Agustin', 'Ulfah', 'Ayodya', 'Meisya', 'Erianty', 'Ghassani', 'Dzakiyah',
            'Raina', 'Putri', 'Zulka', 'Mukmin', 'Apriani', 'Julian', 'Astuti', 'Arrajwa', 'Yasmin',
            'Kirana', 'Rahmah', 'Diazka', 'Alfathin', 'Shafika', 'Maharani', 'Nailatul', 'Fadhillah'
        ];

        $categories = ['Mahasiswa', 'Pelajar', 'Umum'];
        
        $universities = [
            'UIN Sulthan Thaha Saifuddin Jambi',
            'UIN Raden Mas Said Surakarta',
            'UIN Sunan Gunung Djati Bandung',
            'UIN Maulana Malik Ibrahim Malang',
            'UIN Syarif Hidayatullah Jakarta',
            'Universitas Jambi (UNJA)',
            'Universitas Indonesia (UI)',
            'Universitas Gadjah Mada (UGM)',
            'STAIN Sultan Abdurrahman Kepri',
            'Universitas Islam Yasni Bungo',
            'Universitas Islam An-Nadwah Kuala Tungkal'
        ];

        $schools = [
            'MAN 1 Kota Batam',
            'MAN 2 Kota Jambi',
            'SMAN Titian Teras Jambi',
            'MAN 2 Kota Sungai Penuh',
            'SMAN 1 Kota Jambi',
            'SMAN 5 Kota Sungai Penuh',
            'MAN 1 Bintan',
            'MAN 1 Pekanbaru'
        ];

        $occupations = [
            'Guru / Pendidik',
            'Dosen / Akademisi',
            'ASN / Pegawai Negeri',
            'Karyawan Swasta',
            'Wiraswasta / Pengusaha',
            'Tenaga Kesehatan',
            'Pelajar / Mahasiswa'
        ];

        $reflections = [
            'Asesmen ini sangat membuka mata saya mengenai pentingnya menjaga keseimbangan antara kesibukan harian dengan ketenangan jiwa (God Spot).',
            'Saya merasa terdorong untuk lebih konsisten dalam ibadah dan menjaga kejujuran batin di tengah godaan gosip dan media sosial.',
            'Hasil asesmen sangat sesuai dengan apa yang saya rasakan. Nilai WHO-5 saya menunjukkan perlunya lebih banyak waktu jeda dan istirahat berkualitas.',
            'Sangat bermanfaat! Saya menyadari bahwa nilai diri tidak ditentukan oleh validasi sosial, melainkan kebersihan hati dan kedekatan dengan Tuhan.',
            'Panduan level yang diberikan memberikan motivasi nyata untuk terus meningkatkan kualitas akhlak dan integritas dalam pekerjaan saya.',
            'Asesmen RQI-20 membantu saya merefleksikan posisi ruhani dan konsistensi muhasabah diri.',
            'Tingkat kesehatan mental dan rasa syukur saya semakin terarah setelah mengikuti tes ini.'
        ];

        $batchSize = 250;
        $totalTarget = 3000;
        $chunks = ceil($totalTarget / $batchSize);

        $this->command->info("Memulai pembuatan 3.000 Peserta Mandiri dalam {$chunks} batch...");

        for ($b = 0; $b < $chunks; $b++) {
            $resultsBatch = [];
            $answersBatch = [];

            $now = now();

            for ($i = 0; $i < $batchSize; $i++) {
                $isMale = rand(0, 1) === 1;
                $gender = $isMale ? 'Laki-laki' : 'Perempuan';
                
                if ($isMale) {
                    $name = $firstNamesMale[array_rand($firstNamesMale)] . ' ' . $lastNamesMale[array_rand($lastNamesMale)];
                } else {
                    $name = $firstNamesFemale[array_rand($firstNamesFemale)] . ' ' . $lastNamesFemale[array_rand($lastNamesFemale)];
                }

                $category = $categories[array_rand($categories)];
                $emailPrefix = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name)) . '.' . Str::random(5);
                $domain = (rand(1, 10) <= 6) ? 'gmail.com' : ((rand(1, 10) <= 8) ? 'ruhiologyinstitute.com' : 'yahoo.com');
                $email = "{$emailPrefix}@{$domain}";

                $phonePrefixes = ['0812', '0813', '0821', '0822', '0852', '0853', '0857', '0895', '0896', '0831'];
                $phone = $phonePrefixes[array_rand($phonePrefixes)] . rand(10000000, 99999999);

                $province = $provinces->random();
                $regenciesList = $province->regencies;
                $regency = ($regenciesList && $regenciesList->isNotEmpty()) ? $regenciesList->random() : null;

                $schoolCustom = null;
                $universityCustom = null;
                $occupationCustom = null;

                if ($category === 'Mahasiswa') {
                    $universityCustom = $universities[array_rand($universities)];
                } elseif ($category === 'Pelajar') {
                    $schoolCustom = $schools[array_rand($schools)];
                } else {
                    $occupationCustom = $occupations[array_rand($occupations)];
                }

                $createdAt = (clone $now)->subDays(rand(1, 180))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                $participantCode = 'PAR-' . strtoupper(Str::random(8));
                $assessmentCode = 'RQI-' . strtoupper(Str::random(6));

                $participantId = DB::table('participants')->insertGetId([
                    'event_id' => null,
                    'access_type' => 'PUBLIC',
                    'participant_code' => $participantCode,
                    'assessment_code' => $assessmentCode,
                    'name' => $name,
                    'gender' => $gender,
                    'category' => $category,
                    'email' => $email,
                    'phone' => $phone,
                    'country_id' => $indonesia?->id,
                    'province_id' => $province->id,
                    'regency_id' => $regency?->id,
                    'university_custom' => $universityCustom,
                    'school_custom' => $schoolCustom,
                    'occupation' => $occupationCustom,
                    'status' => 'ACTIVE',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $submissionCode = 'SUB-' . strtoupper(Str::random(10));
                $startedAt = (clone $createdAt)->addMinutes(rand(1, 5));
                $submittedAt = (clone $startedAt)->addMinutes(rand(7, 25));

                $submissionId = DB::table('assessment_submissions')->insertGetId([
                    'event_id' => null,
                    'access_type' => 'PUBLIC',
                    'sub_category' => 'PUBLIC_SELF_ASSESSMENT',
                    'period_id' => $periodId,
                    'participant_id' => $participantId,
                    'submission_type' => 'mandiri',
                    'submission_code' => $submissionCode,
                    'status' => 'completed',
                    'started_at' => $startedAt,
                    'submitted_at' => $submittedAt,
                    'ip_address' => '127.0.0.1',
                    'created_at' => $startedAt,
                    'updated_at' => $submittedAt,
                ]);

                $totalScore = 0;
                $rqiSum = 0;
                $rqiCount = 0;
                $whoSum = 0;
                $whoCount = 0;
                $dimScores = [];

                foreach ($questions as $question) {
                    $score = rand(3, 5);
                    if (rand(1, 10) <= 2) {
                        $score = rand(2, 3);
                    }

                    $option = $question->options->where('option_value', $score)->first() ?? $question->options->first();

                    $answersBatch[] = [
                        'submission_id' => $submissionId,
                        'question_id' => $question->id,
                        'question_option_id' => $option?->id,
                        'score' => $score,
                        'created_at' => $submittedAt,
                        'updated_at' => $submittedAt,
                    ];

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

                $resultsBatch[] = [
                    'submission_id' => $submissionId,
                    'total_score' => $totalScore,
                    'rqi_score' => $rqiSum,
                    'category_name' => $levelName,
                    'max_score' => 100,
                    'percentage' => round(($totalScore / 100) * 100, 1),
                    'overall_interpretation' => 'Peserta mandiri telah menyelesaikan asesmen RQI-20 secara mandiri.',
                    'who5_raw_score' => $whoRawScore,
                    'who5_percentage' => $whoPercentage,
                    'who5_screening_note' => $whoNote,
                    'dimension_scores' => json_encode($dimScores),
                    'reflection_text' => $reflections[array_rand($reflections)],
                    'scoring_version' => '1.0',
                    'created_at' => $submittedAt,
                    'updated_at' => $submittedAt,
                ];
            }

            DB::table('assessment_answers')->insert($answersBatch);
            DB::table('assessment_results')->insert($resultsBatch);

            $this->command->info("Batch " . ($b + 1) . " selesai (" . (($b + 1) * $batchSize) . " / {$totalTarget} peserta).");
        }

        $this->command->info("BERHASIL! 3.000 Peserta Mandiri beserta jawaban & skor hasil asesmen telah berhasil dibuat.");
    }
}
