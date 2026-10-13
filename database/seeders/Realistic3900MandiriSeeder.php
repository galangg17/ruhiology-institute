<?php

namespace Database\Seeders;

use App\Models\AssessmentPeriod;
use App\Models\Country;
use App\Models\Instrument;
use App\Models\Province;
use App\Models\Question;
use App\Models\Regency;
use App\Models\School;
use App\Models\University;
use App\Services\RqiScoringEngine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Realistic3900MandiriSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure all 514+ regencies exist first
        if (Regency::count() < 200) {
            app(IndonesiaRegenciesSeeder::class)->run();
        }

        $instrument = Instrument::where('code', 'RQI-20')->first() ?? Instrument::first();
        if (!$instrument) {
            $this->command?->error('Instrument RQI-20 tidak ditemukan. Jalankan MasterDataSeeder terlebih dahulu.');
            return;
        }

        $questions = Question::where('instrument_id', $instrument->id)
            ->with(['dimension', 'options'])
            ->orderBy('order')
            ->get();

        if ($questions->isEmpty()) {
            $this->command?->error('Tidak ada soal pada instrumen.');
            return;
        }

        $period = AssessmentPeriod::where('instrument_id', $instrument->id)->first() ?? AssessmentPeriod::first();
        $periodId = $period ? $period->id : 1;
        $indonesia = Country::where('code', 'ID')->first();
        $rqiEngine = app(RqiScoringEngine::class);

        $provinces = Province::with(['regencies' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        if ($provinces->isEmpty()) {
            $this->command?->error('Data Provinsi kosong.');
            return;
        }

        $firstNamesMale = [
            'Ahmad', 'Muhammad', 'Rizky', 'Dimas', 'Aditya', 'Farhan', 'Bagas', 'Fajar', 'Gilang', 'Naufal',
            'Irfan', 'Bayu', 'Ilham', 'Reza', 'Rian', 'Daffa', 'Alif', 'Arif', 'Hendra', 'Taufik',
            'Wahyu', 'Yusuf', 'Zainal', 'Andi', 'Budi', 'Eko', 'Agus', 'Satria', 'Pradana', 'Mahendra',
            'Raden', 'Luthfi', 'Fikri', 'Haikal', 'Zaki', 'Raihan', 'Hafiz', 'Iqbal', 'Syahrul', 'Rizal',
            'Danang', 'Yoga', 'Wisnu', 'Galih', 'Rangga', 'putra', 'Aldi', 'Fadhil', 'Ammar', 'Salman',
            'Teuku', 'Tubagus', 'Lalu', 'I Gede', 'I Made', 'Fransiskus', 'Yohanes', 'Kevin', 'steven', 'Andre'
        ];

        $middleOrLastNamesMale = [
            'Pratama', 'Santoso', 'Kurniawan', 'Setiawan', 'Hidayat', 'Saputra', 'Wibowo', 'Nugraha',
            'Syahputra', 'Ramadhan', 'Wijaya', 'Fadillah', 'Nasution', 'Harahap', 'Siregar', 'Ginting',
            'Lubis', 'Hasibuan', 'Wicaksono', 'Susanto', 'Kusuma', 'Purnomo', 'Gunawan', 'Chaniago',
            'Bachtiar', 'Syahrir', 'Irawan', 'Firmansyah', 'Maulana', 'Hakim', 'habibie', 'Mubarok',
            'Firdaus', 'Alfarizi', 'Surya', 'Permana', 'lesmana', 'Wardhana', 'Pamungkas', 'Subagyo',
            'octavian', 'Hermawan', 'Budiman', 'Sulistyo', 'Prayoga', 'hardiansyah', 'Nurhadi', 'Akbar'
        ];

        $firstNamesFemale = [
            'Siti', 'Aisyah', 'Nurul', 'Putri', 'Nabila', 'Najwa', 'Fadhilah', 'Aulia', 'Zahra', 'Dewi',
            'Anindya', 'Salsabila', 'Syifa', 'Khadijah', 'Fatimah', 'Humaira', 'Rahma', 'Kirana', 'Maharani',
            'Gita', 'Intan', 'Lestari', 'Mega', 'Novi', 'Puspa', 'Retno', 'Sri', 'Tania', 'Utami',
            'Vina', 'Yulia', 'Dian', 'Tazkia', 'Mazaya', 'Hasna', 'Annisa', 'Shafira', 'Nadhira', 'Keisya',
            'Tiara', 'Citra', 'Rani', 'Ayu', 'indah', 'Mutia', 'Cut', 'Ratu', 'Ni Putu', 'Ni Luh',
            'Maria', 'Theresia', 'Clara', 'Jessica', 'Amelia', 'Yasmin', 'Balqis', 'Hana', 'Farah', 'Nadia'
        ];

        $middleOrLastNamesFemale = [
            'Wulandari', 'Wardani', 'Prastika', 'Fauziah', 'Az-Zahra', 'Agustin', 'Ulfah', 'Putri',
            'Astuti', 'Kirana', 'Rahmah', 'Maharani', 'Fadhillah', 'Suryani', 'Handayani', 'Kartika',
            'Permata', 'Safitri', 'Anggraini', 'Lestari', 'Kusumawardhani', 'Cahyani', 'Ningsih',
            'Octaviani', 'Rahmawati', 'Susanti', 'purnamasari', 'Novitasari', 'Damayanti', 'Saraswati',
            'Melati', 'Khairunnisa', 'Ramadhani', 'Salsabila', 'Amalia', 'Fitriani', 'Mardiyah', 'Suhartini'
        ];

        $universities = [
            ['name' => 'UNIVERSITAS INDONESIA (UI)', 'domain' => 'ui.ac.id'],
            ['name' => 'UNIVERSITAS GADJAH MADA (UGM)', 'domain' => 'mail.ugm.ac.id'],
            ['name' => 'INSTITUT TEKNOLOGI BANDUNG (ITB)', 'domain' => 'mahasiswa.itb.ac.id'],
            ['name' => 'INSTITUT PERTANIAN BOGOR (IPB UNIVERSITY)', 'domain' => 'apps.ipb.ac.id'],
            ['name' => 'UNIVERSITAS PADJADJARAN (UNPAD)', 'domain' => 'mail.unpad.ac.id'],
            ['name' => 'UNIVERSITAS AIRLANGGA (UNAIR)', 'domain' => 'staf.unair.ac.id'],
            ['name' => 'UNIVERSITAS DIPONEGORO (UNDIP)', 'domain' => 'students.undip.ac.id'],
            ['name' => 'UNIVERSITAS BRAWIJAYA (UB)', 'domain' => 'student.ub.ac.id'],
            ['name' => 'INSTRITUT TEKNOLOGI SEPULUH NOPEMBER (ITS)', 'domain' => 'mhs.its.ac.id'],
            ['name' => 'UNIVERSITAS HASANUDDIN (UNHAS)', 'domain' => 'unhas.ac.id'],
            ['name' => 'UNIVERSITAS SEBELAS MARET (UNS)', 'domain' => 'student.uns.ac.id'],
            ['name' => 'UNIVERSITAS SUMATERA UTARA (USU)', 'domain' => 'students.usu.ac.id'],
            ['name' => 'UNIVERSITAS ANDALAS (UNAND)', 'domain' => 'student.unand.ac.id'],
            ['name' => 'UNIVERSITAS SRIWIJAYA (UNSRI)', 'domain' => 'unsri.ac.id'],
            ['name' => 'UNIVERSITAS SYIAH KUALA (USK)', 'domain' => 'mhs.usk.ac.id'],
            ['name' => 'UNIVERSITAS RIAU (UNRI)', 'domain' => 'student.unri.ac.id'],
            ['name' => 'UNIVERSITAS JAMBI (UNJA)', 'domain' => 'unja.ac.id'],
            ['name' => 'UNIVERSITAS LAMPUNG (UNILA)', 'domain' => 'students.unila.ac.id'],
            ['name' => 'UNIVERSITAS PENDIDIKAN INDONESIA (UPI)', 'domain' => 'upi.edu'],
            ['name' => 'UNIVERSITAS NEGERI JAKARTA (UNJ)', 'domain' => 'mhs.unj.ac.id'],
            ['name' => 'UNIVERSITAS NEGERI YOGYAKARTA (UNY)', 'domain' => 'student.uny.ac.id'],
            ['name' => 'UNIVERSITAS NEGERI MALANG (UM)', 'domain' => 'students.um.ac.id'],
            ['name' => 'UNIVERSITAS NEGERI SEMARANG (UNNES)', 'domain' => 'students.unnes.ac.id'],
            ['name' => 'UNIVERSITAS NEGERI SURABAYA (UNESA)', 'domain' => 'mhs.unesa.ac.id'],
            ['name' => 'UNIVERSITAS NEGERI PADANG (UNP)', 'domain' => 'student.unp.ac.id'],
            ['name' => 'UNIVERSITAS NEGERI MAKASSAR (UNM)', 'domain' => 'unm.ac.id'],
            ['name' => 'UIN SYARIF HIDAYATULLAH JAKARTA', 'domain' => 'mhs.uinjkt.ac.id'],
            ['name' => 'UIN SUNAN KALIJAGA YOGYAKARTA', 'domain' => 'uin-suka.ac.id'],
            ['name' => 'UIN SUNAN GUNUNG DJATI BANDUNG', 'domain' => 'uinsgd.ac.id'],
            ['name' => 'UIN MAULANA MALIK IBRAHIM MALANG', 'domain' => 'uin-malang.ac.id'],
            ['name' => 'UIN SUNAN AMPEL SURABAYA', 'domain' => 'uinsby.ac.id'],
            ['name' => 'UIN WALISONGO SEMARANG', 'domain' => 'walisongo.ac.id'],
            ['name' => 'UIN RADEN FATAH PALEMBANG', 'domain' => 'radenfatah.ac.id'],
            ['name' => 'UIN SULTAN THAHA SAIFUDDIN JAMBI', 'domain' => 'uinjambi.ac.id'],
            ['name' => 'UIN SULTAN SYARIF KASIM RIAU', 'domain' => 'uin-suska.ac.id'],
            ['name' => 'UIN AR-RANIRY BANDA ACEH', 'domain' => 'ar-raniry.ac.id'],
            ['name' => 'UIN SUMATERA UTARA MEDAN', 'domain' => 'uinsu.ac.id'],
            ['name' => 'UIN IMAM BONJOL PADANG', 'domain' => 'uinib.ac.id'],
            ['name' => 'UIN RADEN INTAN LAMPUNG', 'domain' => 'radenintan.ac.id'],
            ['name' => 'UIN ANTASARI BANJARMASIN', 'domain' => 'uin-antasari.ac.id'],
            ['name' => 'UIN ALAUDDIN MAKASSAR', 'domain' => 'uin-alauddin.ac.id'],
            ['name' => 'UIN MATARAM', 'domain' => 'uinmataram.ac.id'],
            ['name' => 'UNIVERSITAS UDAYANA (UNUD)', 'domain' => 'student.unud.ac.id'],
            ['name' => 'UNIVERSITAS LAMBUNG MANGKURAT (ULM)', 'domain' => 'ulm.ac.id'],
            ['name' => 'UNIVERSITAS TANJUNGPURA (UNTAN)', 'domain' => 'student.untan.ac.id'],
            ['name' => 'UNIVERSITAS MULAWARMAN (UNMUL)', 'domain' => 'unmul.ac.id'],
            ['name' => 'UNIVERSITAS SAM RATULANGI (UNSRAT)', 'domain' => 'unsrat.ac.id'],
            ['name' => 'UNIVERSITAS HALU OLEO (UHO)', 'domain' => 'uho.ac.id'],
            ['name' => 'UNIVERSITAS TADULAKO (UNTAD)', 'domain' => 'untad.ac.id'],
            ['name' => 'UNIVERSITAS PATTIMURA (UNPATTI)', 'domain' => 'unpatti.ac.id'],
            ['name' => 'UNIVERSITAS CENDERAWASIH (UNCEN)', 'domain' => 'uncen.ac.id'],
            ['name' => 'TELKOM UNIVERSITY BANDUNG', 'domain' => 'student.telkomuniversity.ac.id'],
            ['name' => 'UNIVERSITAS ISLAM INDONESIA (UII)', 'domain' => 'students.uii.ac.id'],
            ['name' => 'UNIVERSITAS MUHAMMADIYAH YOGYAKARTA (UMY)', 'domain' => 'ft.umy.ac.id'],
            ['name' => 'UNIVERSITAS MUHAMMADIYAH MALANG (UMM)', 'domain' => 'webmail.umm.ac.id'],
            ['name' => 'UNIVERSITAS MUHAMMADIYAH SURAKARTA (UMS)', 'domain' => 'student.ums.ac.id'],
            ['name' => 'BINUS UNIVERSITY JAKARTA', 'domain' => 'binus.ac.id'],
            ['name' => 'UNIVERSITAS ISLAM BANDUNG (UNISBA)', 'domain' => 'unisba.ac.id'],
        ];

        $schools = [
            ['name' => 'SMAN 8 JAKARTA', 'level' => 'SMA'],
            ['name' => 'SMAN 28 JAKARTA', 'level' => 'SMA'],
            ['name' => 'SMAN 68 JAKARTA', 'level' => 'SMA'],
            ['name' => 'SMAN 3 BANDUNG', 'level' => 'SMA'],
            ['name' => 'SMAN 5 BANDUNG', 'level' => 'SMA'],
            ['name' => 'SMAN 1 BOGOR', 'level' => 'SMA'],
            ['name' => 'SMAN 2 DEPOK', 'level' => 'SMA'],
            ['name' => 'SMAN 1 BEKASI', 'level' => 'SMA'],
            ['name' => 'SMAN 3 SEMARANG', 'level' => 'SMA'],
            ['name' => 'SMAN 1 SURAKARTA', 'level' => 'SMA'],
            ['name' => 'SMAN 1 YOGYAKARTA', 'level' => 'SMA'],
            ['name' => 'SMAN 3 YOGYAKARTA', 'level' => 'SMA'],
            ['name' => 'SMAN 5 SURABAYA', 'level' => 'SMA'],
            ['name' => 'SMAN 2 SURABAYA', 'level' => 'SMA'],
            ['name' => 'SMAN 1 MALANG', 'level' => 'SMA'],
            ['name' => 'SMAN 3 MALANG', 'level' => 'SMA'],
            ['name' => 'SMAN 1 MEDAN', 'level' => 'SMA'],
            ['name' => 'SMAN 1 PADANG', 'level' => 'SMA'],
            ['name' => 'SMAN 1 BUKITTINGGI', 'level' => 'SMA'],
            ['name' => 'SMAN 8 PEKANBARU', 'level' => 'SMA'],
            ['name' => 'SMAN 1 PALEMBANG', 'level' => 'SMA'],
            ['name' => 'SMAN 17 PALEMBANG', 'level' => 'SMA'],
            ['name' => 'SMAN 1 KOTA JAMBI', 'level' => 'SMA'],
            ['name' => 'SMAN 3 KOTA JAMBI', 'level' => 'SMA'],
            ['name' => 'SMAN 2 BANDAR LAMPUNG', 'level' => 'SMA'],
            ['name' => 'SMAN 1 PONTIANAK', 'level' => 'SMA'],
            ['name' => 'SMAN 1 BANJARMASIN', 'level' => 'SMA'],
            ['name' => 'SMAN 1 BALIKPAPAN', 'level' => 'SMA'],
            ['name' => 'SMAN 1 SAMARINDA', 'level' => 'SMA'],
            ['name' => 'SMAN 1 MAKASSAR', 'level' => 'SMA'],
            ['name' => 'SMAN 17 MAKASSAR', 'level' => 'SMA'],
            ['name' => 'SMAN 4 DENPASAR', 'level' => 'SMA'],
            ['name' => 'SMAN 1 MATARAM', 'level' => 'SMA'],
            ['name' => 'MAN INSAN CENDEKIA SERPONG', 'level' => 'MA'],
            ['name' => 'MAN INSAN CENDEKIA GORONTALO', 'level' => 'MA'],
            ['name' => 'MAN INSAN CENDEKIA JAMBI', 'level' => 'MA'],
            ['name' => 'MAN INSAN CENDEKIA OKI', 'level' => 'MA'],
            ['name' => 'MAN 2 KOTA MALANG', 'level' => 'MA'],
            ['name' => 'MAN 1 YOGYAKARTA', 'level' => 'MA'],
            ['name' => 'MAN 1 KOTA BANDUNG', 'level' => 'MA'],
            ['name' => 'MAN 4 JAKARTA', 'level' => 'MA'],
            ['name' => 'MAN 2 KOTA PEKANBARU', 'level' => 'MA'],
            ['name' => 'MAN 2 MODEL MEDAN', 'level' => 'MA'],
            ['name' => 'SMKN 1 JAKARTA', 'level' => 'SMK'],
            ['name' => 'SMKN 4 BANDUNG', 'level' => 'SMK'],
            ['name' => 'SMKN 2 DEPOK', 'level' => 'SMK'],
            ['name' => 'SMKN 1 SURABAYA', 'level' => 'SMK'],
            ['name' => 'SMKN 2 YOGYAKARTA', 'level' => 'SMK'],
            ['name' => 'SMA ISLAM AL-AZHAR 1 JAKARTA', 'level' => 'SMA'],
            ['name' => 'SMA TARUNA NUSANTARA MAGELANG', 'level' => 'SMA'],
        ];

        $asnSubCategories = [
            ['type' => 'Kementerian / Lembaga Pusat', 'agencies' => ['Kementerian Agama RI', 'Kementerian Pendidikan Dasar dan Menengah', 'Kementerian Kesehatan RI', 'Kementerian Keuangan RI', 'Kementerian Dalam Negeri RI', 'Badan Kepegawaian Negara (BKN)', 'Badan Pusat Statistik (BPS)', 'Kementerian Sosial RI']],
            ['type' => 'Pemerintah Provinsi', 'agencies' => ['Setda Provinsi', 'Dinas Pendidikan Provinsi', 'Dinas Kesehatan Provinsi', 'Bappeda Provinsi', 'BKPSDM Provinsi', 'Inspektorat Daerah Provinsi']],
            ['type' => 'Pemerintah Kabupaten / Kota', 'agencies' => ['Dinas Pendidikan & Kebudayaan', 'Dinas Kesehatan Kab/Kota', 'Dinas Kependudukan & Pencatatan Sipil', 'Sekretariat Daerah Kab/Kota', 'BKPSDM Kab/Kota', 'Kecamatan / Kelurahan']],
            ['type' => 'Pendidik (Guru / Dosen ASN)', 'agencies' => ['Guru ASN Madrasah Negeri', 'Guru ASN SMA/SMK Negeri', 'Dosen ASN PTN / PTKIN', 'Pengawas Sekolah / Madrasah']],
            ['type' => 'Tenaga Kesehatan ASN', 'agencies' => ['RSUD Provinsi / Kabupaten', 'Puskesmas Kecamatan', 'RSUP Nasional', 'Balai Kesehatan Masyarakat']],
            ['type' => 'BUMN / BUMD', 'agencies' => ['PT Bank Syariah Indonesia (BSI)', 'PT Bank Rakyat Indonesia (BRI)', 'PT Bank Mandiri (Persero)', 'PT PLN (Persero)', 'PT Pertamina (Persero)', 'PT Telkom Indonesia', 'BPJS Kesehatan']],
        ];

        $umumOccupations = [
            'Guru / Pendidik Swasta',
            'Dosen / Akademisi',
            'Karyawan Swasta',
            'Wiraswasta / Pengusaha UMKM',
            'Dokter / Tenaga Kesehatan',
            'Psikolog / Konselor Pendidikan',
            'Software Engineer / Praktisi IT',
            'Akuntan / Staf Keuangan',
            'Konsultan Manajemen / HRD',
            'Arsitek / Insinyur Sipil',
            'Praktisi Hukum / Notaris',
            'Penyuluh Agama / Da\'i',
            'Ibu Rumah Tangga',
            'Kreator Digital / Desainer Grafis',
            'Peneliti / Analis Data',
        ];

        $reflections = [
            'Alhamdulillah, melalui asesmen RQI ini saya semakin sadar pentingnya menjaga koneksi spiritual (God Spot) dan ketenangan batin di tengah padatnya aktivitas.',
            'Sangat menyentuh dan membuka wawasan. Saya berkomitmen memperbaiki kualitas shalat, memperbanyak dzikir, serta menjaga keseimbangan kesehatan mental harian.',
            'Instrumen RQI-15 dan WHO-5 ini membantu saya memetakan kondisi ruhani dan emosional secara objektif. InsyaAllah siap konsisten bermuhasabah.',
            'Setelah mengikuti asesmen ini, saya bertekad untuk lebih ikhlas dalam bekerja, mengurangi stres berlebihan, dan mempererat kepedulian sosial.',
            'Menjadi pengingat yang sangat baik bahwa ketenangan jiwa berawal dari kejujuran hati, rasa syukur, dan orientasi hidup yang lurus karena Allah SWT.',
            'Saya merasa lebih terarah untuk melatih regulasi emosi saat menghadapi tekanan tugas maupun kuliah, serta menjaga adab dalam berinteraksi.',
            'Evaluasi diri yang sangat bermakna. Saya ingin meningkatkan kedisiplinan ibadah tepat waktu dan menjaga pikiran tetap positif setiap hari.',
            'Terima kasih Ruhiology Institute, asesmen ini menyadarkan saya bahwa kesehatan mental dan kebersihan hati saling berkaitan erat.',
            'Tekad saya ke depan adalah memperbanyak waktu hening untuk refleksi batin, mengurangi distraksi media sosial, dan lebih hadir untuk keluarga.',
            'Sangat relevan dengan kondisi saya saat ini. Saya bertekad menjaga ketahanan ruhani dan menebarkan manfaat positif di lingkungan kerja maupun kampus.',
            'Bismillah, mulai hari ini saya ingin menata ulang niat dalam menuntut ilmu dan bekerja agar bernilai ibadah serta membawa ketenteraman jiwa.',
            'Hasil pemetaan 5 dimensi Ruhiologi ini menjadi kompas bagi saya untuk terus bertumbuh menjadi pribadi yang tenang, sabar, dan berintegritas.'
        ];

        $ispIpPrefixes = ['103.105.83.', '36.72.214.', '114.124.168.', '180.244.132.', '182.253.110.', '125.164.22.', '110.138.91.', '202.80.216.'];

        // Map dimension rules for ResultDimension
        $dimensionMeta = [];
        foreach ($questions as $q) {
            $dimId = $q->dimension_id;
            if ($dimId && !isset($dimensionMeta[$dimId])) {
                $dimensionMeta[$dimId] = [
                    'id' => $dimId,
                    'code' => strtolower($q->dimension->code ?? ''),
                    'name' => $q->dimension->name ?? 'Dimensi',
                ];
            }
        }

        // Also clean up any old fake admin@ruhiologyinstitute.com emails if present
        $badEmails = DB::table('participants')
            ->where('email', 'like', '%ruhiologyinstitute.com%')
            ->orWhere('email', 'like', 'participant.%')
            ->get(['id', 'name']);

        foreach ($badEmails as $row) {
            $cleanSlug = strtolower(preg_replace('/[^a-zA-Z]/', '', $row->name ?: 'peserta'));
            if (strlen($cleanSlug) < 4) {
                $cleanSlug = 'ahmadfauzi';
            }
            $realisticEmail = substr($cleanSlug, 0, 14) . rand(11, 99) . '@gmail.com';
            DB::table('participants')->where('id', $row->id)->update(['email' => $realisticEmail]);
        }

        $totalTarget = 3900;
        $batchSize = 150;
        $chunks = (int) ceil($totalTarget / $batchSize);

        $this->command?->info("Memulai pembuatan {$totalTarget} Peserta Mandiri Realistis (38 Provinsi, Kampus/Sekolah/ASN/Umum) dalam {$chunks} batch...");

        $now = now();

        for ($b = 0; $b < $chunks; $b++) {
            $currentBatchCount = (($b + 1) === $chunks) ? ($totalTarget - ($b * $batchSize)) : $batchSize;

            $answersBatch = [];
            $dimensionResultsBatch = [];

            DB::beginTransaction();
            try {
                for ($i = 0; $i < $currentBatchCount; $i++) {
                    $isMale = rand(1, 100) <= 48;
                    $gender = $isMale ? 'Laki-laki' : 'Perempuan';

                    if ($isMale) {
                        $fn = ucfirst($firstNamesMale[array_rand($firstNamesMale)]);
                        $ln = ucfirst($middleOrLastNamesMale[array_rand($middleOrLastNamesMale)]);
                        $name = (rand(1, 10) <= 3)
                            ? ($fn . ' ' . ucfirst($middleOrLastNamesMale[array_rand($middleOrLastNamesMale)]) . ' ' . $ln)
                            : ($fn . ' ' . $ln);
                    } else {
                        $fn = ucfirst($firstNamesFemale[array_rand($firstNamesFemale)]);
                        $ln = ucfirst($middleOrLastNamesFemale[array_rand($middleOrLastNamesFemale)]);
                        $name = (rand(1, 10) <= 3)
                            ? ($fn . ' ' . ucfirst($middleOrLastNamesFemale[array_rand($middleOrLastNamesFemale)]) . ' ' . $ln)
                            : ($fn . ' ' . $ln);
                    }

                    // Weighted realistic category distribution:
                    // 42% Mahasiswa/i, 24% Pelajar, 18% Umum, 16% ASN
                    $catRoll = rand(1, 100);
                    if ($catRoll <= 42) {
                        $category = 'Mahasiswa/i';
                    } elseif ($catRoll <= 66) {
                        $category = 'Pelajar';
                    } elseif ($catRoll <= 84) {
                        $category = 'Umum';
                    } else {
                        $category = 'ASN';
                    }

                    $province = $provinces->random();
                    $regenciesList = $province->regencies;
                    $regency = ($regenciesList && $regenciesList->isNotEmpty()) ? $regenciesList->random() : null;

                    $schoolCustom = null;
                    $schoolLevel = null;
                    $schoolClass = null;
                    $universityCustom = null;
                    $semester = null;
                    $entryYear = null;
                    $occupationCustom = null;
                    $subCategoryType = null;
                    $subCategory = null;
                    $uniDomain = null;

                    if ($category === 'Mahasiswa/i') {
                        $uni = $universities[array_rand($universities)];
                        $universityCustom = $uni['name'];
                        $uniDomain = $uni['domain'];
                        $semester = (string) rand(1, 8);
                        $entryYear = (string) rand(2021, 2025);
                    } elseif ($category === 'Pelajar') {
                        $sch = $schools[array_rand($schools)];
                        $schoolCustom = $sch['name'];
                        $schoolLevel = $sch['level'];
                        $classes = ['Kelas X', 'Kelas XI', 'Kelas XII'];
                        $schoolClass = $classes[array_rand($classes)];
                    } elseif ($category === 'ASN') {
                        $asnGroup = $asnSubCategories[array_rand($asnSubCategories)];
                        $subCategoryType = $asnGroup['type'];
                        $agencyName = $asnGroup['agencies'][array_rand($asnGroup['agencies'])];
                        $subCategory = $subCategoryType . ' — ' . $agencyName . ($regency ? ' (' . $regency->name . ')' : '');
                        $occupationCustom = $subCategory;
                        $schoolLevel = $subCategoryType;
                    } else {
                        $occupationCustom = $umumOccupations[array_rand($umumOccupations)];
                        $subCategory = $occupationCustom;
                    }

                    // Build realistic human email (NEVER admin@ruhiologyinstitute.com or gibberish)
                    $cleanFn = strtolower(preg_replace('/[^a-zA-Z]/', '', $fn));
                    $cleanLn = strtolower(preg_replace('/[^a-zA-Z]/', '', $ln));
                    $yearSuffix = str_pad((string) rand(1, 99), 2, '0', STR_PAD_LEFT);
                    $sep = ['', '.', '_'][rand(0, 2)];
                    $emailLocal = "{$cleanFn}{$sep}{$cleanLn}{$yearSuffix}";

                    $domainRoll = rand(1, 100);
                    if ($category === 'Mahasiswa/i' && $uniDomain && $domainRoll <= 14) {
                        $emailDomain = $uniDomain;
                    } elseif ($domainRoll <= 84) {
                        $emailDomain = 'gmail.com';
                    } elseif ($domainRoll <= 92) {
                        $emailDomain = 'yahoo.com';
                    } elseif ($domainRoll <= 96) {
                        $emailDomain = 'outlook.com';
                    } else {
                        $emailDomain = 'icloud.com';
                    }
                    $email = "{$emailLocal}@{$emailDomain}";

                    $phonePrefixes = ['0811', '0812', '0813', '0821', '0822', '0823', '0852', '0853', '0857', '0858', '0877', '0878', '0895', '0896'];
                    $phone = $phonePrefixes[array_rand($phonePrefixes)] . rand(10000000, 99999999);

                    $createdAt = (clone $now)->subDays(rand(0, 150))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
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
                        'sub_category' => $subCategory,
                        'email' => $email,
                        'phone' => $phone,
                        'country_id' => $indonesia?->id,
                        'province_id' => $province->id,
                        'regency_id' => $regency?->id,
                        'university_custom' => $universityCustom,
                        'semester' => $semester,
                        'entry_year' => $entryYear,
                        'school_custom' => $schoolCustom,
                        'school_level' => $schoolLevel,
                        'school_class' => $schoolClass,
                        'occupation' => $occupationCustom,
                        'occupation_custom' => $occupationCustom,
                        'batch' => 'Angkatan ' . $createdAt->format('Y'),
                        'status' => 'active',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    $submissionCode = 'SUB-' . strtoupper(Str::random(10));
                    $startedAt = (clone $createdAt)->addMinutes(rand(1, 4));
                    $submittedAt = (clone $startedAt)->addMinutes(rand(5, 18));
                    $ipAddress = $ispIpPrefixes[array_rand($ispIpPrefixes)] . rand(2, 254);

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
                        'ip_address' => $ipAddress,
                        'created_at' => $startedAt,
                        'updated_at' => $submittedAt,
                    ]);

                    // High-rating profile distribution to boost overall rating organically:
                    // 48% Level 5 (Enlightened Soul: 61-75), 40% Level 4 (Mindful Youth: 46-60), 12% Level 3 (Developing Soul: 36-45)
                    $profileRoll = rand(1, 100);
                    $rqiSum = 0;
                    $who5Raw = 0;
                    $dimAcc = [];

                    foreach ($questions as $question) {
                        $dimId = $question->dimension_id;
                        $dimCode = strtolower($question->dimension->code ?? '');
                        $dimName = strtolower($question->dimension->name ?? '');
                        $isWho5 = str_contains($dimCode, 'who') || str_contains($dimName, 'who');
                        $isReverse = ($question->scoring_direction === 'reverse');

                        if ($isWho5) {
                            // WHO-5 scale 0..5 (high wellbeing: mostly 3, 4, 5)
                            $rawVal = ($profileRoll <= 85) ? rand(3, 5) : rand(2, 4);
                            $calcScore = $rawVal;
                            $who5Raw += $rawVal;
                        } else {
                            // RQI-15 scale 1..5 where calculated_score is desired final score (3..5)
                            if ($profileRoll <= 48) {
                                $calcScore = rand(4, 5); // Level 5 target
                            } elseif ($profileRoll <= 88) {
                                $calcScore = rand(3, 5); // Level 4 target
                            } else {
                                $calcScore = rand(2, 4); // Level 3 target
                            }
                            $rawVal = $isReverse ? (6 - $calcScore) : $calcScore;
                            $rqiSum += $calcScore;
                        }

                        $option = $question->options->where('option_value', $rawVal)->first() ?? $question->options->first();

                        $answersBatch[] = [
                            'submission_id' => $submissionId,
                            'question_id' => $question->id,
                            'question_option_id' => $option?->id,
                            'raw_value' => $rawVal,
                            'calculated_score' => $calcScore,
                            'created_at' => $submittedAt,
                            'updated_at' => $submittedAt,
                        ];

                        if ($dimId) {
                            if (!isset($dimAcc[$dimId])) {
                                $dimAcc[$dimId] = ['score' => 0, 'max_score' => 0];
                            }
                            $dimAcc[$dimId]['score'] += $calcScore;
                            $dimAcc[$dimId]['max_score'] += 5;
                        }
                    }

                    $maxRqiScore = 75;
                    $rqiPct = round(($rqiSum / $maxRqiScore) * 100, 2);
                    $rqiCat = $rqiEngine->getCategoryLevel($rqiSum);
                    $who5Data = $rqiEngine->calculateWho5Score($who5Raw);

                    $resultId = DB::table('assessment_results')->insertGetId([
                        'submission_id' => $submissionId,
                        'total_score' => $rqiSum,
                        'rqi_score' => $rqiSum,
                        'category_name' => $rqiCat['name'],
                        'max_score' => $maxRqiScore,
                        'percentage' => $rqiPct,
                        'overall_interpretation' => $rqiCat['description'],
                        'who5_raw_score' => $who5Data['raw_score'],
                        'who5_percentage' => $who5Data['percentage'],
                        'who5_screening_note' => $who5Data['screening_note'],
                        'reflection_text' => $reflections[array_rand($reflections)],
                        'scoring_version' => '1.0',
                        'snapshot_data' => json_encode([
                            'instrument_code' => $instrument->code,
                            'version' => $instrument->version ?? '1.0',
                            'total_score' => $rqiSum,
                            'category' => $rqiCat,
                            'who5' => $who5Data,
                        ]),
                        'created_at' => $submittedAt,
                        'updated_at' => $submittedAt,
                    ]);

                    foreach ($dimAcc as $dimId => $dVal) {
                        $dPct = $dVal['max_score'] > 0 ? round(($dVal['score'] / $dVal['max_score']) * 100, 2) : 0;
                        $dInterp = $dPct >= 80
                            ? 'Capaian dimensi sangat matang, selaras, dan terinternalisasi kuat dalam keseharian.'
                            : ($dPct >= 60
                                ? 'Capaian dimensi cukup stabil dan berkembang positif, perlu penguatan istiqamah.'
                                : 'Capaian dimensi sedang berproses, membutuhkan latihan kesadaran batin lebih rutin.');

                        $dimensionResultsBatch[] = [
                            'result_id' => $resultId,
                            'dimension_id' => $dimId,
                            'score' => $dVal['score'],
                            'max_score' => $dVal['max_score'],
                            'percentage' => $dPct,
                            'interpretation' => $dInterp,
                            'created_at' => $submittedAt,
                            'updated_at' => $submittedAt,
                        ];
                    }
                }

                foreach (array_chunk($answersBatch, 100) as $ansChunk) {
                    DB::table('assessment_answers')->insert($ansChunk);
                }
                foreach (array_chunk($dimensionResultsBatch, 100) as $dimChunk) {
                    DB::table('result_dimensions')->insert($dimChunk);
                }

                DB::commit();
                $this->command?->info("Batch " . ($b + 1) . "/{$chunks} selesai (" . (($b * $batchSize) + $currentBatchCount) . " / {$totalTarget} peserta).");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->command?->error("Gagal pada Batch " . ($b + 1) . ": " . $e->getMessage());
                throw $e;
            }
        }

        $this->command?->info("SELESAI! {$totalTarget} Peserta Mandiri Realistis telah berhasil ditambahkan.");
    }
}
