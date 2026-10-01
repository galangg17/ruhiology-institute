<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AssessmentResult;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Testimonial;
use App\Models\TrainingProgram;

class HomeController extends Controller
{
    public function index()
    {
        $trainings = TrainingProgram::where('status', 'published')->with('batches')->take(3)->get();
        $books = Product::where('status', 'published')->take(4)->get();
        $articles = Article::where('status', 'published')->latest('published_at')->take(4)->get();
        $quotes = Quote::where('status', 'published')->latest('published_at')->take(4)->get();
        $testimonials = Testimonial::where('status', 'approved')->take(3)->get();

        // Query Real Participant Tekad & Refleksi Batin
        $realTekad = AssessmentResult::whereNotNull('reflection_text')
            ->where('reflection_text', '!=', '')
            ->with(['submission.participant.institution', 'submission.event'])
            ->inRandomOrder()
            ->take(12)
            ->get()
            ->map(function ($res) {
                $p = $res->submission->participant ?? null;
                $instName = $p->institution->name ?? ($res->submission->event->institution_name ?? ($p->institution_name ?? 'Peserta Mandiri'));
                $rawName = $p->name ?? 'Peserta Ruhiology';
                $nameParts = explode(' ', trim($rawName));
                $displayName = count($nameParts) > 1 ? $nameParts[0] . ' ' . substr($nameParts[count($nameParts) - 1], 0, 1) . '.' : $rawName;
                $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));

                return [
                    'name' => $displayName,
                    'institution' => $instName,
                    'category' => $p->category ?? ($p->school_level ?? 'Peserta Asesmen'),
                    'tekad' => $res->reflection_text,
                    'date' => $res->updated_at ? $res->updated_at->diffForHumans() : 'Baru saja',
                    'initials' => $initials ?: 'PR',
                    'is_real' => true,
                ];
            })
            ->toArray();

        $defaultTekad = [
            [
                'name' => 'Fadhil R.',
                'institution' => 'SMAN Titian Teras Jambi',
                'category' => 'Pelajar / Siswa',
                'tekad' => 'Bismillah, setelah melihat hasil RQI saya bertekad memperbaiki niat belajar, mengurangi gadget sebelum tidur, dan menjaga konsistensi shalat berjamaah tepat waktu.',
                'date' => '2 hari lalu',
                'initials' => 'FR',
                'is_real' => false,
            ],
            [
                'name' => 'Nabila K.',
                'institution' => 'MAN 1 Kota Batam',
                'category' => 'Pelajar / Siswa',
                'tekad' => 'Saya bertekad untuk lebih bisa mengendalikan emosi batin saat menghadapi ujian sekolah, memperbanyak istighfar, serta menata ulang impian hidup karena Allah.',
                'date' => '3 hari lalu',
                'initials' => 'NK',
                'is_real' => false,
            ],
            [
                'name' => 'Rahmat H.',
                'institution' => 'UIN Sulthan Thaha Saifuddin Jambi',
                'category' => 'Mahasiswa',
                'tekad' => 'Refleksi hasil asesmen ini menyadarkan saya pentingnya kesehatan batin. Tekad saya adalah rutin dzikir pagi sore dan lebih berbakti kepada orang tua.',
                'date' => '5 hari lalu',
                'initials' => 'RH',
                'is_real' => false,
            ],
            [
                'name' => 'Siti A.',
                'institution' => 'UI YASNI Bungo',
                'category' => 'Mahasiswa',
                'tekad' => 'Saya bertekad untuk memperbaiki hubungan sosial, menjaga lisanku dari ghibah, dan lebih rutin membaca Al-Qur\'an setiap selesai shalat Shubuh.',
                'date' => '1 minggu lalu',
                'initials' => 'SA',
                'is_real' => false,
            ],
            [
                'name' => 'Dwi P.',
                'institution' => 'UIN Bukittinggi',
                'category' => 'Mahasiswa / Umum',
                'tekad' => 'Tekad saya adalah menjadi pribadi yang lebih sabar, jujur dalam setiap tindakan akademik, dan menjaga kebersihan hati dari rasa iri dengki.',
                'date' => '1 minggu lalu',
                'initials' => 'DP',
                'is_real' => false,
            ],
            [
                'name' => 'Dr. Farhan',
                'institution' => 'Dosen & Peneliti',
                'category' => 'Pengajar / Praktisi',
                'tekad' => 'Memadukan metode psikometri ruhiologi dalam pendampingan batin mahasiswa agar tercipta generasi akademisi yang tangguh dan berakhlaq mulia.',
                'date' => '2 minggu lalu',
                'initials' => 'DF',
                'is_real' => false,
            ],
        ];

        $tekadList = array_merge($realTekad, $defaultTekad);

        $stats = [
            'total_provinces' => \App\Models\Province::count(),
            'total_institutions' => \App\Models\University::count() + \App\Models\School::count() + \App\Models\Institution::count(),
            'total_participants' => \App\Models\Participant::count(),
            'total_submissions' => \App\Models\AssessmentSubmission::count(),
        ];

        return view('public.home', compact('trainings', 'books', 'articles', 'quotes', 'testimonials', 'stats', 'tekadList'));
    }

    public function aboutRuhiology()
    {
        return view('public.about_ruhiology');
    }

    public function aboutInstitute()
    {
        return view('public.about_institute');
    }
}
