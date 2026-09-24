<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Hasil Asesmen Ruhiologi — Ruhiology Institute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #ffffff; color: #1e293b; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="p-6 sm:p-10 max-w-5xl mx-auto space-y-8">

    <!-- PRINT CONTROL BAR -->
    <div class="no-print bg-[#0B2A43] text-white p-4 rounded-2xl flex items-center justify-between shadow-xl">
        <div class="flex items-center gap-2">
            <span>📄</span>
            <span class="text-xs font-bold">Laporan Eksekutif Hasil Asesmen Ruhiologi (Siap Cetak / Save PDF)</span>
        </div>
        <button onclick="window.print()" class="px-5 py-2 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-bold text-xs rounded-xl shadow cursor-pointer transition">
            🖨️ Cetak / Download PDF →
        </button>
    </div>

    <!-- DOCUMENT HEADER -->
    <div class="border-b-2 border-[#0B2A43] pb-6 flex items-start justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-[#C9A24D] bg-[#0B2A43] px-3 py-1 rounded-full uppercase tracking-widest">
                EXECUTIVE ASSESSMENT REPORT
            </span>
            <h1 class="text-2xl font-serif font-bold text-[#0B2A43] pt-2">
                {{ $event ? $event->title : 'Laporan Hasil Asesmen Terintegrasi' }}
            </h1>
            <p class="text-xs text-slate-500 font-medium">
                Penyelenggara: <strong class="text-slate-800">{{ $event->institution_name ?? 'Ruhiology Institute (Umum)' }}</strong> | 
                Tanggal Cetak: <strong class="text-slate-800">{{ date('d F Y') }}</strong>
            </p>
        </div>
        <div class="text-right">
            <div class="text-lg font-extrabold font-serif text-[#0B2A43]">RUHIOLOGY INSTITUTE</div>
            <div class="text-[10px] text-slate-400">Pusat Studi Kesadaran Batin & Well-Being</div>
        </div>
    </div>

    <!-- EXECUTIVE STATS SUMMARY -->
    <div class="grid grid-cols-4 gap-4 text-center">
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Peserta</span>
            <strong class="text-2xl font-bold text-[#0B2A43]">{{ $submissions->count() }}</strong>
        </div>

        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200">
            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Rata-Rata RQI (0-100)</span>
            <strong class="text-2xl font-bold text-[#B48A16]">{{ $avgRqi }}</strong>
        </div>

        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
            <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Rata-Rata WHO-5 (%)</span>
            <strong class="text-2xl font-bold text-emerald-600">{{ $avgWho5 }}%</strong>
        </div>

        <div class="p-4 bg-blue-50 rounded-2xl border border-blue-200">
            <span class="text-[10px] font-bold text-blue-800 uppercase tracking-wider block">Sehat vs Skrining</span>
            <strong class="text-base font-bold text-slate-800">
                <span class="text-emerald-600">{{ $who5SehatCount }} Sehat</span> / 
                <span class="text-rose-600">{{ $who5SkriningCount }} Perlu Skrining</span>
            </strong>
        </div>
    </div>

    <!-- NARASUMBER / FACILITATOR INSIGHT BOX -->
    <div class="p-5 bg-[#0B2A43]/5 border-2 border-[#0B2A43]/20 rounded-2xl space-y-2 text-xs">
        <h3 class="font-serif font-bold text-[#0B2A43] text-sm flex items-center gap-2">
            <span>💡</span> <span>Catatan Evaluasi Eksekutif & Rekomendasi Narasumber</span>
        </h3>
        <p class="text-slate-700 leading-relaxed">
            Berdasarkan data asesmen terhadap <strong>{{ $submissions->count() }} peserta</strong>, rata-rata Indeks Ruhiologi (RQI-15) berada di angka <strong>{{ $avgRqi }} / 100</strong>, menunjukkan tingkat kesadaran batiniah secara umum dalam kategori stabil. Pada skrining kesehatan emosional (WHO-5), <strong>{{ $who5SehatCount }} peserta ({{ $submissions->count() > 0 ? round(($who5SehatCount/$submissions->count())*100) : 0 }}%)</strong> memiliki vitalitas emosional yang baik, sedangkan <strong>{{ $who5SkriningCount }} peserta ({{ $submissions->count() > 0 ? round(($who5SkriningCount/$submissions->count())*100) : 0 }}%)</strong> menunjukkan indikasi kelesuan batin/stres yang membutuhkan sesi penguatan amalan dan pendampingan batin.
        </p>
    </div>

    <!-- DETAILED PARTICIPANTS TABLE -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="font-serif font-bold text-[#0B2A43] text-sm uppercase tracking-wide">Tabel Rincian Hasil Asesmen Peserta</h3>
            <span class="text-xs text-slate-400 font-medium">Halaman 1 dari 1</span>
        </div>

        <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
            <thead class="bg-[#0B2A43] text-white font-bold text-[10px] uppercase">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">Kode Sesi</th>
                    <th class="p-3">Nama Peserta</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Daerah</th>
                    <th class="p-3 text-center">Skor RQI</th>
                    <th class="p-3 text-center">Skor WHO-5</th>
                    <th class="p-3 text-center">Status WHO-5</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($submissions as $index => $sub)
                    @php
                        $res = $sub->result;
                        $p = $sub->participant;
                    @endphp
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                        <td class="p-3 font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-3 font-mono font-bold text-[#0B2A43]">{{ $sub->submission_code }}</td>
                        <td class="p-3 font-bold text-slate-900">{{ $p->name ?? 'Anonim' }}</td>
                        <td class="p-3">{{ $p->category ?? 'Umum' }}</td>
                        <td class="p-3 text-slate-600">{{ $p->province->name ?? '-' }} ({{ $p->regency->name ?? '-' }})</td>
                        <td class="p-3 text-center font-bold text-[#0B2A43]">{{ $res->rqi_score ?? '-' }}</td>
                        <td class="p-3 text-center font-bold {{ $res->who5_percentage >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $res->who5_percentage !== null ? $res->who5_percentage . '%' : '-' }}
                        </td>
                        <td class="p-3 text-center font-bold text-[10px]">
                            @if($res && $res->who5_percentage !== null)
                                <span class="{{ $res->who5_percentage >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
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

    <!-- DOCUMENT FOOTER SIGNATURE -->
    <div class="pt-8 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
        <div>
            <div>Dokumen Resmi Hasil Uji Ruhiologi & Well-Being</div>
            <div class="text-[10px] text-slate-400">Dicetak secara otomatis oleh Ruhiology Institute Engine</div>
        </div>
        <div class="text-right">
            <div class="font-bold text-[#0B2A43]">Tim Validator Ruhiology Institute</div>
            <div class="h-12"></div>
            <div class="text-[10px] font-mono text-slate-400">VERIFIED OFFICIAL REPORT</div>
        </div>
    </div>

</body>
</html>
