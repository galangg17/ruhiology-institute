@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- BREADCRUMB & EVENT HEADER -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#0B2A43] flex items-center gap-1">
                <span>← Kembali ke Daftar Event</span>
            </a>
            <span class="font-mono text-xs font-extrabold text-[#C9A24D] bg-[#0B2A43] px-3 py-1 rounded-full uppercase">
                KODE: {{ $event->event_code }}
            </span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h1 class="text-2xl font-bold font-serif text-[#0B2A43]">{{ $event->title }}</h1>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                    <span>🏛️ {{ $event->institution_name ?? 'Umum' }}</span>
                    <span>•</span>
                    <span>📅 {{ $event->start_date ? $event->start_date->format('d M Y') : 'Kapan Saja' }}</span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.reports.export_csv', ['event_id' => $event->id]) }}" class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                    <span>📥 Export Excel (.xlsx)</span>
                </a>
                <a href="{{ route('admin.reports.export_pdf', ['event_id' => $event->id]) }}" target="_blank" class="px-4 py-2.5 bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                    <span>📄 Export Laporan PDF</span>
                </a>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 text-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Submissions</span>
                <strong class="text-2xl font-bold text-[#0B2A43]">{{ $totalSubmissions }}</strong>
            </div>

            <div class="p-4 bg-amber-50/70 rounded-2xl border border-amber-200/80 text-center">
                <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Rata-Rata RQI (0-100)</span>
                <strong class="text-2xl font-bold text-[#B48A16]">{{ $avgRqi }}</strong>
            </div>

            <div class="p-4 bg-emerald-50/70 rounded-2xl border border-emerald-200/80 text-center">
                <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Rata-Rata WHO-5 (%)</span>
                <strong class="text-2xl font-bold text-emerald-600">{{ $avgWho5 }}%</strong>
            </div>

            <div class="p-4 bg-blue-50/70 rounded-2xl border border-blue-200/80 text-center">
                <span class="text-[10px] font-bold text-blue-800 uppercase tracking-wider block">WHO-5 Sehat / Skrining</span>
                <strong class="text-lg font-bold text-slate-800">
                    <span class="text-emerald-600">{{ $who5SehatCount }}</span> / <span class="text-rose-600">{{ $who5PerluSkriningCount }}</span>
                </strong>
            </div>
        </div>
    </div>

    <!-- SUBMISSIONS TABLE FOR THIS EVENT -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-serif font-bold text-[#0B2A43] text-base">Daftar Hasil Peserta Event Ini</h3>
            <span class="text-xs text-slate-500 font-medium">Menampilkan {{ $submissions->count() }} data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-sans">
                <thead class="bg-[#0B2A43] text-white uppercase font-bold text-[11px] tracking-wider">
                    <tr>
                        <th class="p-4">Kode Sesi</th>
                        <th class="p-4">Peserta</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Daerah</th>
                        <th class="p-4 text-center">Indeks RQI (0-100)</th>
                        <th class="p-4 text-center">Kesejahteraan WHO-5 (%)</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($submissions as $sub)
                        @php
                            $res = $sub->result;
                            $p = $sub->participant;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 font-mono font-bold text-[#B48A16]">{{ $sub->submission_code }}</td>
                            <td class="p-4">
                                <strong class="text-slate-900 block font-bold text-sm">{{ $p->name ?? 'Anonim' }}</strong>
                                <span class="text-[10px] text-slate-400">{{ $p->assessment_code ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $p->category ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $p->province->name ?? '-' }}
                                <span class="block text-[10px] text-slate-400">{{ $p->regency->name ?? '' }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="font-bold text-sm text-[#0B2A43]">{{ $res->rqi_score ?? '-' }}</span>
                                <span class="block text-[10px] font-bold text-amber-700">{{ $res->category_name ?? '-' }}</span>
                            </td>
                            <td class="p-4 text-center">
                                @if($res && $res->who5_percentage !== null)
                                    <span class="font-bold text-sm {{ $res->who5_percentage >= 50 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $res->who5_percentage }}%
                                    </span>
                                    <span class="block text-[10px] font-bold {{ $res->who5_percentage >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $res->who5_percentage >= 50 ? '🟢 Sehat' : '🔴 Skrining' }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.results.show', $sub) }}" class="px-3 py-1.5 bg-[#0B2A43] hover:bg-[#123B59] text-white text-[11px] font-bold rounded-lg shadow transition">
                                    Detail Hasil →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                Belum ada jawaban peserta yang masuk pada event ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $submissions->links() }}
        </div>
    </div>

</div>
@endsection
