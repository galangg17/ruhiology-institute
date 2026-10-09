<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create participant_sub_categories table
        Schema::create('participant_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('participant_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->string('detail_label')->nullable(); // Helper label for Level 3 input
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. Ensure initial 9 core categories exist in participant_categories
        $categoriesData = [
            ['name' => 'GURU', 'code' => 'guru', 'icon' => '👨‍🏫', 'description' => 'Tenaga Pendidik Sekolah / Madrasah', 'order' => 1],
            ['name' => 'DOSEN', 'code' => 'dosen', 'icon' => '🧑‍🏫', 'description' => 'Tenaga Pendidik Perguruan Tinggi', 'order' => 2],
            ['name' => 'MURID', 'code' => 'murid', 'icon' => '🏫', 'description' => 'Siswa SD / SMP / SMA / SMK / MA / Sederajat', 'order' => 3],
            ['name' => 'MAHASISWA', 'code' => 'mahasiswa', 'icon' => '🎓', 'description' => 'Mahasiswa Diploma / Sarjana / Pascasarjana', 'order' => 4],
            ['name' => 'APH', 'code' => 'aph', 'icon' => '⚖️', 'description' => 'Aparat Penegak Hukum (POLRI, TNI, Kejaksaan, KPK, Hakim, Advokat)', 'order' => 5],
            ['name' => 'ASN', 'code' => 'asn', 'icon' => '🏛️', 'description' => 'Aparatur Sipil Negara (Kementerian, Pemprov, Pemkab/Pemkot)', 'order' => 6],
            ['name' => 'TENAGA KEPENDIDIKAN', 'code' => 'tenaga_kependidikan', 'icon' => '💼', 'description' => 'Tenaga Kependidikan / Staf Administrasi Pendidikan', 'order' => 7],
            ['name' => 'WARGA BINAAN', 'code' => 'warga_binaan', 'icon' => '🤝', 'description' => 'Warga Binaan Pemasyarakatan (Lapas / Rutan / Rehabilitasi)', 'order' => 8],
            ['name' => 'UMUM', 'code' => 'umum', 'icon' => '👤', 'description' => 'Masyarakat Umum / Swasta / Wiraswasta / Profesional', 'order' => 9],
        ];

        foreach ($categoriesData as $cat) {
            DB::table('participant_categories')->updateOrInsert(
                ['code' => $cat['code']],
                array_merge($cat, ['status' => 'active', 'updated_at' => now(), 'created_at' => now()])
            );
        }

        // 3. Seed initial Sub-Categories for each Category
        $categories = DB::table('participant_categories')->get()->keyBy('code');

        $subCategoriesData = [
            // GURU
            'guru' => [
                ['name' => 'SMA / Sederajat', 'code' => 'sma', 'detail_label' => 'Nama Sekolah / Tempat Mengajar'],
                ['name' => 'SMK / Sederajat', 'code' => 'smk', 'detail_label' => 'Nama Sekolah / Tempat Mengajar'],
                ['name' => 'MA (Madrasah Aliyah)', 'code' => 'ma', 'detail_label' => 'Nama Madrasah'],
                ['name' => 'SMP / MTs', 'code' => 'smp', 'detail_label' => 'Nama SMP / MTs'],
                ['name' => 'SD / MI', 'code' => 'sd', 'detail_label' => 'Nama SD / MI'],
                ['name' => 'Sederajat Lainnya', 'code' => 'sederajat_lainnya', 'detail_label' => 'Nama Lembaga Pendidikan'],
            ],
            // DOSEN
            'dosen' => [
                ['name' => 'Perguruan Tinggi Negeri (PTN)', 'code' => 'ptn', 'detail_label' => 'Nama Perguruan Tinggi (PTN)'],
                ['name' => 'Perguruan Tinggi Swasta (PTS)', 'code' => 'pts', 'detail_label' => 'Nama Perguruan Tinggi (PTS)'],
                ['name' => 'Perguruan Tinggi Keagamaan (PTKI)', 'code' => 'ptki', 'detail_label' => 'Nama PTKI / IAIN / UIN'],
                ['name' => 'Perguruan Tinggi Kedinasan', 'code' => 'kedinasan', 'detail_label' => 'Nama Sekolah Tinggi Kedinasan'],
            ],
            // PELAJAR
            'pelajar' => [
                ['name' => 'SMA / Sederajat', 'code' => 'sma', 'detail_label' => 'Nama Sekolah (misal: SMAN 1 Jambi)'],
                ['name' => 'SMK / Sederajat', 'code' => 'smk', 'detail_label' => 'Nama Sekolah (misal: SMKN 1 Jambi)'],
                ['name' => 'MA (Madrasah Aliyah)', 'code' => 'ma', 'detail_label' => 'Nama Madrasah (misal: MAN 1 Jambi)'],
                ['name' => 'SMP / MTs', 'code' => 'smp', 'detail_label' => 'Nama SMP / MTs'],
                ['name' => 'SD / MI', 'code' => 'sd', 'detail_label' => 'Nama SD / MI'],
                ['name' => 'Sederajat Lainnya', 'code' => 'sederajat_lainnya', 'detail_label' => 'Nama Sekolah / Lembaga'],
            ],
            // MURID
            'murid' => [
                ['name' => 'SMA / Sederajat', 'code' => 'sma', 'detail_label' => 'Nama Sekolah (misal: SMAN 1 Jambi)'],
                ['name' => 'SMK / Sederajat', 'code' => 'smk', 'detail_label' => 'Nama Sekolah (misal: SMKN 1 Jambi)'],
                ['name' => 'MA (Madrasah Aliyah)', 'code' => 'ma', 'detail_label' => 'Nama Madrasah (misal: MAN 1 Jambi)'],
                ['name' => 'SMP / MTs', 'code' => 'smp', 'detail_label' => 'Nama SMP / MTs'],
                ['name' => 'SD / MI', 'code' => 'sd', 'detail_label' => 'Nama SD / MI'],
                ['name' => 'Sederajat Lainnya', 'code' => 'sederajat_lainnya', 'detail_label' => 'Nama Sekolah / Lembaga'],
            ],
            // MAHASISWA
            'mahasiswa' => [
                ['name' => 'Diploma (D3 / D4)', 'code' => 'diploma', 'detail_label' => 'Nama Kampus & Program Studi'],
                ['name' => 'Sarjana (S1)', 'code' => 's1', 'detail_label' => 'Nama Kampus & Fakultas/Prodi'],
                ['name' => 'Magister (S2)', 'code' => 's2', 'detail_label' => 'Nama Perguruan Tinggi & Magister'],
                ['name' => 'Doktor (S3)', 'code' => 's3', 'detail_label' => 'Nama Perguruan Tinggi & Program Doktoral'],
            ],
            // APH
            'aph' => [
                ['name' => 'POLRI', 'code' => 'polri', 'detail_label' => 'Nama Polda / Polres / Polresta / Satuan Kerja'],
                ['name' => 'TNI (AD / AL / AU)', 'code' => 'tni', 'detail_label' => 'Nama Kodam / Korem / Kodim / Batalyon / Satuan'],
                ['name' => 'Kejaksaan', 'code' => 'kejaksaan', 'detail_label' => 'Nama Kejati / Kejari / Satuan Kerja'],
                ['name' => 'KPK', 'code' => 'kpk', 'detail_label' => 'Nama Unit / Direktorat KPK'],
                ['name' => 'Hakim / Pengadilan', 'code' => 'hakim', 'detail_label' => 'Nama Pengadilan Tinggi / Negeri / Agama'],
                ['name' => 'Advokat / Pengacara', 'code' => 'advokat', 'detail_label' => 'Nama Kantor Hukum / Asosiasi'],
                ['name' => 'Sektor Hukum Lainnya', 'code' => 'hukum_lainnya', 'detail_label' => 'Nama Lembaga / Instansi Hukum'],
            ],
            // ASN
            'asn' => [
                ['name' => 'Kementerian / Lembaga Pusat', 'code' => 'kementerian', 'detail_label' => 'Nama Kementerian / Lembaga'],
                ['name' => 'Pemprov (Pemerintah Provinsi)', 'code' => 'pemprov', 'detail_label' => 'Nama Dinas / Badan Pemprov'],
                ['name' => 'Pemkab / Pemkot (Kabupaten/Kota)', 'code' => 'pemkab_pemkot', 'detail_label' => 'Nama Dinas / Badan Pemkab/Pemkot'],
            ],
            // TENAGA KEPENDIDIKAN
            'tenaga_kependidikan' => [
                ['name' => 'Sekolah / Madrasah', 'code' => 'sekolah', 'detail_label' => 'Nama Sekolah / Madrasah'],
                ['name' => 'Perguruan Tinggi', 'code' => 'kampus', 'detail_label' => 'Nama Perguruan Tinggi / Kampus'],
                ['name' => 'Lembaga Pendidikan Lainnya', 'code' => 'lembaga_lain', 'detail_label' => 'Nama Instansi / Dinas Pendidikan'],
            ],
            // WARGA BINAAN
            'warga_binaan' => [
                ['name' => 'Lapas (Lembaga Pemasyarakatan)', 'code' => 'lapas', 'detail_label' => 'Nama Lapas & Blok / Kamar'],
                ['name' => 'Rutan (Rumah Tahanan)', 'code' => 'rutan', 'detail_label' => 'Nama Rutan & Blok / Kamar'],
                ['name' => 'Balai Rehabilitasi', 'code' => 'rehabilitasi', 'detail_label' => 'Nama Balai / Panti Rehabilitasi'],
            ],
            // UMUM
            'umum' => [
                ['name' => 'Karyawan Swasta', 'code' => 'swasta', 'detail_label' => 'Nama Perusahaan / Perusahaan Tempat Bekerja'],
                ['name' => 'Wiraswasta / Pengusaha', 'code' => 'wiraswasta', 'detail_label' => 'Nama Bidang Usaha / Bisnis'],
                ['name' => 'Profesional / Mandiri', 'code' => 'profesional', 'detail_label' => 'Nama Profesi / Keahlian'],
                ['name' => 'Belum / Tidak Bekerja', 'code' => 'belum_bekerja', 'detail_label' => 'Keterangan Tambahan (Opsional)'],
                ['name' => 'Lainnya', 'code' => 'lainnya', 'detail_label' => 'Keterangan Kelompok / Tempat'],
            ],
        ];

        foreach ($subCategoriesData as $catCode => $subCats) {
            if (isset($categories[$catCode])) {
                $catId = $categories[$catCode]->id;
                foreach ($subCats as $orderIdx => $sub) {
                    DB::table('participant_sub_categories')->updateOrInsert(
                        ['category_id' => $catId, 'code' => $sub['code']],
                        [
                            'category_id' => $catId,
                            'name' => $sub['name'],
                            'code' => $sub['code'],
                            'detail_label' => $sub['detail_label'],
                            'order' => $orderIdx + 1,
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_sub_categories');
    }
};
