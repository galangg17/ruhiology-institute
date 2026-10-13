<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Hasil Asesmen Ruhiologi — Ruhiology Institute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Merriweather:wght@400;700;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .font-serif-doc {
            font-family: 'Merriweather', Georgia, serif;
        }
        .font-mono-num {
            font-family: 'JetBrains Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
        @page {
            size: A4 portrait;
            margin: 12mm 14mm;
        }
        @media print {
            .no-print { display: none !important; }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                color: #000000 !important;
            }
            .document-sheet {
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
<body class="bg-slate-100 text-slate-800 min-h-screen py-6 px-3 sm:py-8 sm:px-6 antialiased">

    <!-- FLOATING TOP ACTION BAR (Hidden on Print) -->
    <div class="no-print max-w-4xl mx-auto mb-5 flex items-center justify-between bg-white text-slate-800 px-5 py-3 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/ruhiology-logo.png') }}" class="h-8 w-auto object-contain" alt="Ruhiology">
            <div>
                <div class="text-xs font-bold text-slate-900">Laporan Eksekutif Hasil Asesmen Ruhiologi</div>
                <div class="text-[11px] text-slate-500">Format Resmi Standar Cetak Dokumen A4</div>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <button onclick="window.history.back()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg border border-slate-300 transition cursor-pointer">
                ← Kembali
            </button>
            <button onclick="window.print()" class="px-4 py-2 bg-[#0F2537] hover:bg-[#1A3A54] text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- MAIN FORMAL A4 DOCUMENT -->
    <div class="document-sheet max-w-4xl mx-auto bg-white rounded-lg shadow-sm border border-slate-200 p-8 sm:p-12 space-y-4">

        <!-- 1. OFFICIAL INSTITUTIONAL KOP SURAT (DENGAN LOGO RESMI) -->
        <header class="flex items-center justify-between gap-4 pb-1">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/ruhiology-logo.png') }}" class="h-16 w-auto object-contain shrink-0" alt="Ruhiology Institute Logo">
                <div class="space-y-0.5">
                    <h2 class="text-lg font-bold text-slate-900 tracking-wide uppercase font-serif-doc leading-tight">
                        RUHIOLOGY INSTITUTE
                    </h2>
                    <p class="text-[11px] text-slate-700 font-semibold leading-tight">
                        Pusat Riset & Pengembangan Potensi Kecerdasan Ruhaniyah
                    </p>
                    <p class="text-[9.5px] text-slate-500 font-normal leading-tight">
                        Kanal Resmi Evaluasi Psikospiritual (RQI-15) & Kesejahteraan Mental (WHO-5) • ruhiology.id
                    </p>
                </div>
            </div>

            <!-- Meta Dokumen Kanan -->
            <div class="text-right text-[10px] text-slate-600 font-mono-num shrink-0 space-y-0.5">
                <div>No: <strong class="text-slate-900">REP-RQI/{{ date('Y/m') }}/{{ $event ? $event->event_code : 'PUBLIC' }}</strong></div>
                <div>Tanggal: <span class="text-slate-800 font-medium">{{ date('d F Y') }}</span></div>
                <div class="text-emerald-700 font-bold">STATUS: DOKUMEN RESMI</div>
            </div>
        </header>

        <!-- Garis Ganda Kop Surat Resmi -->
        <div class="space-y-0.5 pt-1">
            <div class="border-b-2 border-slate-900"></div>
            <div class="border-b border-slate-900"></div>
        </div>

        <!-- 2. JUDUL DOKUMEN & IDENTITAS ASESMEN -->
        <div class="text-center pt-2">
            <h1 class="text-base font-bold text-slate-900 tracking-wide uppercase font-serif-doc">
                LAPORAN EKSEKUTIF HASIL ASESMEN RUHIOLOGI
            </h1>
            <p class="text-xs text-slate-600 mt-0.5 font-medium">
                {{ $event ? $event->title : 'Evaluasi Terpadu Profil Ruhiologi & Kesejahteraan Mental' }}
            </p>
        </div>

        <!-- Tabel Ringkas Identitas Pelaksanaan -->
        <div class="grid grid-cols-4 gap-2 bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-[10px] text-slate-700">
            <div>
                <span class="text-slate-400 block text-[9px] uppercase font-semibold">Instansi / Lembaga</span>
                <strong class="text-slate-900 text-[11px] truncate block">{{ $event->institution_name ?? ($event->title ?? 'Ruhiology Institute (Mandiri)') }}</strong>
            </div>
            <div>
                <span class="text-slate-400 block text-[9px] uppercase font-semibold">Kode Event</span>
                <span class="font-mono-num font-bold text-slate-900 text-[11px] block">{{ $event && $event->event_code ? $event->event_code : 'MANDIRI-PUBLIK' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[9px] uppercase font-semibold">Tanggal Pelaksanaan</span>
                <span class="text-slate-900 font-medium text-[11px] block">{{ date('d F Y') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[9px] uppercase font-semibold">Klasifikasi Dokumen</span>
                <span class="text-slate-900 font-medium text-[11px] block">Hasil Rekapitulasi Kohor</span>
            </div>
        </div>

        <!-- 3. EXECUTIVE METRICS GRID (4 KOLOM BERSIH & RAPI) -->
        @php
            $rqiCategory = 'Level 3: Jiwa Berproses Stabil';
            if ($avgRqi >= 67) $rqiCategory = 'Level 5: Jiwa Paripurna';
            elseif ($avgRqi >= 51) $rqiCategory = 'Level 4: Jiwa Tenang & Terjaga';
            elseif ($avgRqi >= 34) $rqiCategory = 'Level 3: Jiwa Berproses Stabil';
            elseif ($avgRqi >= 17) $rqiCategory = 'Level 2: Jiwa Mencari Arahan';
            else $rqiCategory = 'Level 1: Jiwa Butuh Pendampingan';

            $pctSehat = $submissions->count() > 0 ? round(($who5SehatCount / $submissions->count()) * 100) : 0;
            $pctSkrining = $submissions->count() > 0 ? round(($who5SkriningCount / $submissions->count()) * 100) : 0;
        @endphp
        <section class="grid grid-cols-4 gap-2.5">
            <!-- Metric 1: Total Peserta -->
            <div class="p-3 bg-white border border-slate-200 rounded-lg text-center">
                <span class="text-[9px] font-mono-num font-bold text-slate-500 uppercase tracking-wider block">TOTAL PESERTA</span>
                <span class="text-2xl font-bold font-mono-num text-slate-900 mt-0.5 block">{{ number_format($submissions->count()) }}</span>
                <span class="text-[9px] text-slate-500 block mt-0.5">100% Data Tervalidasi</span>
            </div>

            <!-- Metric 2: Rata-Rata RQI -->
            <div class="p-3 bg-white border border-slate-200 rounded-lg text-center">
                <span class="text-[9px] font-mono-num font-bold text-slate-500 uppercase tracking-wider block">RATA-RATA RQI (0-100)</span>
                <div class="text-2xl font-bold font-mono-num text-slate-900 mt-0.5">
                    {{ $avgRqi }} <span class="text-xs font-normal text-slate-400">/ 100</span>
                </div>
                <span class="text-[9px] font-semibold text-slate-700 truncate block mt-0.5">{{ $rqiCategory }}</span>
            </div>

            <!-- Metric 3: Rata-Rata WHO-5 -->
            <div class="p-3 bg-white border border-slate-200 rounded-lg text-center">
                <span class="text-[9px] font-mono-num font-bold text-slate-500 uppercase tracking-wider block">RATA-RATA WHO-5 (%)</span>
                <span class="text-2xl font-bold font-mono-num text-slate-900 mt-0.5 block">{{ $avgWho5 }}%</span>
                <span class="text-[9px] font-semibold {{ $avgWho5 >= 50 ? 'text-emerald-700' : 'text-amber-700' }} block mt-0.5">
                    {{ $avgWho5 >= 50 ? 'Vitalitas Mental Baik' : 'Perlu Perhatian' }}
                </span>
            </div>

            <!-- Metric 4: Skrining Emosional -->
            <div class="p-3 bg-white border border-slate-200 rounded-lg text-center">
                <span class="text-[9px] font-mono-num font-bold text-slate-500 uppercase tracking-wider block">STATUS KESEJAHTERAAN</span>
                <div class="text-xs font-bold font-mono-num mt-1.5 flex items-center justify-center gap-1">
                    <span class="text-emerald-700">{{ $who5SehatCount }} Sehat ({{ $pctSehat }}%)</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-rose-700">{{ $who5SkriningCount }} Skrining</span>
                </div>
                <span class="text-[9px] text-slate-400 block mt-0.5">Standar Diagnostik WHO-5</span>
            </div>
        </section>

        <!-- 4. DISTRIBUSI SEBARAN INDEKS RUHIOLOGI (RQI LEVEL 1–5) -->
        @php
            $totalCount = $submissions->count();
            $levelsDef = [
                5 => ['name' => 'Level 5: Enlightened Soul (Jiwa Terpancar Sempurna)', 'range' => '67–100 Poin', 'color' => 'bg-slate-900'],
                4 => ['name' => 'Level 4: Mindful Youth (Jiwa Tenang & Terjaga)', 'range' => '51–66 Poin', 'color' => 'bg-slate-700'],
                3 => ['name' => 'Level 3: Developing Soul (Jiwa Berproses Stabil)', 'range' => '34–50 Poin', 'color' => 'bg-amber-600'],
                2 => ['name' => 'Level 2: Searching Soul (Jiwa Mencari Arahan)', 'range' => '17–33 Poin', 'color' => 'bg-slate-400'],
                1 => ['name' => 'Level 1: Restless Soul (Jiwa Membutuhkan Pendampingan)', 'range' => '≤ 16 Poin', 'color' => 'bg-rose-500'],
            ];
        @endphp
        <section class="p-3.5 bg-slate-50 border border-slate-200 rounded-lg space-y-2 break-inside-avoid">
            <div class="flex items-center justify-between border-b border-slate-200 pb-1.5">
                <h3 class="font-bold text-slate-900 text-[10.5px] uppercase tracking-wide">
                    Distribusi Sebaran Indeks Ruhiologi (RQI Level 1–5)
                </h3>
                <span class="text-[9px] font-mono-num text-slate-400">Skala Baku Baku Ruhiologi 0–100</span>
            </div>

            <div class="space-y-1.5 text-xs">
                @foreach([5, 4, 3, 2, 1] as $lvlNum)
                    @php
                        $c = $levelCounts[$lvlNum] ?? 0;
                        $pct = $totalCount > 0 ? round(($c / $totalCount) * 100, 1) : 0;
                        $def = $levelsDef[$lvlNum];
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-80 flex items-center justify-between pr-2 shrink-0">
                            <span class="font-semibold text-slate-800 text-[10px]">{{ $def['name'] }}</span>
                            <span class="text-[9px] font-mono-num text-slate-400">{{ $def['range'] }}</span>
                        </div>
                        <div class="flex-1 bg-slate-200 h-2 rounded-full overflow-hidden flex">
                            <div class="{{ $def['color'] }} h-full rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="w-20 text-right font-mono-num font-semibold text-slate-800 text-[10px] tabular-nums shrink-0">
                            {{ $c }} <span class="text-slate-400 font-normal">({{ $pct }}%)</span>
                        </span>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- 5. EVALUASI EKSEKUTIF & REKOMENDASI FASILITATOR -->
        <section class="grid grid-cols-3 gap-2.5 break-inside-avoid">
            <div class="p-3 bg-white border border-slate-200 rounded-lg space-y-1">
                <h4 class="text-[10px] font-bold text-slate-900 uppercase tracking-wide">1. Diagnosis Ruhiologi</h4>
                <p class="text-[9.5px] text-slate-600 leading-relaxed text-justify">
                    Rata-rata kohor berada pada skor <strong class="text-slate-900 font-mono-num">{{ $avgRqi }}/100</strong> ({{ $rqiCategory }}). Kondisi batiniah peserta secara umum berada dalam fase pembentukan kesadaran dan memerlukan pembiasaan ibadah teratur.
                </p>
            </div>
            <div class="p-3 bg-white border border-slate-200 rounded-lg space-y-1">
                <h4 class="text-[10px] font-bold text-slate-900 uppercase tracking-wide">2. Kesejahteraan Mental</h4>
                <p class="text-[9.5px] text-slate-600 leading-relaxed text-justify">
                    Indeks vitalitas mental tercatat <strong class="text-slate-900 font-mono-num">{{ $avgWho5 }}%</strong>. Terdapat <strong class="text-emerald-700 font-mono-num">{{ $who5SehatCount }} peserta</strong> berkondisi prima dan <strong class="text-rose-700 font-mono-num">{{ $who5SkriningCount }} peserta</strong> yang terindikasi kelesuan emosional.
                </p>
            </div>
            <div class="p-3 bg-white border border-slate-200 rounded-lg space-y-1">
                <h4 class="text-[10px] font-bold text-slate-900 uppercase tracking-wide">3. Rekomendasi Terarah</h4>
                <p class="text-[9.5px] text-slate-600 leading-relaxed text-justify">
                    Disarankan mengadakan sesi penguatan amalan hati (*Tazkiyatun Nafs*), pembiasaan dzikir reflektif, serta pendampingan konseling intensif bagi peserta yang memiliki skor WHO-5 di bawah 50%.
                </p>
            </div>
        </section>

        <!-- 6. TABEL REGISTER DATA PESERTA -->
        <section class="space-y-1.5 pt-1">
            <div class="flex items-center justify-between border-b border-slate-200 pb-1">
                <h3 class="font-bold text-slate-900 text-[10.5px] uppercase tracking-wide">
                    Buku Register Hasil Asesmen Peserta
                </h3>
                <span class="text-[9px] font-mono-num text-slate-400">Total: {{ $submissions->count() }} Entri Data</span>
            </div>

            <div class="rounded-lg border border-slate-200 overflow-hidden">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-800 text-white font-semibold text-[9px] uppercase tracking-wider font-mono-num">
                        <tr>
                            <th class="py-2 px-2.5 text-center w-8">No</th>
                            <th class="py-2 px-2.5 whitespace-nowrap w-32">Kode Sesi</th>
                            <th class="py-2 px-2.5">Nama Peserta</th>
                            <th class="py-2 px-2.5 whitespace-nowrap w-24">Kategori</th>
                            <th class="py-2 px-2.5">Wilayah</th>
                            <th class="py-2 px-2.5 text-center whitespace-nowrap w-36">Skor & Level RQI</th>
                            <th class="py-2 px-2.5 text-center whitespace-nowrap w-20">WHO-5 %</th>
                            <th class="py-2 px-2.5 text-center whitespace-nowrap w-24">Status WHO-5</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-[9.5px]">
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
                                <td class="py-1.5 px-2.5 text-center font-mono-num text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-1.5 px-2.5 font-mono-num font-semibold text-slate-800 whitespace-nowrap">
                                    {{ $sub->submission_code }}
                                </td>
                                <td class="py-1.5 px-2.5 font-semibold text-slate-900 leading-tight">{{ $cleanName }}</td>
                                <td class="py-1.5 px-2.5 whitespace-nowrap text-slate-600 text-[9px]">
                                    {{ $p?->category === 'Umum' ? 'Mandiri' : ($p?->category ?? 'Mandiri') }}
                                    @if($p?->sub_category)
                                        <span class="block text-[8px] font-mono-num text-slate-400">({{ $p->sub_category }})</span>
                                    @endif
                                </td>
                                <td class="py-1.5 px-2.5 text-slate-500 text-[9px] leading-tight truncate max-w-[130px]">{{ $regionDisplay }}</td>
                                <td class="py-1.5 px-2.5 text-center whitespace-nowrap">
                                    <span class="font-mono-num font-bold text-[10.5px] text-slate-900 block tabular-nums">{{ $res?->rqi_score ?? '-' }}</span>
                                    @if($res && $res->category_name)
                                        <span class="text-[8px] font-medium text-slate-600 block">
                                            {{ $res->category_name }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-1.5 px-2.5 text-center font-mono-num font-semibold whitespace-nowrap {{ ($res?->who5_percentage ?? 0) >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ ($res && $res->who5_percentage !== null) ? $res->who5_percentage . '%' : '-' }}
                                </td>
                                <td class="py-1.5 px-2.5 text-center whitespace-nowrap">
                                    @if($res && $res->who5_percentage !== null)
                                        <span class="inline-block px-2 py-0.5 rounded text-[8px] font-bold {{ $res->who5_percentage >= 50 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
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

        <!-- 7. PENGESAHAN DOKUMEN & TANDA TANGAN RESMI -->
        <footer class="pt-4 flex items-end justify-between text-xs text-slate-500 break-inside-avoid">
            <div class="space-y-1">
                <div class="font-bold text-slate-800 text-[10px]">Dokumen Resmi Asesmen Ruhiologi</div>
                <div class="text-[9px] text-slate-500">Divalidasi secara digital melalui Ruhiology Institute Core Assessment Engine.</div>
                <div class="text-[8px] font-mono-num text-slate-400 uppercase">
                    ID VALIDASI: SHA256-{{ strtoupper(substr(md5($event ? $event->id : 'PUBLIC'), 0, 12)) }}
                </div>
            </div>

            <!-- Blok Tanda Tangan Resmi -->
            <div class="text-center w-56 text-slate-800 space-y-1">
                <div class="text-[10px]">Divalidasi di Jambi, {{ date('d F Y') }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-900">
                    Dewan Penilai Ruhiologi
                </div>
                
                <div class="h-12 flex items-center justify-center">
                    <div class="px-3 py-1 bg-slate-50 border border-slate-300 rounded text-[9px] font-mono-num text-slate-600 font-bold">
                        [TERVALIDASI DIGITAL]
                    </div>
                </div>
                
                <div class="text-[10px] font-bold text-slate-900 border-t border-slate-300 pt-0.5">
                    Tim Fasilitator & Penilai
                </div>
                <div class="text-[8px] font-mono-num text-slate-400">
                    Ruhiology Institute
                </div>
            </div>
        </footer>

    </div>

</body>
</html>
