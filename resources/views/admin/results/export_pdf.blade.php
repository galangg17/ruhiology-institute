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
        }
    </style>
</head>
<body class="p-6 sm:p-10 max-w-5xl mx-auto space-y-6 text-slate-800">

    <!-- FLOATING MINIMALIST PRINT BUTTON (Hidden on Print) -->
    <div class="no-print flex items-center justify-between bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-xl border border-slate-800 mb-6">
        <div class="flex items-center gap-2 text-xs">
            <span class="w-2 h-2 rounded-full bg-[#C9A24D] animate-pulse"></span>
            <span class="font-medium text-slate-300">Pratinjau Dokumen Cetak Eksekutif (A4 Portrait)</span>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-[#C9A24D] hover:bg-[#b58f3c] text-[#0B2A43] font-bold text-xs rounded-xl shadow cursor-pointer transition flex items-center gap-2">
            <span>🖨️</span> Cetak / Simpan PDF
        </button>
    </div>

    <!-- DOCUMENT HEADER (ELEGANT KOP SURAT) -->
    <div class="border-b border-slate-200 pb-5 flex items-end justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 text-[9px] font-mono font-bold text-[#96710E] uppercase tracking-widest bg-amber-50 border border-amber-200/60 px-2.5 py-0.5 rounded-md mb-1">
                <span>✦</span> EXECUTIVE REPORT
            </div>
            <h1 class="text-2xl font-serif font-extrabold text-[#0B2A43] tracking-tight">
                {{ $event ? $event->title : 'Laporan Hasil Asesmen Terintegrasi' }}
            </h1>
            <p class="text-xs text-slate-500 font-medium pt-0.5">
                Penyelenggara: <strong class="text-slate-800">{{ $event->institution_name ?? 'Ruhiology Institute (Personal / Mandiri)' }}</strong>
                <span class="text-slate-300 px-1">•</span>
                Tanggal: <strong class="text-slate-800">{{ date('d F Y') }}</strong>
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
        <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200/70">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Peserta</span>
            <strong class="text-xl font-extrabold font-serif text-[#0B2A43] mt-0.5 block">{{ $submissions->count() }}</strong>
        </div>

        <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200/70">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata RQI (0-100)</span>
            <strong class="text-xl font-extrabold font-serif text-[#96710E] mt-0.5 block">{{ $avgRqi }}</strong>
        </div>

        <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200/70">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata WHO-5 (%)</span>
            <strong class="text-xl font-extrabold font-serif text-emerald-700 mt-0.5 block">{{ $avgWho5 }}%</strong>
        </div>

        <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200/70">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Status WHO-5</span>
            <div class="text-xs font-bold text-slate-800 mt-1">
                <span class="text-emerald-700">{{ $who5SehatCount }} Sehat</span>
                <span class="text-slate-300">/</span>
                <span class="text-rose-700">{{ $who5SkriningCount }} Skrining</span>
            </div>
        </div>
    </div>

    <!-- EXECUTIVE INSIGHT BOX -->
    <div class="p-4 bg-slate-50/60 border-l-2 border-[#C9A24D] rounded-r-xl space-y-1 text-xs break-inside-avoid">
        <h3 class="font-serif font-bold text-[#0B2A43] text-xs">
            Evaluasi Eksekutif & Rekomendasi Facilitator
        </h3>
        <p class="text-slate-600 leading-relaxed text-[11px]">
            Berdasarkan data asesmen terhadap <strong>{{ $submissions->count() }} peserta</strong>, rata-rata Indeks Ruhiologi (RQI-15) berada di angka <strong>{{ $avgRqi }} / 100</strong>, menunjukkan tingkat kesadaran batiniah secara umum dalam kategori stabil. Pada skrining kesehatan emosional (WHO-5), <strong>{{ $who5SehatCount }} peserta ({{ $submissions->count() > 0 ? round(($who5SehatCount/$submissions->count())*100) : 0 }}%)</strong> memiliki vitalitas emosional yang baik, sedangkan <strong>{{ $who5SkriningCount }} peserta ({{ $submissions->count() > 0 ? round(($who5SkriningCount/$submissions->count())*100) : 0 }}%)</strong> menunjukkan indikasi kelesuan batin/stres yang membutuhkan sesi penguatan amalan dan pendampingan batin.
        </p>
    </div>

    <!-- PARTICIPANTS TABLE -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <h3 class="font-serif font-bold text-[#0B2A43] text-xs uppercase tracking-wider">Rincian Hasil Asesmen Peserta</h3>
            <span class="text-[10px] text-slate-400 font-medium">Total: {{ $submissions->count() }} Peserta</span>
        </div>

        <div class="rounded-xl border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-[#0B2A43] text-white font-bold text-[9px] uppercase tracking-wider">
                    <tr>
                        <th class="p-2.5 text-center w-8">No</th>
                        <th class="p-2.5 whitespace-nowrap w-36">Kode Sesi</th>
                        <th class="p-2.5">Nama Peserta</th>
                        <th class="p-2.5 whitespace-nowrap w-24">Kategori</th>
                        <th class="p-2.5">Daerah</th>
                        <th class="p-2.5 text-center whitespace-nowrap w-20">Skor RQI</th>
                        <th class="p-2.5 text-center whitespace-nowrap w-20">WHO-5 %</th>
                        <th class="p-2.5 text-center whitespace-nowrap w-28">Status WHO-5</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-[11px]">
                    @forelse($submissions as $index => $sub)
                        @php
                            $res = $sub->result;
                            $p = $sub->participant;
                        @endphp
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/40' }}">
                            <td class="p-2.5 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-2.5 font-mono font-bold text-[#0B2A43] whitespace-nowrap">{{ $sub->submission_code }}</td>
                            <td class="p-2.5 font-bold text-slate-900">{{ $p?->name ?? 'Anonim' }}</td>
                            <td class="p-2.5 whitespace-nowrap text-slate-600">{{ $p?->category ?? 'Mandiri' }}</td>
                            <td class="p-2.5 text-slate-600">{{ $p?->province?->name ?? '-' }} {{ $p?->regency ? '('.$p?->regency?->name.')' : '' }}</td>
                            <td class="p-2.5 text-center font-extrabold text-[#0B2A43] whitespace-nowrap">{{ $res->rqi_score ?? '-' }}</td>
                            <td class="p-2.5 text-center font-bold whitespace-nowrap {{ ($res->who5_percentage ?? 0) >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $res->who5_percentage !== null ? $res->who5_percentage . '%' : '-' }}
                            </td>
                            <td class="p-2.5 text-center font-bold text-[10px] whitespace-nowrap">
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

    <!-- DOCUMENT FOOTER SIGNATURE & VALIDATION -->
    <div class="pt-6 border-t border-slate-200 flex items-end justify-between text-xs text-slate-500 break-inside-avoid">
        <div class="space-y-0.5">
            <div class="font-bold text-slate-700">Dokumen Resmi Hasil Asesmen Ruhiologi</div>
            <div class="text-[10px] text-slate-400">Dicetak secara otomatis oleh Ruhiology Institute Engine</div>
        </div>
        <div class="text-center w-56">
            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Divalidasi Oleh:</div>
            <div class="font-bold text-[#0B2A43] text-xs">Tim Validator Ruhiology Institute</div>
            <div class="h-12 my-1 flex items-center justify-center">
                <div class="w-20 h-9 border border-dashed border-slate-300 rounded flex items-center justify-center text-[8px] text-slate-400 font-mono uppercase tracking-widest">
                    [ STAMP / TTD ]
                </div>
            </div>
            <div class="text-[9px] font-mono text-slate-400 border-t border-slate-200 pt-1">VERIFIED OFFICIAL REPORT</div>
        </div>
    </div>

</body>
</html>
