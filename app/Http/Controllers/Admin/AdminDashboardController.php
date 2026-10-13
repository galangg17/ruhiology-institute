<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $kpis = [
            'total_participants' => \App\Models\Participant::count(),
            'active_programs' => \App\Models\Program::where('status', 'active')->count(),
            'active_periods' => AssessmentPeriod::where('status', 'active')->count(),
            'training_registrations' => \App\Models\TrainingRegistration::count(),
            'pending_orders' => \App\Models\Order::where('payment_status', 'pending')->count(),
            'new_consultations' => \App\Models\Consultation::where('status', 'new')->count(),
            'published_articles' => \App\Models\Article::where('status', 'published')->count(),
            'total_submissions' => \App\Models\AssessmentSubmission::where(function($q) {
                $q->whereIn('status', ['submitted', 'completed', 'SUBMITTED', 'COMPLETED'])->orWhereNotNull('submitted_at');
            })->count(),
            'pending_institutions' => \App\Models\PendingInstitution::where('status', 'pending')->count(),
            'avg_rq_score' => round(\App\Models\AssessmentResult::avg('percentage') ?? 0, 1),
            'avg_rqi_raw' => round(\App\Models\AssessmentResult::avg('rqi_score') ?? 0, 1),
            'avg_who5_score' => round(\App\Models\AssessmentResult::whereNotNull('who5_percentage')->avg('who5_percentage') ?? 0, 1),
        ];

        // RQ Category Level Distribution
        $rqDistribution = [
            'sangat_tinggi' => \App\Models\AssessmentResult::where('percentage', '>=', 81)->count(),
            'tinggi' => \App\Models\AssessmentResult::whereBetween('percentage', [61, 80.99])->count(),
            'sedang' => \App\Models\AssessmentResult::whereBetween('percentage', [48, 60.99])->count(),
            'perlu_penguatan' => \App\Models\AssessmentResult::where('percentage', '<', 48)->count(),
        ];

        // Demographic Category Breakdown
        $categoryBreakdown = \App\Models\Participant::selectRaw('COALESCE(category, "Umum") as category_label, count(*) as total')
            ->groupBy('category_label')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        // Top 5 Provinces by Participation
        $topProvinces = \App\Models\Participant::whereNotNull('province_id')
            ->selectRaw('province_id, count(*) as total')
            ->with('province:id,name')
            ->groupBy('province_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $recentRegistrations = \App\Models\TrainingRegistration::with('training')->latest()->take(5)->get();
        $recentOrders = \App\Models\Order::latest()->take(5)->get();
        $recentConsultations = \App\Models\Consultation::latest()->take(5)->get();
        $recentSubmissions = \App\Models\AssessmentSubmission::with(['participant.province', 'period.instrument', 'result'])->latest()->take(6)->get();

        $alerts = [];
        $expiringPeriods = AssessmentPeriod::where('status', 'active')
            ->where('posttest_end', '<=', now()->addDays(7))
            ->get();

        foreach ($expiringPeriods as $ep) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Periode Asesmen '{$ep->title}' akan berakhir dalam kurun waktu kurang dari 7 hari.",
            ];
        }

        if ($kpis['pending_institutions'] > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "Terdapat {$kpis['pending_institutions']} usulan kampus/sekolah baru yang memerlukan verifikasi admin.",
            ];
        }

        if ($kpis['pending_orders'] > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "Terdapat {$kpis['pending_orders']} pesanan buku yang memerlukan verifikasi pembayaran.",
            ];
        }

        if ($kpis['new_consultations'] > 0) {
            $alerts[] = [
                'type' => 'important',
                'message' => "Terdapat {$kpis['new_consultations']} permintaan konsultasi baru yang belum dihubungi.",
            ];
        }

        return view('admin.dashboard', compact(
            'kpis',
            'rqDistribution',
            'categoryBreakdown',
            'topProvinces',
            'recentRegistrations',
            'recentOrders',
            'recentConsultations',
            'recentSubmissions',
            'alerts'
        ));
    }
}
