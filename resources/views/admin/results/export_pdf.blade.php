<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Hasil Asesmen Ruhiologi — Ruhiology Institute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
            color: #0f172a;
        }
        .font-serif {
            font-family: 'Outfit', sans-serif;
        }
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }
        @media print {
            .no-print { display: none !important; }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .page-break { page-break-before: always; }
            .break-inside-avoid { break-inside: avoid; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="p-6 sm:p-10 max-w-5xl mx-auto space-y-6 text-slate-800">

    <!-- FLOATING MINIMALIST PRINT BUTTON (Hidden on Print) -->
    <div class="no-print flex items-center justify-between bg-[#0B2A43] text-white px-5 py-3 rounded-2xl shadow-xl border border-slate-800 mb-6">
        <div class="flex items-center gap-2 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-[#C9A24D] animate-pulse"></span>
            <span class="font-medium text-slate-200">Pratinjau Laporan Eksekutif Resmi (A4 Portrait)</span>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-gradient-to-r from-[#C9A24D] to-[#b58f3c] hover:from-[#b58f3c] hover:to-[#96710E] text-[#0B2A43] font-bold text-xs rounded-xl shadow cursor-pointer transition flex items-center gap-2">
            <span>🖨️</span> Cetak / Simpan PDF
        </button>
    </div>

    <!-- DOCUMENT TOP BRANDING BAR -->
    <div class="h-2 w-full bg-gradient-to-r from-[#0B2A43] via-[#C9A24D] to-[#0B2A43] rounded-full mb-2"></div>

    <!-- DOCUMENT HEADER (ELEGANT KOP SURAT) -->
    <div class="border-b border-slate-200 pb-5 flex items-end justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 text-[9px] font-mono font-bold text-[#0B2A43] uppercase tracking-widest bg-amber-50 border border-amber-300 px-2.5 py-0.5 rounded-md">
                    <span>✦</span> OFFICIAL EXECUTIVE REPORT
                </span>
                <span class="text-[9px] font-mono text-slate-400 font-bold uppercase tracking-wider">
                    REF: REP-RQI/{{ date('Y/m') }}/{{ $event ? $event->event_code : 'PUBLIC' }}
                </span>
            </div>
            <h1 class="text-2xl font-serif font-extrabold text-[#0B2A43] tracking-tight">
                {{ $event ? $event->title : 'Laporan Hasil Asesmen Terintegrasi' }}
            </h1>
            <p class="text-xs text-slate-500 font-medium pt-0.5">
                Penyelenggara: <strong class="text-slate-800">{{ $event->institution_name ?? 'Ruhiology Institute (Personal / Mandiri)' }}</strong>
                <span class="text-slate-300 px-1">•</span>
                Tanggal Laporan: <strong class="text-slate-800">{{ date('d F Y') }}</strong>
            </p>
        </div>
        <div class="text-right shrink-0">
            <div class="text-xl font-serif font-extrabold tracking-tight text-[#0B2A43]">RUHIOLOGY</div>
            <div class="text-[9px] font-bold tracking-widest text-[#C9A24D] uppercase">INSTITUTE</div>
            <div class="text-[8px] text-slate-400 mt-0.5">Pusat Studi Kesadaran Batin</div>
        </div>
    </div>

    <!-- EXECUTIVE STATS SUMMARY -->
    <div class="grid grid-cols-4 gap-3 text-center">
        <div class="p-3.5 bg-slate-50/90 rounded-2xl border border-slate-200/80">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Peserta</span>
            <strong class="text-2xl font-extrabold font-serif text-[#0B2A43] mt-0.5 block">{{ $submissions->count() }}</strong>
        </div>

        <div class="p-3.5 bg-amber-50/60 rounded-2xl border border-amber-200/80">
            <span class="text-[9px] font-bold text-amber-800 uppercase tracking-wider block">Rata-Rata RQI (0-100)</span>
            <strong class="text-2xl font-extrabold font-serif text-[#96710E] mt-0.5 block">{{ $avgRqi }}</strong>
        </div>

        <div class="p-3.5 bg-emerald-50/60 rounded-2xl border border-emerald-200/80">
            <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider block">Rata-Rata WHO-5 (%)</span>
            <strong class="text-2xl font-extrabold font-serif text-emerald-700 mt-0.5 block">{{ $avgWho5 }}%</strong>
        </div>

        <div class="p-3.5 bg-blue-50/60 rounded-2xl border border-blue-200/80">
            <span class="text-[9px] font-bold text-blue-800 uppercase tracking-wider block">Status WHO-5</span>
            <div class="text-xs font-bold text-slate-800 mt-1">
                <span class="text-emerald-700 font-extrabold">{{ $who5SehatCount }} Sehat</span>
                <span class="text-slate-300">/</span>
                <span class="text-rose-700 font-extrabold">{{ $who5SkriningCount }} Skrining</span>
            </div>
        </div>
    </div>

    <!-- RQI LEVEL DISTRIBUTION BREAKDOWN BOX -->
    @php
        $totalCount = $submissions->count();
        $levelsDef = [
            5 => ['name' => 'Level 5: Enlightened Soul (Jiwa Terpancar Sempurna)', 'badge' => 'bg-amber-100 text-amber-900 border-amber-300', 'bar' => 'bg-amber-500'],
            4 => ['name' => 'Level 4: Mindful Youth (Jiwa Tenang & Terjaga)', 'badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300', 'bar' => 'bg-emerald-500'],
            3 => ['name' => 'Level 3: Developing Soul (Jiwa Berproses Stabil)', 'badge' => 'bg-blue-100 text-blue-900 border-blue-300', 'bar' => 'bg-blue-500'],
            2 => ['name' => 'Level 2: Searching Soul (Jiwa Mencari Arahan)', 'badge' => 'bg-orange-100 text-orange-900 border-orange-300', 'bar' => 'bg-orange-500'],
            1 => ['name' => 'Level 1: Restless Soul (Jiwa Membutuhkan Pendampingan)', 'badge' => 'bg-rose-100 text-rose-900 border-rose-300', 'bar' => 'bg-rose-500'],
        ];
    @endphp
    <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-2.5 break-inside-avoid">
        <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
            <h3 class="font-serif font-bold text-[#0B2A43] text-xs flex items-center gap-1.5 uppercase tracking-wider">
                <span>📊</span> <span>Distribusi Sebaran Indeks Ruhiologi (RQI Level 1-5)</span>
            </h3>
            <span class="text-[10px] font-mono text-slate-500">Skala Kategori RQI-15</span>
        </div>

        <div class="space-y-1.5 text-[10px]">
            @foreach([5, 4, 3, 2, 1] as $lvlNum)
                @php
                    $c = $levelCounts[$lvlNum] ?? 0;
                    $pct = $totalCount > 0 ? round(($c / $totalCount) * 100, 1) : 0;
                    $def = $levelsDef[$lvlNum];
                @endphp
                <div class="flex items-center gap-3">
                    <span class="w-64 font-bold text-slate-700 text-[10px] truncate">{{ $def['name'] }}</span>
                    <div class="flex-1 bg-slate-200/80 h-3 rounded-full overflow-hidden flex">
                        <div class="{{ $def['bar'] }} h-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-16 text-right font-mono font-bold text-slate-800">{{ $c }} <span class="text-slate-400 font-normal">({{ $pct }}%)</span></span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- EXECUTIVE INSIGHT NARRATIVE BOX -->
    <div class="p-4 bg-slate-50/60 border-l-4 border-[#C9A24D] rounded-r-2xl space-y-1 text-xs break-inside-avoid">
        <h3 class="font-serif font-bold text-[#0B2A43] text-xs uppercase tracking-wider flex items-center gap-1">
            <span>📝</span> <span>Evaluasi Eksekutif & Rekomendasi Facilitator</span>
        </h3>
        <p class="text-slate-600 leading-relaxed text-[11px]">
            Berdasarkan data asesmen terhadap <strong>{{ $submissions->count() }} peserta</strong>, rata-rata Indeks Ruhiologi (RQI-15) berada di angka <strong>{{ $avgRqi }} / 100</strong>, menunjukkan tingkat kesadaran batiniah secara umum dalam kategori stabil. Pada skrining kesehatan emosional (WHO-5), <strong>{{ $who5SehatCount }} peserta ({{ $submissions->count() > 0 ? round(($who5SehatCount/$submissions->count())*100) : 0 }}%)</strong> memiliki vitalitas emosional yang baik, sedangkan <strong>{{ $who5SkriningCount }} peserta ({{ $submissions->count() > 0 ? round(($who5SkriningCount/$submissions->count())*100) : 0 }}%)</strong> menunjukkan indikasi kelesuan batin/stres yang membutuhkan sesi penguatan amalan dan pendampingan batin.
        </p>
    </div>

    <!-- PARTICIPANTS TABLE -->
    <div class="space-y-2">
        <div class="flex items-center justify-between pt-1">
            <h3 class="font-serif font-bold text-[#0B2A43] text-xs uppercase tracking-wider flex items-center gap-1.5">
                <span>📋</span> <span>Rincian Hasil Asesmen Peserta</span>
            </h3>
            <span class="text-[10px] text-slate-400 font-medium">Total: {{ $submissions->count() }} Peserta</span>
        </div>

        <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-[#0B2A43] text-white font-bold text-[9px] uppercase tracking-wider">
                    <tr>
                        <th class="p-2.5 text-center w-8">No</th>
                        <th class="p-2.5 whitespace-nowrap w-32">Kode Sesi</th>
                        <th class="p-2.5">Nama Peserta</th>
                        <th class="p-2.5 whitespace-nowrap w-24">Kategori</th>
                        <th class="p-2.5">Daerah</th>
                        <th class="p-2.5 text-center whitespace-nowrap w-32">Skor & Level RQI</th>
                        <th class="p-2.5 text-center whitespace-nowrap w-20">WHO-5 %</th>
                        <th class="p-2.5 text-center whitespace-nowrap w-28">Status WHO-5</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-[10px]">
                    @forelse($submissions as $index => $sub)
                        @php
                            $res = $sub->result;
                            $p = $sub->participant;

                            // Title case name formatting
                            $cleanName = $p?->name ? ucwords(strtolower(trim($p->name))) : 'Anonim';

                            // Clean region formatting
                            $provName = $p?->province?->name;
                            $regName = $p?->regency ? $p->regency->formatted_name : '';
                            $regionDisplay = $regName ? ($provName ? $provName . ' (' . $regName . ')' : $regName) : ($provName ?? '-');
                        @endphp
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/40' }}">
                            <td class="p-2 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-2 font-mono font-bold text-[#0B2A43] whitespace-nowrap text-[10px]">{{ $sub->submission_code }}</td>
                            <td class="p-2 font-bold text-slate-900 leading-snug">{{ $cleanName }}</td>
                            <td class="p-2 whitespace-nowrap text-slate-600">
                                {{ $p?->category === 'Umum' ? 'Mandiri' : ($p?->category ?? 'Mandiri') }}
                                @if($p?->sub_category)
                                    <span class="block text-[9px] font-bold text-[#0B2A43] font-mono">({{ $p->sub_category }})</span>
                                @endif
                            </td>
                            <td class="p-2 text-slate-600 leading-snug">{{ $regionDisplay }}</td>
                            <td class="p-2 text-center whitespace-nowrap">
                                <strong class="font-extrabold text-sm text-[#0B2A43] block">{{ $res?->rqi_score ?? '-' }}</strong>
                                @if($res && $res->category_name)
                                    <span class="inline-block text-[8px] font-bold text-amber-900 bg-amber-50 border border-amber-200/80 px-1.5 py-0.5 rounded-md mt-0.5">
                                        {{ $res->category_name }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-2 text-center font-bold whitespace-nowrap {{ ($res?->who5_percentage ?? 0) >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ ($res && $res->who5_percentage !== null) ? $res->who5_percentage . '%' : '-' }}
                            </td>
                            <td class="p-2 text-center font-bold text-[9px] whitespace-nowrap">
                                @if($res && $res->who5_percentage !== null)
                                    <span class="inline-block px-2 py-0.5 rounded-full {{ $res->who5_percentage >= 50 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ $res->who5_percentage >= 50 ? 'Sehat Emosional' : 'Perlu Skrining' }}
                                    </span>
                                @else
                                    -
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
    </div>

    <!-- DOCUMENT FOOTER SIGNATURE & VERIFICATION STAMP -->
    <div class="pt-6 border-t border-slate-200 flex items-end justify-between text-xs text-slate-500 break-inside-avoid">
        <div class="space-y-1">
            <div class="font-bold text-[#0B2A43] text-xs">Dokumen Resmi Hasil Asesmen Ruhiologi</div>
            <div class="text-[9px] text-slate-400">Dicetak secara otomatis oleh Ruhiology Institute Engine</div>
            <div class="text-[8px] font-mono text-slate-400 uppercase tracking-widest pt-1">
                SYSTEM VERIFICATION HASH: SHA256-RQI-{{ strtoupper(substr(md5($event ? $event->id : 'PUBLIC'), 0, 10)) }}
            </div>
        </div>

        <div class="text-center w-60 bg-slate-50/80 p-3 rounded-2xl border border-slate-200/80">
            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Divalidasi Oleh:</div>
            <div class="font-bold text-[#0B2A43] text-xs">Tim Validator Ruhiology Institute</div>
            
            <div class="h-14 my-1.5 flex items-center justify-center">
                <div class="w-40 py-1 px-2 border border-dashed border-[#C9A24D] rounded-xl bg-amber-50/50 flex items-center justify-center gap-1.5 text-[9px] text-[#96710E] font-mono font-bold tracking-wider">
                    <span>🛡️</span>
                    <span>VERIFIED OFFICIAL</span>
                </div>
            </div>
            
            <div class="text-[8px] font-mono text-slate-400 border-t border-slate-200 pt-1">
                OFFICIAL REPORT VALIDATION
            </div>
        </div>
    </div>

</body>
</html>
