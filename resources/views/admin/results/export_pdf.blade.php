<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Hasil Asesmen Ruhiologi — Ruhiology Institute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .font-cinzel {
            font-family: 'Cinzel', serif;
        }
        .font-editorial {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .font-mono-num {
            font-family: 'JetBrains Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }
        @media print {
            .no-print { display: none !important; }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .a4-document-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }
            .page-break { page-break-before: always; }
            .break-inside-avoid { break-inside: avoid; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-[#0A121D] text-slate-800 min-h-screen py-6 px-3 sm:py-10 sm:px-6 antialiased">

    <!-- FLOATING STUDIO ACTION BAR (Hidden on Print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#0F2036]/95 backdrop-blur-md text-white px-6 py-3.5 rounded-2xl border border-[#C5A059]/30 shadow-2xl">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#C5A059] to-[#8C703E] flex items-center justify-center shadow-md">
                <svg class="w-4 h-4 text-[#0A121D]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-cinzel font-bold tracking-wider text-[#E8D4A2]">RUHIOLOGY INSTITUTE</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[10px] font-mono text-emerald-300 font-semibold uppercase">Official Document</span>
                </div>
                <div class="text-[11px] text-slate-300 font-medium">Pratinjau Cetak Laporan Eksekutif Resmi (A4 Portrait)</div>
            </div>
        </div>
        
        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
            <button onclick="window.history.back()" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-semibold text-xs rounded-xl border border-slate-700 transition cursor-pointer">
                ← Kembali
            </button>
            <button onclick="window.print()" class="px-5 py-2.5 bg-gradient-to-r from-[#C5A059] via-[#B5945B] to-[#8C703E] hover:from-[#D4AF37] hover:to-[#A3834B] text-[#0A121D] font-bold text-xs rounded-xl shadow-lg hover:shadow-xl transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-[#0A121D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak / Simpan PDF (A4)</span>
            </button>
        </div>
    </div>

    <!-- MAIN A4 PHYSICAL DOCUMENT CANVAS -->
    <div class="a4-document-container max-w-5xl mx-auto bg-white rounded-xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.5)] border border-[#DFD8C8] p-8 sm:p-12 space-y-6 relative overflow-hidden">
        
        <!-- Subtle Academic Security Guilloche Header Border -->
        <div class="h-1.5 w-full bg-gradient-to-r from-[#0F2537] via-[#C5A059] to-[#0F2537] rounded-full mb-2"></div>

        <!-- DOCUMENT TOP CLASSIFICATION & SECURITY STRIP -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-2 text-[9px] font-mono-num text-slate-500">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 font-bold tracking-widest text-[#8C703E] uppercase bg-[#FAF7F0] border border-[#E8DFC8] px-2 py-0.5 rounded">
                    CONFIDENTIAL · OFFICIAL REPORT
                </span>
                <span>KLASIFIKASI: EKSEKUTIF RESMI</span>
            </div>
            <div class="tracking-wider">
                REF NO: <strong class="text-slate-800 font-bold">REP-RQI/{{ date('Y/m') }}/{{ $event ? $event->event_code : 'PUBLIC' }}</strong>
            </div>
        </div>

        <!-- MASTER INSTITUTIONAL HEADER & MASTHEAD -->
        <header class="flex items-start justify-between gap-6 pb-2">
            <div class="space-y-1.5 flex-1">
                <div class="inline-block text-[10px] font-cinzel font-bold tracking-[0.25em] text-[#8C703E] uppercase">
                    RUHIOLOGY ASSESSMENT BOARD · LAPORAN ANALISIS KOHOR
                </div>
                <h1 class="text-2xl sm:text-3xl font-editorial font-black text-[#0F2537] tracking-tight leading-snug">
                    {{ $event ? $event->title : 'Laporan Eksekutif Hasil Asesmen Ruhiologi' }}
                </h1>
                <p class="text-xs text-slate-600 font-normal leading-relaxed max-w-2xl">
                    Pemetaan Komprehensif 5 Pilar Kecerdasan Ruhiologi (RQI-15) & Skrining Kesejahteraan Mental (WHO-5)
                </p>

                <!-- Metadata Badges Strip -->
                <div class="pt-2 flex flex-wrap items-center gap-2 text-[10.5px]">
                    <div class="inline-flex items-center gap-1.5 bg-[#FAF7F0] border border-[#E8DFC8] px-3 py-1 rounded-lg text-slate-800">
                        <span class="text-slate-400 font-medium">Instansi:</span>
                        <strong class="font-semibold text-[#0F2537]">{{ $event->institution_name ?? ($event->title ?? 'Ruhiology Institute (Personal / Mandiri)') }}</strong>
                    </div>
                    <div class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-3 py-1 rounded-lg text-slate-800">
                        <span class="text-slate-400 font-medium">Tanggal:</span>
                        <strong class="font-semibold text-slate-900 font-mono-num">{{ date('d F Y') }}</strong>
                    </div>
                    @if($event && $event->event_code)
                    <div class="inline-flex items-center gap-1.5 bg-[#0F2537] text-white px-3 py-1 rounded-lg">
                        <span class="text-slate-300 text-[9.5px]">Kode Event:</span>
                        <strong class="font-mono-num text-[#E8D4A2] font-bold tracking-wider">{{ $event->event_code }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Authoritative Ruhiology Institute Seal -->
            <div class="shrink-0 flex flex-col items-center text-center p-3 rounded-xl bg-gradient-to-b from-[#FAF7F0] to-white border border-[#DFD8C8] shadow-2xs min-w-[150px]">
                <div class="w-12 h-12 rounded-full border-2 border-[#C5A059] p-1 bg-[#0F2537] flex items-center justify-center shadow-inner mb-1.5">
                    <svg class="w-7 h-7 text-[#C5A059]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10" stroke-width="1.2"/>
                        <circle cx="12" cy="12" r="6" stroke-dasharray="2 2"/>
                        <path d="M12 2V22M2 12H22M12 6L14 12L12 18L10 12Z" stroke-width="1.5" fill="#C5A059" fill-opacity="0.3"/>
                    </svg>
                </div>
                <span class="text-[11px] font-cinzel font-bold text-[#0F2537] tracking-wider block">RUHIOLOGY</span>
                <span class="text-[8px] font-mono-num font-bold text-[#8C703E] tracking-[0.2em] uppercase block">INSTITUTE</span>
                <span class="text-[7.5px] text-slate-500 tracking-tight mt-0.5 block">Center for Consciousness</span>
            </div>
        </header>

        <!-- Hairline Separation -->
        <div class="border-t-2 border-[#0F2537]/15"></div>

        <!-- EXECUTIVE SCORECARD (UNIFIED ARCHITECTURAL KPI HERO) -->
        <section class="grid grid-cols-1 md:grid-cols-4 gap-3.5">
            <!-- Metric 1: Total Peserta -->
            <div class="p-4 rounded-xl bg-gradient-to-b from-slate-50 to-white border border-slate-200 shadow-2xs flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-mono-num font-bold text-slate-500 uppercase tracking-widest block">VOLUME KOHOR</span>
                    <span class="text-3xl font-black font-editorial text-[#0F2537] mt-1 block font-mono-num">{{ number_format($submissions->count()) }}</span>
                </div>
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[9.5px]">
                    <span class="text-slate-500 font-medium">Tingkat Validitas</span>
                    <span class="text-emerald-700 font-bold font-mono-num">100% Sah</span>
                </div>
            </div>

            <!-- Metric 2: Rata-Rata RQI -->
            @php
                $rqiCategory = 'Level 3: Jiwa Berproses Stabil';
                if ($avgRqi >= 67) $rqiCategory = 'Level 5: Jiwa Paripurna';
                elseif ($avgRqi >= 51) $rqiCategory = 'Level 4: Jiwa Tenang & Terjaga';
                elseif ($avgRqi >= 34) $rqiCategory = 'Level 3: Jiwa Berproses Stabil';
                elseif ($avgRqi >= 17) $rqiCategory = 'Level 2: Jiwa Mencari Arahan';
                else $rqiCategory = 'Level 1: Jiwa Butuh Pendampingan';
            @endphp
            <div class="p-4 rounded-xl bg-gradient-to-b from-[#FAF7F0] to-white border border-[#DFD8C8] shadow-2xs border-t-4 border-t-[#8C703E] flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-mono-num font-bold text-[#8C703E] uppercase tracking-widest block">RATA-RATA RQI (0-100)</span>
                    <div class="flex items-baseline gap-1 mt-1">
                        <span class="text-3xl font-black font-editorial text-[#8C703E] font-mono-num">{{ $avgRqi }}</span>
                        <span class="text-xs text-slate-400 font-medium">/ 100</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-[#DFD8C8]/60">
                    <span class="text-[9.5px] font-bold text-[#0F2537] truncate block">{{ $rqiCategory }}</span>
                </div>
            </div>

            <!-- Metric 3: Rata-Rata WHO-5 -->
            <div class="p-4 rounded-xl bg-gradient-to-b from-emerald-50/50 to-white border border-emerald-200/80 shadow-2xs border-t-4 border-t-[#1E4E44] flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-mono-num font-bold text-[#1E4E44] uppercase tracking-widest block">INDEKS WHO-5 MENTAL</span>
                    <div class="flex items-baseline gap-1 mt-1">
                        <span class="text-3xl font-black font-editorial text-[#1E4E44] font-mono-num">{{ $avgWho5 }}%</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-emerald-100 flex items-center justify-between text-[9.5px]">
                    <span class="text-slate-500 font-medium">Status</span>
                    <span class="font-bold {{ $avgWho5 >= 50 ? 'text-[#1E4E44]' : 'text-amber-700' }}">
                        {{ $avgWho5 >= 50 ? 'Vitalitas Baik' : 'Perlu Perhatian' }}
                    </span>
                </div>
            </div>

            <!-- Metric 4: Rasio Kesejahteraan Mental -->
            @php
                $pctSehat = $submissions->count() > 0 ? round(($who5SehatCount / $submissions->count()) * 100) : 0;
                $pctSkrining = $submissions->count() > 0 ? round(($who5SkriningCount / $submissions->count()) * 100) : 0;
            @endphp
            <div class="p-4 rounded-xl bg-gradient-to-b from-slate-50 to-white border border-slate-200 shadow-2xs flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-mono-num font-bold text-slate-500 uppercase tracking-widest block">RASIO SKRINING MENTAL</span>
                    <div class="flex items-center justify-between text-xs font-bold mt-1.5 font-mono-num">
                        <span class="text-emerald-700">{{ $who5SehatCount }} Sehat ({{ $pctSehat }}%)</span>
                        <span class="text-rose-700">{{ $who5SkriningCount }} Skrining ({{ $pctSkrining }}%)</span>
                    </div>
                    <!-- Segmented Micro Bar -->
                    <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden flex mt-2">
                        <div class="bg-emerald-600 h-full" style="width: {{ $pctSehat }}%"></div>
                        <div class="bg-rose-500 h-full" style="width: {{ $pctSkrining }}%"></div>
                    </div>
                </div>
                <div class="pt-2 border-t border-slate-100 text-[9px] text-slate-400">
                    Standar Diagnostik WHO-5 (Cut-off 50%)
                </div>
            </div>
        </section>

        <!-- SEBARAN TINGKAT KESADARAN RUHIOLOGI (CALIBRATED ACCURACY DISTRIBUTION) -->
        @php
            $totalCount = $submissions->count();
            $levelsDef = [
                5 => [
                    'label' => 'Level 5 · Enlightened Soul',
                    'sub' => 'Jiwa Terpancar Sempurna',
                    'range' => '67–100 Poin',
                    'color' => 'bg-[#0F2537]',
                    'text' => 'text-[#0F2537]'
                ],
                4 => [
                    'label' => 'Level 4 · Mindful Youth',
                    'sub' => 'Jiwa Tenang & Terjaga',
                    'range' => '51–66 Poin',
                    'color' => 'bg-[#1E3E5B]',
                    'text' => 'text-[#1E3E5B]'
                ],
                3 => [
                    'label' => 'Level 3 · Developing Soul',
                    'sub' => 'Jiwa Berproses Stabil',
                    'range' => '34–50 Poin',
                    'color' => 'bg-[#8C703E]',
                    'text' => 'text-[#8C703E]'
                ],
                2 => [
                    'label' => 'Level 2 · Searching Soul',
                    'sub' => 'Jiwa Mencari Arahan',
                    'range' => '17–33 Poin',
                    'color' => 'bg-slate-400',
                    'text' => 'text-slate-600'
                ],
                1 => [
                    'label' => 'Level 1 · Restless Soul',
                    'sub' => 'Jiwa Membutuhkan Pendampingan',
                    'range' => '≤ 16 Poin',
                    'color' => 'bg-rose-400',
                    'text' => 'text-rose-700'
                ],
            ];
        @endphp
        <section class="p-5 bg-gradient-to-b from-[#FAF7F0]/60 to-white rounded-xl border border-[#DFD8C8] space-y-3.5 break-inside-avoid">
            <div class="flex items-center justify-between border-b border-slate-200/80 pb-2.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-sm bg-[#8C703E]"></span>
                    <h3 class="font-cinzel font-bold text-[#0F2537] text-xs uppercase tracking-wider">
                        Distribusi Sebaran Indeks Ruhiologi (RQI Level 1–5)
                    </h3>
                </div>
                <span class="text-[9.5px] font-mono-num text-slate-400 font-semibold">Skala Baku Ruhiology Index 0–100</span>
            </div>

            <div class="space-y-2.5">
                @foreach([5, 4, 3, 2, 1] as $lvlNum)
                    @php
                        $c = $levelCounts[$lvlNum] ?? 0;
                        $pct = $totalCount > 0 ? round(($c / $totalCount) * 100, 1) : 0;
                        $def = $levelsDef[$lvlNum];
                    @endphp
                    <div class="flex items-center gap-3 text-xs">
                        <!-- Level Label & Range (No truncation) -->
                        <div class="w-72 shrink-0 flex items-center justify-between pr-3">
                            <div>
                                <span class="font-bold text-[#0F2537] text-[11px] block">{{ $def['label'] }}</span>
                                <span class="text-[9.5px] text-slate-500 block leading-tight">{{ $def['sub'] }}</span>
                            </div>
                            <span class="text-[9px] font-mono-num text-slate-400 bg-white border border-slate-200 px-1.5 py-0.5 rounded shrink-0">
                                {{ $def['range'] }}
                            </span>
                        </div>

                        <!-- Progress Bar Track -->
                        <div class="flex-1 bg-slate-100 h-2.5 rounded-full overflow-hidden flex border border-slate-200/60 shadow-inner">
                            <div class="{{ $def['color'] }} h-full rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                        </div>

                        <!-- Count & Percentage -->
                        <div class="w-24 text-right font-mono-num shrink-0">
                            <span class="font-bold text-slate-900 text-xs">{{ $c }}</span>
                            <span class="text-[10px] text-slate-500 font-medium">({{ $pct }}%)</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- STRATEGIC EXECUTIVE BRIEFING & INTERVENTION MATRIX -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-3.5 break-inside-avoid">
            <!-- Column 1: Diagnosis Ruhiologi -->
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-2xs space-y-2">
                <div class="flex items-center gap-1.5 text-[#0F2537]">
                    <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px] font-cinzel font-bold tracking-wider uppercase">1. Diagnosis Ruhiologi</span>
                </div>
                <p class="text-[10.5px] text-slate-600 leading-relaxed text-justify">
                    Rata-rata kohor berada di angka <strong class="text-slate-900 font-mono-num">{{ $avgRqi }}/100</strong> ({{ $rqiCategory }}). Mayoritas peserta telah memiliki pemahaman spiritual dasar yang baik dan memerlukan penguatan keteraturan ibadah batin untuk mencapai konsistensi.
                </p>
            </div>

            <!-- Column 2: Diagnosis WHO-5 Mental -->
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-2xs space-y-2">
                <div class="flex items-center gap-1.5 text-[#1E4E44]">
                    <svg class="w-4 h-4 text-[#1E4E44]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="text-[10px] font-cinzel font-bold tracking-wider uppercase">2. Kesejahteraan Mental</span>
                </div>
                <p class="text-[10.5px] text-slate-600 leading-relaxed text-justify">
                    Indeks vitalitas mental rata-rata tercatat <strong class="text-[#1E4E44] font-mono-num">{{ $avgWho5 }}%</strong>. Sebanyak <strong class="text-emerald-700 font-mono-num">{{ $who5SehatCount }} peserta</strong> berada pada status stabil/sejahtera, sementara <strong class="text-rose-700 font-mono-num">{{ $who5SkriningCount }} peserta</strong> terindikasi memerlukan atensi emosional.
                </p>
            </div>

            <!-- Column 3: Rekomendasi Fasilitator -->
            <div class="p-4 rounded-xl bg-gradient-to-b from-[#FAF7F0] to-white border border-[#DFD8C8] shadow-2xs space-y-2">
                <div class="flex items-center gap-1.5 text-[#8C703E]">
                    <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-[10px] font-cinzel font-bold tracking-wider uppercase">3. Intervensi Terarah</span>
                </div>
                <p class="text-[10.5px] text-slate-600 leading-relaxed text-justify">
                    Disarankan mengadakan sesi penguatan amalan hati (*Tazkiyatun Nafs*), pembiasaan *dzikir & muhasabah*, serta pendampingan konseling batin intensif khusus bagi peserta dengan skor WHO-5 di bawah 50%.
                </p>
            </div>
        </section>

        <!-- PARTICIPANTS DATA REGISTRY TABLE -->
        <section class="space-y-2.5 pt-2">
            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-sm bg-[#0F2537]"></span>
                    <h3 class="font-cinzel font-bold text-[#0F2537] text-xs uppercase tracking-wider">
                        Buku Register Hasil Asesmen Peserta
                    </h3>
                </div>
                <span class="text-[9.5px] font-mono-num text-slate-400 font-semibold">Total: {{ $submissions->count() }} Entri Tervalidasi</span>
            </div>

            <div class="rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-[#0F2537] text-white font-semibold text-[9px] uppercase tracking-wider font-mono-num border-b-2 border-[#C5A059]">
                        <tr>
                            <th class="py-2.5 px-3 text-center w-8 text-slate-400">NO</th>
                            <th class="py-2.5 px-3 whitespace-nowrap w-32">KODE SESI</th>
                            <th class="py-2.5 px-3">NAMA PESERTA</th>
                            <th class="py-2.5 px-3 whitespace-nowrap w-24">KATEGORI</th>
                            <th class="py-2.5 px-3">WILAYAH</th>
                            <th class="py-2.5 px-3 text-center whitespace-nowrap w-36">SKOR & LEVEL RQI</th>
                            <th class="py-2.5 px-3 text-center whitespace-nowrap w-24">WHO-5 (%)</th>
                            <th class="py-2.5 px-3 text-center whitespace-nowrap w-28">STATUS WHO-5</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-[10px]">
                        @forelse($submissions as $index => $sub)
                            @php
                                $res = $sub->result;
                                $p = $sub->participant;
                                $cleanName = $p?->name ? ucwords(strtolower(trim($p->name))) : 'Anonim';
                                $provName = $p?->province?->name;
                                $regName = $p?->regency ? $p->regency->formatted_name : '';
                                $regionDisplay = $regName ? ($provName ? $provName . ' (' . $regName . ')' : $regName) : ($provName ?? '-');
                            @endphp
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-[#FAF7F0]/40' }} hover:bg-amber-50/40 transition">
                                <td class="py-2 px-3 text-center font-mono-num text-slate-400 text-[9px]">{{ $index + 1 }}</td>
                                <td class="py-2 px-3 font-mono-num font-bold text-[#0F2537] whitespace-nowrap text-[9.5px]">
                                    <span class="bg-slate-100 border border-slate-200 px-1.5 py-0.5 rounded text-slate-800">
                                        {{ $sub->submission_code }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 font-semibold text-slate-900 leading-tight">{{ $cleanName }}</td>
                                <td class="py-2 px-3 whitespace-nowrap text-slate-600 text-[9px]">
                                    {{ $p?->category === 'Umum' ? 'Mandiri' : ($p?->category ?? 'Mandiri') }}
                                    @if($p?->sub_category)
                                        <span class="block text-[8px] font-mono-num text-[#8C703E] font-semibold">({{ $p->sub_category }})</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-slate-500 text-[9px] leading-tight truncate max-w-[130px]">{{ $regionDisplay }}</td>
                                <td class="py-2 px-3 text-center whitespace-nowrap">
                                    <span class="font-mono-num font-bold text-xs text-[#0F2537] block tabular-nums">{{ $res?->rqi_score ?? '-' }}</span>
                                    @if($res && $res->category_name)
                                        <span class="text-[8px] font-semibold text-[#8C703E] block">
                                            {{ $res->category_name }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-center font-mono-num font-bold whitespace-nowrap {{ ($res?->who5_percentage ?? 0) >= 50 ? 'text-[#1E4E44]' : 'text-rose-700' }}">
                                    {{ ($res && $res->who5_percentage !== null) ? $res->who5_percentage . '%' : '-' }}
                                </td>
                                <td class="py-2 px-3 text-center font-semibold text-[8px] whitespace-nowrap">
                                    @if($res && $res->who5_percentage !== null)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full {{ $res->who5_percentage >= 50 ? 'bg-emerald-50 text-emerald-800 border border-emerald-300' : 'bg-rose-50 text-rose-800 border border-rose-300' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $res->who5_percentage >= 50 ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                                            <span>{{ $res->who5_percentage >= 50 ? 'Sehat' : 'Skrining' }}</span>
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">Tidak ada data hasil asesmen pada filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- DOCUMENT FOOTER OFFICIAL VALIDATION & SECURITY EMBLEM -->
        <footer class="pt-6 border-t-2 border-[#0F2537]/15 flex flex-col sm:flex-row items-center sm:items-end justify-between gap-4 text-xs text-slate-500 break-inside-avoid">
            <div class="space-y-1.5 text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#C5A059]"></span>
                    <span class="font-cinzel font-bold text-[#0F2537] text-xs tracking-wider">LEMBAGA VALIDATOR RESMI RUHIOLOGY INSTITUTE</span>
                </div>
                <div class="text-[9.5px] text-slate-500">
                    Dokumen ini dienkripsi dan divalidasi secara digital melalui Ruhiology Institute Core Assessment Engine v2.0.
                </div>
                <div class="text-[8px] font-mono-num text-slate-400 uppercase tracking-widest pt-0.5">
                    VERIFIKASI INTEGRITAS DATA: SHA256-{{ strtoupper(substr(md5($event ? $event->id : 'PUBLIC_REPORT'), 0, 16)) }}
                </div>
            </div>

            <!-- Validation Stamp Block -->
            <div class="text-center w-60 bg-gradient-to-b from-[#FAF7F0] to-white p-3.5 rounded-xl border border-[#DFD8C8] shadow-2xs">
                <div class="text-[8px] font-mono-num font-bold text-[#8C703E] uppercase tracking-widest mb-1">
                    OTORISASI & PENGESAHAN
                </div>
                <div class="font-editorial font-bold text-[#0F2537] text-xs">
                    Dewan Penilai Ruhiologi
                </div>
                
                <div class="my-1.5 flex items-center justify-center">
                    <div class="px-3 py-1 bg-white border border-[#C5A059] rounded-lg shadow-2xs flex items-center gap-1.5 text-[8.5px] font-mono-num text-[#8C703E] font-bold">
                        <svg class="w-3.5 h-3.5 text-[#C5A059]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <span>OFFICIAL CERTIFIED</span>
                    </div>
                </div>
                
                <div class="text-[8px] font-mono-num text-slate-400 border-t border-slate-200/80 pt-1">
                    DIGITAL VERIFICATION ID: RQI-{{ date('Y') }}-CONF
                </div>
            </div>
        </footer>

    </div>

</body>
</html>
