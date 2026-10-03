<?php

namespace Database\Seeders;

use App\Models\AssessmentPeriod;
use App\Models\Country;
use App\Models\Instrument;
use App\Models\Province;
use App\Models\Question;
use App\Models\School;
use App\Models\University;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Dummy5500MandiriSeeder extends Seeder
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

        // STRICTLY EXCLUDE JAMBI TO DIVERSIFY ALL 38 PROVINCES ACROSS INDONESIA
        $provinces = Province::where('name', 'NOT LIKE', '%JAMBI%')
            ->with(['regencies' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        if ($provinces->isEmpty()) {
            // Fallback if no outer provinces filtered
            $provinces = Province::with('regencies')->get();
        }

        $firstNamesMale = [
            'Ahmad', 'Muhammad', 'Faris', 'Bagas', 'Budi', 'Fajar', 'Rizky', 'Dimas', 'Aditya', 'Farhan',
            'Gilang', 'Hendra', 'Irfan', 'Joko', 'Kevin', 'Luki', 'Mahendra', 'Naufal', 'Oktavian', 'Pradana',
            'Raden', 'Satria', 'Taufik', 'Utama', 'Vicky', 'Wahyudi', 'Yusuf', 'Zainal', 'Andi', 'I Made',
            'Sutan', 'Gede', 'Agus', 'Eko', 'Slamet', 'Bambang', 'Wahyu', 'Doni', 'Bayu', 'Ilham', 'Reza', 'Rian',
            'M. Thaha', 'M. Hasbi', 'M. Irwansyah', 'M. Sopan', 'M. Alvin', 'M. Alif', 'M. Daffa', 'Al-Fatih'
        ];

        $lastNamesMale = [
            'Pratama', 'Santoso', 'Kurniawan', 'Iskandar', 'Setiawan', 'Hidayat', 'Saputra', 'Wibowo',
            'Nugraha', 'Syahputra', 'Ramadhan', 'Wijaya', 'Fadillah', 'Suryono', 'Nasution', 'Harahap',
            'Siregar', 'Ginting', 'Sitorus', 'Lubis', 'Hasibuan', 'Sitompul', 'Wicaksono', 'Susanto',
            'Handoko', 'Kusuma', 'Purnomo', 'Suwandi', 'Sudrajat', 'Gunawan', 'Chaniago', 'Sembiring',
            'Daulay', 'Makatita', 'Wenda', 'Kaharuddin', 'Bachtiar', 'Syahrir', 'Irawan', 'Firmansyah'
        ];

        $firstNamesFemale = [
            'Putri', 'Aisyah', 'Nabila', 'Najwa', 'Fadhilah', 'Aulia', 'Husna', 'Zahra', 'Winda', 'Maria',
            'Zahrotunnisa', 'Dewi', 'Siti', 'Nur', 'Rahma', 'Anindya', 'Dzakiyah', 'Kirana', 'Maharani', 'Ratu',
            'Alifa', 'Azzura', 'Nurul', 'Gita', 'Intan', 'Lestari', 'Mega', 'Novi', 'Oktavia', 'Puspa',
            'Retno', 'Sri', 'Tania', 'Utami', 'Vina', 'Yulia', 'Ni Luh', 'Ni Made', 'Cut', 'Dian', 'Tia',
            'Tazkia', 'Mazaya', 'Salsabila', 'Syifa', 'Khadijah', 'Fatimah', 'Humaira', 'Nabila'
        ];

        $lastNamesFemale = [
            'Wulandari', 'Wardani', 'Prastika', 'Fauziah', 'Az Zahra', 'Agustin', 'Ulfah', 'Ghassani',
            'Putri', 'Astuti', 'Yasmin', 'Kirana', 'Rahmah', 'Maharani', 'Fadhillah', 'Suryani',
            'Handayani', 'Kartika', 'Permata', 'Safitri', 'Anggraini', 'Lestari', 'Kusumawardhani',
            'Simanjuntak', 'Manurung', 'Purba', 'Siregar', 'Panggabean', 'Suci', 'Zulaiha', 'Cahyani', 'Ningsih'
        ];

        $categories = ['Mahasiswa/i', 'Pelajar', 'Umum'];
        
        $universities = [
            'UNIVERSITAS INDONESIA (UI)',
            'UNIVERSITAS GADJAH MADA (UGM)',
            'INSTITUT TEKNOLOGI BANDUNG (ITB)',
            'UNIVERSITAS PADJADJARAN (UNPAD)',
            'UNIVERSITAS AIRLANGGA (UNAIR)',
            'UNIVERSITAS DIPONEGORO (UNDIP)',
            'UNIVERSITAS BRAWIJAYA (UB)',
            'UNIVERSITAS HASANUDDIN (UNHAS)',
            'UNIVERSITAS SUMATERA UTARA (USU)',
            'UNIVERSITAS ANDALAS (UNAND)',
            'UNIVERSITAS PENDIDIKAN INDONESIA (UPI)',
            'UIN SYARIF HIDAYATULLAH JAKARTA',
            'UIN SUNAN KALIJAGA YOGYAKARTA',
            'UIN SUNAN GUNUNG DJATI BANDUNG',
            'UIN MAULANA MALIK IBRAHIM MALANG',
            'UIN RADEN MAS SAID SURAKARTA',
            'UIN ALAUDDIN MAKASSAR',
            'UIN AR-RANIRY BANDA ACEH',
            'UIN WALISONGO SEMARANG',
            'UIN IMAM BONJOL PADANG',
            'STAIN SULTAN ABDURRAHMAN KEPRI',
            'UNIVERSITAS NEGERI JAKARTA (UNJ)',
            'UNIVERSITAS NEGERI YOGYAKARTA (UNY)',
            'UNIVERSITAS NEGERI MALANG (UM)',
            'UNIVERSITAS NEGERI SURABAYA (UNESA)',
            'UNIVERSITAS UDAYANA (UNUD)',
            'UNIVERSITAS LAMBUNG MANGKURAT (ULM)',
            'UNIVERSITAS SAM RATULANGI (UNSRAT)'
        ];

        $schools = [
            'SMAN 8 JAKARTA',
            'SMAN 28 JAKARTA',
            'SMAN 3 BANDUNG',
            'SMAN 5 SURABAYA',
            'SMAN 1 YOGYAKARTA',
            'SMAN 3 SEMARANG',
            'SMAN 1 SURAKARTA',
            'SMAN 1 MEDAN',
            'SMAN 1 PADANG',
            'SMAN 4 DENPASAR',
            'SMAN 1 MAKASSAR',
            'SMAN 1 PEKANBARU',
            'SMAN 1 PALEMBANG',
            'MAN 2 KOTA MALANG',
            'MAN 1 KOTA KEDIRI',
            'MAN 2 KOTA SEMARANG',
            'MAN IC SERPONG',
            'MAN IC GORONTALO',
            'MAN 1 KOTA BATAM',
            'MAN 2 KOTA BANDUNG',
            'MAN 1 YOGYAKARTA',
            'MAN 3 PALEMBANG',
            'SMAN 2 BALIKPAPAN'
        ];

        $occupations = [
            'Guru / Pendidik',
            'Dosen / Akademisi',
            'ASN / Pegawai Negeri Sipil',
            'Tenaga Kesehatan / Dokter / Perawat',
            'Karyawan Swasta / BUMN',
            'Wiraswasta / Pengusaha',
            'Psikolog / Konselor Bimbingan',
            'Peneliti / Scientist',
            'Arsitek / Insinyur',
            'Praktisi Hukum / Advokat'
        ];

        // Seed Master Universities & Schools with Uppercase Normalization
        foreach ($universities as $uName) {
            $prov = $provinces->random();
            $reg = $prov->regencies?->first();
            University::firstOrCreate(
                ['name' => strtoupper(trim($uName))],
                [
                    'province_id' => $prov->id,
                    'regency_id' => $reg?->id ?? 1,
                    'status' => 'active'
                ]
            );
        }

        foreach ($schools as $sName) {
            $prov = $provinces->random();
            $reg = $prov->regencies?->first();
            School::firstOrCreate(
                ['name' => strtoupper(trim($sName))],
                [
                    'province_id' => $prov->id,
                    'regency_id' => $reg?->id ?? 1,
                    'level' => 'SMA',
                    'status' => 'active'
                ]
            );
        }

        $reflections = [
            'Asesmen RQI-20 ini sangat menyadarkan saya akan pentingnya menjaga kebersihan batin dan keikhlasan niat di tengah kesibukan sehari-hari.',
            'Hasil WHO-5 dan RQI saya sangat relevan. Saya bertekad meluangkan lebih banyak waktu untuk ibadah khusyu dan menata kestabilan emosi.',
            'Sangat bermanfaat! Saya belajar bahwa ketenangan ruhani adalah kunci utama dalam menghadapi tekanan ujian dan tanggung jawab pekerjaan.',
            'Tingkat kesehatan mental dan kesadaran spiritual saya semakin terarah. InsyaAllah saya akan merutinkan muhasabah malam.',
            'Asesmen ini memberikan panduan yang sangat konkret untuk memperbaiki niat belajar dan melayani sesama secara tulus.',
            'Refleksi asesmen Ruhiologi ini mendorong saya untuk lebih resilien, sabar, dan mengurangi distraksi gadget di waktu ibadah.',
            'Sangat ilmiah dan menyentuh jiwa. Pengukuran 5 dimensi Ruhiologi memberi peta motivasi nyata bagi pengembangan potensi diri saya.',
            'Tekad saya setelah melihat skor RQI adalah menata ulang orientasi hidup agar senantiasa mengharapkan keridhaan Allah SWT.',
            'Saya berkomitmen untuk meningkatkan kejujuran batin, menjaga lisan dari ghibah, dan memperkuat kepedulian sosial di lingkungan saya.',
            'Asesmen ini menjadi sarana muhasabah yang sangat berharga untuk menyeimbangkan kesehatan emosional dan spiritual.'
        ];

        $totalTarget = 5500;
        $batchSize = 250;
        $chunks = ceil($totalTarget / $batchSize);

        $this->command->info("Memulai pembuatan 5.500 Peserta Mandiri (Wilayah Luar Jambi) dalam {$chunks} batch...");

        $now = now();

        for ($b = 0; $b < $chunks; $b++) {
            $currentBatchCount = (($b + 1) == $chunks) ? ($totalTarget - ($b * $batchSize)) : $batchSize;
            
            $answersBatch = [];
            $resultsBatch = [];

            for ($i = 0; $i < $currentBatchCount; $i++) {
                $isMale = rand(0, 1) === 1;
                $gender = $isMale ? 'Laki-laki' : 'Perempuan';
                
                if ($isMale) {
                    $name = $firstNamesMale[array_rand($firstNamesMale)] . ' ' . $lastNamesMale[array_rand($lastNamesMale)];
                } else {
                    $name = $firstNamesFemale[array_rand($firstNamesFemale)] . ' ' . $lastNamesFemale[array_rand($lastNamesFemale)];
                }

                $category = $categories[array_rand($categories)];
                $emailPrefix = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name)) . '.' . Str::random(5);
                $domain = (rand(1, 10) <= 6) ? 'gmail.com' : ((rand(1, 10) <= 8) ? 'ruhiologyinstitute.com' : 'yahoo.co.id');
                $email = "{$emailPrefix}@{$domain}";

                $phonePrefixes = ['0812', '0813', '0821', '0822', '0852', '0853', '0857', '0895', '0896', '0831', '0878', '0818'];
                $phone = $phonePrefixes[array_rand($phonePrefixes)] . rand(10000000, 99999999);

                $province = $provinces->random();
                $regenciesList = $province->regencies;
                $regency = ($regenciesList && $regenciesList->isNotEmpty()) ? $regenciesList->random() : null;

                $schoolCustom = null;
                $universityCustom = null;
                $occupationCustom = null;

                if ($category === 'Mahasiswa/i') {
                    $universityCustom = strtoupper(trim($universities[array_rand($universities)]));
                } elseif ($category === 'Pelajar') {
                    $schoolCustom = strtoupper(trim($schools[array_rand($schools)]));
                } else {
                    $occupationCustom = $occupations[array_rand($occupations)];
                }

                $createdAt = (clone $now)->subDays(rand(1, 210))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                $participantCode = 'PAR-' . strtoupper(Str::random(8));
                $assessmentCode = 'RQI-' . strtoupper(Str::random(6));

                $participantId = DB::table('participants')->insertGetId([
                    'event_id' => null,
                    'access_type' => 'PUBLIC_SELF',
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
                    'status' => 'active',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $submissionCode = 'SUB-' . strtoupper(Str::random(10));
                $startedAt = (clone $createdAt)->addMinutes(rand(1, 5));
                $submittedAt = (clone $startedAt)->addMinutes(rand(7, 25));

                $submissionId = DB::table('assessment_submissions')->insertGetId([
                    'event_id' => null,
                    'access_type' => 'PUBLIC_SELF',
                    'sub_category' => 'PUBLIC_SELF_ASSESSMENT',
                    'period_id' => $periodId,
                    'participant_id' => $participantId,
                    'submission_type' => 'mandiri',
                    'submission_code' => $submissionCode,
                    'status' => 'submitted',
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
                        'raw_value' => $score,
                        'calculated_score' => $score,
                        'created_at' => $submittedAt,
                        'updated_at' => $submittedAt,
                    ];

                    $totalScore += $score;
                    $dimCode = $question->dimension?->code ?? 'DIM-1';
                    $dimScores[$dimCode] = ($dimScores[$dimCode] ?? 0) + $score;

                    if (str_contains(strtolower($dimCode), 'who')) {
                        $whoSum += $score;
                        $whoCount++;
                    } else {
                        $rqiSum += $score;
                        $rqiCount++;
                    }
                }

                $whoRawScore = $whoSum;
                $whoPercentage = ($whoCount > 0) ? round(($whoSum / ($whoCount * 5)) * 100, 1) : 75;
                
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

            $this->command->info("Batch " . ($b + 1) . " selesai (" . (($b * $batchSize) + $currentBatchCount) . " / {$totalTarget} peserta).");
        }

        $this->command->info("BERHASIL! 5.500 Peserta Mandiri (Wilayah Luar Jambi & Kampus Nasional) beserta jawaban & skor hasil asesmen telah berhasil dibuat & disinkronkan.");
    }
}
