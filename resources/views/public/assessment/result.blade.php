@extends('layouts.app')

@section('content')
<div x-data="rqResultPage()">
<style>
@media print {
    nav, footer, .no-print, header { display: none !important; }
    body { background-color: #ffffff !important; color: #000000 !important; font-size: 11pt !important; }
    .print-container { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
    .page-break { page-break-before: always; }
    .shadow-sm, .shadow-md, .shadow-lg, .shadow-xl { box-shadow: none !important; }
    .border { border-color: #d1d5db !important; }
    .bg-[#0B2A43] { background-color: #0B2A43 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-white { color: #ffffff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .bg-[#F8F6F0] { background-color: #ffffff !important; }
}
</style>

<!-- Hero Header Banner -->
<div class="bg-[#0B2A43] text-white py-6 sm:py-10 border-b border-slate-800 no-print">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-[9px] sm:text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full border border-white/20 inline-block mb-2">
                    LAPORAN HASIL INDIVIDUAL RQ ASSESSMENT
                </span>
                <h1 class="text-xl sm:text-3xl font-extrabold font-sans text-white tracking-tight">{{ $submission->period->title }}</h1>
                <p class="text-xs text-slate-300 mt-1">
                    Kode Assessment: <strong class="font-mono text-[#C9A24D] text-sm">{{ $submission->participant->assessment_code ?? $submission->participant->participant_code }}</strong> &bull; Sesi: <strong class="uppercase text-emerald-400 font-bold">{{ $submission->submission_type }}</strong>
                </p>
            </div>
            <div class="flex gap-2.5 w-full md:w-auto">
                <button onclick="window.print()" class="flex-1 md:flex-none justify-center px-5 py-3 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] text-xs font-bold rounded-xl shadow-lg transition cursor-pointer flex items-center gap-2 font-mono uppercase tracking-wider min-h-[44px]">
                    <span>🖨️</span> <span>Cetak / PDF</span>
                </button>
                <button @click="openStoryModal()" class="flex-1 md:flex-none justify-center px-5 py-3 bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 hover:from-purple-700 hover:to-rose-700 text-white text-xs font-bold rounded-xl shadow-lg transition cursor-pointer flex items-center gap-2 font-mono uppercase tracking-wider min-h-[44px]">
                    <span>📱</span> <span>Story IG / WA</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="py-6 sm:py-10 bg-[#F8F6F0] min-h-screen print-container">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-10 space-y-6 sm:space-y-8">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-900 text-xs font-bold rounded-2xl border border-emerald-200 shadow-sm flex items-center justify-between no-print">
                <span>✨ {{ session('success') }}</span>
            </div>
        @endif
        
        <!-- Official Printable Header (Visible only in PDF Print) -->
        <div class="hidden print:block border-b-2 border-[#0B2A43] pb-4 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-serif font-bold text-[#0B2A43]">RUHIOLOGY INSTITUTE</h2>
                    <p class="text-xs text-slate-600">Laporan Resmi Hasil Individu Asesmen Kesadaran Ruhiologi (RQI-15 & WHO-5)</p>
                </div>
                <div class="text-right text-xs font-mono">
                    <p class="font-bold text-[#0B2A43]">KODE: {{ $submission->participant->assessment_code }}</p>
                    <p class="text-slate-500">Tgl: {{ optional($submission->submitted_at)->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Participant Info Metadata Header -->
        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-sm grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 text-xs">
            <div>
                <span class="text-slate-400 uppercase font-bold text-[10px] block font-mono">Nama Peserta</span>
                <span class="font-bold text-[#0B2A43] text-sm block mt-0.5">{{ $submission->participant->name }}</span>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-bold text-[10px] block font-mono">Kategori & Institusi</span>
                <span class="font-bold text-[#0B2A43] text-xs block mt-0.5">
                    {{ $submission->participant->category }}
                    @php
                        $instName = $submission->participant->school->name 
                            ?? $submission->participant->university->name 
                            ?? $submission->participant->occupationModel->name 
                            ?? $submission->participant->occupation_custom 
                            ?? null;
                    @endphp
                    @if($instName)
                        &mdash; <span class="text-slate-600 font-semibold">{{ $instName }}</span>
                    @endif
                </span>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-bold text-[10px] block font-mono">Wilayah asal</span>
                <span class="font-bold text-[#0B2A43] text-xs block mt-0.5">
                    {{ $submission->participant->regency->name ?? '' }}{{ isset($submission->participant->regency) && isset($submission->participant->province) ? ', ' : '' }}{{ $submission->participant->province->name ?? '-' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-bold text-[10px] block font-mono">Tanggal & Sesi Asesmen</span>
                <span class="font-bold text-slate-700 text-xs block mt-0.5">
                    {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }} 
                    <span class="uppercase text-amber-700 bg-amber-50 px-2 py-0.5 rounded text-[10px] font-mono border border-amber-200">({{ $submission->submission_type }})</span>
                </span>
            </div>
        </div>

        @php
            $rqiScore = number_format($submission->result->rqi_score ?? $submission->result->total_score, 0);
            $maxRqiScore = $submission->result->max_score ?? 75;
            $catName = $submission->result->category_name ?? 'Level 3: Developing Soul (Jiwa Berproses Stabil)';
            $scorePct = round(($rqiScore / $maxRqiScore) * 100, 1);

            // Dynamic Level Badge Styling for RQI-15 Levels 1..5
            if ($rqiScore >= 65) {
                $badgeStyle = 'bg-emerald-500 text-white border-emerald-400';
            } elseif ($rqiScore >= 54) {
                $badgeStyle = 'bg-sky-600 text-white border-sky-400';
            } elseif ($rqiScore >= 41) {
                $badgeStyle = 'bg-amber-500 text-slate-950 border-amber-300';
            } elseif ($rqiScore >= 27) {
                $badgeStyle = 'bg-orange-600 text-white border-orange-400';
            } else {
                $badgeStyle = 'bg-rose-600 text-white border-rose-400';
            }
        @endphp

        <!-- Main RQI Score Card -->
        <div class="bg-[#0B2A43] text-white p-6 sm:p-10 rounded-3xl shadow-xl border border-slate-800 grid grid-cols-1 md:grid-cols-12 gap-6 sm:gap-8 items-center relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-[#C9A24D]/15 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Score Number & Radial Visual Gauge (5 cols) -->
            <div class="md:col-span-5 text-center md:text-left space-y-4 border-b md:border-b-0 md:border-r border-slate-700/80 pb-6 md:pb-0 md:pr-8 flex flex-col items-center md:items-start justify-center">
                <span class="text-xs text-[#C9A24D] font-mono font-bold uppercase tracking-widest block">Skor Kecerdasan Ruhiologi (RQI-15)</span>
                
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <!-- SVG Circular Progress Gauge -->
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-800" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-[#C9A24D]" stroke-dasharray="{{ min(100, $scorePct) }}, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute font-mono font-bold text-sm text-[#C9A24D]">{{ $scorePct }}%</span>
                    </div>

                    <div class="text-center sm:text-left">
                        <div class="text-4xl sm:text-6xl font-extrabold font-serif text-white tracking-tight">
                            {{ $rqiScore }}
                            <span class="text-lg sm:text-xl text-slate-400 font-normal">/ {{ $maxRqiScore }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1.5 sm:px-4 sm:py-1.5 rounded-full text-[11px] sm:text-xs font-black uppercase tracking-wider font-mono border shadow-md {{ $badgeStyle }}">
                                ✦ {{ $catName }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interpretation Narrative (7 cols) -->
            <div class="md:col-span-7 space-y-3">
                <h4 class="text-xs font-bold text-[#C9A24D] uppercase tracking-widest font-mono">✦ Gambaran Profil & Refleksi Batiniah</h4>
                <p class="text-xs sm:text-base font-serif italic text-slate-100 leading-relaxed font-normal">
                    "{{ $submission->result->overall_interpretation }}"
                </p>
                @if($submission->result->pre_post_diff !== null)
                    <div class="pt-3 border-t border-slate-700/80 flex items-center gap-3 text-xs">
                        <span class="text-slate-300 font-medium">Perkembangan Delta RQI (dibanding Pretest):</span>
                        <span class="font-bold font-mono px-3 py-1 rounded-full text-xs {{ $submission->result->pre_post_diff >= 0 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30' : 'bg-rose-500/20 text-rose-300 border border-rose-400/30' }}">
                            {{ $submission->result->pre_post_diff >= 0 ? '+' : '' }}{{ number_format($submission->result->pre_post_diff, 1) }} poin
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- PRE VS POST COMPARATIVE PROGRESS CARD -->
        @if(isset($preSubmission) && isset($postSubmission))
            <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-emerald-200 shadow-md space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-emerald-700 uppercase tracking-widest block">PERBANDINGAN SIKLUS PRE VS POST</span>
                        <h3 class="text-lg sm:text-xl font-serif font-bold text-[#0B2A43]">Hasil Perkembangan & Evaluasi Diri</h3>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full border border-emerald-300">Matrik Evaluasi Longitudinal</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 text-center space-y-1">
                        <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">PRETEST</span>
                        <div class="text-3xl font-serif font-bold text-[#0B2A43]">{{ number_format($preSubmission->result->rqi_score ?? 0, 0) }} / 75</div>
                        <span class="text-xs font-semibold text-slate-600 block">{{ $preSubmission->result->category_name ?? '' }}</span>
                    </div>

                    <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-200 text-center space-y-1">
                        <span class="text-xs text-emerald-800 font-bold uppercase tracking-wider block">POSTTEST</span>
                        <div class="text-3xl font-serif font-bold text-emerald-900">{{ number_format($postSubmission->result->rqi_score ?? 0, 0) }} / 75</div>
                        <span class="text-xs font-bold text-emerald-700 block">{{ $postSubmission->result->category_name ?? '' }}</span>
                    </div>

                    <div class="p-5 bg-amber-50 rounded-2xl border border-amber-200 text-center space-y-1 flex flex-col justify-center">
                        <span class="text-xs text-amber-900 font-bold uppercase tracking-wider block">PERUBAHAN SKOR</span>
                        @php
                            $diff = ($postSubmission->result->rqi_score ?? 0) - ($preSubmission->result->rqi_score ?? 0);
                        @endphp
                        <div class="text-3xl font-serif font-bold {{ $diff >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 0) }} Poin
                        </div>
                        <span class="text-[11px] text-slate-600 font-medium">Perbandingan capaian awal dan evaluasi akhir</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- WHO-5 WELL-BEING INDEX CARD -->
        @if($submission->result->who5_percentage !== null)
            @php
                $who5Raw = $submission->result->who5_raw_score ?? 0;
                $who5Pct = $submission->result->who5_percentage ?? 0;
                
                if ($who5Raw >= 20) {
                    $who5LevelName = 'Level 5: Thriving & Energetic (Super Bahagia)';
                    $who5BadgeStyle = 'bg-emerald-500 text-white border-emerald-400';
                } elseif ($who5Raw >= 16) {
                    $who5LevelName = 'Level 4: Good Vibe & Fresh (Sejahtera)';
                    $who5BadgeStyle = 'bg-sky-600 text-white border-sky-400';
                } elseif ($who5Raw >= 11) {
                    $who5LevelName = 'Level 3: Moderate Well-Being (Cukup Stabil)';
                    $who5BadgeStyle = 'bg-amber-500 text-slate-950 border-amber-300';
                } elseif ($who5Raw >= 6) {
                    $who5LevelName = 'Level 2: Low Energy (Sering Cemas)';
                    $who5BadgeStyle = 'bg-orange-600 text-white border-orange-400';
                } else {
                    $who5LevelName = 'Level 1: Mental Exhausted (Burnout Parah)';
                    $who5BadgeStyle = 'bg-rose-600 text-white border-rose-400';
                }
            @endphp
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <div class="md:col-span-5 space-y-3 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-4">
                    <span class="text-[10px] font-mono font-bold text-emerald-700 uppercase tracking-widest block">🌿 SKRINING KESEJAHTERAAN (WHO-5)</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-serif font-bold text-[#0B2A43]">{{ number_format($who5Pct, 0) }}%</span>
                        <span class="text-xs text-slate-500 font-mono">({{ $who5Raw }} / 25 Poin)</span>
                    </div>
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider font-mono border shadow-sm {{ $who5BadgeStyle }}">
                            ✦ {{ $who5LevelName }}
                        </span>
                    </div>
                </div>

                <div class="md:col-span-7 space-y-2">
                    <h4 class="text-xs font-bold text-[#0B2A43] uppercase tracking-wider font-mono">Deskripsi Interpretasi Kesejahteraan Mental</h4>
                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                        "{{ $submission->result->who5_screening_note }}"
                    </p>
                    <p class="text-[11px] text-slate-400 italic">
                        *Catatan: WHO-5 (Well-Being Index) digunakan untuk pemetaan kesejahteraan emosional harian gaya hidup Gen Z & generasi muda.
                    </p>
                </div>
            </div>
        @endif

        <!-- VISUAL RADAR CHART 5 DIMENSI RQI -->
        <div class="bg-[#0B2A43] text-white p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-md grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-5 bg-white/10 p-5 rounded-2xl border border-white/15 backdrop-blur-md">
                <h4 class="font-serif font-bold text-sm text-[#C9A24D] mb-1 flex items-center gap-2">
                    <span>🕸️</span> <span>Grafik Keseimbangan 5 Dimensi RQ</span>
                </h4>
                <p class="text-[11px] text-slate-300 mb-3">Keseimbangan indikator potensial ruhiologi individu.</p>
                <div class="relative w-full aspect-square max-w-xs mx-auto">
                    <canvas id="resultRadarChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-7 space-y-3">
                <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full border border-white/20 inline-block">
                    PROFIL METRIK 5 PILAR
                </span>
                <h3 class="text-xl sm:text-2xl font-serif font-bold text-white leading-snug">
                    Pemetaan Keseimbangan Potensi Ruh Individual
                </h3>
                <p class="text-xs text-slate-200 leading-relaxed font-normal">
                    Setiap dimensi merepresentasikan bagian integral dari kesadaran batin manusia: dari hubungan vertikal dengan Sang Pencipta, regulasi emosi di bawah tekanan (Tazkiyah), hingga kontribusi altruistik bagi masyarakat.
                </p>
                <div class="grid grid-cols-2 gap-2 pt-2">
                    @foreach($submission->result->dimensionResults as $dRes)
                        <div class="p-2.5 bg-white/10 rounded-xl border border-white/10 flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-200 truncate">{{ $dRes->dimension->name }}</span>
                            <span class="font-mono font-bold text-[#C9A24D] shrink-0 ml-1">{{ number_format($dRes->percentage, 0) }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 5 DIMENSIONS BREAKDOWN TABLE (DESKTOP: TABLE, MOBILE: RESPONSIVE CARDS) -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-serif font-bold text-[#0B2A43] text-base sm:text-lg">Rincian Perolehan 5 Dimensi Utama RQI</h3>
                <span class="text-[10px] sm:text-xs font-mono font-bold text-[#C9A24D] uppercase bg-slate-900 px-3 py-1 rounded-full">RQI-15 Index</span>
            </div>

            <!-- Desktop View: Table (Visible on MD screens and above) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Dimensi Kecerdasan Ruhiologi</th>
                            <th class="px-6 py-4">Skor Mentah</th>
                            <th class="px-6 py-4">Capaian (%)</th>
                            <th class="px-6 py-4">Interpretasi Kualitatif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($submission->result->dimensionResults as $dimRes)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-4 font-mono font-bold text-[#C9A24D]">{{ $dimRes->dimension->code }}</td>
                                <td class="px-6 py-4 text-[#0B2A43] font-bold text-sm">{{ $dimRes->dimension->name }}</td>
                                <td class="px-6 py-4 text-slate-600 font-medium">{{ $dimRes->score }} / {{ $dimRes->max_score }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-[#0B2A43]">{{ number_format($dimRes->percentage, 1) }}%</span>
                                        <div class="w-20 bg-slate-200 h-2 rounded-full overflow-hidden">
                                            <div class="bg-[#C9A24D] h-2 rounded-full" style="width: {{ min(100, $dimRes->percentage) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-700 leading-relaxed font-normal">{{ $dimRes->interpretation }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Vertical Responsive Cards (Visible on Mobile screens < md) -->
            <div class="block md:hidden p-4 space-y-3">
                @foreach($submission->result->dimensionResults as $dimRes)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                        <div class="flex justify-between items-center border-b border-slate-200/60 pb-2">
                            <span class="font-mono font-bold text-xs text-[#C9A24D] bg-[#0B2A43] px-2 py-0.5 rounded">{{ $dimRes->dimension->code }}</span>
                            <span class="font-bold text-xs text-[#0B2A43] font-mono">{{ $dimRes->score }} / {{ $dimRes->max_score }} Poin</span>
                        </div>
                        <h4 class="font-bold text-sm text-[#0B2A43]">{{ $dimRes->dimension->name }}</h4>
                        
                        <div class="space-y-1 pt-1">
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-slate-500">Capaian:</span>
                                <span class="text-[#0B2A43]">{{ number_format($dimRes->percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-[#C9A24D] h-2 rounded-full" style="width: {{ min(100, $dimRes->percentage) }}%"></div>
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 pt-1 leading-relaxed">
                            {{ $dimRes->interpretation }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- PERSONAL REFLECTION & TEKAD PERUBAHAN BATIN FORM -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 no-print">
            <div>
                <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">✦ KOMITMEN REFLEKSI BATIN</span>
                <h3 class="text-base sm:text-lg font-serif font-bold text-[#0B2A43]">Tekad Perubahan Batin Anda</h3>
                <p class="text-xs text-slate-600 font-normal mt-1">
                    "Tuliskan satu tekad perubahan batin yang paling ingin Anda wujudkan agar studi dan hidup Anda terasa lebih bermakna dan damai."
                </p>
            </div>

            <form action="{{ route('assessment.reflection', ['submission_code' => $submission->submission_code]) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="reflection_text" rows="3" required placeholder="Tuliskan tekad perubahan batin Anda di sini..." class="w-full p-4 rounded-2xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none transition">{{ old('reflection_text', $submission->result->reflection_text ?? '') }}</textarea>
                <div class="flex justify-end">
                    <button type="submit" class="w-full sm:w-auto py-3.5 px-6 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow transition-colors cursor-pointer min-h-[44px]">
                        Simpan Tekad Perubahan Batin →
                    </button>
                </div>
            </form>
        </div>

        <!-- CONSULTATION CTA FOOTER -->
        <div class="bg-gradient-to-r from-[#0B2A43] via-[#123B59] to-[#0B2A43] p-6 sm:p-8 rounded-3xl text-white shadow-xl flex flex-col sm:flex-row justify-between items-center gap-6 no-print text-center sm:text-left">
            <div class="space-y-1">
                <h3 class="text-lg sm:text-xl font-serif font-bold text-white">Ingin Diskusi / Pendampingan Lebih Lanjut?</h3>
                <p class="text-xs text-slate-300 font-normal">Konsultasikan hasil profil RQ Anda bersama para pakar & konselor berpengalaman Ruhiology Institute.</p>
            </div>
            <a href="{{ route('consultation.index') }}" class="w-full sm:w-auto px-8 py-4 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-bold rounded-2xl text-xs uppercase tracking-wider shadow-lg transition-transform hover:scale-105 whitespace-nowrap inline-block text-center min-h-[44px]">
                Konsultasi Dengan Pakar →
            </a>
        </div>

    </div>

    <!-- INSTAGRAM / WHATSAPP STORY 9:16 SHARE MODAL -->
    <div x-show="showStoryModal" x-cloak class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fadeIn no-print">
        <div @click.away="showStoryModal = false" class="bg-slate-900 rounded-3xl max-w-lg w-full p-5 sm:p-6 shadow-2xl border border-slate-700 text-center space-y-4">
            
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📱</span>
                    <h3 class="text-base font-serif font-bold text-white">Kartu Story Instagram / WhatsApp</h3>
                </div>
                <button @click="showStoryModal = false" class="text-slate-400 hover:text-white font-mono text-lg font-bold cursor-pointer">✕</button>
            </div>

            <p class="text-xs text-slate-300">Pratinjau kartu 9:16 beresolusi tinggi dengan badge level Gen-Z Anda:</p>

            <!-- Preview Canvas Container -->
            <div class="bg-black/50 p-3 rounded-2xl border border-slate-800 flex justify-center items-center">
                <canvas id="storyCanvas" class="w-full max-w-[260px] sm:max-w-[300px] aspect-[9/16] rounded-2xl shadow-2xl border border-slate-700"></canvas>
            </div>

            <!-- Actions -->
            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                <button type="button" @click="showStoryModal = false" class="w-full sm:w-1/2 py-3 rounded-xl border border-slate-700 text-slate-300 font-bold text-xs hover:bg-slate-800 transition cursor-pointer">
                    Tutup
                </button>
                <button type="button" @click="downloadStoryImage()" class="w-full sm:w-1/2 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition transform hover:scale-[1.02] cursor-pointer flex items-center justify-center gap-2">
                    <span>📥 Unduh PNG (9:16)</span>
                </button>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
function rqResultPage() {
    return {
        showStoryModal: false,

        openStoryModal() {
            this.showStoryModal = true;
            this.$nextTick(() => {
                this.drawStoryCard();
            });
        },

        drawStoryCard() {
            const canvas = document.getElementById('storyCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            canvas.width = 1080;
            canvas.height = 1920;

            // Background
            const bgGrad = ctx.createLinearGradient(0, 0, 1080, 1920);
            bgGrad.addColorStop(0, '#0B2A43');
            bgGrad.addColorStop(0.5, '#123B59');
            bgGrad.addColorStop(1, '#08141E');
            ctx.fillStyle = bgGrad;
            ctx.fillRect(0, 0, 1080, 1920);

            // Glow Circle
            const glowGrad = ctx.createRadialGradient(540, 450, 50, 540, 450, 500);
            glowGrad.addColorStop(0, 'rgba(201, 162, 77, 0.3)');
            glowGrad.addColorStop(1, 'rgba(11, 42, 67, 0)');
            ctx.fillStyle = glowGrad;
            ctx.fillRect(0, 0, 1080, 1920);

            // Header Logo Text
            ctx.fillStyle = '#C9A24D';
            ctx.font = 'bold 36px "Plus Jakarta Sans", sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('RUHIOLOGY INSTITUTE', 540, 180);

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 26px monospace';
            ctx.fillText('KECERDASAN RUHIOLOGI (RQI)', 540, 230);

            // Divider Line
            ctx.strokeStyle = 'rgba(201, 162, 77, 0.4)';
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.moveTo(180, 280);
            ctx.lineTo(900, 280);
            ctx.stroke();

            // Participant Name Box
            ctx.fillStyle = 'rgba(255, 255, 255, 0.08)';
            ctx.beginPath();
            ctx.roundRect(140, 340, 800, 120, 24);
            ctx.fill();
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.15)';
            ctx.stroke();

            ctx.fillStyle = '#94a3b8';
            ctx.font = 'bold 24px monospace';
            ctx.fillText('NAMA PESERTA:', 540, 385);

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 42px "Outfit", sans-serif';
            ctx.fillText('{{ addslashes($submission->participant->name) }}', 540, 435);

            // Big Circle Score
            ctx.fillStyle = 'rgba(201, 162, 77, 0.15)';
            ctx.beginPath();
            ctx.arc(540, 720, 190, 0, Math.PI * 2);
            ctx.fill();
            ctx.strokeStyle = '#C9A24D';
            ctx.lineWidth = 8;
            ctx.stroke();

            ctx.fillStyle = '#C9A24D';
            ctx.font = 'bold 28px monospace';
            ctx.fillText('TOTAL SKOR RQI-15', 540, 630);

            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 110px "Outfit", sans-serif';
            ctx.fillText('{{ $rqiScore }}', 540, 740);

            ctx.fillStyle = '#94a3b8';
            ctx.font = '600 34px "Plus Jakarta Sans", sans-serif';
            ctx.fillText('/ {{ $maxRqiScore }} POIN ({{ $scorePct }}%)', 540, 800);

            // Level Badge Pill
            ctx.fillStyle = '#C9A24D';
            ctx.beginPath();
            ctx.roundRect(140, 980, 800, 100, 50);
            ctx.fill();

            ctx.fillStyle = '#0B2A43';
            ctx.font = 'bold 36px "Outfit", sans-serif';
            ctx.fillText('✦ {{ addslashes($catName) }}', 540, 1045);

            // Quote Box
            ctx.fillStyle = 'rgba(255, 255, 255, 0.06)';
            ctx.beginPath();
            ctx.roundRect(140, 1140, 800, 360, 24);
            ctx.fill();
            ctx.strokeStyle = 'rgba(201, 162, 77, 0.3)';
            ctx.stroke();

            ctx.fillStyle = '#e2e8f0';
            ctx.font = 'italic 28px "Plus Jakarta Sans", sans-serif';
            this.wrapText(ctx, '"{{ addslashes($submission->result->overall_interpretation) }}"', 540, 1220, 740, 42);

            // Footer URL & Verification Code
            ctx.fillStyle = '#C9A24D';
            ctx.font = 'bold 30px monospace';
            ctx.fillText('ruhiologyinstitute.com', 540, 1750);

            ctx.fillStyle = '#94a3b8';
            ctx.font = '24px monospace';
            ctx.fillText('Kode Evaluasi: {{ $submission->participant->assessment_code ?? $submission->participant->participant_code }}', 540, 1800);
        },

        wrapText(ctx, text, x, y, maxWidth, lineHeight) {
            const words = text.split(' ');
            let line = '';
            let currentY = y;
            for (let n = 0; n < words.length; n++) {
                let testLine = line + words[n] + ' ';
                let metrics = ctx.measureText(testLine);
                if (metrics.width > maxWidth && n > 0) {
                    ctx.fillText(line, x, currentY);
                    line = words[n] + ' ';
                    currentY += lineHeight;
                    if (currentY > y + 250) break;
                } else {
                    line = testLine;
                }
            }
            ctx.fillText(line, x, currentY);
        },

        downloadStoryImage() {
            const canvas = document.getElementById('storyCanvas');
            if (!canvas) return;
            const link = document.createElement('a');
            link.download = 'ruhiology_story_{{ $submission->submission_code }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('resultRadarChart');
    if (ctx && typeof Chart !== 'undefined') {
        @php
            $dimLabels = [];
            $dimValues = [];
            foreach($submission->result->dimensionResults as $dr) {
                $dimLabels[] = $dr->dimension->name;
                $dimValues[] = round($dr->percentage, 1);
            }
        @endphp
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: {!! json_encode($dimLabels) !!},
                datasets: [{
                    label: 'Skor Dimensi (%)',
                    data: {!! json_encode($dimValues) !!},
                    backgroundColor: 'rgba(201, 162, 77, 0.3)',
                    borderColor: '#C9A24D',
                    borderWidth: 2,
                    pointBackgroundColor: '#C9A24D',
                    pointBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255, 255, 255, 0.2)' },
                        grid: { color: 'rgba(255, 255, 255, 0.2)' },
                        pointLabels: {
                            color: '#e2e8f0',
                            font: { size: 9, family: 'Plus Jakarta Sans', weight: 'bold' }
                        },
                        ticks: { display: false, beginAtZero: true, max: 100 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
