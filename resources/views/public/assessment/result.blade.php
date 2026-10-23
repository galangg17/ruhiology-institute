@extends('layouts.app')

@section('content')
@php
    $isEn = app()->getLocale() === 'en';
    $rqiScore = number_format($submission->result->rqi_score ?? $submission->result->total_score, 0);
    $maxRqiScore = $submission->result->max_score ?? 75;
    
    // Localize Category Name
    $rawCatName = $submission->result->category_name ?? 'Level 3: Developing Soul (Jiwa Berproses Stabil)';
    if ($isEn) {
        $catMap = [
            'Level 5: Enlightened Soul (Jiwa Mutmainnah Paripurna)' => 'Level 5: Enlightened Soul (Complete Serenity & Harmony)',
            'Level 4: Mindful Youth (Jiwa Sadar Bertumbuh)' => 'Level 4: Mindful Soul (Conscious Growth & Alignment)',
            'Level 3: Developing Soul (Jiwa Berproses Stabil)' => 'Level 3: Developing Soul (Stable Process & Balance)',
            'Level 2: Awakening Pilgrim (Jiwa Mulai Terpanggil)' => 'Level 2: Awakening Pilgrim (Spiritual Awakening Phase)',
            'Level 1: Spiritual Lowbat (Jiwa Membutuhkan Pengisian)' => 'Level 1: Spiritual Recharge (Seeking Guidance & Renewal)',
        ];
        $catName = $catMap[$rawCatName] ?? str_replace(
            ['Jiwa Mutmainnah Paripurna', 'Jiwa Sadar Bertumbuh', 'Jiwa Berproses Stabil', 'Jiwa Mulai Terpanggil', 'Jiwa Membutuhkan Pengisian'],
            ['Complete Serenity', 'Conscious Growth', 'Stable Growth', 'Awakening Phase', 'Needs Renewal'],
            $rawCatName
        );
    } else {
        $catName = $rawCatName;
    }
    
    $scorePct = round(($rqiScore / $maxRqiScore) * 100, 1);

    $allDimResults = $submission->result->dimensionResults ?? collect();

    // 5 RQI-15 Dimensions
    $rqiOnlyDimensions = $allDimResults->filter(function($dr) {
        $code = strtolower($dr->dimension->code ?? '');
        $name = strtolower($dr->dimension->name ?? '');
        return !str_contains($code, 'who') && !str_contains($name, 'who');
    })->take(5)->values();

    // WHO-5 Dimension Record if present in dimensionResults
    $who5DimRecord = $allDimResults->first(function($dr) {
        $code = strtolower($dr->dimension->code ?? '');
        $name = strtolower($dr->dimension->name ?? '');
        return str_contains($code, 'who') || str_contains($name, 'who');
    });

    $instName = $submission->resolved_institution;
    $regionText = $submission->resolved_domicile;
    $categoryText = $submission->resolved_category;

    $who5Raw = $submission->result->who5_raw_score
        ?? ($who5DimRecord ? $who5DimRecord->score : round(($scorePct / 100) * 25));
    $who5Pct = $submission->result->who5_percentage
        ?? ($who5DimRecord ? $who5DimRecord->percentage : round(($who5Raw / 25) * 100, 1));
    $hasWho5 = true;
    
    if ($who5Raw >= 20 || $who5Pct >= 80) {
        $who5LevelName = $isEn ? 'Level 5: Thriving & Energetic (Highly Flourishing)' : 'Level 5: Thriving & Energetic (Sangat Sejahtera)';
        $who5BadgeTitleShort = 'Level 5: Thriving & Energetic';
        $who5DefaultNote = $isEn ? 'Psychological well-being and emotional vitality are at an optimal level, filled with positive energy.' : 'Kondisi kesejahteraan psikologis dan vitalitas emosional berada pada tingkat sangat prima, penuh energi positif, dan sangat sejahtera.';
    } elseif ($who5Raw >= 16 || $who5Pct >= 64) {
        $who5LevelName = $isEn ? 'Level 4: Good Vibe & Fresh (Flourishing)' : 'Level 4: Good Vibe & Fresh (Sejahtera)';
        $who5BadgeTitleShort = 'Level 4: Good Vibe & Fresh';
        $who5DefaultNote = $isEn ? 'Emotional condition is healthy, fresh, and resilient in navigating daily challenges.' : 'Kondisi emosional tergolong sehat, segar, dan stabil dalam menjalani aktivitas harian.';
    } elseif ($who5Raw >= 11 || $who5Pct >= 44) {
        $who5LevelName = $isEn ? 'Level 3: Moderate Well-Being (Moderately Stable)' : 'Level 3: Moderate Well-Being (Cukup Stabil)';
        $who5BadgeTitleShort = 'Level 3: Moderate Well-Being';
        $who5DefaultNote = $isEn ? 'Emotional well-being is fairly stable, with a recommendation to maintain inner peace and balanced rest.' : 'Kesejahteraan emosional berada pada rentang cukup stabil, namun perlu menjaga keseimbangan istirahat dan ketenangan batin.';
    } elseif ($who5Raw >= 6 || $who5Pct >= 24) {
        $who5LevelName = $isEn ? 'Level 2: Low Energy (Needs Attention)' : 'Level 2: Low Energy (Perlu Perhatian)';
        $who5BadgeTitleShort = 'Level 2: Low Energy';
        $who5DefaultNote = $isEn ? 'Indications of emotional fatigue. Strengthening spiritual relaxation and counseling is recommended.' : 'Terdapat indikasi kelelahan emosional. Disarankan memperkuat relaksasi spiritual dan pendampingan konseling.';
    } else {
        $who5LevelName = $isEn ? 'Level 1: Mental Exhausted (Needs Support)' : 'Level 1: Mental Exhausted (Perlu Pendampingan)';
        $who5BadgeTitleShort = 'Level 1: Mental Exhausted';
        $who5DefaultNote = $isEn ? 'Significant mental fatigue. Consultation with Ruhiology Institute counselors is strongly advised.' : 'Kelelahan mental cukup signifikan. Sangat disarankan berkonsultasi dengan pakar/konselor Ruhiology Institute.';
    }

    $who5ScreeningNote = $isEn ? $who5DefaultNote : ($submission->result->who5_screening_note ?: $who5DefaultNote);

    // Build unified 6 Dimensions (5 Pilar RQI + Dimensi ke-6 WHO-5)
    $allSixDimensions = $rqiOnlyDimensions->map(function($dr, $idx) use ($isEn) {
        $name = $dr->dimension->name ?? ('Dimensi ' . ($idx + 1));
        if ($isEn) {
            $name = $dr->dimension->localized_name ?? \App\Services\TranslationService::getCuratedDictionary($name) ?? $name;
        }
        $interpretation = $dr->interpretation;
        if ($isEn && !empty($interpretation)) {
            $interpretation = str_replace(
                ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Tingkat kesadaran sangat prima', 'Perlu ditingkatkan'],
                ['Excellent', 'Good', 'Moderate', 'Needs Improvement', 'Optimal consciousness level', 'Room for growth'],
                $interpretation
            );
        }
        return (object) [
            'code' => $dr->dimension->code ?? ('DIM-' . ($idx + 1)),
            'name' => $name,
            'score' => $dr->score,
            'max_score' => $dr->max_score,
            'percentage' => $dr->percentage,
            'interpretation' => $interpretation,
            'is_who5' => false,
        ];
    })->values();

    $who5Name = $isEn ? 'Dimension 6: Mental Well-Being Index (WHO-5)' : 'Dimensi ke-6: Indeks Kesejahteraan Mental (WHO-5)';
    $allSixDimensions->push((object) [
        'code' => $who5DimRecord?->dimension?->code ?: 'DIM-6 (WHO-5)',
        'name' => $who5Name,
        'score' => $who5Raw,
        'max_score' => 25,
        'percentage' => $who5Pct,
        'interpretation' => $who5LevelName . ' — ' . $who5ScreeningNote,
        'is_who5' => true,
    ]);

    $canvasPillars = $allSixDimensions->map(function($dr) use ($isEn) {
        $rawName = trim($dr->name ?? 'Dimensi');
        $lower = strtolower($rawName);
        if (!empty($dr->is_who5) || str_contains($lower, 'who')) {
            $cleanName = $isEn ? 'Mental Well-Being (WHO-5)' : 'Kesejahteraan Mental (WHO-5)';
        } elseif (str_contains($lower, 'diri') || str_contains($lower, 'authentic self')) {
            $cleanName = $isEn ? 'Self-Awareness & Soul' : 'Kesadaran Diri & Ruh';
        } elseif (str_contains($lower, 'puncak') || str_contains($lower, 'peak divine')) {
            $cleanName = $isEn ? 'Peak Divine Awareness' : 'Kesadaran Puncak Ilahi';
        } elseif (str_contains($lower, 'god spot') || str_contains($lower, 'ketuhanan') || str_contains($lower, 'divine recognition')) {
            $cleanName = $isEn ? 'Divine Recognition (God Spot)' : 'Kesadaran Ketuhanan (God Spot)';
        } elseif (str_contains($lower, 'ibadah') || str_contains($lower, 'ketaatan') || str_contains($lower, 'devotion')) {
            $cleanName = $isEn ? 'Spiritual Devotion' : 'Ketaatan Spiritual & Ibadah';
        } elseif (str_contains($lower, 'tazkiyah') || str_contains($lower, 'purifikasi') || str_contains($lower, 'inner peace')) {
            $cleanName = $isEn ? 'Inner Purification (Tazkiyah)' : 'Purifikasi Batin (Tazkiyah)';
        } elseif (str_contains($lower, 'perilaku') || str_contains($lower, 'akhlak') || str_contains($lower, 'noble ethics')) {
            $cleanName = $isEn ? 'Character & Noble Ethics' : 'Perubahan Perilaku & Akhlak';
        } elseif (str_contains($lower, 'resiliensi') || str_contains($lower, 'ketangguhan') || str_contains($lower, 'resilience')) {
            $cleanName = $isEn ? 'Resilience & Soul Strength' : 'Ketangguhan & Resiliensi';
        } else {
            $cleanName = preg_replace('/^\d+\.\s*/', '', $rawName);
            $cleanName = preg_replace('/\s*\(.*?\)/', '', $cleanName);
        }
        return [
            'code' => $dr->code ?? 'D',
            'name' => $cleanName,
            'score' => $dr->score,
            'max' => $dr->max_score,
            'pct' => round($dr->percentage),
            'is_who5' => !empty($dr->is_who5)
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

<!-- Hero Header Banner (Calm Editorial Oxford Navy) -->
<div class="bg-[#0F2537] text-white py-7 sm:py-10 border-b border-slate-800 no-print">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-[10px] font-mono font-semibold text-[#C5A059] uppercase tracking-[0.18em] block mb-1.5">
                    {{ $isEn ? 'INDIVIDUAL RQ ASSESSMENT REPORT' : 'LAPORAN HASIL INDIVIDUAL RQ ASSESSMENT' }}
                </span>
                <h1 class="text-xl sm:text-3xl font-bold font-serif text-white tracking-tight">{{ $submission->period->title }}</h1>
                <p class="text-xs text-slate-300 mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1">
                    <span>{{ $isEn ? 'Assessment Code:' : 'Kode Assessment:' }} <strong class="font-mono text-white">{{ $submission->participant->assessment_code ?? $submission->participant->participant_code }}</strong></span>
                    <span class="text-slate-600">|</span>
                    <span>{{ $isEn ? 'Session Code:' : 'Kode Sesi:' }} <strong class="font-mono text-[#C5A059]">{{ $submission->submission_code }}</strong></span>
                    <span class="text-slate-600">|</span>
                    <span>{{ $isEn ? 'Session Type:' : 'Tipe Sesi:' }} <strong class="uppercase text-slate-200">{{ $submission->submission_type }}</strong></span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2.5 w-full md:w-auto">
                <a href="{{ route('assessment.certificate', $submission->submission_code) }}" target="_blank" class="flex-1 md:flex-none justify-center px-4 py-2.5 bg-[#B5945B] hover:bg-[#A3834B] text-white text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer flex items-center gap-2 min-h-[42px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <span>{{ $isEn ? 'Official Certificate' : 'Sertifikat Resmi' }}</span>
                </a>
                <button @click="openStoryModal()" class="flex-1 md:flex-none justify-center px-4 py-2.5 bg-white/10 hover:bg-white/15 text-white border border-white/20 text-xs font-semibold rounded-xl transition cursor-pointer flex items-center gap-2 min-h-[42px]">
                    <svg class="w-4 h-4 text-[#C5A059]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    <span>{{ $isEn ? 'Social Card (IG/WA)' : 'Kartu Sosmed (IG/WA)' }}</span>
                </button>
                <button onclick="window.print()" class="flex-1 md:flex-none justify-center px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold rounded-xl transition cursor-pointer flex items-center gap-2 min-h-[42px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>{{ $isEn ? 'Print Report' : 'Cetak Laporan' }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="py-6 sm:py-10 bg-[#F8F7F4] min-h-screen print-container">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-10 space-y-6 sm:space-y-8">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-900 text-xs font-semibold rounded-2xl border border-emerald-200 shadow-2xs flex items-center justify-between no-print">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        <!-- Official Printable Header (Visible only in PDF Print) -->
        <div class="hidden print:block border-b-2 border-[#0F2537] pb-4 mb-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" class="w-11 h-11 object-contain" alt="Logo">
                    <div>
                        <h2 class="text-lg font-serif font-bold text-[#0F2537] tracking-wide uppercase">RUHIOLOGY INSTITUTE</h2>
                        <p class="text-[11px] text-slate-600">{{ $isEn ? 'Official Individual Assessment Report on Ruhiology Intelligence (RQI-15 & WHO-5 Index)' : 'Laporan Resmi Hasil Individu Asesmen Kesadaran Ruhiologi (RQI-15 & WHO-5 Index)' }}</p>
                    </div>
                </div>
                <div class="text-right text-[11px] font-mono">
                    <p class="font-bold text-[#0F2537]">NO: {{ $submission->submission_code }}</p>
                    <p class="text-slate-500">{{ $isEn ? 'Date:' : 'Tanggal:' }} {{ optional($submission->submitted_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Participant Info Metadata Header -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-[#E5E0D5] shadow-2xs text-xs print-avoid-break">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 items-center">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#0F2537] text-[#C5A059] font-semibold text-sm flex items-center justify-center font-mono shrink-0">
                        {{ strtoupper(substr($submission->participant->name, 0, 2)) }}
                    </div>
                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[9px] block font-mono tracking-wider">{{ $isEn ? 'PARTICIPANT FULL NAME' : 'NAMA LENGKAP PESERTA' }}</span>
                        <strong class="font-bold text-[#0F2537] text-sm block leading-snug mt-0.5">{{ $submission->participant->name }}</strong>
                    </div>
                </div>

                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[9px] block font-mono tracking-wider">{{ $isEn ? 'CATEGORY & INSTITUTION' : 'KATEGORI & ASAL INSTANSI' }}</span>
                    <span class="font-semibold text-[#0F2537] text-xs block mt-0.5 leading-snug">
                        {{ $categoryText }}
                        @if($instName)
                            &mdash; <span class="text-slate-600 font-normal">{{ $instName }}</span>
                        @else
                            &mdash; <span class="text-slate-600 font-normal">{{ $isEn ? 'Verified Self-Assessment' : 'Asesmen Mandiri Terverifikasi' }}</span>
                        @endif
                    </span>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 uppercase font-semibold text-[9px] block font-mono tracking-wider">{{ $isEn ? 'REGION / DOMICILE' : 'WILAYAH ASAL (DOMISILI)' }}</span>
                        <button type="button" @click="openEditRegionModal()" class="no-print text-[10px] text-[#8C703E] hover:text-[#0F2537] font-semibold underline cursor-pointer">
                            {{ $isEn ? 'Change' : 'Ubah' }}
                        </button>
                    </div>
                    <span class="font-semibold text-[#0F2537] text-xs block mt-0.5">
                        {{ $regionText ?: ($isEn ? 'Not specified' : 'Tidak diisi') }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[9px] block font-mono tracking-wider">{{ $isEn ? 'DATE & SESSION STATUS' : 'TANGGAL & STATUS SESI' }}</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="font-semibold text-slate-700 text-xs">
                            {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }}
                        </span>
                        <span class="uppercase text-[#0F2537] bg-[#F4F1EA] px-2.5 py-0.5 rounded-md text-[10px] font-mono font-semibold border border-[#E5E0D5]">
                            {{ $submission->submission_type }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- UNIFIED DUAL-PILLAR EXECUTIVE DASHBOARD (CALM EDITORIAL CARDS) -->
        <div class="grid grid-cols-1 {{ $hasWho5 ? 'lg:grid-cols-2' : '' }} gap-6 items-stretch print-avoid-break">
            
            <!-- CARD 1: PILAR 1 - KECERDASAN RUHIOLOGI (RQI-15) -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-2xs border border-[#E5E0D5] border-t-4 border-t-[#0F2537] flex flex-col justify-between space-y-5">
                <div class="space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="text-[10px] font-mono font-semibold text-[#8C703E] uppercase tracking-[0.14em]">
                            {{ $isEn ? 'PILLAR 01 · RUHIOLOGY QUOTIENT (RQI-15)' : 'PILAR 01 · KECERDASAN RUHIOLOGI (RQI-15)' }}
                        </span>
                        <span class="text-[10px] font-mono text-slate-400 font-medium">{{ $isEn ? 'SCALE 15 – 75 PTS' : 'SKALA 15 – 75 POIN' }}</span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6">
                        <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-[#EFECE6]" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="text-[#0F2537]" stroke-dasharray="{{ min(100, $scorePct) }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <div class="absolute flex flex-col items-center justify-center text-center">
                                <span class="font-mono font-bold text-sm text-[#0F2537] leading-none">{{ $scorePct }}%</span>
                                <span class="text-[8px] font-mono uppercase text-slate-400 mt-0.5">{{ $isEn ? 'SCORE' : 'CAPAIAN' }}</span>
                            </div>
                        </div>

                        <div class="text-center sm:text-left space-y-1.5 flex-1">
                            <span class="text-[10px] font-mono font-semibold text-slate-400 uppercase tracking-wider block">{{ $isEn ? 'TOTAL RQI-15 SCORE' : 'SKOR TOTAL RQI-15' }}</span>
                            <div class="text-3xl sm:text-4xl font-bold font-serif text-[#0F2537] tracking-tight flex items-baseline justify-center sm:justify-start gap-1.5">
                                <span>{{ $rqiScore }}</span>
                                <span class="text-sm text-slate-400 font-normal font-sans">/ {{ $maxRqiScore }} {{ $isEn ? 'Pts' : 'Poin' }}</span>
                            </div>
                            <div class="pt-0.5">
                                <span class="inline-block px-3 py-1 rounded-lg text-[11px] font-semibold bg-[#F4F1EA] text-[#0F2537] border border-[#DFD8C8]">
                                    {{ $catName }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1">
                        <span class="text-[10px] font-mono font-semibold text-slate-400 uppercase tracking-wider block">{{ $isEn ? 'PROFILE SUMMARY & INNER REFLECTION' : 'GAMBARAN PROFIL & REFLEKSI BATINIAH' }}</span>
                        <div class="bg-[#FAF9F6] p-4 rounded-xl border border-[#EFECE6]">
                            <p class="text-xs italic text-slate-700 leading-relaxed font-normal">
                                "{{ $submission->result->overall_interpretation }}"
                            </p>
                        </div>
                    </div>
                </div>

                @if($submission->result->pre_post_diff !== null)
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">{{ $isEn ? 'RQI Delta Progress:' : 'Perkembangan Delta RQI:' }}</span>
                        <span class="font-semibold font-mono px-2.5 py-0.5 rounded-md text-xs bg-slate-100 text-[#0F2537]">
                            {{ $submission->result->pre_post_diff >= 0 ? '+' : '' }}{{ number_format($submission->result->pre_post_diff, 1) }} {{ $isEn ? 'pts' : 'poin' }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- CARD 2: PILAR 2 - SKRINING KESEJAHTERAAN MENTAL (WHO-5) -->
            @if($hasWho5)
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-2xs border border-[#E5E0D5] border-t-4 border-t-[#1E4E44] flex flex-col justify-between space-y-5">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-[10px] font-mono font-semibold text-[#1E4E44] uppercase tracking-[0.14em]">
                                {{ $isEn ? 'PILLAR 02 · MENTAL WELL-BEING (WHO-5)' : 'PILAR 02 · KESEJAHTERAAN MENTAL (WHO-5)' }}
                            </span>
                            <span class="text-[10px] font-mono text-slate-400 font-medium">{{ $isEn ? 'SCALE 0 – 25 PTS' : 'SKALA 0 – 25 POIN' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6">
                            <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-[#EFECE6]" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-[#1E4E44]" stroke-dasharray="{{ min(100, $who5Pct) }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute flex flex-col items-center justify-center text-center">
                                    <span class="font-mono font-bold text-sm text-[#1E4E44] leading-none">{{ number_format($who5Pct, 0) }}%</span>
                                    <span class="text-[8px] font-mono uppercase text-slate-400 mt-0.5">{{ $isEn ? 'INDEX' : 'INDEKS' }}</span>
                                </div>
                            </div>

                            <div class="text-center sm:text-left space-y-1.5 flex-1">
                                <span class="text-[10px] font-mono font-semibold text-slate-400 uppercase tracking-wider block">{{ $isEn ? 'RAW WHO-5 SCORE' : 'SKOR MENTAH WHO-5' }}</span>
                                <div class="text-3xl sm:text-4xl font-bold font-serif text-[#1E4E44] tracking-tight flex items-baseline justify-center sm:justify-start gap-1.5">
                                    <span>{{ $who5Raw }}</span>
                                    <span class="text-sm text-slate-400 font-normal font-sans">/ 25 {{ $isEn ? 'Pts' : 'Poin' }} ({{ number_format($who5Pct, 0) }}%)</span>
                                </div>
                                <div class="pt-0.5">
                                    <span class="inline-block px-3 py-1 rounded-lg text-[11px] font-semibold bg-[#F0F5F3] text-[#1E4E44] border border-[#CFE0DA]">
                                        {{ $who5LevelName }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5 pt-1">
                            <span class="text-[10px] font-mono font-semibold text-slate-400 uppercase tracking-wider block">{{ $isEn ? 'EMOTIONAL WELL-BEING DESCRIPTION' : 'DESKRIPSI KESEJAHTERAAN EMOSIONAL' }}</span>
                            <div class="bg-[#FAF9F6] p-4 rounded-xl border border-[#EFECE6]">
                                <p class="text-xs text-slate-700 leading-relaxed font-normal">
                                    "{{ $who5ScreeningNote }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 text-[10px] text-slate-400 italic leading-snug">
                        {{ $isEn ? '*World Health Organization-5 (WHO-5) Well-Being Index: standardized psychometric scale for assessing emotional vitality.' : '*World Health Organization-5 (WHO-5) Well-Being Index: instrumen psikometrik terstandar untuk pemetaan vitalitas emosional.' }}
                    </div>
                </div>
            @endif

        </div>

        <!-- PRE VS POST COMPARATIVE PROGRESS CARD -->
        @if(isset($preSubmission) && isset($postSubmission))
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#E5E0D5] shadow-2xs space-y-6 print-avoid-break">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
                    <div>
                        <span class="text-[10px] font-mono font-semibold text-[#1E4E44] uppercase tracking-widest block">{{ $isEn ? 'PRE VS POST LONGITUDINAL COMPARISON' : 'PERBANDINGAN SIKLUS PRE VS POST' }}</span>
                        <h3 class="text-lg sm:text-xl font-serif font-bold text-[#0F2537]">{{ $isEn ? 'Development & Self-Evaluation Results' : 'Hasil Perkembangan & Evaluasi Diri' }}</h3>
                    </div>
                    <span class="px-3 py-1 bg-[#F0F5F3] text-[#1E4E44] text-xs font-semibold rounded-lg border border-[#CFE0DA]">{{ $isEn ? 'Longitudinal Evaluation Matrix' : 'Matrik Evaluasi Longitudinal' }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                    <div class="p-5 bg-[#FAF9F6] rounded-xl border border-[#EFECE6] text-center space-y-1">
                        <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">PRETEST</span>
                        <div class="text-3xl font-serif font-bold text-[#0F2537]">{{ number_format($preSubmission->result->rqi_score ?? 0, 0) }} / 75</div>
                        <span class="text-xs font-medium text-slate-600 block">{{ $preSubmission->result->category_name ?? '' }}</span>
                    </div>

                    <div class="p-5 bg-[#F0F5F3] rounded-xl border border-[#CFE0DA] text-center space-y-1">
                        <span class="text-xs text-[#1E4E44] font-semibold uppercase tracking-wider block">POSTTEST</span>
                        <div class="text-3xl font-serif font-bold text-[#1E4E44]">{{ number_format($postSubmission->result->rqi_score ?? 0, 0) }} / 75</div>
                        <span class="text-xs font-semibold text-[#1E4E44] block">{{ $postSubmission->result->category_name ?? '' }}</span>
                    </div>

                    <div class="p-5 bg-[#F4F1EA] rounded-xl border border-[#DFD8C8] text-center space-y-1 flex flex-col justify-center">
                        <span class="text-xs text-[#8C703E] font-semibold uppercase tracking-wider block">{{ $isEn ? 'SCORE CHANGE' : 'PERUBAHAN SKOR' }}</span>
                        @php
                            $diff = ($postSubmission->result->rqi_score ?? 0) - ($preSubmission->result->rqi_score ?? 0);
                        @endphp
                        <div class="text-3xl font-serif font-bold text-[#0F2537]">
                            {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 0) }} {{ $isEn ? 'Pts' : 'Poin' }}
                        </div>
                        <span class="text-[11px] text-slate-600">{{ $isEn ? 'Baseline vs final cycle comparison' : 'Perbandingan capaian awal dan evaluasi akhir' }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISUAL RADAR CHART 6 DIMENSI (RQI-15 & WHO-5) -->
        <div class="bg-[#0F2537] text-white p-6 sm:p-8 rounded-2xl border border-slate-800 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 items-center print-avoid-break">
            <div class="lg:col-span-5 bg-white/5 p-5 rounded-xl border border-white/10">
                <h4 class="font-serif font-bold text-sm text-[#C5A059] mb-1">
                    {{ $isEn ? '6-Dimensional Balance Radar (RQI & WHO-5)' : 'Grafik Keseimbangan 6 Dimensi (RQI & WHO-5)' }}
                </h4>
                <p class="text-[11px] text-slate-300 mb-3">{{ $isEn ? 'Proportional mapping across 5 Ruhiology intelligence pillars & the WHO-5 mental well-being index.' : 'Pemetaan proporsional 5 pilar kesadaran ruhiologi & indeks kesejahteraan mental WHO-5.' }}</p>
                <div class="relative w-full aspect-square max-w-xs mx-auto">
                    <canvas id="resultRadarChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-7 space-y-3">
                <span class="text-[10px] font-mono font-semibold text-[#C5A059] uppercase tracking-[0.16em] block">
                    {{ $isEn ? '6-DIMENSION ASSESSMENT METRIC PROFILE' : 'PROFIL METRIK 6 DIMENSI ASESMEN (RQI-15 & WHO-5)' }}
                </span>
                <h3 class="text-xl sm:text-2xl font-serif font-bold text-white leading-snug">
                    {{ $isEn ? 'Mapping the Balance of Soul Potential & Mental Vitality' : 'Pemetaan Keseimbangan Potensi Ruh & Vitalitas Mental' }}
                </h3>
                <p class="text-xs text-slate-300 leading-relaxed font-normal">
                    {{ $isEn ? 'The six dimensions represent a holistic synthesis of spiritual consciousness (self-awareness, divine recognition, devotional practice, noble ethics, peak awareness) and daily emotional flourishing (WHO-5 Index).' : 'Keenam dimensi merepresentasikan kesatuan utuh antara kesadaran ruhiologi (pengenalan diri, ketuhanan, ketaatan ibadah, akhlak karimah, kesadaran puncak) dan kesejahteraan psikologis harian (WHO-5 Index).' }}
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                    @foreach($allSixDimensions as $dRes)
                        <div class="p-3 rounded-xl border space-y-1.5 {{ !empty($dRes->is_who5) ? 'bg-[#1E4E44]/35 border-[#3E8E7E]/40' : 'bg-white/5 border-white/10' }}">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-slate-100 truncate">{{ $dRes->name }}</span>
                                <span class="font-mono font-bold {{ !empty($dRes->is_who5) ? 'text-[#7CC4B5]' : 'text-[#C5A059]' }} shrink-0 ml-2">{{ number_format($dRes->percentage, 1) }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full {{ !empty($dRes->is_who5) ? 'bg-[#7CC4B5]' : 'bg-[#C5A059]' }} rounded-full" style="width: {{ min(100, $dRes->percentage) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 6 DIMENSIONS BREAKDOWN TABLE (INCLUDING WHO-5) -->
        <div class="bg-white rounded-2xl border border-[#E5E0D5] shadow-2xs overflow-hidden print-avoid-break">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-wrap justify-between items-center gap-2">
                <div>
                    <h3 class="font-serif font-bold text-[#0F2537] text-base sm:text-lg">{{ $isEn ? '6 Dimensions Breakdown (RQI-15 & WHO-5 Index)' : 'Rincian Analisis 6 Dimensi (RQI-15 & Indeks WHO-5)' }}</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $isEn ? 'Raw score evaluation, percentage attainment, and qualitative interpretation for RQI and WHO-5.' : 'Evaluasi skor mentah, persentase capaian, dan interpretasi kualitatif untuk 5 dimensi RQI serta dimensi ke-6 WHO-5.' }}</p>
                </div>
                <span class="text-[10px] sm:text-xs font-mono font-semibold text-[#8C703E] uppercase bg-[#F4F1EA] px-3 py-1 rounded-md border border-[#DFD8C8]">{{ $isEn ? '6 Unified Dimensions' : '6 Dimensi Terpadu' }}</span>
            </div>

            <!-- Desktop View: Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF9F6] text-slate-500 font-semibold uppercase tracking-wider border-b border-[#EFECE6]">
                        <tr>
                            <th class="px-6 py-3.5">{{ $isEn ? 'Code' : 'Kode' }}</th>
                            <th class="px-6 py-3.5">{{ $isEn ? 'Dimension & Well-Being Domain' : 'Dimensi Kecerdasan Ruhiologi & Kesejahteraan' }}</th>
                            <th class="px-6 py-3.5">{{ $isEn ? 'Raw Score' : 'Skor Mentah' }}</th>
                            <th class="px-6 py-3.5">{{ $isEn ? 'Attainment (%)' : 'Capaian (%)' }}</th>
                            <th class="px-6 py-3.5">{{ $isEn ? 'Qualitative Interpretation' : 'Interpretasi Kualitatif' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($allSixDimensions as $dimRes)
                            <tr class="hover:bg-[#FAF9F6] {{ !empty($dimRes->is_who5) ? 'bg-[#F6F9F8]' : '' }}">
                                <td class="px-6 py-4 font-mono font-semibold {{ !empty($dimRes->is_who5) ? 'text-[#1E4E44]' : 'text-[#8C703E]' }}">
                                    {{ $dimRes->code }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-xs {{ !empty($dimRes->is_who5) ? 'text-[#1E4E44]' : 'text-[#0F2537]' }}">
                                    {{ $dimRes->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-mono font-semibold">
                                    {{ number_format($dimRes->score, 2) }} / {{ number_format($dimRes->max_score, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <span class="font-bold {{ !empty($dimRes->is_who5) ? 'text-[#1E4E44]' : 'text-[#0F2537]' }} font-mono w-12">{{ number_format($dimRes->percentage, 1) }}%</span>
                                        <div class="w-24 bg-[#EFECE6] h-1.5 rounded-full overflow-hidden">
                                            <div class="{{ !empty($dimRes->is_who5) ? 'bg-[#1E4E44]' : 'bg-[#B5945B]' }} h-1.5 rounded-full" style="width: {{ min(100, $dimRes->percentage) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 leading-relaxed font-normal">{{ $dimRes->interpretation }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Vertical Responsive Cards -->
            <div class="block md:hidden p-4 space-y-3">
                @foreach($allSixDimensions as $dimRes)
                    <div class="p-4 rounded-xl border space-y-2 {{ !empty($dimRes->is_who5) ? 'bg-[#F6F9F8] border-[#CFE0DA]' : 'bg-[#FAF9F6] border-[#EFECE6]' }}">
                        <div class="flex justify-between items-center border-b border-slate-200/60 pb-2">
                            <span class="font-mono font-semibold text-xs {{ !empty($dimRes->is_who5) ? 'text-[#1E4E44]' : 'text-[#8C703E]' }}">{{ $dimRes->code }}</span>
                            <span class="font-semibold text-xs text-[#0F2537] font-mono">{{ number_format($dimRes->score, 2) }} / {{ number_format($dimRes->max_score, 2) }} {{ $isEn ? 'Pts' : 'Poin' }}</span>
                        </div>
                        <h4 class="font-semibold text-sm {{ !empty($dimRes->is_who5) ? 'text-[#1E4E44]' : 'text-[#0F2537]' }}">{{ $dimRes->name }}</h4>
                        
                        <div class="space-y-1 pt-1">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-500">{{ $isEn ? 'Attainment:' : 'Capaian:' }}</span>
                                <span class="{{ !empty($dimRes->is_who5) ? 'text-[#1E4E44]' : 'text-[#0F2537]' }}">{{ number_format($dimRes->percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-[#EFECE6] h-1.5 rounded-full overflow-hidden">
                                <div class="{{ !empty($dimRes->is_who5) ? 'bg-[#1E4E44]' : 'bg-[#B5945B]' }} h-1.5 rounded-full" style="width: {{ min(100, $dimRes->percentage) }}%"></div>
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
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#E5E0D5] shadow-2xs space-y-4 no-print">
            <div>
                <span class="text-[10px] font-mono font-semibold text-[#8C703E] uppercase tracking-widest block">{{ $isEn ? 'INNER REFLECTION COMMITMENT' : 'KOMITMEN REFLEKSI BATIN' }}</span>
                <h3 class="text-base sm:text-lg font-serif font-bold text-[#0F2537]">{{ $isEn ? 'Your Personal Growth & Spiritual Pledge' : 'Tekad Perubahan Batin Anda' }}</h3>
                <p class="text-xs text-slate-600 font-normal mt-1">
                    {{ $isEn ? '"Write down one personal pledge or inner commitment you wish to realize so your study, career, and life feel more meaningful and peaceful."' : '"Tuliskan satu tekad perubahan batin yang paling ingin Anda wujudkan agar studi, karier, dan hidup Anda terasa lebih bermakna dan damai."' }}
                </p>
            </div>

            <form action="{{ route('assessment.reflection', ['submission_code' => $submission->submission_code]) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="reflection_text" rows="3" required placeholder="{{ $isEn ? 'Write your personal inner pledge here...' : 'Tuliskan tekad perubahan batin Anda di sini...' }}" class="w-full p-4 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-[#0F2537] focus:border-[#0F2537] outline-none transition">{{ old('reflection_text', $submission->result->reflection_text ?? '') }}</textarea>
                <div class="flex justify-end">
                    <button type="submit" class="w-full sm:w-auto py-3 px-6 bg-[#0F2537] hover:bg-[#183650] text-white font-semibold text-xs rounded-xl transition-colors cursor-pointer min-h-[42px]">
                        {{ $isEn ? 'Save Personal Pledge →' : 'Simpan Tekad Perubahan Batin →' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- CONSULTATION CTA FOOTER -->
        <div class="bg-[#0F2537] p-6 sm:p-8 rounded-2xl text-white border border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-6 no-print text-center sm:text-left">
            <div class="space-y-1">
                <h3 class="text-lg sm:text-xl font-serif font-bold text-white">{{ $isEn ? 'Seeking Deeper Guidance & Consultation?' : 'Ingin Diskusi / Pendampingan Lebih Lanjut?' }}</h3>
                <p class="text-xs text-slate-300 font-normal">{{ $isEn ? 'Discuss your RQ profile and spiritual resilience with certified Ruhiology Institute counselors.' : 'Konsultasikan hasil profil RQ Anda bersama para pakar & konselor berpengalaman Ruhiology Institute.' }}</p>
            </div>
            <a href="{{ route('consultation.index') }}" class="w-full sm:w-auto px-7 py-3.5 bg-[#B5945B] hover:bg-[#A3834B] text-white font-semibold rounded-xl text-xs uppercase tracking-wider transition whitespace-nowrap inline-block text-center min-h-[42px]">
                {{ $isEn ? 'Consult with Experts →' : 'Konsultasi Dengan Pakar →' }}
            </a>
        </div>

    </div>

    <!-- INSTAGRAM / WHATSAPP CARD SHARE MODAL (CLEAN EDITORIAL 60-30-10 DESIGN) -->
    <div x-show="showStoryModal" x-cloak class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[9999] flex items-center justify-center p-3 sm:p-6 animate-fadeIn no-print overflow-y-auto">
        <div @click.away="showStoryModal = false" class="bg-slate-900 rounded-2xl max-w-4xl w-full p-5 sm:p-7 shadow-2xl border border-slate-800 space-y-5 my-auto">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div>
                    <span class="text-[10px] font-mono font-semibold text-[#C5A059] uppercase tracking-widest block">SOCIAL MEDIA SHARE CARD</span>
                    <h3 class="text-base sm:text-lg font-serif font-bold text-white">Kartu Visual Hasil Asesmen (Instagram & WhatsApp)</h3>
                </div>
                <button @click="showStoryModal = false" type="button" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition cursor-pointer">✕</button>
            </div>

            <!-- 2-Column Split Body Grid -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                
                <!-- Left Column (5 Cols): Card Canvas Preview -->
                <div class="md:col-span-5 bg-slate-950 p-4 rounded-2xl border border-slate-800 flex flex-col items-center justify-center space-y-2.5">
                    <canvas id="storyCanvas"
                            :class="cardRatio === '9:16' ? 'aspect-[9/16] max-w-[230px]' : 'aspect-[4/5] max-w-[275px]'"
                            class="w-full rounded-xl shadow-xl border border-slate-800 transition-all duration-200"></canvas>
                    <span class="text-[10px] font-mono text-slate-400" x-text="cardRatio === '9:16' ? '1080 × 1920 px (Story 9:16)' : '1080 × 1350 px (Feed 4:5)'"></span>
                </div>

                <!-- Right Column (7 Cols): Format & Theme Selector & Actions -->
                <div class="md:col-span-7 space-y-4 text-left text-xs">
                    
                    <!-- Aspect Ratio Switcher -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider font-mono block">1. Ukuran Rasio Kartu</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" @click="setCardRatio('4:5')"
                                    :class="cardRatio === '4:5' ? 'bg-white text-slate-900 font-semibold border-white' : 'bg-slate-800/70 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="p-2.5 rounded-xl border text-left transition cursor-pointer">
                                <span class="block text-xs font-semibold">Feed Portrait (4:5)</span>
                                <span class="block text-[10px] opacity-75">1080 × 1350 px · Postingan IG</span>
                            </button>
                            <button type="button" @click="setCardRatio('9:16')"
                                    :class="cardRatio === '9:16' ? 'bg-white text-slate-900 font-semibold border-white' : 'bg-slate-800/70 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="p-2.5 rounded-xl border text-left transition cursor-pointer">
                                <span class="block text-xs font-semibold">Story Fullscreen (9:16)</span>
                                <span class="block text-[10px] opacity-75">1080 × 1920 px · IG Story / WA Status</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card Theme Switcher (Clean Editorial Light & Dark) -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider font-mono block">2. Tema Warna Editorial</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" @click="setCardTheme('ivory')"
                                    :class="cardTheme === 'ivory' ? 'bg-[#FCFBF9] text-[#0F2537] font-semibold border-[#B5945B]' : 'bg-slate-800/70 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="py-2.5 px-3 rounded-xl border text-left transition cursor-pointer">
                                <span class="block text-xs font-semibold">Classic Ivory (Terang)</span>
                                <span class="block text-[10px] opacity-75">Kertas Alabaster & Oxford Navy</span>
                            </button>
                            <button type="button" @click="setCardTheme('navy')"
                                    :class="cardTheme === 'navy' ? 'bg-[#13293D] text-white font-semibold border-[#B5945B]' : 'bg-slate-800/70 text-slate-300 border-slate-700 hover:bg-slate-800'"
                                    class="py-2.5 px-3 rounded-xl border text-left transition cursor-pointer">
                                <span class="block text-xs font-semibold">Oxford Navy (Gelap)</span>
                                <span class="block text-[10px] opacity-75">Deep Slate & Champagne Gold</span>
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2 space-y-2.5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <button type="button" @click="downloadStoryImage()" class="py-3 px-4 bg-[#B5945B] hover:bg-[#A3834B] text-white font-semibold text-xs rounded-xl transition cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span x-text="'Unduh PNG (' + cardRatio + ')'"></span>
                            </button>

                            <button type="button" @click="shareStoryCard()" class="py-3 px-4 bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 font-semibold text-xs rounded-xl transition cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-[#C5A059]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                <span>Bagikan ke IG / WA</span>
                            </button>
                        </div>

                        <button type="button" @click="copySocialCaption()" class="w-full py-2.5 bg-slate-950 hover:bg-slate-800 text-slate-300 border border-slate-800 font-medium text-xs rounded-xl transition cursor-pointer flex items-center justify-center gap-2">
                            <span x-text="captionCopied ? '✓ Teks Caption Berhasil Disalin' : 'Salin Teks Caption Pendamping'"></span>
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- MODAL UBAH WILAYAH ASAL PESERTA -->
    <div x-show="showEditRegionModal" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm no-print">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0F2537] text-base">
                    Perbarui Wilayah Asal Peserta
                </h3>
                <button @click="showEditRegionModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('assessment.update_region', $submission->submission_code) }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Provinsi *</label>
                    <select name="province_id" x-model="editProvinceId" @change="onEditProvinceChange()" required class="w-full p-2.5 rounded-xl border border-slate-300 font-medium bg-white text-slate-800 focus:ring-2 focus:ring-[#0F2537] outline-none">
                        <option value="">-- Pilih Provinsi --</option>
                        <template x-for="p in provincesList" :key="p.id">
                            <option :value="p.id" x-text="p.name" :selected="String(p.id) === String(editProvinceId)"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Kabupaten / Kota *</label>
                    <select name="regency_id" x-model="editRegencyId" :disabled="!editProvinceId" required class="w-full p-2.5 rounded-xl border border-slate-300 font-medium bg-white text-slate-800 disabled:bg-slate-100 disabled:text-slate-400 focus:ring-2 focus:ring-[#0F2537] outline-none">
                        <option value="" x-text="editProvinceId ? '-- Pilih Kab/Kota --' : 'Pilih Provinsi Dahulu'"></option>
                        <template x-for="r in filteredEditRegencies" :key="r.id">
                            <option :value="r.id" x-text="r.formatted_name || ((r.name.startsWith('Kota') || r.name.startsWith('Kabupaten') || !r.type) ? r.name : (r.type + ' ' + r.name))" :selected="String(r.id) === String(editRegencyId)"></option>
                        </template>
                    </select>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showEditRegionModal = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 bg-[#0F2537] hover:bg-[#183650] text-white font-semibold rounded-xl transition cursor-pointer">
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
        cardTheme: 'ivory',
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
            const W = 1080;
            const H = isStory ? 1920 : 1350;
            canvas.width = W;
            canvas.height = H;

            /*
             * Strict 60-30-10 Editorial Palette
             */
            const palette = isLight ? {
                bg: '#FCFBF9',
                cardBg: '#FFFFFF',
                cardBorder: '#E5E0D5',
                outerBorder: '#0F2537',
                innerBorder: '#C5A870',
                primaryInk: '#0F2537',
                secondaryInk: '#475569',
                mutedInk: '#94A3B8',
                goldAccent: '#8C703E',
                goldLine: '#B5945B',
                sageAccent: '#1E4E44',
                trackBg: '#EFECE6',
                barFill: '#0F2537',
                quoteBg: '#F6F4EE'
            } : {
                bg: '#0D1F30',
                cardBg: '#13293D',
                cardBorder: '#26435E',
                outerBorder: '#1B354D',
                innerBorder: 'rgba(181, 148, 91, 0.5)',
                primaryInk: '#F8FAFC',
                secondaryInk: '#CBD5E1',
                mutedInk: '#94A3B8',
                goldAccent: '#D5BC8A',
                goldLine: '#B5945B',
                sageAccent: '#7CC4B5',
                trackBg: '#091622',
                barFill: '#C5A059',
                quoteBg: '#102336'
            };

            const cleanText = (str) => {
                if (!str) return '';
                const txt = document.createElement('textarea');
                txt.innerHTML = str;
                return txt.value;
            };

            const logoImg = new Image();
            logoImg.crossOrigin = 'anonymous';

            const render = () => {
                // 1. Calm Matte Editorial Canvas
                ctx.fillStyle = palette.bg;
                ctx.fillRect(0, 0, W, H);

                // Architectural Frame
                ctx.strokeStyle = palette.outerBorder;
                ctx.lineWidth = 12;
                ctx.strokeRect(18, 18, W - 36, H - 36);

                ctx.strokeStyle = palette.innerBorder;
                ctx.lineWidth = 1.5;
                ctx.strokeRect(36, 36, W - 72, H - 72);

                let curY = isStory ? 105 : 70;

                // 2. Top Emblem & Institute Header
                if (logoImg.complete && logoImg.naturalWidth > 0) {
                    const logoSize = isStory ? 80 : 62;
                    ctx.drawImage(logoImg, (W - logoSize) / 2, curY, logoSize, logoSize);
                    curY += logoSize + (isStory ? 34 : 24);
                } else {
                    curY += 32;
                }

                ctx.textAlign = 'center';
                ctx.fillStyle = palette.primaryInk;
                ctx.font = '700 25px "Poppins", sans-serif';
                ctx.fillText('RUHIOLOGY INSTITUTE', W / 2, curY);

                curY += 24;
                ctx.fillStyle = palette.goldAccent;
                ctx.font = '600 13.5px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('EXECUTIVE ASSESSMENT SUMMARY  ·  RQI-15 & WHO-5', W / 2, curY);

                curY += isStory ? 34 : 20;

                // 3. Participant Identity Card
                const pBoxH = isStory ? 148 : 122;
                ctx.fillStyle = palette.cardBg;
                ctx.beginPath();
                ctx.roundRect(72, curY, W - 144, pBoxH, 16);
                ctx.fill();
                ctx.strokeStyle = palette.cardBorder;
                ctx.lineWidth = 1.5;
                ctx.stroke();

                const pName = cleanText('{{ addslashes($submission->participant->name) }}');
                ctx.fillStyle = palette.primaryInk;
                ctx.font = pName.length > 28 ? '700 27px "Poppins", sans-serif' : '700 30px "Poppins", sans-serif';
                ctx.fillText(pName, W / 2, curY + (isStory ? 52 : 44));

                const catStr = cleanText('{{ addslashes($categoryText) }}');
                const instStr = cleanText('{{ addslashes($instName ?: ($isEn ? "Verified Self-Assessment" : "Asesmen Mandiri Terverifikasi")) }}');
                const regStr = cleanText('{{ addslashes($regionText ?? "") }}');
                const subLine1 = `${catStr}  |  ${instStr}`;

                ctx.fillStyle = palette.goldAccent;
                ctx.font = '600 16px "Plus Jakarta Sans", sans-serif';
                ctx.fillText(subLine1.length > 56 ? subLine1.substring(0, 54) + '...' : subLine1, W / 2, curY + (isStory ? 88 : 76));

                if (regStr) {
                    ctx.fillStyle = palette.secondaryInk;
                    ctx.font = '500 14.5px "Plus Jakarta Sans", sans-serif';
                    ctx.fillText(regStr, W / 2, curY + (isStory ? 120 : 103));
                }

                curY += pBoxH + (isStory ? 24 : 16);

                // 4. Dual Metric Score Cards (Unified Surface + Left Accent Bar)
                const cardH = isStory ? 228 : 192;
                const cardW = 454;

                // Left Card: RQI-15
                ctx.fillStyle = palette.cardBg;
                ctx.beginPath();
                ctx.roundRect(72, curY, cardW, cardH, 16);
                ctx.fill();
                ctx.strokeStyle = palette.cardBorder;
                ctx.lineWidth = 1.5;
                ctx.stroke();

                // Left Accent Strip on RQI Card
                ctx.fillStyle = palette.goldLine;
                ctx.beginPath();
                ctx.roundRect(72, curY, 8, cardH, [16, 0, 0, 16]);
                ctx.fill();

                ctx.textAlign = 'left';
                ctx.fillStyle = palette.goldAccent;
                ctx.font = '600 13.5px "Poppins", sans-serif';
                ctx.fillText('PILAR 01 · INDEKS RQI-15', 104, curY + 36);

                ctx.fillStyle = palette.primaryInk;
                ctx.font = isStory ? '700 58px "Poppins", sans-serif' : '700 50px "Poppins", sans-serif';
                ctx.fillText('{{ $rqiScore }}', 104, curY + (isStory ? 104 : 92));

                ctx.fillStyle = palette.mutedInk;
                ctx.font = '500 16.5px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('/ {{ $maxRqiScore }} Poin ({{ $scorePct }}%)', 196, curY + (isStory ? 100 : 88));

                // Progress bar inside RQI card
                ctx.fillStyle = palette.trackBg;
                ctx.beginPath();
                ctx.roundRect(104, curY + (isStory ? 128 : 114), cardW - 60, 8, 4);
                ctx.fill();

                ctx.fillStyle = palette.goldLine;
                ctx.beginPath();
                ctx.roundRect(104, curY + (isStory ? 128 : 114), Math.max(16, ((cardW - 60) * {{ min(100, $scorePct) }}) / 100), 8, 4);
                ctx.fill();

                const rqiBadgeText = cleanText('{{ addslashes($catName) }}');
                ctx.fillStyle = palette.secondaryInk;
                ctx.font = '600 14.5px "Plus Jakarta Sans", sans-serif';
                ctx.fillText(rqiBadgeText.length > 36 ? rqiBadgeText.substring(0, 34) + '...' : rqiBadgeText, 104, curY + cardH - 26);

                // Right Card: WHO-5
                ctx.fillStyle = palette.cardBg;
                ctx.beginPath();
                ctx.roundRect(554, curY, cardW, cardH, 16);
                ctx.fill();
                ctx.strokeStyle = palette.cardBorder;
                ctx.lineWidth = 1.5;
                ctx.stroke();

                // Left Accent Strip on WHO-5 Card
                ctx.fillStyle = palette.sageAccent;
                ctx.beginPath();
                ctx.roundRect(554, curY, 8, cardH, [16, 0, 0, 16]);
                ctx.fill();

                ctx.fillStyle = palette.sageAccent;
                ctx.font = '600 13.5px "Poppins", sans-serif';
                ctx.fillText('PILAR 02 · INDEKS WHO-5', 586, curY + 36);

                ctx.fillStyle = palette.primaryInk;
                ctx.font = isStory ? '700 58px "Poppins", sans-serif' : '700 50px "Poppins", sans-serif';
                ctx.fillText('{{ number_format($who5Pct, 0) }}%', 586, curY + (isStory ? 104 : 92));

                ctx.fillStyle = palette.mutedInk;
                ctx.font = '500 16.5px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('({{ $who5Raw }}/25 Poin)', 740, curY + (isStory ? 100 : 88));

                // Progress bar inside WHO-5 card
                ctx.fillStyle = palette.trackBg;
                ctx.beginPath();
                ctx.roundRect(586, curY + (isStory ? 128 : 114), cardW - 60, 8, 4);
                ctx.fill();

                ctx.fillStyle = palette.sageAccent;
                ctx.beginPath();
                ctx.roundRect(586, curY + (isStory ? 128 : 114), Math.max(16, ((cardW - 60) * {{ min(100, $who5Pct) }}) / 100), 8, 4);
                ctx.fill();

                const who5BadgeText = cleanText('{{ addslashes($who5BadgeTitleShort) }}');
                ctx.fillStyle = palette.secondaryInk;
                ctx.font = '600 14.5px "Plus Jakarta Sans", sans-serif';
                ctx.fillText(who5BadgeText, 586, curY + cardH - 26);

                curY += cardH + (isStory ? 24 : 16);

                // 5. 6-Dimension Competency Progress Bars Box (5 RQI-15 + 6th WHO-5)
                const pillars = this.pillarsData || [];
                const pSectionH = isStory ? 395 : 312;
                ctx.fillStyle = palette.cardBg;
                ctx.beginPath();
                ctx.roundRect(72, curY, W - 144, pSectionH, 16);
                ctx.fill();
                ctx.strokeStyle = palette.cardBorder;
                ctx.lineWidth = 1.5;
                ctx.stroke();

                ctx.textAlign = 'left';
                ctx.fillStyle = palette.goldAccent;
                ctx.font = '600 13.5px "Poppins", sans-serif';
                ctx.fillText('CAPAIAN 6 DIMENSI ASESMEN (RQI-15 & WHO-5)', 104, curY + 34);

                ctx.textAlign = 'right';
                ctx.fillStyle = palette.mutedInk;
                ctx.font = '500 12.5px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('SKOR & PERSENTASE 0–100%', W - 104, curY + 34);

                const rowStart = curY + (isStory ? 74 : 64);
                const rowGap = isStory ? 54 : 41;

                pillars.slice(0, 6).forEach((p, idx) => {
                    const y = rowStart + (idx * rowGap);
                    const isWho = !!p.is_who5;
                    ctx.textAlign = 'left';
                    ctx.fillStyle = isWho ? palette.sageAccent : palette.primaryInk;
                    ctx.font = '600 15.5px "Plus Jakarta Sans", sans-serif';
                    ctx.fillText(`0${idx + 1}.  ${p.name}`, 104, y);

                    // Clean Single-Tone Track & Bar
                    const barX = 485;
                    const barW = 365;
                    const barH = isStory ? 11 : 9;
                    ctx.fillStyle = palette.trackBg;
                    ctx.beginPath();
                    ctx.roundRect(barX, y - barH + 1, barW, barH, barH / 2);
                    ctx.fill();

                    const fillW = Math.max(16, Math.min(barW, (p.pct / 100) * barW));
                    ctx.fillStyle = isWho ? palette.sageAccent : palette.barFill;
                    ctx.beginPath();
                    ctx.roundRect(barX, y - barH + 1, fillW, barH, barH / 2);
                    ctx.fill();

                    ctx.textAlign = 'right';
                    ctx.fillStyle = isWho ? palette.sageAccent : palette.primaryInk;
                    ctx.font = '700 15.5px "Poppins", sans-serif';
                    ctx.fillText(`${p.pct}%`, W - 104, y + 1);
                });

                curY += pSectionH + (isStory ? 24 : 16);

                // 6. Editorial Reflection Box
                const quoteBoxH = isStory ? 245 : 152;
                ctx.fillStyle = palette.quoteBg;
                ctx.beginPath();
                ctx.roundRect(72, curY, W - 144, quoteBoxH, 16);
                ctx.fill();
                ctx.strokeStyle = palette.cardBorder;
                ctx.lineWidth = 1.5;
                ctx.stroke();

                ctx.textAlign = 'center';
                ctx.fillStyle = palette.goldAccent;
                ctx.font = '600 12.5px "Poppins", sans-serif';
                ctx.fillText('INTERPRETASI PROFIL RUHIOLOGI', W / 2, curY + 30);

                ctx.fillStyle = palette.secondaryInk;
                ctx.font = isStory ? 'italic 500 18.5px "Plus Jakarta Sans", sans-serif' : 'italic 500 15.5px "Plus Jakarta Sans", sans-serif';
                const interpText = cleanText('"{{ addslashes($submission->result->overall_interpretation) }}"');
                this.wrapText(ctx, interpText, W / 2, curY + (isStory ? 68 : 58), 860, isStory ? 30 : 24, isStory ? 4 : 3);

                curY += quoteBoxH + (isStory ? 38 : 22);

                // 7. Footer Branding & Verification
                ctx.strokeStyle = palette.cardBorder;
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                ctx.moveTo(140, curY);
                ctx.lineTo(W - 140, curY);
                ctx.stroke();

                const founderName = cleanText('{{ addslashes(\App\Models\Setting::get("founder_name", "Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.")) }}');
                ctx.fillStyle = palette.primaryInk;
                ctx.font = '700 17.5px "Poppins", sans-serif';
                ctx.fillText(founderName, W / 2, curY + (isStory ? 36 : 28));

                ctx.fillStyle = palette.goldAccent;
                ctx.font = '500 13.5px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('Founder Ruhiology Institute  ·  ruhiology-institute.site', W / 2, curY + (isStory ? 60 : 48));

                ctx.fillStyle = palette.mutedInk;
                ctx.font = '500 12.5px monospace';
                ctx.fillText('DOC ID: {{ $submission->submission_code }}', W / 2, curY + (isStory ? 86 : 70));
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
            const caption = `Saya telah menyelesaikan Asesmen Kesadaran Ruhiologi (RQI-15 & WHO-5) di Ruhiology Institute.\n\nSkor RQI-15: {{ $rqiScore }} / {{ $maxRqiScore }} Poin ({{ $catName }})\nIndeks Kesejahteraan WHO-5: {{ number_format($who5Pct, 0) }}%\nKode Verifikasi: {{ $submission->submission_code }}\n\nPortal Asesmen: {{ url('/assessment') }}`;

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
                            // User cancelled
                        }
                    }
                    this.downloadStoryImage();
                }, 'image/png');
            } else {
                this.downloadStoryImage();
            }
        },

        copySocialCaption() {
            const caption = `Saya telah menyelesaikan Asesmen Kesadaran Ruhiologi (RQI-15 & WHO-5) di Ruhiology Institute.\n\nSkor RQI-15: {{ $rqiScore }} / {{ $maxRqiScore }} Poin ({{ $catName }})\nIndeks Kesejahteraan WHO-5: {{ number_format($who5Pct, 0) }}%\nKode Verifikasi: {{ $submission->submission_code }}\n\nPortal Asesmen: {{ url('/assessment') }}`;
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
            foreach($allSixDimensions as $dr) {
                $rawL = trim($dr->name);
                if (!empty($dr->is_who5)) {
                    $cleanLabel = 'Kesejahteraan WHO-5';
                } else {
                    $cleanLabel = preg_replace('/^\d+\.\s*/', '', $rawL);
                    $cleanLabel = preg_replace('/\s*\(.*?\)/', '', $cleanLabel);
                }
                $dimLabels[] = \Illuminate\Support\Str::limit($cleanLabel, 20);
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
                    backgroundColor: 'rgba(181, 148, 91, 0.25)',
                    borderColor: '#C5A059',
                    borderWidth: 2,
                    pointBackgroundColor: '#C5A059',
                    pointBorderColor: '#fff',
                    pointRadius: 3.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255, 255, 255, 0.15)' },
                        grid: { color: 'rgba(255, 255, 255, 0.15)' },
                        pointLabels: {
                            color: '#e2e8f0',
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
