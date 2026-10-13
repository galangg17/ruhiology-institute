@extends('layouts.admin')

@section('content')
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm;
        }
        .print-avoid-break {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
</style>

<div class="space-y-6 print:space-y-4">

    <!-- Official Print-Only Header (Kop Laporan) -->
    <div class="hidden print:flex items-center justify-between border-b-2 border-[#0B2A43] pb-4 mb-4">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology Institute" class="h-12 w-auto object-contain">
            <div>
                <span class="text-[10px] font-mono font-bold text-amber-700 uppercase tracking-widest block">RUHIOLOGY INSTITUTE — EXECUTIVE AUDIT REPORT</span>
                <h1 class="text-lg font-bold font-serif text-[#0B2A43]">Laporan Detail Hasil Individual Assessment</h1>
            </div>
        </div>
        <div class="text-right font-mono text-[10px] text-slate-600">
            <div>Kode Sesi: <strong class="text-slate-900">{{ $submission->submission_code }}</strong></div>
            <div>Dicetak: {{ now()->format('d M Y, H:i') }} WIB</div>
        </div>
    </div>

    <!-- Top Action Toolbar (Hidden when printing) -->
    <div class="no-print print:hidden flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.results.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1 transition">
                    <span>←</span> <span>Kembali ke Laporan</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-[11px] font-mono font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                    {{ $submission->submission_code }}
                </span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold font-serif text-slate-900">Detail Hasil Individual Assessment</h2>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('assessment.result', $submission->submission_code) }}" target="_blank" class="px-3.5 py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <span>🌐</span> <span>Laporan Publik</span>
            </a>
            <a href="{{ route('assessment.certificate', $submission->submission_code) }}" target="_blank" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <span>📜</span> <span>Sertifikat Resmi</span>
            </a>
            <button onclick="window.print()" type="button" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                <span>🖨️</span> <span>Cetak</span>
            </button>
        </div>
    </div>

    <!-- Participant Demographic & Identity Profile Box -->
    <div class="print-avoid-break bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-slate-100 gap-3">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-[#0B2A43] text-[#C9A24D] font-bold text-base flex items-center justify-center font-mono shrink-0 shadow border border-[#C9A24D]/30">
                    {{ strtoupper(substr($submission->participant->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold font-serif text-slate-900 leading-snug">{{ $submission->participant->name }}</h3>
                    <div class="flex flex-wrap items-center gap-2 mt-0.5">
                        <span class="text-xs font-mono font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            {{ $submission->participant->participant_code }}
                        </span>
                        @if($submission->participant->assessment_code)
                            <span class="text-xs font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                Akses: {{ $submission->participant->assessment_code }}
                            </span>
                        @endif
                        <span class="text-[10px] uppercase font-mono font-bold px-2 py-0.5 rounded-full {{ $submission->submission_type === 'pretest' ? 'bg-sky-100 text-sky-800 border border-sky-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                            {{ $submission->submission_type }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-left sm:text-right text-xs">
                <span class="text-slate-400 font-mono block text-[10px] uppercase">Waktu Pengisian</span>
                <strong class="text-slate-700 font-medium">
                    {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }} WIB
                </strong>
                <span class="text-slate-400 font-mono block text-[10px] mt-0.5">IP: {{ $submission->ip_address ?? '127.0.0.1' }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs font-sans">
            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                <span class="text-slate-400 font-mono text-[10px] uppercase block">Kategori & Instansi</span>
                <strong class="text-slate-900 text-xs block leading-snug">
                    {{ $submission->participant->category }}
                    @php
                        $instName = $submission->participant->sub_category 
                            ?? $submission->participant->occupation_custom 
                            ?? $submission->participant->school_custom 
                            ?? $submission->participant->university_custom 
                            ?? $submission->participant->school?->name 
                            ?? $submission->participant->university?->name 
                            ?? null;
                    @endphp
                    @if($instName)
                        <span class="text-slate-600 block text-[11px] font-normal mt-0.5">{{ $instName }}</span>
                    @endif
                </strong>
            </div>

            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                <span class="text-slate-400 font-mono text-[10px] uppercase block">Wilayah Asal</span>
                <strong class="text-slate-900 text-xs block leading-snug">
                    📍 {{ $submission->participant->regency?->name ?? '-' }}
                </strong>
                <span class="text-slate-500 text-[11px] block">{{ $submission->participant->province?->name ?? 'Tidak diisi' }}</span>
            </div>

            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                <span class="text-slate-400 font-mono text-[10px] uppercase block">Kontak Peserta</span>
                <strong class="text-slate-900 text-xs block truncate">{{ $submission->participant->email }}</strong>
                @if($submission->participant->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $submission->participant->phone) }}" target="_blank" class="text-emerald-700 hover:underline font-mono text-[11px] block mt-0.5 flex items-center gap-1">
                        <span>💬</span> <span>{{ $submission->participant->phone }}</span>
                    </a>
                @else
                    <span class="text-slate-400 text-[11px] block">-</span>
                @endif
            </div>

            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                <span class="text-slate-400 font-mono text-[10px] uppercase block">Event / Program Asesmen</span>
                <strong class="text-slate-900 text-xs block leading-snug">
                    {{ $submission->event?->title ?? ($submission->period?->title ?? 'Asesmen Publik') }}
                </strong>
                <span class="text-slate-500 text-[10px] font-mono block">
                    {{ $submission->period?->instrument?->name ?? 'RQI-20 & WHO-5' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Dual Metric Score Summary Banner -->
    <div class="print-avoid-break grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
        
        <!-- Score Card 1: RQI-15 -->
        @php
            $rqiScore = number_format($submission->result->rqi_score ?? $submission->result->total_score, 0);
            $maxRqiScore = $submission->result->max_score ?? 75;
            $catName = $submission->result->category_name ?? 'Level 3: Developing Soul (Jiwa Berproses Stabil)';
            $scorePct = round(($rqiScore / $maxRqiScore) * 100, 1);
        @endphp
        <div class="bg-[#0B2A43] text-white p-6 rounded-3xl border border-[#C9A24D]/35 shadow-xl flex flex-col justify-between space-y-4">
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-white/10 pb-3">
                    <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest bg-white/5 px-2.5 py-1 rounded-full border border-[#C9A24D]/30">
                        ✦ SKOR KECERDASAN RUHIOLOGI (RQI-15)
                    </span>
                    <span class="text-[10px] font-mono text-slate-300">Skala 15 - 75 Poin</span>
                </div>

                <div class="flex items-center gap-5">
                    <div class="text-4xl sm:text-5xl font-black font-serif text-white tracking-tight">
                        {{ $rqiScore }} <span class="text-sm font-normal text-slate-400 font-sans">/ {{ $maxRqiScore }} Poin</span>
                    </div>
                    <span class="font-mono font-bold text-lg text-[#C9A24D] bg-white/10 px-3 py-1 rounded-xl border border-white/10">
                        {{ $scorePct }}%
                    </span>
                </div>

                <div>
                    <span class="inline-block px-3 py-1.5 rounded-xl text-xs font-bold font-mono uppercase bg-amber-500/20 text-[#C9A24D] border border-[#C9A24D]/40">
                        ✦ {{ $catName }}
                    </span>
                </div>

                <div class="bg-white/5 p-4 rounded-2xl border border-white/10 text-xs italic text-slate-200 font-serif leading-relaxed">
                    "{{ $submission->result->overall_interpretation }}"
                </div>
            </div>
        </div>

        <!-- Score Card 2: WHO-5 Index -->
        @php
            $hasWho5 = ($submission->result->who5_percentage !== null);
            $who5Raw = $submission->result->who5_raw_score ?? 0;
            $who5Pct = $submission->result->who5_percentage ?? 0;
        @endphp
        <div class="bg-[#0B2A43] text-white p-6 rounded-3xl border border-emerald-500/35 shadow-xl flex flex-col justify-between space-y-4">
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-white/10 pb-3">
                    <span class="text-[10px] font-mono font-bold text-emerald-400 uppercase tracking-widest bg-emerald-500/10 px-2.5 py-1 rounded-full border border-emerald-500/30">
                        🌿 SKRINING KESEJAHTERAAN MENTAL (WHO-5)
                    </span>
                    <span class="text-[10px] font-mono text-slate-300">Skala 0 - 25 Poin</span>
                </div>

                <div class="flex items-center gap-5">
                    <div class="text-4xl sm:text-5xl font-black font-serif text-emerald-400 tracking-tight">
                        {{ number_format($who5Pct, 0) }}% <span class="text-sm font-normal text-slate-400 font-sans">({{ $who5Raw }} / 25 Poin)</span>
                    </div>
                </div>

                <div>
                    <span class="inline-block px-3 py-1.5 rounded-xl text-xs font-bold font-mono uppercase {{ $who5Pct >= 50 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/40' : 'bg-rose-500/20 text-rose-300 border border-rose-400/40' }}">
                        🌱 {{ $who5Pct >= 50 ? 'Kesejahteraan Mental Terpenuhi' : 'Perlu Pendampingan / Skrining Lanjut' }}
                    </span>
                </div>

                <div class="bg-white/5 p-4 rounded-2xl border border-white/10 text-xs text-slate-200 leading-relaxed">
                    "{{ $submission->result->who5_screening_note ?? 'Pemetaan kesejahteraan emosional harian gaya hidup.' }}"
                </div>
            </div>
        </div>

    </div>

    <!-- Personal Commitment / Tekad Perubahan Batin if present -->
    @if(!empty($submission->result->reflection_text))
        <div class="print-avoid-break bg-amber-50 p-6 rounded-3xl border border-amber-200 shadow-xs space-y-2">
            <div class="flex items-center gap-2">
                <span class="text-base">✨</span>
                <h4 class="font-bold font-serif text-amber-950 text-sm uppercase font-mono tracking-wider">Tekad Perubahan Batin Peserta</h4>
            </div>
            <p class="text-xs text-slate-800 italic bg-white p-4 rounded-2xl border border-amber-200/80 leading-relaxed">
                "{{ $submission->result->reflection_text }}"
            </p>
        </div>
    @endif

    <!-- Pretest vs Posttest Delta Comparison Banner if available -->
    @if(isset($pretestSubmission) && isset($posttestSubmission) && $pretestSubmission && $posttestSubmission && $pretestSubmission->result && $posttestSubmission->result)
        <div class="print-avoid-break bg-gradient-to-r from-[#0B2A43] to-[#123B59] text-white p-6 rounded-3xl border border-[#C9A24D]/30 shadow-xl space-y-4 font-sans">
            <div class="flex justify-between items-center border-b border-white/10 pb-3">
                <h3 class="text-[#C9A24D] font-bold text-xs uppercase tracking-wider font-serif flex items-center gap-2">
                    <span>🔄</span> <span>Komparasi Longitudinal: Pretest vs Posttest</span>
                </h3>
                <span class="text-[10px] font-mono text-slate-300">Evaluasi Perkembangan Intervensi</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-[10px] text-slate-400 uppercase block font-bold font-mono">Skor Pretest</span>
                    <span class="text-2xl font-bold text-sky-400 font-mono">{{ number_format($pretestSubmission->result->percentage, 1) }}%</span>
                    <span class="text-[11px] text-slate-300 block mt-0.5">({{ number_format($pretestSubmission->result->rqi_score ?? 0, 0) }} Poin)</span>
                </div>
                <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-[10px] text-slate-400 uppercase block font-bold font-mono">Skor Posttest</span>
                    <span class="text-2xl font-bold text-emerald-400 font-mono">{{ number_format($posttestSubmission->result->percentage, 1) }}%</span>
                    <span class="text-[11px] text-slate-300 block mt-0.5">({{ number_format($posttestSubmission->result->rqi_score ?? 0, 0) }} Poin)</span>
                </div>
                <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-[10px] text-slate-400 uppercase block font-bold font-mono">Delta Perkembangan</span>
                    @php
                        $delta = $posttestSubmission->result->percentage - $pretestSubmission->result->percentage;
                    @endphp
                    <span class="text-2xl font-bold font-mono {{ $delta >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ $delta >= 0 ? '+' : '' }}{{ number_format($delta, 1) }}%
                    </span>
                    <span class="text-[11px] text-slate-300 block mt-0.5">Selisih capaian pasca program</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Visual Radar Chart & Dimensions Breakdown Table Grid -->
    <div class="print-avoid-break grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Radar Chart Column (5 cols) -->
        <div class="lg:col-span-5 bg-[#0B2A43] text-white p-6 rounded-3xl border border-slate-800 shadow-md space-y-4">
            <div>
                <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">GRAFIK RADAR PILAR</span>
                <h4 class="font-serif font-bold text-base text-white mt-0.5">Keseimbangan Potensi Ruh</h4>
                <p class="text-xs text-slate-300 mt-1">Pemetaan seimbang 5 dimensi ruhiologi peserta.</p>
            </div>

            <div class="relative w-full aspect-square max-w-[280px] mx-auto py-2">
                <canvas id="adminRadarChart"></canvas>
            </div>
        </div>

        <!-- Dimension Results Table (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Capaian Per Dimensi Ruhiologi</h3>
                <span class="text-xs font-mono font-bold text-amber-800 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                    5 Pilar RQI
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-3">Kode</th>
                            <th class="p-3">Dimensi</th>
                            <th class="p-3">Skor</th>
                            <th class="p-3">Persentase</th>
                            <th class="p-3">Interpretasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($submission->result->dimensionResults as $d)
                            <tr class="hover:bg-slate-50/70">
                                <td class="p-3 font-mono font-bold text-amber-800">{{ $d->dimension->code }}</td>
                                <td class="p-3 font-bold text-slate-900">{{ $d->dimension->name }}</td>
                                <td class="p-3 text-slate-600 font-mono">{{ $d->score }} / {{ $d->max_score }}</td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 font-mono">{{ number_format($d->percentage, 1) }}%</span>
                                        <div class="w-16 bg-slate-200 h-1.5 rounded-full overflow-hidden shrink-0">
                                            <div class="bg-amber-600 h-1.5 rounded-full" style="width: {{ min(100, $d->percentage) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 text-slate-600 leading-snug text-[11px]">{{ $d->interpretation }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Answers Breakdown Table (Item-by-Item) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6 space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 pb-4 gap-2">
            <div>
                <h3 class="font-bold font-serif text-slate-900 text-base">Jawaban Per Butir Soal (Raw & Calculated Score)</h3>
                <p class="text-xs text-slate-500">Daftar rekonsiliasi jawaban peserta, bobot mentah, serta kalkulasi reverse scoring.</p>
            </div>
            <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-3 py-1 rounded-full">
                {{ count($submission->answers) }} Butir Soal
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-3 w-10 text-center">#</th>
                        <th class="p-3">Soal Asesmen</th>
                        <th class="p-3">Dimensi</th>
                        <th class="p-3 text-center">Arah Penilaian</th>
                        <th class="p-3">Jawaban Peserta</th>
                        <th class="p-3 text-center">Nilai Mentah</th>
                        <th class="p-3 text-center">Skor Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($submission->answers as $idx => $ans)
                        <tr class="hover:bg-slate-50/70">
                            <td class="p-3 font-bold text-slate-400 text-center font-mono">{{ $idx + 1 }}</td>
                            <td class="p-3 text-slate-900 font-medium max-w-md leading-relaxed">{{ $ans->question->question_text }}</td>
                            <td class="p-3 font-bold text-slate-700 font-mono text-[11px]">{{ $ans->question->dimension->code ?? '-' }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ans->question->scoring_direction === 'reverse' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $ans->question->scoring_direction }}
                                </span>
                            </td>
                            <td class="p-3 font-semibold text-slate-800">{{ $ans->option->option_text ?? '-' }}</td>
                            <td class="p-3 text-slate-600 font-mono text-center">{{ $ans->raw_value }}</td>
                            <td class="p-3 font-bold text-amber-800 font-mono text-center">{{ $ans->calculated_score }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('adminRadarChart');
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
                    label: 'Capaian Dimensi (%)',
                    data: {!! json_encode($dimValues) !!},
                    backgroundColor: 'rgba(201, 162, 77, 0.35)',
                    borderColor: '#C9A24D',
                    borderWidth: 2,
                    pointBackgroundColor: '#C9A24D',
                    pointBorderColor: '#fff',
                    pointRadius: 3
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
