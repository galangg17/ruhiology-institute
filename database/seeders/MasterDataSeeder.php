<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Province;
use App\Models\Regency;
use App\Models\School;
use App\Models\University;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Occupation;
use App\Models\Instrument;
use App\Models\Dimension;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\ScoringRule;
use App\Models\Institution;
use App\Models\Program;
use App\Models\AssessmentPeriod;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Country
        $indonesia = Country::firstOrCreate(
            ['code' => 'ID'],
            ['name' => 'Indonesia', 'status' => 'active']
        );

        // 2. 38 Provinces of Indonesia
        $provincesData = [
            ['code' => '11', 'name' => 'Aceh'],
            ['code' => '12', 'name' => 'Sumatera Utara'],
            ['code' => '13', 'name' => 'Sumatera Barat'],
            ['code' => '14', 'name' => 'Riau'],
            ['code' => '15', 'name' => 'Jambi'],
            ['code' => '16', 'name' => 'Sumatera Selatan'],
            ['code' => '17', 'name' => 'Bengkulu'],
            ['code' => '18', 'name' => 'Lampung'],
            ['code' => '19', 'name' => 'Kepulauan Bangka Belitung'],
            ['code' => '21', 'name' => 'Kepulauan Riau'],
            ['code' => '31', 'name' => 'DKI Jakarta'],
            ['code' => '32', 'name' => 'Jawa Barat'],
            ['code' => '33', 'name' => 'Jawa Tengah'],
            ['code' => '34', 'name' => 'DI Yogyakarta'],
            ['code' => '35', 'name' => 'Jawa Timur'],
            ['code' => '36', 'name' => 'Banten'],
            ['code' => '51', 'name' => 'Bali'],
            ['code' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['code' => '53', 'name' => 'Nusa Tenggara Timur'],
            ['code' => '61', 'name' => 'Kalimantan Barat'],
            ['code' => '62', 'name' => 'Kalimantan Tengah'],
            ['code' => '63', 'name' => 'Kalimantan Selatan'],
            ['code' => '64', 'name' => 'Kalimantan Timur'],
            ['code' => '65', 'name' => 'Kalimantan Utara'],
            ['code' => '71', 'name' => 'Sulawesi Utara'],
            ['code' => '72', 'name' => 'Sulawesi Tengah'],
            ['code' => '73', 'name' => 'Sulawesi Selatan'],
            ['code' => '74', 'name' => 'Sulawesi Tenggara'],
            ['code' => '75', 'name' => 'Gorontalo'],
            ['code' => '76', 'name' => 'Sulawesi Barat'],
            ['code' => '81', 'name' => 'Maluku'],
            ['code' => '82', 'name' => 'Maluku Utara'],
            ['code' => '91', 'name' => 'Papua Barat'],
            ['code' => '92', 'name' => 'Papua'],
            ['code' => '93', 'name' => 'Papua Selatan'],
            ['code' => '94', 'name' => 'Papua Tengah'],
            ['code' => '95', 'name' => 'Papua Pegunungan'],
            ['code' => '96', 'name' => 'Papua Barat Daya'],
        ];

        $provinceModels = [];
        foreach ($provincesData as $pData) {
            $provinceModels[$pData['code']] = Province::firstOrCreate(
                ['code' => $pData['code']],
                ['country_id' => $indonesia->id, 'name' => $pData['name'], 'status' => 'active']
            );
        }

        $jambi = $provinceModels['15'];

        // 3. Regencies for Jambi & Major Indonesian Cities
        $regenciesData = [
            // Jambi
            ['prov_code' => '15', 'code' => '1501', 'name' => 'Kerinci', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1502', 'name' => 'Merangin', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1503', 'name' => 'Sarolangun', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1504', 'name' => 'Batanghari', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1505', 'name' => 'Muaro Jambi', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1506', 'name' => 'Tanjung Jabung Barat', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1507', 'name' => 'Tanjung Jabung Timur', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1508', 'name' => 'Bungo', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1509', 'name' => 'Tebo', 'type' => 'Kabupaten'],
            ['prov_code' => '15', 'code' => '1571', 'name' => 'Kota Jambi', 'type' => 'Kota'],
            ['prov_code' => '15', 'code' => '1572', 'name' => 'Kota Sungai Penuh', 'type' => 'Kota'],

            // DKI Jakarta
            ['prov_code' => '31', 'code' => '3171', 'name' => 'Jakarta Selatan', 'type' => 'Kota'],
            ['prov_code' => '31', 'code' => '3172', 'name' => 'Jakarta Timur', 'type' => 'Kota'],
            ['prov_code' => '31', 'code' => '3173', 'name' => 'Jakarta Pusat', 'type' => 'Kota'],
            ['prov_code' => '31', 'code' => '3174', 'name' => 'Jakarta Barat', 'type' => 'Kota'],
            ['prov_code' => '31', 'code' => '3175', 'name' => 'Jakarta Utara', 'type' => 'Kota'],

            // Jawa Barat
            ['prov_code' => '32', 'code' => '3273', 'name' => 'Kota Bandung', 'type' => 'Kota'],
            ['prov_code' => '32', 'code' => '3275', 'name' => 'Kota Bekasi', 'type' => 'Kota'],
            ['prov_code' => '32', 'code' => '3276', 'name' => 'Kota Depok', 'type' => 'Kota'],
            ['prov_code' => '32', 'code' => '3204', 'name' => 'Bandung', 'type' => 'Kabupaten'],
            ['prov_code' => '32', 'code' => '3201', 'name' => 'Bogor', 'type' => 'Kabupaten'],

            // Jawa Tengah
            ['prov_code' => '33', 'code' => '3374', 'name' => 'Kota Semarang', 'type' => 'Kota'],
            ['prov_code' => '33', 'code' => '3372', 'name' => 'Kota Surakarta (Solo)', 'type' => 'Kota'],

            // DI Yogyakarta
            ['prov_code' => '34', 'code' => '3471', 'name' => 'Kota Yogyakarta', 'type' => 'Kota'],
            ['prov_code' => '34', 'code' => '3404', 'name' => 'Sleman', 'type' => 'Kabupaten'],
            ['prov_code' => '34', 'code' => '3402', 'name' => 'Bantul', 'type' => 'Kabupaten'],

            // Jawa Timur
            ['prov_code' => '35', 'code' => '3578', 'name' => 'Kota Surabaya', 'type' => 'Kota'],
            ['prov_code' => '35', 'code' => '3573', 'name' => 'Kota Malang', 'type' => 'Kota'],

            // Banten
            ['prov_code' => '36', 'code' => '3674', 'name' => 'Kota Tangerang Selatan', 'type' => 'Kota'],
            ['prov_code' => '36', 'code' => '3671', 'name' => 'Kota Tangerang', 'type' => 'Kota'],

            // Sumatera Utara
            ['prov_code' => '12', 'code' => '1271', 'name' => 'Kota Medan', 'type' => 'Kota'],

            // Sumatera Barat
            ['prov_code' => '13', 'code' => '1371', 'name' => 'Kota Padang', 'type' => 'Kota'],

            // Riau
            ['prov_code' => '14', 'code' => '1471', 'name' => 'Kota Pekanbaru', 'type' => 'Kota'],

            // Sumatera Selatan
            ['prov_code' => '16', 'code' => '1671', 'name' => 'Kota Palembang', 'type' => 'Kota'],

            // Lampung
            ['prov_code' => '18', 'code' => '1871', 'name' => 'Kota Bandar Lampung', 'type' => 'Kota'],

            // Bali
            ['prov_code' => '51', 'code' => '5171', 'name' => 'Kota Denpasar', 'type' => 'Kota'],
            ['prov_code' => '51', 'code' => '5103', 'name' => 'Badung', 'type' => 'Kabupaten'],

            // Sulawesi Selatan
            ['prov_code' => '73', 'code' => '7371', 'name' => 'Kota Makassar', 'type' => 'Kota'],

            // Kalimantan Timur
            ['prov_code' => '64', 'code' => '6471', 'name' => 'Kota Balikpapan', 'type' => 'Kota'],
            ['prov_code' => '64', 'code' => '6472', 'name' => 'Kota Samarinda', 'type' => 'Kota'],
        ];

        $regencies = [];
        foreach ($regenciesData as $r) {
            $prov = $provinceModels[$r['prov_code']] ?? $jambi;
            $regencies[$r['code']] = Regency::firstOrCreate(
                ['code' => $r['code']],
                ['province_id' => $prov->id, 'name' => $r['name'], 'type' => $r['type'], 'status' => 'active']
            );
        }

        // 4. Representative Schools
        $muaroJambiId = $regencies['1505']->id;
        $kotaJambiId = $regencies['1571']->id;

        $schoolsData = [
            ['province_id' => $jambi->id, 'regency_id' => $muaroJambiId, 'level' => 'SMA', 'npsn' => '10501234', 'name' => 'SMA Negeri 1 Muaro Jambi'],
            ['province_id' => $jambi->id, 'regency_id' => $muaroJambiId, 'level' => 'SMA', 'npsn' => '10501235', 'name' => 'SMA Negeri 2 Muaro Jambi'],
            ['province_id' => $jambi->id, 'regency_id' => $kotaJambiId, 'level' => 'SMA', 'npsn' => '10502001', 'name' => 'SMA Negeri 1 Kota Jambi'],
            ['province_id' => $jambi->id, 'regency_id' => $kotaJambiId, 'level' => 'SMK', 'npsn' => '10502002', 'name' => 'SMK Negeri 1 Kota Jambi'],
            ['province_id' => $jambi->id, 'regency_id' => $muaroJambiId, 'level' => 'SMP', 'npsn' => '10501100', 'name' => 'SMP Negeri 1 Muaro Jambi'],
        ];

        foreach ($schoolsData as $s) {
            School::firstOrCreate(
                ['name' => $s['name'], 'regency_id' => $s['regency_id']],
                $s + ['status' => 'active']
            );
        }

        // 5. Representative Universities
        $uin = University::firstOrCreate(
            ['name' => 'UIN Sulthan Thaha Saifuddin Jambi'],
            [
                'province_id' => $jambi->id,
                'regency_id' => $muaroJambiId,
                'code' => 'UIN-STS-JAMBI',
                'status' => 'active'
            ]
        );

        $unja = University::firstOrCreate(
            ['name' => 'Universitas Jambi (UNJA)'],
            [
                'province_id' => $jambi->id,
                'regency_id' => $muaroJambiId,
                'code' => 'UNJA',
                'status' => 'active'
            ]
        );

        // 6. Faculties for UIN STS Jambi
        $fst = Faculty::firstOrCreate(
            ['university_id' => $uin->id, 'name' => 'Fakultas Sains dan Teknologi'],
            ['code' => 'FST', 'status' => 'active']
        );

        $ftk = Faculty::firstOrCreate(
            ['university_id' => $uin->id, 'name' => 'Fakultas Tarbiyah dan Keguruan'],
            ['code' => 'FTK', 'status' => 'active']
        );

        $febi = Faculty::firstOrCreate(
            ['university_id' => $uin->id, 'name' => 'Fakultas Ekonomi dan Bisnis Islam'],
            ['code' => 'FEBI', 'status' => 'active']
        );

        // 7. Study Programs
        StudyProgram::firstOrCreate(
            ['university_id' => $uin->id, 'faculty_id' => $fst->id, 'name' => 'Sistem Informasi'],
            ['code' => 'SI', 'status' => 'active']
        );

        StudyProgram::firstOrCreate(
            ['university_id' => $uin->id, 'faculty_id' => $ftk->id, 'name' => 'Manajemen Pendidikan Islam'],
            ['code' => 'MPI', 'status' => 'active']
        );

        StudyProgram::firstOrCreate(
            ['university_id' => $uin->id, 'faculty_id' => $febi->id, 'name' => 'Ekonomi Syariah'],
            ['code' => 'ES', 'status' => 'active']
        );

        // 8. Occupations
        $occupations = [
            'Guru / Pendidik',
            'Dosen / Akademisi',
            'ASN / Pegawai Negeri',
            'Karyawan Swasta',
            'Wiraswasta / Pengusaha',
            'Tenaga Kesehatan',
            'Pelajar / Mahasiswa',
            'Lainnya',
        ];

        foreach ($occupations as $occ) {
            Occupation::firstOrCreate(['name' => $occ], ['status' => 'active']);
        }

        // 9. Primary Instrument: RQI-15 (Inventori Kecerdasan Ruhiologi 15 Butir)
        $rqi = Instrument::firstOrCreate(
            ['code' => 'RQI-15'],
            [
                'name' => 'Inventori Kecerdasan Ruhiologi (RQI-15)',
                'description' => 'Instrumen 15 butir terstruktur untuk mengukur 5 tahap utama Kecerdasan Ruhiologi manusia.',
                'version' => '1.0',
                'instructions' => 'Pilih frekuensi yang paling menggambarkan kondisi dan perasaan Anda yang sebenarnya.',
                'status' => 'active'
            ]
        );

        // 5 RQI-15 Dimensions (Tahap 1 s/d Tahap 5)
        $dim1 = Dimension::firstOrCreate(
            ['instrument_id' => $rqi->id, 'code' => 'DIM-1'],
            ['name' => 'Pengenalan & Kesadaran Diri Hakiki', 'description' => 'Kesadaran mendalam akan kedudukan ruh sebagai intisari kemanusiaan.', 'order' => 1]
        );

        $dim2 = Dimension::firstOrCreate(
            ['instrument_id' => $rqi->id, 'code' => 'DIM-2'],
            ['name' => 'Pengenalan Ketuhanan (God Spot)', 'description' => 'Kepekaan batin merasakan kehadiran Ilahi dan pertolongan-Nya.', 'order' => 2]
        );

        $dim3 = Dimension::firstOrCreate(
            ['instrument_id' => $rqi->id, 'code' => 'DIM-3'],
            ['name' => 'Ketaatan Ibadah', 'description' => 'Kekhusyukan dan komitmen menjalankan ibadah serta doa.', 'order' => 3]
        );

        $dim4 = Dimension::firstOrCreate(
            ['instrument_id' => $rqi->id, 'code' => 'DIM-4'],
            ['name' => 'Perubahan Perilaku & Akhlak Karimah', 'description' => 'Kebersihan batin dari penyakit sosial, gosip, dan komitmen jujur.', 'order' => 4]
        );

        $dim5 = Dimension::firstOrCreate(
            ['instrument_id' => $rqi->id, 'code' => 'DIM-5'],
            ['name' => 'Kesadaran Puncak Ketuhanan (God Light & Muraqabah)', 'description' => 'Kesadaran bahwa Tuhan selalu mengawasi dan integritas kejujuran puncak.', 'order' => 5]
        );

        // 15 RQI Questions (3 questions per dimension)
        $questionsData = [
            // Tahap 1: Pengenalan & Kesadaran Diri Hakiki (Q1 - Q3)
            ['dim' => $dim1, 'text' => 'Saya menyadari bahwa diri saya bukan sekadar fisik atau status sosial, melainkan ruh yang sedang berproses, sehingga saya memaknai setiap aktivitas harian sebagai sarana pembentukan jiwa.', 'direction' => 'normal'],
            ['dim' => $dim1, 'text' => 'Ketika rasa penat atau godaan gawai (scrolling berjam-jam) datang, saya sadar kapan harus segera \'rem darurat\' agar waktu dan energi saya tidak habis sia-sia.', 'direction' => 'normal'],
            ['dim' => $dim1, 'text' => 'Saat saya merasa gagal, tertinggal, atau insecure melihat pencapaian orang lain di media sosial, batin saya kembali tenang karena menyadari nilai diri saya tidak ditentukan oleh validasi dunia maya.', 'direction' => 'normal'],

            // Tahap 2: Pengenalan Ketuhanan (God Spot) (Q4 - Q6)
            ['dim' => $dim2, 'text' => 'Di tengah kesibukan dan kepala yang pusing karena aktivitas, ada \'ruang sunyi\' dalam diri saya yang secara alami merindukan ketenangan dengan mengingat Tuhan.', 'direction' => 'normal'],
            ['dim' => $dim2, 'text' => 'Saya meyakini secara mendalam bahwa setiap masalah, tekanan, atau jalan buntu yang saya alami adalah rancangan kasih sayang Tuhan untuk mendewasakan jiwa saya.', 'direction' => 'normal'],
            ['dim' => $dim2, 'text' => 'Ketika menghadapi masalah yang membuat frustrasi, saya membiasakan diri untuk tidak dikuasai emosi negatif, melainkan langsung berserah dan memohon petunjuk kepada-Nya.', 'direction' => 'normal'],

            // Tahap 3: Ketaatan Ibadah (Q7 - Q9)
            ['dim' => $dim3, 'text' => 'Saya menunaikan ibadah dengan tenang dan penuh penghayatan (thuma\'ninah), karena saya merasakannya sebagai \'ruang jeda\' yang memulihkan kepenatan mental saya.', 'direction' => 'normal'],
            ['dim' => $dim3, 'text' => 'Saya rutin meluangkan waktu untuk mengevaluasi diri (muhasabah), sehingga ibadah dan doa yang saya lakukan benar-benar meredakan kecemasan saya terhadap masa depan.', 'direction' => 'normal'],
            ['dim' => $dim3, 'text' => 'Saya menjaga kedisiplinan ibadah harian atas dorongan nurani dan kebutuhan jiwa saya sendiri, bukan karena sekadar ikut-ikutan tren atau ingin dipuji orang lain.', 'direction' => 'normal'],

            // Tahap 4: Perubahan Perilaku & Akhlak Karimah (Q10 - Q12)
            ['dim' => $dim4, 'text' => 'Saya menjalankan peran, pekerjaan, atau komitmen kerja sama secara tuntas dan jujur, serta menghindari sikap lepas tangan (anti-freerider) kepada orang lain.', 'direction' => 'normal'],
            ['dim' => $dim4, 'text' => 'Ketika berada di tengah lingkungan yang suka bergosip, drama pertemanan, atau konflik, batin saya menolak untuk ikut-ikutan memperkeruh suasana.', 'direction' => 'normal'],
            ['dim' => $dim4, 'text' => 'Saya tergerak secara tulus untuk membantu, berbagi ilmu, atau meringankan beban orang di sekitar yang sedang mengalami kesulitan.', 'direction' => 'normal'],

            // Tahap 5: Kesadaran Puncak Ketuhanan (God Light & Muraqabah) (Q13 - Q15)
            ['dim' => $dim5, 'text' => 'Kesadaran bahwa Tuhan selalu mengawasi membuat saya menolak segala bentuk kecurangan, manipulasi, atau plagiarisme, sekecil apa pun itu.', 'direction' => 'normal'],
            ['dim' => $dim5, 'text' => 'Ketika sedang sendirian di kamar larut malam memegang gawai, kesadaran bahwa Tuhan Maha Melihat menjaga saya dari hal-hal yang merusak kesucian pikiran.', 'direction' => 'normal'],
            ['dim' => $dim5, 'text' => 'Bagi saya, proses yang jujur dan keberkahan hidup jauh lebih berharga daripada meraih hasil instan atau keuntungan besar dengan cara yang melanggar nilai moral.', 'direction' => 'normal'],
        ];

        $likertOptions = [
            ['text' => 'Sepanjang Waktu', 'val' => 5],
            ['text' => 'Sebagian Besar Waktu', 'val' => 4],
            ['text' => 'Kadang-kadang', 'val' => 3],
            ['text' => 'Jarang', 'val' => 2],
            ['text' => 'Tidak Pernah', 'val' => 1],
        ];

        foreach ($questionsData as $idx => $qData) {
            $question = Question::firstOrCreate(
                ['instrument_id' => $rqi->id, 'order' => $idx + 1],
                [
                    'dimension_id' => $qData['dim']->id,
                    'question_text' => $qData['text'],
                    'type' => 'likert',
                    'scoring_direction' => $qData['direction'],
                    'status' => 'active'
                ]
            );

            foreach ($likertOptions as $optIdx => $opt) {
                QuestionOption::firstOrCreate(
                    ['question_id' => $question->id, 'option_value' => $opt['val']],
                    ['option_text' => $opt['text'], 'order' => $optIdx + 1]
                );
            }
        }

        // Scoring rule for RQI-15
        ScoringRule::firstOrCreate(
            ['instrument_id' => $rqi->id],
            [
                'scale_min' => 1,
                'scale_max' => 5,
                'reverse_mapping' => [1 => 5, 2 => 4, 3 => 3, 4 => 2, 5 => 1],
                'interpretation_ranges' => [
                    ['min' => 65, 'max' => 75, 'level' => 'Level 5: Enlightened Soul (Jiwa Terpancar Sempurna)', 'description' => 'Level puncak RQ! Integritas moral mutlak di ruang privat maupun publik, pasrah total ke Tuhan, dan vibes-nya bener-bener bawa keberkahan buat orang sekitar.'],
                    ['min' => 54, 'max' => 64, 'level' => 'Level 4: Mindful Youth (Jiwa Tenang & Terjaga)', 'description' => 'Udah zen banget. Self-awareness tinggi, anti-prokrastinasi, dan ibadah berasa jadi mental detox yang bikin adem. God Light mulai aktif ngebimbing pilihan hidup.'],
                    ['min' => 41, 'max' => 53, 'level' => 'Level 3: Developing Soul (Jiwa Berproses Stabil)', 'description' => 'On track! Fondasi God Spot dan ketaatan ibadah udah lumayan konsisten. Akhlak sosial mulai kerasa green flag-nya, tinggal ngelatih ketenangan pas lagi banyak tekanan.'],
                    ['min' => 27, 'max' => 40, 'level' => 'Level 2: Awakening Pilgrim (Jiwa Mulai Tergugah)', 'description' => 'Mulai sadar kalau hidup butuh arah yang bener. Sadar diri dan niat ibadah mulai tumbuh, tapi rem batin masih sering jebol kalau kena godaan atau stres harian.'],
                    ['min' => 15, 'max' => 26, 'level' => 'Level 1: Spiritual Lowbat (Jiwa Kelelahan)', 'description' => 'Batin kamu lagi burnout parah dan kecanduan distraksi digital. Koneksi God Spot lowbat, gampang insecure, dan butuh digital-spiritual detox secepatnya.'],
                ]
            ]
        );

        // 10. Secondary Instrument: WHO-5 Well-Being Index
        $who5 = Instrument::firstOrCreate(
            ['code' => 'WHO-5'],
            [
                'name' => 'Indeks Kesejahteraan Mental (WHO-5)',
                'description' => 'Instrumen 5 butir untuk menilai kondisi kesejahteraan psikologis & keseimbangan batiniah dalam 2 minggu terakhir.',
                'version' => '1.0',
                'instructions' => 'Pilih frekuensi yang menggambarkan kondisi Anda dalam 2 minggu terakhir.',
                'status' => 'active'
            ]
        );

        $dimWho = Dimension::firstOrCreate(
            ['instrument_id' => $who5->id, 'code' => 'DIM-WHO5'],
            ['name' => 'Skrining Kesejahteraan Emosional', 'description' => 'Indikator kesejahteraan umum berdasarkan WHO-5.', 'order' => 1]
        );

        $whoQuestions = [
            'Saya merasa bersemangat, ceria, dan termotivasi dalam menjalani hari-hari.',
            'Saya merasa tenang, damai, dan tidak terbebani oleh kecemasan berlebih.',
            'Tubuh dan pikiran saya merasa aktif, segar, dan bertenaga.',
            'Saya bisa bangun tidur pagi dengan perasaan segar dan istirahat yang cukup.',
            'Kehidupan sehari-hari saya terasa bermakna dan memicu antusiasme saya.',
        ];

        $whoOptions = [
            ['text' => 'Sepanjang Waktu', 'val' => 5],
            ['text' => 'Sebagian Besar Waktu', 'val' => 4],
            ['text' => 'Kadang-kadang', 'val' => 3],
            ['text' => 'Jarang', 'val' => 2],
            ['text' => 'Tidak Pernah', 'val' => 1],
        ];

        foreach ($whoQuestions as $idx => $qText) {
            $question = Question::firstOrCreate(
                ['instrument_id' => $who5->id, 'order' => $idx + 1],
                [
                    'dimension_id' => $dimWho->id,
                    'question_text' => $qText,
                    'type' => 'likert',
                    'scoring_direction' => 'normal',
                    'status' => 'active'
                ]
            );

            foreach ($whoOptions as $optIdx => $opt) {
                QuestionOption::firstOrCreate(
                    ['question_id' => $question->id, 'option_value' => $opt['val']],
                    ['option_text' => $opt['text'], 'order' => $optIdx + 1]
                );
            }
        }


        // 11. Institution, Program & Assessment Period setup
        $inst = Institution::firstOrCreate(
            ['code' => 'RQI-HQ'],
            [
                'name' => 'Ruhiology Institute Utama',
                'type' => 'University',
                'status' => 'active'
            ]
        );

        $prog = Program::firstOrCreate(
            ['code' => 'RQI-PROG-2026'],
            [
                'institution_id' => $inst->id,
                'name' => 'Program Asesmen & Coaching Ruhiologi 2026',
                'status' => 'active'
            ]
        );

        AssessmentPeriod::firstOrCreate(
            ['period_code' => 'RQI-PERIOD-2026'],
            [
                'program_id' => $prog->id,
                'instrument_id' => $rqi->id,
                'title' => 'Asesmen Nasional Kesadaran Ruhiologi 2026',
                'pretest_start' => now()->subDays(30),
                'pretest_end' => now()->addDays(180),
                'posttest_start' => now()->subDays(10),
                'posttest_end' => now()->addDays(180),
                'status' => 'active'
            ]
        );
    }
}
