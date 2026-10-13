@extends('layouts.app')

@section('content')
@php
    $rqiScore = number_format($submission->result->rqi_score ?? $submission->result->total_score, 0);
    $maxRqiScore = $submission->result->max_score ?? 75;
    $catName = $submission->result->category_name ?? 'Level 3: Developing Soul (Jiwa Berproses Stabil)';
    $scorePct = round(($rqiScore / $maxRqiScore) * 100, 1);

    // Filter strictly 5 RQI-15 Dimensions (exclude any 6th WHO-5 row if present)
    $rqiOnlyDimensions = ($submission->result->dimensionResults ?? collect())->filter(function($dr) {
        $code = strtolower($dr->dimension->code ?? '');
        $name = strtolower($dr->dimension->name ?? '');
        return !str_contains($code, 'who') && !str_contains($name, 'who');
    })->take(5)->values();

    $instName = $submission->participant->sub_category
        ?: $submission->participant->detail_institution
        ?: $submission->participant->university_custom 
        ?: $submission->participant->school_custom 
        ?: $submission->participant->occupation_custom 
        ?: $submission->participant->school?->name 
        ?: $submission->participant->university?->name 
        ?: null;

    $regionText = trim(($submission->participant->regency->name ?? '') . (isset($submission->participant->regency) && isset($submission->participant->province) ? ', ' : '') . ($submission->participant->province->name ?? ''));

    // Dynamic Level Badge Styling for RQI-15 Levels 1..5
    if ($rqiScore >= 65) {
        $badgeGradient = 'from-emerald-500 to-teal-600 text-white border-emerald-400';
    } elseif ($rqiScore >= 54) {
        $badgeGradient = 'from-sky-600 to-indigo-600 text-white border-sky-400';
    } elseif ($rqiScore >= 41) {
        $badgeGradient = 'from-amber-500 to-amber-600 text-slate-950 border-amber-300';
    } elseif ($rqiScore >= 27) {
        $badgeGradient = 'from-orange-600 to-amber-700 text-white border-orange-400';
    } else {
        $badgeGradient = 'from-rose-600 to-red-700 text-white border-rose-400';
    }

    $hasWho5 = ($submission->result->who5_percentage !== null);
    $who5Raw = $submission->result->who5_raw_score ?? 0;
    $who5Pct = $submission->result->who5_percentage ?? 0;
    
    if ($who5Raw >= 20 || $who5Pct >= 80) {
        $who5LevelName = 'Level 5: Thriving & Energetic (Super Bahagia)';
        $who5BadgeStyle = 'from-emerald-500 to-teal-600 text-white border-emerald-400';
        $who5BadgeTitleShort = 'Level 5: Thriving & Energetic';
        $who5BadgeBgHex = '#10b981';
    } elseif ($who5Raw >= 16 || $who5Pct >= 64) {
        $who5LevelName = 'Level 4: Good Vibe & Fresh (Sejahtera)';
        $who5BadgeStyle = 'from-sky-600 to-blue-600 text-white border-sky-400';
        $who5BadgeTitleShort = 'Level 4: Good Vibe & Fresh';
        $who5BadgeBgHex = '#0284c7';
    } elseif ($who5Raw >= 11 || $who5Pct >= 44) {
        $who5LevelName = 'Level 3: Moderate Well-Being (Cukup Stabil)';
        $who5BadgeStyle = 'from-amber-500 to-yellow-600 text-slate-950 border-amber-300';
        $who5BadgeTitleShort = 'Level 3: Moderate Well-Being';
        $who5BadgeBgHex = '#f59e0b';
    } elseif ($who5Raw >= 6 || $who5Pct >= 24) {
        $who5LevelName = 'Level 2: Low Energy (Sering Cemas)';
        $who5BadgeStyle = 'from-orange-600 to-amber-700 text-white border-orange-400';
        $who5BadgeTitleShort = 'Level 2: Low Energy';
        $who5BadgeBgHex = '#ea580c';
    } else {
        $who5LevelName = 'Level 1: Mental Exhausted (Burnout Parah)';
        $who5BadgeStyle = 'from-rose-600 to-red-700 text-white border-rose-400';
        $who5BadgeTitleShort = 'Level 1: Mental Exhausted';
        $who5BadgeBgHex = '#e11d48';
    }

    $canvasPillars = $rqiOnlyDimensions->map(function($dr) {
        $rawName = trim($dr->dimension->name ?? 'Dimensi');
        $lower = strtolower($rawName);
        if (str_contains($lower, 'god spot') || str_contains($lower, 'diri')) $cleanName = 'Kesadaran Diri & Ruh';
        elseif (str_contains($lower, 'puncak')) $cleanName = 'Kesadaran Puncak Ilahi';
        elseif (str_contains($lower, 'ketuhanan') || str_contains($lower, 'spiritual connection')) $cleanName = 'Kesadaran Ketuhanan';
        elseif (str_contains($lower, 'ibadah') || str_contains($lower, 'ketaatan')) $cleanName = 'Ketaatan Spiritual & Ibadah';
        elseif (str_contains($lower, 'tazkiyah') || str_contains($lower, 'purifikasi') || str_contains($lower, 'inner peace')) $cleanName = 'Purifikasi Batin (Tazkiyah)';
        elseif (str_contains($lower, 'perilaku') || str_contains($lower, 'akhlak') || str_contains($lower, 'altruistic')) $cleanName = 'Akhlak & Kepedulian Sosial';
        elseif (str_contains($lower, 'resiliensi') || str_contains($lower, 'ketangguhan')) $cleanName = 'Ketangguhan & Resiliensi';
        else {
            $cleanName = preg_replace('/^\d+\.\s*/', '', $rawName);
            $cleanName = preg_replace('/\s*\(.*?\)/', '', $cleanName);
        }
        return [
            'code' => $dr->dimension->code ?? 'D',
            'name' => $cleanName,
            'score' => $dr->score,
            'max' => $dr->max_score,
            'pct' => round($dr->percentage)
        ];
    })->values();
@endphp

<div x-data="rqResultPage()">
<style>
@page {
    size: A4 portrait;
    margin: 12mm;
}
@media print {
    nav, footer, .no-print, header { display: none !important; }
    body {
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-size: 10pt !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .print-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }
    .shadow-sm, .shadow-md, .shadow-lg, .shadow-xl, .shadow-2xl {
        box-shadow: none !important;
    }
    .print-avoid-break {
        break-inside: avoid;
        page-break-inside: avoid;
    }
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
                <h1 class="text-xl sm:text-3xl font-extrabold font-serif text-white tracking-tight">{{ $submission->period->title }}</h1>
                <p class="text-xs text-slate-300 mt-1">
                    Kode Assessment: <strong class="font-mono text-[#C9A24D] text-sm">{{ $submission->participant->assessment_code ?? $submission->participant->participant_code }}</strong>
                    &bull; Kode Sesi: <strong class="font-mono text-white text-xs">{{ $submission->submission_code }}</strong>
                    &bull; Sesi: <strong class="uppercase text-emerald-400 font-bold">{{ $submission->submission_type }}</strong>
                </p>
            </div>
            <div class="flex flex-wrap gap-2.5 w-full md:w-auto">
                <a href="{{ route('assessment.certificate', $submission->submission_code) }}" target="_blank" class="flex-1 md:flex-none justify-center px-4 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg transition cursor-pointer flex items-center gap-2 uppercase tracking-wider min-h-[44px]">
                    <span>📜</span> <span>Sertifikat Resmi</span>
                </a>
                <button onclick="window.print()" class="flex-1 md:flex-none justify-center px-4 py-3 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] text-xs font-bold rounded-xl shadow-lg transition cursor-pointer flex items-center gap-2 uppercase tracking-wider min-h-[44px]">
                    <span>🖨️</span> <span>Cetak Laporan</span>
                </button>
                <button @click="openStoryModal()" class="flex-1 md:flex-none justify-center px-4 py-3 bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 hover:from-purple-700 hover:to-rose-700 text-white text-xs font-bold rounded-xl shadow-lg transition cursor-pointer flex items-center gap-2 uppercase tracking-wider min-h-[44px]">
                    <span>📱</span> <span>Kartu Sosmed (IG/WA)</span>
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
        <div class="hidden print:block border-b-2 border-[#0B2A43] pb-4 mb-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" class="w-11 h-11 object-contain" alt="Logo">
                    <div>
                        <h2 class="text-lg font-serif font-extrabold text-[#0B2A43] tracking-wide uppercase">RUHIOLOGY INSTITUTE</h2>
                        <p class="text-[11px] text-slate-600">Laporan Resmi Hasil Individu Asesmen Kesadaran Ruhiologi (RQI-15 & WHO-5 Index)</p>
                    </div>
                </div>
                <div class="text-right text-[11px] font-mono">
                    <p class="font-bold text-[#0B2A43]">NO: {{ $submission->submission_code }}</p>
                    <p class="text-slate-500">Tanggal: {{ optional($submission->submitted_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Participant Info Metadata Header -->
        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-sm text-xs print-avoid-break">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#0B2A43] text-[#C9A24D] font-bold text-sm flex items-center justify-center font-mono shrink-0 shadow border border-[#C9A24D]/30">
                        {{ strtoupper(substr($submission->participant->name, 0, 2)) }}
                    </div>
                    <div>
                        <span class="text-slate-400 uppercase font-bold text-[9px] block font-mono">NAMA LENGKAP PESERTA</span>
                        <strong class="font-bold text-[#0B2A43] text-sm block leading-snug">{{ $submission->participant->name }}</strong>
                    </div>
                </div>

                <div>
                    <span class="text-slate-400 uppercase font-bold text-[9px] block font-mono">KATEGORI & ASAL INSTANSI</span>
                    <span class="font-bold text-[#0B2A43] text-xs block mt-0.5 leading-snug">
                        {{ $submission->participant->category ?: 'UMUM' }}
                        @if($instName)
                            &mdash; <span class="text-slate-600 font-semibold">{{ $instName }}</span>
                        @endif
                    </span>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 uppercase font-bold text-[9px] block font-mono">WILAYAH ASAL (DOMISILI)</span>
                        <button type="button" @click="openEditRegionModal()" class="no-print text-[10px] text-amber-700 hover:text-amber-900 font-bold underline cursor-pointer inline-flex items-center gap-0.5">
                            <span>✏️</span> <span>Ubah</span>
                        </button>
                    </div>
                    <span class="font-bold text-[#0B2A43] text-xs block mt-0.5">
                        📍 {{ $regionText ?: 'Tidak diisi' }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-400 uppercase font-bold text-[9px] block font-mono">TANGGAL & STATUS SESI</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="font-bold text-slate-700 text-xs">
                            {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }}
                        </span>
                        <span class="uppercase text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full text-[10px] font-mono font-bold border border-amber-200">
                            {{ $submission->submission_type }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- UNIFIED DUAL-PILLAR EXECUTIVE DASHBOARD (TWIN LUXURY CARDS) -->
        <div class="grid grid-cols-1 {{ $hasWho5 ? 'lg:grid-cols-2' : '' }} gap-6 sm:gap-8 items-stretch print-avoid-break">
            
            <!-- CARD 1: PILAR 1 - KECERDASAN RUHIOLOGI (RQI-15) -->
            <div class="bg-[#0B2A43] text-white p-6 sm:p-8 rounded-3xl shadow-2xl border border-[#C9A24D]/35 relative overflow-hidden flex flex-col justify-between space-y-6">
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-[#C9A24D]/20 rounded-full blur-3xl pointer-events-none no-print"></div>

                <div class="space-y-5 relative z-10">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest bg-white/5 px-3 py-1 rounded-full border border-[#C9A24D]/30 inline-flex items-center gap-1.5">
                            <span>✦</span> <span>PILAR 1: KECERDASAN RUHIOLOGI (RQI-15)</span>
                        </span>
                        <span class="text-[10px] font-mono text-slate-400 font-semibold">SKALA 15 – 75 POIN</span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6 pt-1">
                        <div class="relative w-28 h-28 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-slate-800/80" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="text-[#C9A24D]" stroke-dasharray="{{ min(100, $scorePct) }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <div class="absolute flex flex-col items-center justify-center text-center">
                                <span class="font-mono font-black text-base text-[#C9A24D] leading-none">{{ $scorePct }}%</span>
                                <span class="text-[8px] font-mono uppercase text-slate-400 mt-0.5">CAPAIAN</span>
                            </div>
                        </div>

                        <div class="text-center sm:text-left space-y-1">
                            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest block">SKOR TOTAL RQI</span>
                            <div class="text-4xl sm:text-5xl font-black font-serif text-white tracking-tight flex items-baseline justify-center sm:justify-start gap-1">
                                <span>{{ $rqiScore }}</span>
                                <span class="text-sm text-slate-400 font-normal font-sans">/ {{ $maxRqiScore }} Poin</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="px-3.5 py-2 rounded-2xl text-[11px] font-bold uppercase tracking-wider font-mono border shadow-md bg-gradient-to-r {{ $badgeGradient }} text-center flex items-center justify-center gap-1.5">
                            <span>✦</span>
                            <span class="truncate">{{ $catName }}</span>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1">
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-wider block">✦ GAMBARAN PROFIL & REFLEKSI BATINIAH</span>
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-md relative">
                            <p class="text-xs italic text-slate-100 leading-relaxed font-normal">
                                "{{ $submission->result->overall_interpretation }}"
                            </p>
                        </div>
                    </div>
                </div>

                @if($submission->result->pre_post_diff !== null)
                    <div class="pt-2 border-t border-white/10 flex items-center justify-between text-xs relative z-10">
                        <span class="text-slate-300 font-medium">Perkembangan Delta RQI:</span>
                        <span class="font-bold font-mono px-2.5 py-0.5 rounded-full text-xs {{ $submission->result->pre_post_diff >= 0 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30' : 'bg-rose-500/20 text-rose-300 border border-rose-400/30' }}">
                            {{ $submission->result->pre_post_diff >= 0 ? '+' : '' }}{{ number_format($submission->result->pre_post_diff, 1) }} poin
                        </span>
                    </div>
                @endif
            </div>

            <!-- CARD 2: PILAR 2 - SKRINING KESEJAHTERAAN MENTAL (WHO-5) -->
            @if($hasWho5)
                <div class="bg-[#0B2A43] text-white p-6 sm:p-8 rounded-3xl shadow-2xl border border-emerald-500/35 relative overflow-hidden flex flex-col justify-between space-y-6">
                    <div class="absolute -top-20 -right-20 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none no-print"></div>

                    <div class="space-y-5 relative z-10">
                        <div class="flex items-center justify-between border-b border-white/10 pb-3">
                            <span class="text-[10px] font-mono font-bold text-emerald-400 uppercase tracking-widest bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/30 inline-flex items-center gap-1.5">
                                <span>🌿</span> <span>PILAR 2: KESEJAHTERAAN MENTAL (WHO-5)</span>
                            </span>
                            <span class="text-[10px] font-mono text-slate-400 font-semibold">SKALA 0 – 25 POIN</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6 pt-1">
                            <div class="relative w-28 h-28 shrink-0 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-800/80" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-emerald-400" stroke-dasharray="{{ min(100, $who5Pct) }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute flex flex-col items-center justify-center text-center">
                                    <span class="font-mono font-black text-base text-emerald-400 leading-none">{{ number_format($who5Pct, 0) }}%</span>
                                    <span class="text-[8px] font-mono uppercase text-slate-400 mt-0.5">INDEKS</span>
                                </div>
                            </div>

                            <div class="text-center sm:text-left space-y-1">
                                <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest block">SKOR MENTAH WHO-5</span>
                                <div class="text-4xl sm:text-5xl font-black font-serif text-emerald-400 tracking-tight flex items-baseline justify-center sm:justify-start gap-1">
                                    <span>{{ $who5Raw }}</span>
                                    <span class="text-sm text-slate-300 font-normal font-sans">/ 25 Poin</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="px-3.5 py-2 rounded-2xl text-[11px] font-bold uppercase tracking-wider font-mono border shadow-md bg-gradient-to-r {{ $who5BadgeStyle }} text-center flex items-center justify-center gap-1.5">
                                <span>🌱</span>
                                <span class="truncate">{{ $who5LevelName }}</span>
                            </div>
                        </div>

                        <div class="space-y-1.5 pt-1">
                            <span class="text-[10px] font-mono font-bold text-emerald-400 uppercase tracking-wider block">🌿 DESKRIPSI KESEJAHTERAAN EMOSIONAL</span>
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-md relative">
                                <p class="text-xs text-slate-100 leading-relaxed font-normal">
                                    "{{ $submission->result->who5_screening_note }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-white/10 text-[10px] text-slate-400 italic leading-snug relative z-10">
                        *World Health Organization-5 (WHO-5) Well-Being Index: instrumen psikometrik global untuk pemetaan kesehatan mental dan vitalitas emosional.
                    </div>
                </div>
            @endif

        </div>

        <!-- PRE VS POST COMPARATIVE PROGRESS CARD -->
        @if(isset($preSubmission) && isset($postSubmission))
            <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-emerald-200 shadow-md space-y-6 print-avoid-break">
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

        <!-- VISUAL RADAR CHART 5 DIMENSI RQI -->
        <div class="bg-[#0B2A43] text-white p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-md grid grid-cols-1 lg:grid-cols-12 gap-6 items-center print-avoid-break">
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
                    PROFIL METRIK 5 PILAR RQI-15
                </span>
                <h3 class="text-xl sm:text-2xl font-serif font-bold text-white leading-snug">
                    Pemetaan Keseimbangan Potensi Ruh Individual
                </h3>
                <p class="text-xs text-slate-200 leading-relaxed font-normal">
                    Setiap dimensi merepresentasikan bagian integral dari kesadaran batin manusia: dari hubungan vertikal dengan Sang Pencipta, regulasi emosi di bawah tekanan (Tazkiyah), hingga kontribusi altruistik bagi masyarakat.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                    @foreach($rqiOnlyDimensions as $dRes)
                        <div class="p-3 bg-white/10 rounded-xl border border-white/10 space-y-1.5">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-slate-100 truncate">{{ $dRes->dimension->name }}</span>
                                <span class="font-mono font-bold text-[#C9A24D] shrink-0 ml-2">{{ number_format($dRes->percentage, 0) }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-[#C9A24D] rounded-full" style="width: {{ min(100, $dRes->percentage) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 5 DIMENSIONS BREAKDOWN TABLE (DESKTOP: TABLE, MOBILE: RESPONSIVE CARDS) -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden print-avoid-break">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-serif font-bold text-[#0B2A43] text-base sm:text-lg">Rincian Perolehan 5 Dimensi Utama RQI-15</h3>
                <span class="text-[10px] sm:text-xs font-mono font-bold text-[#C9A24D] uppercase bg-[#0B2A43] px-3 py-1 rounded-full">5 Pilar RQI-15</span>
            </div>

            <!-- Desktop View: Table -->
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
                        @foreach($rqiOnlyDimensions as $dimRes)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-4 font-mono font-bold text-[#C9A24D]">{{ $dimRes->dimension->code }}</td>
                                <td class="px-6 py-4 text-[#0B2A43] font-bold text-sm">{{ $dimRes->dimension->name }}</td>
                                <td class="px-6 py-4 text-slate-600 font-mono font-bold">{{ $dimRes->score }} / {{ $dimRes->max_score }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-[#0B2A43] font-mono">{{ number_format($dimRes->percentage, 1) }}%</span>
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

            <!-- Mobile View: Vertical Responsive Cards -->
            <div class="block md:hidden p-4 space-y-3">
                @foreach($rqiOnlyDimensions as $dimRes)
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
                    "Tuliskan satu tekad perubahan batin yang paling ingin Anda wujudkan agar studi, karier, dan hidup Anda terasa lebih bermakna dan damai."
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

    <!-- INSTAGRAM / WHATSAPP CARD SHARE MODAL (SUPPORTS 4:5 FEED & 9:16 STORY) -->
    <div x-show="showStoryModal" x-cloak class="fixed inset-0 bg-slate-950/85 backdrop-blur-md z-[9999] flex items-center justify-center p-3 sm:p-6 animate-fadeIn no-print overflow-y-auto">
        <div @click.away="showStoryModal = false" class="bg-slate-900 rounded-3xl max-w-4xl w-full p-5 sm:p-8 shadow-2xl border border-slate-800 space-y-5 my-auto">
            
            <!-- Modal Header -->
            <div class="flex flex-wrap items-center justify-between border-b border-slate-800 pb-4 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#C9A24D]/15 border border-[#C9A24D]/35 flex items-center justify-center text-xl text-[#C9A24D]">
                        📱
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">SOCIAL MEDIA SHARE CARD GENERATOR</span>
                        <h3 class="text-base sm:text-lg font-serif font-bold text-white">Kartu Visual Hasil Asesmen (Instagram & WhatsApp)</h3>
                    </div>
                </div>
                <button @click="showStoryModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-400 hover:text-white flex items-center justify-center transition cursor-pointer">✕</button>
            </div>

            <!-- 2-Column Split Body Grid -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                
                <!-- Left Column (5 Cols): Card Canvas Preview -->
                <div class="md:col-span-5 bg-slate-950/80 p-4 rounded-3xl border border-slate-800 flex flex-col items-center justify-center space-y-3">
                    <canvas id="storyCanvas"
                            :class="cardRatio === '9:16' ? 'aspect-[9/16] max-w-[235px]' : 'aspect-[4/5] max-w-[280px]'"
                            class="w-full rounded-2xl shadow-2xl border border-[#C9A24D]/30 transition-all duration-200"></canvas>
                    <span class="text-[10px] font-mono text-slate-400" x-text="cardRatio === '9:16' ? 'Resolusi: 1080 × 1920 px (Full Story 9:16)' : 'Resolusi: 1080 × 1350 px (Feed Portrait 4:5)'"></span>
                </div>

                <!-- Right Column (7 Cols): Format & Theme Selector & Actions -->
                <div class="md:col-span-7 space-y-4 text-left text-xs">
                    
                    <!-- Aspect Ratio Switcher -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-300 uppercase tracking-wider font-mono block">1. Pilih Ukuran Kartu Sosmed:</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" @click="setCardRatio('4:5')"
                                    :class="cardRatio === '4:5' ? 'bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950 font-extrabold border-amber-400 shadow-md' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="p-2.5 rounded-2xl border text-left transition cursor-pointer flex items-center gap-2.5">
                                <span class="text-lg">🖼️</span>
                                <div>
                                    <span class="block text-xs font-bold leading-tight">Feed IG / WA (4:5)</span>
                                    <span class="block text-[10px] opacity-80">1080 × 1350 px</span>
                                </div>
                            </button>
                            <button type="button" @click="setCardRatio('9:16')"
                                    :class="cardRatio === '9:16' ? 'bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950 font-extrabold border-amber-400 shadow-md' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="p-2.5 rounded-2xl border text-left transition cursor-pointer flex items-center gap-2.5">
                                <span class="text-lg">📱</span>
                                <div>
                                    <span class="block text-xs font-bold leading-tight">Story Full (9:16)</span>
                                    <span class="block text-[10px] opacity-80">1080 × 1920 px</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Card Theme Switcher -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-300 uppercase tracking-wider font-mono block">2. Pilih Tema Warna Kartu:</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" @click="setCardTheme('royal')"
                                    :class="cardTheme === 'royal' ? 'bg-amber-400 text-slate-950 font-extrabold border-amber-300 shadow' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="py-2.5 px-3 rounded-xl border text-center transition cursor-pointer">
                                <span class="block text-xs">👑 Royal Gold</span>
                            </button>
                            <button type="button" @click="setCardTheme('emerald')"
                                    :class="cardTheme === 'emerald' ? 'bg-emerald-400 text-slate-950 font-extrabold border-emerald-300 shadow' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="py-2.5 px-3 rounded-xl border text-center transition cursor-pointer">
                                <span class="block text-xs">🌿 Emerald Glow</span>
                            </button>
                            <button type="button" @click="setCardTheme('ivory')"
                                    :class="cardTheme === 'ivory' ? 'bg-white text-slate-950 font-extrabold border-amber-400 shadow' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="py-2.5 px-3 rounded-xl border text-center transition cursor-pointer">
                                <span class="block text-xs">☀️ Ivory Executive</span>
                            </button>
                        </div>
                    </div>

                    <!-- Highlighted Content Info -->
                    <div class="p-3 bg-slate-800/60 rounded-2xl border border-slate-700/60 space-y-1.5">
                        <div class="flex items-center gap-2 text-slate-200">
                            <span class="text-amber-400">🎓</span>
                            <span>Menampilkan Identitas, Instansi/Kampus, & Domisili Anda</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-200">
                            <span class="text-amber-400">📊</span>
                            <span>Dilengkapi Skor Dual-Pilar (RQI-15 & WHO-5) + <strong>5 Bar Dimensi RQI</strong></span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-1 space-y-2.5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <button type="button" @click="downloadStoryImage()" class="py-3.5 px-4 bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-600 hover:from-amber-500 hover:to-yellow-700 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-xl transition transform hover:-translate-y-0.5 cursor-pointer flex items-center justify-center gap-2">
                                <span>📥</span> <span x-text="'Unduh PNG (' + cardRatio + ')'"></span>
                            </button>

                            <button type="button" @click="shareStoryCard()" class="py-3.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-xl transition transform hover:-translate-y-0.5 cursor-pointer flex items-center justify-center gap-2">
                                <span>📲</span> <span>Bagikan ke IG / WA</span>
                            </button>
                        </div>

                        <button type="button" @click="copySocialCaption()" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-amber-300 border border-amber-500/30 font-bold text-xs rounded-2xl transition cursor-pointer flex items-center justify-center gap-2">
                            <span x-text="captionCopied ? '✅ Caption Berhasil Disalin ke Clipboard!' : '📋 Salin Teks Caption Siap Pakai'"></span>
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- MODAL UBAH WILAYAH ASAL PESERTA -->
    <div x-show="showEditRegionModal" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm no-print">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base flex items-center gap-2">
                    <span>📍</span> <span>Perbarui Wilayah Asal Peserta</span>
                </h3>
                <button @click="showEditRegionModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('assessment.update_region', $submission->submission_code) }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Provinsi *</label>
                    <select name="province_id" x-model="editProvinceId" @change="onEditProvinceChange()" required class="w-full p-2.5 rounded-xl border border-slate-300 font-bold bg-white text-slate-800 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                        <option value="">-- Pilih Provinsi --</option>
                        <template x-for="p in provincesList" :key="p.id">
                            <option :value="p.id" x-text="p.name" :selected="String(p.id) === String(editProvinceId)"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kabupaten / Kota *</label>
                    <select name="regency_id" x-model="editRegencyId" :disabled="!editProvinceId" required class="w-full p-2.5 rounded-xl border border-slate-300 font-bold bg-white text-slate-800 disabled:bg-slate-100 disabled:text-slate-400 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                        <option value="" x-text="editProvinceId ? '-- Pilih Kab/Kota --' : 'Pilih Provinsi Dahulu'"></option>
                        <template x-for="r in filteredEditRegencies" :key="r.id">
                            <option :value="r.id" x-text="r.formatted_name || ((r.name.startsWith('Kota') || r.name.startsWith('Kabupaten') || !r.type) ? r.name : (r.type + ' ' + r.name))" :selected="String(r.id) === String(editRegencyId)"></option>
                        </template>
                    </select>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showEditRegionModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-extrabold rounded-xl transition shadow cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function rqResultPage() {
    return {
        showStoryModal: false,
        showEditRegionModal: false,
        cardRatio: '4:5',
        cardTheme: 'royal',
        captionCopied: false,
        editProvinceId: '{{ $submission->participant->province_id ?? '' }}',
        editRegencyId: '{{ $submission->participant->regency_id ?? '' }}',
        provincesList: @json($provinces ?? []),
        regenciesMap: @json($regenciesMap ?? []),
        pillarsData: @json($canvasPillars ?? []),
        filteredEditRegencies: [],

        openEditRegionModal() {
            this.showEditRegionModal = true;
            this.onEditProvinceChange(true);
        },

        onEditProvinceChange(keepRegency = false) {
            if (!keepRegency) {
                this.editRegencyId = '';
            }
            if (!this.editProvinceId) {
                this.filteredEditRegencies = [];
                return;
            }
            const pid = String(this.editProvinceId);
            this.filteredEditRegencies = this.regenciesMap[pid] || this.regenciesMap[Number(pid)] || [];
        },

        openStoryModal() {
            this.showStoryModal = true;
            this.$nextTick(() => {
                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(() => this.drawStoryCard());
                } else {
                    this.drawStoryCard();
                }
            });
        },

        setCardRatio(ratio) {
            this.cardRatio = ratio;
            this.$nextTick(() => this.drawStoryCard());
        },

        setCardTheme(theme) {
            this.cardTheme = theme;
            this.$nextTick(() => this.drawStoryCard());
        },

        drawStoryCard() {
            const canvas = document.getElementById('storyCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            const isStory = (this.cardRatio === '9:16');
            const isLight = (this.cardTheme === 'ivory');
            const isEmerald = (this.cardTheme === 'emerald');
            const W = 1080;
            const H = isStory ? 1920 : 1350;
            canvas.width = W;
            canvas.height = H;

            const cleanText = (str) => {
                if (!str) return '';
                const txt = document.createElement('textarea');
                txt.innerHTML = str;
                return txt.value;
            };

            const logoImg = new Image();
            logoImg.crossOrigin = 'anonymous';

            const render = () => {
                // 1. Vibrant Background Gradient according to Theme
                const bgGrad = ctx.createLinearGradient(0, 0, W, H);
                if (isLight) {
                    bgGrad.addColorStop(0, '#FFFDF9');
                    bgGrad.addColorStop(0.5, '#FEF9EC');
                    bgGrad.addColorStop(1, '#FDF6E2');
                } else if (isEmerald) {
                    bgGrad.addColorStop(0, '#042F2E');
                    bgGrad.addColorStop(0.5, '#065F46');
                    bgGrad.addColorStop(1, '#022C22');
                } else {
                    bgGrad.addColorStop(0, '#061A2E');
                    bgGrad.addColorStop(0.45, '#103B63');
                    bgGrad.addColorStop(1, '#041220');
                }
                ctx.fillStyle = bgGrad;
                ctx.fillRect(0, 0, W, H);

                // Luminous Spotlight Glows
                const glowTop = ctx.createRadialGradient(W / 2, isStory ? 340 : 240, 20, W / 2, isStory ? 340 : 240, 560);
                glowTop.addColorStop(0, isLight ? 'rgba(245, 158, 11, 0.16)' : 'rgba(251, 191, 36, 0.28)');
                glowTop.addColorStop(1, 'rgba(0, 0, 0, 0)');
                ctx.fillStyle = glowTop;
                ctx.fillRect(0, 0, W, H);

                // Outer & Inner Vibrant Gold Frame
                ctx.strokeStyle = isLight ? '#0B2A43' : '#F59E0B';
                ctx.lineWidth = isLight ? 10 : 4;
                ctx.beginPath();
                ctx.roundRect(28, 28, W - 56, H - 56, 36);
                ctx.stroke();

                ctx.strokeStyle = '#F59E0B';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.roundRect(44, 44, W - 88, H - 88, 26);
                ctx.stroke();

                let curY = isStory ? 105 : 72;

                // 2. Top Logo & Institute Header Pill
                if (logoImg.complete && logoImg.naturalWidth > 0) {
                    const logoSize = isStory ? 92 : 76;
                    ctx.drawImage(logoImg, (W - logoSize) / 2, curY, logoSize, logoSize);
                    curY += logoSize + (isStory ? 36 : 28);
                } else {
                    curY += 40;
                }

                ctx.textAlign = 'center';
                ctx.fillStyle = isLight ? '#0B2A43' : '#FBBF24';
                ctx.font = '800 32px "Poppins", sans-serif';
                ctx.fillText('RUHIOLOGY INSTITUTE', W / 2, curY);

                curY += 28;
                ctx.fillStyle = isLight ? '#B45309' : '#E2E8F0';
                ctx.font = '700 15px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('LAPORAN RESMI HASIL ASESMEN RQI-15 & WHO-5 INDEX', W / 2, curY);

                curY += isStory ? 36 : 24;

                // 3. Participant Identity Card (High Contrast)
                const pBoxH = isStory ? 158 : 134;
                ctx.fillStyle = isLight ? '#FFFFFF' : 'rgba(255, 255, 255, 0.09)';
                ctx.beginPath();
                ctx.roundRect(72, curY, W - 144, pBoxH, 24);
                ctx.fill();
                ctx.strokeStyle = isLight ? '#F59E0B' : 'rgba(251, 191, 36, 0.55)';
                ctx.lineWidth = 2;
                ctx.stroke();

                const pName = cleanText('{{ addslashes($submission->participant->name) }}');
                ctx.fillStyle = isLight ? '#0B2A43' : '#FFFFFF';
                ctx.font = pName.length > 28 ? '800 28px "Poppins", sans-serif' : '800 34px "Poppins", sans-serif';
                ctx.fillText(pName, W / 2, curY + (isStory ? 56 : 50));

                const catStr = cleanText('{{ addslashes($submission->participant->category ?: "UMUM") }}');
                const instStr = cleanText('{{ addslashes($instName ?? "") }}');
                const regStr = cleanText('{{ addslashes($regionText ?? "") }}');
                const subLine1 = instStr ? `${catStr}  •  ${instStr}` : catStr;

                ctx.fillStyle = isLight ? '#B45309' : '#FBBF24';
                ctx.font = '700 18px "Plus Jakarta Sans", sans-serif';
                ctx.fillText(subLine1.length > 54 ? subLine1.substring(0, 52) + '...' : subLine1, W / 2, curY + (isStory ? 95 : 84));

                if (regStr) {
                    ctx.fillStyle = isLight ? '#475569' : '#E2E8F0';
                    ctx.font = '600 15px "Plus Jakarta Sans", sans-serif';
                    ctx.fillText('📍 ' + regStr, W / 2, curY + (isStory ? 128 : 112));
                }

                curY += pBoxH + (isStory ? 30 : 20);

                // 4. Dual Metric Score Cards (SOLID VIBRANT ROYAL NAVY & EMERALD)
                const cardH = isStory ? 255 : 220;
                const cardW = 452;

                // Left Card: RQI-15 (Vibrant Navy & Gold)
                const rqiCardGrad = ctx.createLinearGradient(72, curY, 72 + cardW, curY + cardH);
                rqiCardGrad.addColorStop(0, '#0B2A43');
                rqiCardGrad.addColorStop(1, '#15466E');
                ctx.fillStyle = rqiCardGrad;
                ctx.beginPath();
                ctx.roundRect(72, curY, cardW, cardH, 24);
                ctx.fill();
                ctx.strokeStyle = '#FBBF24';
                ctx.lineWidth = 3;
                ctx.stroke();

                ctx.fillStyle = '#FDE047';
                ctx.font = '800 16px "Poppins", sans-serif';
                ctx.fillText('✦ SKOR RQI-15', 298, curY + 38);

                ctx.fillStyle = '#FBBF24';
                ctx.font = isStory ? '800 74px "Poppins", sans-serif' : '800 64px "Poppins", sans-serif';
                ctx.fillText('{{ $rqiScore }}', 298, curY + (isStory ? 116 : 102));

                ctx.fillStyle = '#F8FAFC';
                ctx.font = '700 17px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('/ {{ $maxRqiScore }} Poin ({{ $scorePct }}%)', 298, curY + (isStory ? 150 : 132));

                // RQI Badge Pill
                const rqiBadgeText = cleanText('{{ addslashes($catName) }}');
                const pillGrad1 = ctx.createLinearGradient(92, 0, 92 + cardW - 40, 0);
                pillGrad1.addColorStop(0, '#FDE047');
                pillGrad1.addColorStop(1, '#F59E0B');
                ctx.fillStyle = pillGrad1;
                ctx.beginPath();
                ctx.roundRect(92, curY + cardH - 64, cardW - 40, 44, 22);
                ctx.fill();

                ctx.fillStyle = '#071A2B';
                ctx.font = rqiBadgeText.length > 30 ? '800 13px "Poppins", sans-serif' : '800 15px "Poppins", sans-serif';
                ctx.fillText(rqiBadgeText.length > 38 ? rqiBadgeText.substring(0, 36) + '...' : rqiBadgeText, 298, curY + cardH - 36);

                // Right Card: WHO-5 (Vibrant Emerald Teal)
                const whoCardGrad = ctx.createLinearGradient(556, curY, 556 + cardW, curY + cardH);
                whoCardGrad.addColorStop(0, '#065F46');
                whoCardGrad.addColorStop(1, '#047857');
                ctx.fillStyle = whoCardGrad;
                ctx.beginPath();
                ctx.roundRect(556, curY, cardW, cardH, 24);
                ctx.fill();
                ctx.strokeStyle = '#34D399';
                ctx.lineWidth = 3;
                ctx.stroke();

                ctx.fillStyle = '#A7F3D0';
                ctx.font = '800 16px "Poppins", sans-serif';
                ctx.fillText('🌿 INDEKS WHO-5', 782, curY + 38);

                ctx.fillStyle = '#FFFFFF';
                ctx.font = isStory ? '800 74px "Poppins", sans-serif' : '800 64px "Poppins", sans-serif';
                ctx.fillText('{{ number_format($who5Pct, 0) }}%', 782, curY + (isStory ? 116 : 102));

                ctx.fillStyle = '#D1FAE5';
                ctx.font = '700 17px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('Skor Mentah: {{ $who5Raw }} / 25 Poin', 782, curY + (isStory ? 150 : 132));

                // WHO-5 Badge Pill
                const who5BadgeText = cleanText('{{ addslashes($who5BadgeTitleShort) }}');
                ctx.fillStyle = '#A7F3D0';
                ctx.beginPath();
                ctx.roundRect(576, curY + cardH - 64, cardW - 40, 44, 22);
                ctx.fill();

                ctx.fillStyle = '#064E3B';
                ctx.font = '800 15px "Poppins", sans-serif';
                ctx.fillText(who5BadgeText, 782, curY + cardH - 36);

                curY += cardH + (isStory ? 32 : 20);

                // 5. 5-Pillar Competency Progress Bars Box
                const pillars = this.pillarsData || [];
                const pSectionH = isStory ? 360 : 285;
                ctx.fillStyle = isLight ? '#FFFFFF' : 'rgba(255, 255, 255, 0.08)';
                ctx.beginPath();
                ctx.roundRect(72, curY, W - 144, pSectionH, 24);
                ctx.fill();
                ctx.strokeStyle = isLight ? '#F59E0B' : 'rgba(251, 191, 36, 0.4)';
                ctx.lineWidth = 2;
                ctx.stroke();

                ctx.textAlign = 'left';
                ctx.fillStyle = isLight ? '#0B2A43' : '#FBBF24';
                ctx.font = '800 16px "Poppins", sans-serif';
                ctx.fillText('✦ PROFIL CAPAIAN 5 PILAR DIMENSI RQI-15', 105, curY + 38);

                ctx.textAlign = 'right';
                ctx.fillStyle = isLight ? '#B45309' : '#A7F3D0';
                ctx.font = '700 14px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('SKALA 0–100%', W - 105, curY + 38);

                const rowStart = curY + (isStory ? 74 : 64);
                const rowGap = isStory ? 56 : 43;

                pillars.slice(0, 5).forEach((p, idx) => {
                    const y = rowStart + (idx * rowGap);
                    ctx.textAlign = 'left';
                    ctx.fillStyle = isLight ? '#0B2A43' : '#FFFFFF';
                    ctx.font = '700 17px "Plus Jakarta Sans", sans-serif';
                    ctx.fillText(`${idx + 1}. ${p.name}`, 105, y);

                    // Bar Track
                    const barX = 485;
                    const barW = 390;
                    const barH = isStory ? 18 : 15;
                    ctx.fillStyle = isLight ? '#E2E8F0' : 'rgba(255, 255, 255, 0.16)';
                    ctx.beginPath();
                    ctx.roundRect(barX, y - barH + 3, barW, barH, barH / 2);
                    ctx.fill();

                    // Bar Fill (Vibrant Amber -> Emerald)
                    const fillW = Math.max(22, Math.min(barW, (p.pct / 100) * barW));
                    const barGrad = ctx.createLinearGradient(barX, 0, barX + fillW, 0);
                    barGrad.addColorStop(0, '#F59E0B');
                    barGrad.addColorStop(0.5, '#FBBF24');
                    barGrad.addColorStop(1, '#10B981');
                    ctx.fillStyle = barGrad;
                    ctx.beginPath();
                    ctx.roundRect(barX, y - barH + 3, fillW, barH, barH / 2);
                    ctx.fill();

                    // Pct Text
                    ctx.textAlign = 'right';
                    ctx.fillStyle = isLight ? '#0B2A43' : '#FDE047';
                    ctx.font = '800 18px "Poppins", sans-serif';
                    ctx.fillText(`${p.pct}%`, W - 105, y + 1);
                });

                curY += pSectionH + (isStory ? 32 : 18);

                // 6. Interpretation / Philosophical Reflection Box
                const quoteBoxH = isStory ? 260 : 168;
                ctx.fillStyle = isLight ? '#FEF3C7' : 'rgba(251, 191, 36, 0.1)';
                ctx.beginPath();
                ctx.roundRect(72, curY, W - 144, quoteBoxH, 24);
                ctx.fill();
                ctx.strokeStyle = '#F59E0B';
                ctx.lineWidth = 2;
                ctx.stroke();

                ctx.textAlign = 'center';
                ctx.fillStyle = isLight ? '#92400E' : '#FDE047';
                ctx.font = '800 15px "Poppins", sans-serif';
                ctx.fillText('✦ REFLEKSI PROFIL RUHIOLOGI', W / 2, curY + 34);

                ctx.fillStyle = isLight ? '#0B2A43' : '#FFFFFF';
                ctx.font = isStory ? 'italic 600 20px "Plus Jakarta Sans", sans-serif' : 'italic 600 17px "Plus Jakarta Sans", sans-serif';
                const interpText = cleanText('"{{ addslashes($submission->result->overall_interpretation) }}"');
                this.wrapText(ctx, interpText, W / 2, curY + (isStory ? 74 : 66), 860, isStory ? 32 : 26, isStory ? 4 : 3);

                curY += quoteBoxH + (isStory ? 40 : 22);

                // 7. Footer Branding & Verification
                ctx.strokeStyle = isLight ? '#D97706' : 'rgba(251, 191, 36, 0.45)';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(160, curY);
                ctx.lineTo(W - 160, curY);
                ctx.stroke();

                const founderName = cleanText('{{ addslashes(\App\Models\Setting::get("founder_name", "Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.")) }}');
                ctx.fillStyle = isLight ? '#0B2A43' : '#FFFFFF';
                ctx.font = '800 19px "Poppins", sans-serif';
                ctx.fillText(founderName, W / 2, curY + (isStory ? 38 : 30));

                ctx.fillStyle = isLight ? '#B45309' : '#FBBF24';
                ctx.font = '700 15px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('Founder Ruhiology Institute  •  ruhiology-institute.site', W / 2, curY + (isStory ? 66 : 54));

                ctx.fillStyle = isLight ? '#047857' : '#A7F3D0';
                ctx.font = '700 14px monospace';
                ctx.fillText('✓ KODE VERIFIKASI RESMI: {{ $submission->submission_code }}', W / 2, curY + (isStory ? 96 : 78));
            };

            logoImg.onload = render;
            logoImg.onerror = render;
            logoImg.src = "{{ asset('images/ruhiology-logo.png') }}";
            if (logoImg.complete) {
                render();
            }
        },

        wrapText(ctx, text, x, y, maxWidth, lineHeight, maxLines = 4) {
            const words = text.split(' ');
            let line = '';
            let currentY = y;
            let linesCount = 0;
            for (let n = 0; n < words.length; n++) {
                let testLine = line + words[n] + ' ';
                let metrics = ctx.measureText(testLine);
                if (metrics.width > maxWidth && n > 0) {
                    linesCount++;
                    if (linesCount >= maxLines) {
                        ctx.fillText(line.trim() + '...', x, currentY);
                        return;
                    }
                    ctx.fillText(line.trim(), x, currentY);
                    line = words[n] + ' ';
                    currentY += lineHeight;
                } else {
                    line = testLine;
                }
            }
            if (line.trim()) {
                ctx.fillText(line.trim(), x, currentY);
            }
        },

        downloadStoryImage() {
            const canvas = document.getElementById('storyCanvas');
            if (!canvas) return;
            const suffix = this.cardRatio === '9:16' ? 'story_9x16' : 'feed_4x5';
            const link = document.createElement('a');
            link.download = `Kartu_RQI_{{ $submission->submission_code }}_${this.cardTheme}_${suffix}.png`;
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();
        },

        async shareStoryCard() {
            const canvas = document.getElementById('storyCanvas');
            if (!canvas) return;
            const caption = `Alhamdulillah, saya telah menyelesaikan Asesmen Kesadaran Ruhiologi (RQI-15 & WHO-5) di Ruhiology Institute!\n\n✦ Skor RQI-15: {{ $rqiScore }} / {{ $maxRqiScore }} Poin ({{ $catName }})\n🌿 Indeks Kesejahteraan WHO-5: {{ number_format($who5Pct, 0) }}%\n🔑 Kode Verifikasi: {{ $submission->submission_code }}\n\nYuk kenali profil kesadaran batin & potensi ruh Anda di: {{ url('/assessment') }}`;

            if (navigator.canShare && canvas.toBlob) {
                canvas.toBlob(async (blob) => {
                    if (!blob) {
                        this.downloadStoryImage();
                        return;
                    }
                    const file = new File([blob], `Kartu_RQI_{{ $submission->submission_code }}.png`, { type: 'image/png' });
                    if (navigator.canShare({ files: [file] })) {
                        try {
                            await navigator.share({
                                title: 'Hasil Asesmen RQI-15 & WHO-5 — Ruhiology Institute',
                                text: caption,
                                files: [file]
                            });
                            return;
                        } catch (e) {
                            // User cancelled or share failed
                        }
                    }
                    this.downloadStoryImage();
                }, 'image/png');
            } else {
                this.downloadStoryImage();
            }
        },

        copySocialCaption() {
            const caption = `Alhamdulillah, saya telah menyelesaikan Asesmen Kesadaran Ruhiologi (RQI-15 & WHO-5) di Ruhiology Institute! ✨\n\n📊 Skor RQI-15: {{ $rqiScore }} / {{ $maxRqiScore }} Poin ({{ $catName }})\n🌿 Indeks Kesejahteraan WHO-5: {{ number_format($who5Pct, 0) }}%\n🔑 Kode Verifikasi: {{ $submission->submission_code }}\n\nYuk kenali profil kesadaran batin & potensi ruh Anda di: {{ url('/assessment') }}`;
            navigator.clipboard.writeText(caption).then(() => {
                this.captionCopied = true;
                setTimeout(() => { this.captionCopied = false; }, 2500);
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('resultRadarChart');
    if (ctx && typeof Chart !== 'undefined') {
        @php
            $dimLabels = [];
            $dimValues = [];
            foreach($rqiOnlyDimensions as $dr) {
                $cleanLabel = preg_replace('/^\d+\.\s*/', '', trim($dr->dimension->name));
                $dimLabels[] = \Illuminate\Support\Str::limit($cleanLabel, 22);
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
                    borderWidth: 2.5,
                    pointBackgroundColor: '#C9A24D',
                    pointBorderColor: '#fff',
                    pointRadius: 4
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
                            color: '#f8fafc',
                            font: { size: 10, family: 'Poppins', weight: '600' }
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
