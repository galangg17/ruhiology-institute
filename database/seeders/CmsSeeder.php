<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Consultation;
use App\Models\Quote;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $catNews = ArticleCategory::create(['name' => 'Berita Institute', 'slug' => 'berita-institute', 'description' => 'Kabar terbaru riset dan kegiatan institusi']);
        $catTheory = ArticleCategory::create(['name' => 'Kajian Ruhiologi', 'slug' => 'kajian-ruhiologi', 'description' => 'Artikel akademik dan pendalaman teori RQ']);

        Article::create([
            'title' => 'Kuliah Umum Prof. Iskandar Nazari: Reorientasi Psikologi Pendidikan Berbasis Ruh',
            'slug' => 'kuliah-umum-prof-iskandar-nazari-reorientasi-psikologi-pendidikan-berbasis-ruh',
            'type' => 'news',
            'excerpt' => 'Prof. Dr. Iskandar Nazari memaparkan urgensi menempatkan ruh sebagai pusat peradaban pendidikan pada Simposium Nasional Pendidikan Islam.',
            'content' => '<p>Dalam Simposium Nasional Pendidikan Islam yang diselenggarakan di UIN Sulthan Thaha Saifuddin Jambi, Guru Besar Psikologi Pendidikan <strong>Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.</strong> secara tegas menguraikan perlunya pergeseran paradigma dari sebatas kecerdasan intelektual (IQ) dan emosional (EQ) menuju penguatan <strong>Ruhiology Quotient (RQ)</strong>.</p><p>Beliau menekankan bahwa ruh bukan sekadar entitas abstrak, melainkan pusat navigasi moral, intuisi luhur, dan penggerak utama seluruh dimensi fisik serta rasional manusia.</p>',
            'category_id' => $catNews->id,
            'author' => 'Tim Redaksi Ruhiology Institute',
            'published_at' => now()->subDays(3),
            'status' => 'published',
        ]);

        Article::create([
            'title' => 'Mengapa Ruh Harus Menjadi Pusat Potensi Kemanusiaan?',
            'slug' => 'mengapa-ruh-harus-menjadi-pusat-potensi-kemanusiaan',
            'type' => 'article',
            'excerpt' => 'Ulasan mendalam mengenai perbedaan fundamental antara kesadaran mental biasa dengan kecerdasan ruhiologi yang bersumber dari keterikatan jiwa pada Ilahi.',
            'content' => '<p>Banyak teori psikologi barat menempatkan kognisi dan emosi sebagai puncak kepribadian. Namun dalam konsep <em>Ruhiologi</em>, dimensi jasmani dan rasio hanyalah instrumen luar. Ruh adalah poros utama (<em>center of human potential</em>).</p><p>Ketika ruh seseorang hidup dan tersambung dengan cahaya kebenaran, maka akalnya menjadi jernih, emosinya menjadi stabil, dan aksinya mendatangkan keberkahan bagi alam semesta.</p>',
            'category_id' => $catTheory->id,
            'author' => 'Prof. Dr. Iskandar Nazari',
            'published_at' => now()->subDays(7),
            'status' => 'published',
        ]);

        Quote::create([
            'quote' => 'Akal membimbing langkah kita di dunia, namun ruh-lah yang menentukan arah keabadian dan ketenangan sejati jiwa.',
            'author' => 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.',
            'source' => 'Buku Ruhiology Quotient (2024)',
            'category' => 'Spiritual & Pendidikan',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Quote::create([
            'quote' => 'Puncak kejujuran intelektual tercapai ketika seseorang mengakui bahwa ruh di dalam dirinya merindukan kebenaran Ilahi.',
            'author' => 'Prof. Dr. Iskandar Nazari',
            'source' => 'Kuliah Perdana UIN STS Jambi',
            'category' => 'Filsafat Pendidikan',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Testimonial::create([
            'name' => 'Dr. Hj. Siti Zubaidah, M.Ag.',
            'role' => 'Dosen & Edukator Pendidikan Islam',
            'institution' => 'IAIN Kerinci',
            'testimonial' => 'Konsep Ruhiology Quotient yang digagas Prof. Iskandar Nazari memberikan jawaban ilmiah dan spiritual atas krisis karakter pendidik masa kini. Asesmen RQ di platform ini sangat membantu memetakan potensi spiritual kami.',
            'rating' => 5,
            'status' => 'approved',
        ]);

        Testimonial::create([
            'name' => 'Muhammad Aris, S.Psi.',
            'role' => 'Fasilitator Pengembangan SDM',
            'institution' => 'Lembaga Pelatihan Mandiri',
            'testimonial' => 'Sistem RQ Assessment Engine Ruhiology Institute luar biasa intuitif! Laporan hasil individu yang dihasilkan presisi dan dapat langsung dijadikan pijakan program coaching.',
            'rating' => 5,
            'status' => 'approved',
        ]);

        Consultation::create([
            'consultation_number' => 'CNS-2026-001',
            'name' => 'Drs. H. Ahmad Subandi',
            'email' => 'subandi@kemenag.go.id',
            'phone' => '081174005544',
            'institution' => 'Kanwil Kemenag Provinsi Jambi',
            'consultation_type' => 'Institution RQ Assessment & Training',
            'preferred_date' => now()->addDays(7),
            'preferred_time' => '10:00 - 11:30',
            'message' => 'Kami bermaksud mengintegrasikan tes RQ dan workshop Ruhiologi untuk 50 pejabat pengawas madrasah. Mohon informasi jadwal audiensi dengan Prof. Iskandar Nazari.',
            'status' => 'new',
            'admin_notes' => null,
        ]);
    }
}
