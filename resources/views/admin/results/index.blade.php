@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Title & Export Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold font-serif text-slate-900 tracking-tight">Hasil & Laporan Asesmen RQ</h2>
            <p class="text-xs text-slate-500 mt-0.5">Hasil pengerjaan individu, rekap capaian dimensi, dan perbandingan Pretest vs Posttest.</p>
        </div>
        <a href="{{ route('admin.reports.export_csv', request()->all()) }}" class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow transition flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Export Laporan CSV</span>
        </a>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.results.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 text-xs">
            <div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / kode..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]/20 focus:border-[#0B2A43] focus:bg-white transition">
            </div>
            <div>
                <select name="institution_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]/20 focus:border-[#0B2A43] focus:bg-white transition">
                    <option value="">Semua Institusi</option>
                    @foreach($institutions as $inst)
                        <option value="{{ $inst->id }}" {{ request('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="program_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]/20 focus:border-[#0B2A43] focus:bg-white transition">
                    <option value="">Semua Program</option>
                    @foreach($programs as $prog)
                        <option value="{{ $prog->id }}" {{ request('program_id') == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="submission_type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]/20 focus:border-[#0B2A43] focus:bg-white transition">
                    <option value="">Pretest & Posttest</option>
                    <option value="pretest" {{ request('submission_type') === 'pretest' ? 'selected' : '' }}>Pretest Saja</option>
                    <option value="posttest" {{ request('submission_type') === 'posttest' ? 'selected' : '' }}>Posttest Saja</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-5 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[1050px]">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200 text-[11px] tracking-wider font-mono">
                    <tr>
                        <th class="px-5 py-4 whitespace-nowrap">Submission Code</th>
                        <th class="px-5 py-4 whitespace-nowrap">Peserta</th>
                        <th class="px-5 py-4 whitespace-nowrap">Institusi & Program</th>
                        <th class="px-5 py-4 whitespace-nowrap">Tipe</th>
                        <th class="px-5 py-4 whitespace-nowrap">Skor Mentah</th>
                        <th class="px-5 py-4 whitespace-nowrap">Capaian (%)</th>
                        <th class="px-5 py-4 whitespace-nowrap">Pre-Post Delta</th>
                        <th class="px-5 py-4 whitespace-nowrap">Interpretasi</th>
                        <th class="px-5 py-4 text-right whitespace-nowrap">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($submissions as $sub)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-[#0B2A43] bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200/80 text-[11px] inline-block">
                                    {{ $sub->submission_code }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <strong class="text-slate-900 font-bold block text-xs">{{ $sub->participant->name ?? 'N/A' }}</strong>
                                <span class="text-slate-400 font-mono text-[10px] block mt-0.5">{{ $sub->participant->participant_code ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-4 max-w-[220px]">
                                @if(optional($sub->participant)->institution)
                                    <span class="font-bold text-slate-800 block text-xs leading-snug">{{ $sub->participant->institution->name }}</span>
                                    <span class="text-slate-500 text-[11px] block leading-snug mt-0.5">{{ optional($sub->participant->program)->name ?? '-' }}</span>
                                @else
                                    <span class="text-slate-400 italic text-xs block">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($sub->submission_type === 'pretest')
                                    <span class="px-2.5 py-1 font-bold uppercase rounded-md text-[10px] bg-sky-50 text-sky-700 border border-sky-200/80 tracking-wider">PRETEST</span>
                                @else
                                    <span class="px-2.5 py-1 font-bold uppercase rounded-md text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200/80 tracking-wider">POSTTEST</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-700">
                                <span class="font-bold text-slate-900">{{ $sub->result->total_score ?? 0 }}</span>
                                <span class="text-slate-400"> / {{ $sub->result->max_score ?? 0 }}</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-extrabold text-slate-900 text-xs">
                                {{ number_format($sub->result->percentage ?? 0, 1) }}%
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-mono font-bold">
                                @if($sub->result && $sub->result->pre_post_diff !== null)
                                    <span class="{{ $sub->result->pre_post_diff >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $sub->result->pre_post_diff >= 0 ? '+' : '' }}{{ number_format($sub->result->pre_post_diff, 1) }}%
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-600 text-[11px] max-w-xs truncate" title="{{ $sub->result->overall_interpretation ?? '' }}">
                                {{ $sub->result->overall_interpretation ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.results.show', $sub->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#0B2A43] hover:bg-[#123B59] text-white rounded-lg text-xs font-bold shadow-sm transition hover:shadow">
                                    <span>Lihat Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-8 text-center text-slate-400 text-xs">
                                Tidak ada data hasil asesmen ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $submissions->links() }}
        </div>
    </div>
</div>
@endsection
