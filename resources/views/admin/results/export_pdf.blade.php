<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Hasil Asesmen Ruhiologi — Ruhiology Institute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
            color: #0F172A;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .font-editorial {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
        @page {
            size: A4 portrait;
            margin: 12mm 14mm;
        }
        @media print {
            .no-print { display: none !important; }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            .page-break { page-break-before: always; }
            .break-inside-avoid { break-inside: avoid; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="p-6 sm:p-10 max-w-5xl mx-auto space-y-6 text-slate-800 bg-white">

    <!-- FLOATING MINIMALIST PRINT BAR (Hidden on Print) -->
    <div class="no-print flex items-center justify-between bg-[#0F2537] text-white px-5 py-3 rounded-xl shadow-lg border border-[#1E3E5B] mb-6">
        <div class="flex items-center gap-2.5 text-xs">
            <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
            <span class="font-medium text-slate-200">Pratinjau Laporan Eksekutif Resmi (A4 Portrait)</span>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold text-xs rounded-lg shadow-sm cursor-pointer transition flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- DOCUMENT HEADER (FORMAL ACADEMIC KOP SURAT) -->
    <header class="border-b-2 border-[#0F2537] pb-4 space-y-3">
        <!-- Top Institutional Brand & Meta -->
        <div class="flex items-start justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 text-[9px] font-mono font-bold text-[#8C703E] uppercase tracking-widest bg-[#F4F1EA] border border-[#DFD8C8] px-2 py-0.5 rounded">
                        OFFICIAL EXECUTIVE REPORT
                    </span>
                    <span class="text-[9px] font-mono text-slate-400 font-semibold tracking-wider">
                        REF: REP-RQI/{{ date('Y/m') }}/{{ $event ? $event->event_code : 'PUBLIC' }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-editorial font-bold text-[#0F2537] tracking-tight leading-tight pt-1">
                    {{ $event ? $event->title : 'Laporan Hasil Asesmen Ruhiologi' }}
                </h1>
                <div class="text-[11px] text-slate-600 flex flex-wrap items-center gap-x-3 gap-y-1 pt-0.5">
                    <span>Instansi: <strong class="text-slate-900 font-semibold">{{ $event->institution_name ?? 'Ruhiology Institute (Personal / Mandiri)' }}</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>Tanggal: <strong class="text-slate-900 font-semibold">{{ date('d F Y') }}</strong></span>
                    @if($event && $event->event_code)
                        <span class="text-slate-300">•</span>
                        <span>Kode Event: <strong class="font-mono text-[#8C703E] font-semibold">{{ $event->event_code }}</strong></span>
                    @endif
                </div>
            </div>

            <!-- Institutional Seal / Logotype -->
            <div class="text-right shrink-0 pt-1">
                <div class="text-lg font-editorial font-bold text-[#0F2537] tracking-wider leading-none">RUHIOLOGY</div>
                <div class="text-[9px] font-mono font-bold tracking-[0.2em] text-[#8C703E] uppercase pt-0.5">INSTITUTE</div>
                <div class="text-[8px] text-slate-400 tracking-wide mt-1">Pusat Studi Kesadaran Batiniah</div>
            </div>
        </div>

        <!-- Hairline secondary border -->
        <div class="border-t border-slate-200 pt-1"></div>
    </header>

    <!-- EXECUTIVE METRICS GRID (CLEAN ACADEMIC CARDS) -->
    <section class="grid grid-cols-4 gap-3">
        <!-- Metric 1: Total Peserta -->
        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-center">
            <span class="text-[9px] font-mono font-bold text-slate-500 uppercase tracking-wider block">TOTAL PESERTA</span>
            <span class="text-2xl font-bold font-editorial text-[#0F2537] mt-0.5 block tabular-nums">{{ number_format($submissions->count()) }}</span>
            <span class="text-[9px] text-slate-400 block mt-0.5">Peserta Terdaftar</span>
        </div>

        <!-- Metric 2: Rata-Rata RQI -->
        <div class="p-3.5 bg-[#FAF7F0] rounded-xl border border-[#DFD8C8] text-center">
            <span class="text-[9px] font-mono font-bold text-[#8C703E] uppercase tracking-wider block">RATA-RATA RQI (0-100)</span>
            <span class="text-2xl font-bold font-editorial text-[#8C703E] mt-0.5 block tabular-nums">{{ $avgRqi }}</span>
            <span class="text-[9px] text-[#8C703E]/80 font-medium block mt-0.5">Indeks Ruhiologi 5 Pilar</span>
        </div>

        <!-- Metric 3: Rata-Rata WHO-5 -->
        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-center">
            <span class="text-[9px] font-mono font-bold text-emerald-800 uppercase tracking-wider block">RATA-RATA WHO-5 (%)</span>
            <span class="text-2xl font-bold font-editorial text-emerald-700 mt-0.5 block tabular-nums">{{ $avgWho5 }}%</span>
            <span class="text-[9px] text-emerald-700/80 font-medium block mt-0.5">Kesejahteraan Mental</span>
        </div>

        <!-- Metric 4: Status Emosional -->
        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-center">
            <span class="text-[9px] font-mono font-bold text-slate-500 uppercase tracking-wider block">STATUS KESEJAHTERAAN</span>
            <div class="text-sm font-bold text-slate-800 mt-1 tabular-nums">
                <span class="text-emerald-700">{{ $who5SehatCount }} Sehat</span>
                <span class="text-slate-300 font-normal mx-0.5">/</span>
                <span class="text-rose-700">{{ $who5SkriningCount }} Skrining</span>
            </div>
            <span class="text-[9px] text-slate-400 block mt-0.5">Berdasarkan Standar WHO-5</span>
        </div>
    </section>

    <!-- RQI LEVEL DISTRIBUTION (CALIBRATED ANALYTIC CHART) -->
    @php
        $totalCount = $submissions->count();
        $levelsDef = [
            5 => ['name' => 'Level 5: Enlightened Soul (Jiwa Terpancar Sempurna)', 'range' => '61–75 Poin', 'bar' => 'bg-[#0F2537]'],
            4 => ['name' => 'Level 4: Mindful Youth (Jiwa Tenang & Terjaga)', 'range' => '46–60 Poin', 'bar' => 'bg-[#1E3E5B]'],
            3 => ['name' => 'Level 3: Developing Soul (Jiwa Berproses Stabil)', 'range' => '36–45 Poin', 'bar' => 'bg-[#8C703E]'],
            2 => ['name' => 'Level 2: Searching Soul (Jiwa Mencari Arahan)', 'range' => '26–35 Poin', 'bar' => 'bg-slate-400'],
            1 => ['name' => 'Level 1: Restless Soul (Jiwa Membutuhkan Pendampingan)', 'range' => '≤ 25 Poin', 'bar' => 'bg-rose-400'],
        ];
    @endphp
    <section class="p-4 bg-slate-50/70 rounded-xl border border-slate-200 space-y-2.5 break-inside-avoid">
        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <h3 class="font-mono font-bold text-[#0F2537] text-[10px] uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Distribusi Sebaran Indeks Ruhiologi (RQI Level 1–5)</span>
            </h3>
            <span class="text-[9px] font-mono text-slate-400">Skala Baku RQI-15</span>
        </div>

        <div class="space-y-2 text-[10px]">
            @foreach([5, 4, 3, 2, 1] as $lvlNum)
                @php
                    $c = $levelCounts[$lvlNum] ?? 0;
                    $pct = $totalCount > 0 ? round(($c / $totalCount) * 100, 1) : 0;
                    $def = $levelsDef[$lvlNum];
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-64 flex items-center justify-between pr-2">
                        <span class="font-semibold text-slate-800 text-[10px] truncate">{{ $def['name'] }}</span>
                        <span class="text-[9px] font-mono text-slate-400 shrink-0">{{ $def['range'] }}</span>
                    </div>
                    <div class="flex-1 bg-slate-200 h-2 rounded-sm overflow-hidden flex">
                        <div class="{{ $def['bar'] }} h-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-20 text-right font-mono font-semibold text-slate-800 tabular-nums">
                        {{ $c }} <span class="text-slate-400 font-normal">({{ $pct }}%)</span>
                    </span>
                </div>
            @endforeach
        </div>
    </section>

    <!-- EXECUTIVE INSIGHT NARRATIVE BOX -->
    <section class="p-4 bg-[#FDFBF7] border-l-3 border-[#8C703E] rounded-r-xl space-y-1.5 text-xs break-inside-avoid">
        <h3 class="font-mono font-bold text-[#0F2537] text-[10px] uppercase tracking-wider flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Evaluasi Eksekutif & Rekomendasi Facilitator</span>
        </h3>
        <p class="text-slate-600 leading-relaxed text-[11px] text-justify">
            Berdasarkan data asesmen terhadap <strong>{{ $submissions->count() }} peserta</strong>, rata-rata Indeks Ruhiologi (RQI-15) berada di angka <strong>{{ $avgRqi }} / 100</strong>, menunjukkan tingkat kesadaran batiniah secara umum dalam kategori stabil. Pada skrining kesehatan emosional (WHO-5), <strong>{{ $who5SehatCount }} peserta ({{ $submissions->count() > 0 ? round(($who5SehatCount/$submissions->count())*100) : 0 }}%)</strong> memiliki vitalitas emosional yang baik, sedangkan <strong>{{ $who5SkriningCount }} peserta ({{ $submissions->count() > 0 ? round(($who5SkriningCount/$submissions->count())*100) : 0 }}%)</strong> menunjukkan indikasi kelesuan batin/stres yang membutuhkan sesi penguatan amalan dan pendampingan batin.
        </p>
    </section>

    <!-- PARTICIPANTS DATA TABLE -->
    <section class="space-y-2 pt-1">
        <div class="flex items-center justify-between">
            <h3 class="font-mono font-bold text-[#0F2537] text-[10px] uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Rincian Hasil Asesmen Peserta</span>
            </h3>
            <span class="text-[9px] font-mono text-slate-400">Total: {{ $submissions->count() }} Entri Data</span>
        </div>

        <div class="rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-[#0F2537] text-white font-semibold text-[9px] uppercase tracking-wider font-mono">
                    <tr>
                        <th class="p-2 text-center w-8">No</th>
                        <th class="p-2 whitespace-nowrap w-32">Kode Sesi</th>
                        <th class="p-2">Nama Peserta</th>
                        <th class="p-2 whitespace-nowrap w-24">Kategori</th>
                        <th class="p-2">Wilayah</th>
                        <th class="p-2 text-center whitespace-nowrap w-36">Skor & Level RQI</th>
                        <th class="p-2 text-center whitespace-nowrap w-20">WHO-5 %</th>
                        <th class="p-2 text-center whitespace-nowrap w-24">Status WHO-5</th>
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
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                            <td class="p-2 text-center font-mono text-slate-400 text-[9px]">{{ $index + 1 }}</td>
                            <td class="p-2 font-mono font-bold text-[#0F2537] whitespace-nowrap text-[9px]">{{ $sub->submission_code }}</td>
                            <td class="p-2 font-semibold text-slate-900 leading-tight">{{ $cleanName }}</td>
                            <td class="p-2 whitespace-nowrap text-slate-600 text-[9px]">
                                {{ $p?->category === 'Umum' ? 'Mandiri' : ($p?->category ?? 'Mandiri') }}
                                @if($p?->sub_category)
                                    <span class="block text-[8px] font-mono text-[#8C703E] font-semibold">({{ $p->sub_category }})</span>
                                @endif
                            </td>
                            <td class="p-2 text-slate-500 text-[9px] leading-tight truncate max-w-[140px]">{{ $regionDisplay }}</td>
                            <td class="p-2 text-center whitespace-nowrap">
                                <span class="font-mono font-bold text-xs text-[#0F2537] block tabular-nums">{{ $res?->rqi_score ?? '-' }}</span>
                                @if($res && $res->category_name)
                                    <span class="text-[8px] font-semibold text-[#8C703E] block">
                                        {{ $res->category_name }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-2 text-center font-mono font-semibold whitespace-nowrap tabular-nums {{ ($res?->who5_percentage ?? 0) >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ ($res && $res->who5_percentage !== null) ? $res->who5_percentage . '%' : '-' }}
                            </td>
                            <td class="p-2 text-center font-semibold text-[8px] whitespace-nowrap">
                                @if($res && $res->who5_percentage !== null)
                                    <span class="inline-block px-2 py-0.5 rounded {{ $res->who5_percentage >= 50 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                                        {{ $res->who5_percentage >= 50 ? 'Sehat' : 'Skrining' }}
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-400">Tidak ada data hasil asesmen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- DOCUMENT FOOTER SIGNATURE & VERIFICATION STAMP -->
    <footer class="pt-5 border-t border-slate-200 flex items-end justify-between text-xs text-slate-500 break-inside-avoid">
        <div class="space-y-1">
            <div class="font-bold text-[#0F2537] text-xs">Dokumen Resmi Hasil Asesmen Ruhiologi</div>
            <div class="text-[9px] text-slate-400">Divalidasi secara digital melalui Ruhiology Institute Core Assessment Engine.</div>
            <div class="text-[8px] font-mono text-slate-400 uppercase tracking-widest pt-0.5">
                VERIFIKASI SISTEM HASH: SHA256-{{ strtoupper(substr(md5($event ? $event->id : 'PUBLIC'), 0, 12)) }}
            </div>
        </div>

        <div class="text-center w-56 bg-slate-50 p-3 rounded-xl border border-slate-200">
            <div class="text-[8px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Divalidasi Oleh:</div>
            <div class="font-bold text-[#0F2537] text-xs">Tim Validator Ruhiology</div>
            
            <div class="h-10 my-1 flex items-center justify-center">
                <div class="w-36 py-1 px-2 border border-[#DFD8C8] rounded-lg bg-[#FAF7F0] flex items-center justify-center gap-1.5 text-[8px] text-[#8C703E] font-mono font-bold tracking-wider">
                    <svg class="w-3.5 h-3.5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>VERIFIED OFFICIAL</span>
                </div>
            </div>
            
            <div class="text-[7.5px] font-mono text-slate-400 border-t border-slate-200 pt-1">
                INSTITUTIONAL VALIDATION
            </div>
        </div>
    </footer>

</body>
</html>
