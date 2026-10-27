@extends('layouts.admin')

@section('title', 'Manajemen Formulir & Kuisioner')

@section('content')
<div class="space-y-6">

    <!-- TOP HEADER BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold font-serif text-[#0B2A43]">Formulir & Kuisioner Dinamis</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37]/20 text-[#8C703E] border border-[#D4AF37]/30 uppercase tracking-wider">Ruhiology Forms</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Buat kuisioner, survei, form pendataan guru/peserta, atau registrasi khusus layaknya Google Form dengan tautan publik mandiri.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.forms.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-[#0B2A43] to-[#123B59] hover:from-[#123B59] hover:to-[#1a4a6e] text-[#D4AF37] font-bold rounded-xl text-xs shadow-md transition-all flex items-center gap-2 cursor-pointer border border-[#D4AF37]/30 hover:scale-[1.02]">
                <span class="text-sm">➕</span>
                <span>Buat Formulir Baru</span>
            </a>
        </div>
    </div>

    <!-- METRICS CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs space-y-1">
            <span class="text-[10px] uppercase font-bold text-slate-400 font-mono tracking-wider block">Total Formulir</span>
            <div class="text-2xl font-black text-[#0B2A43]">{{ number_format($stats['total'] ?? $stats['total_forms'] ?? 0) }}</div>
            <span class="text-[11px] text-slate-500 font-medium">Semua form dibuat</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-2xs space-y-1 bg-gradient-to-br from-white to-emerald-50/30">
            <span class="text-[10px] uppercase font-bold text-emerald-600 font-mono tracking-wider block">Aktif / Terbuka</span>
            <div class="text-2xl font-black text-emerald-700">{{ number_format($stats['published'] ?? $stats['published_forms'] ?? 0) }}</div>
            <span class="text-[11px] text-emerald-600 font-medium">Menerima respon</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-amber-100 shadow-2xs space-y-1 bg-gradient-to-br from-white to-amber-50/30">
            <span class="text-[10px] uppercase font-bold text-amber-700 font-mono tracking-wider block">Ditutup / Draft</span>
            <div class="text-2xl font-black text-amber-800">{{ number_format($stats['closed'] ?? 0) }}</div>
            <span class="text-[11px] text-amber-700 font-medium">Pengisian nonaktif</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-2xs space-y-1 bg-gradient-to-br from-white to-blue-50/30">
            <span class="text-[10px] uppercase font-bold text-blue-600 font-mono tracking-wider block">Total Respon Masuk</span>
            <div class="text-2xl font-black text-blue-800">{{ number_format($stats['total_responses'] ?? 0) }}</div>
            <span class="text-[11px] text-blue-600 font-medium">Data terkumpul</span>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.forms.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[220px]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul formulir, deskripsi, tautan..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#0B2A43] focus:border-transparent outline-none bg-slate-50 focus:bg-white font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="status" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-300 text-xs font-semibold bg-white text-slate-700 outline-none">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>🟢 Aktif (Published)</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>🔴 Ditutup (Closed)</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>⚪ Draft</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                Cari
            </button>
            @if(request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.forms.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABLE LIST OF FORMS -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-sans">
                <thead class="bg-[#0B2A43] text-white uppercase font-bold text-[11px] tracking-wider">
                    <tr>
                        <th class="p-4 w-12 text-center">No</th>
                        <th class="p-4">Informasi Formulir</th>
                        <th class="p-4">Tautan Publik & QR</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Respon Masuk</th>
                        <th class="p-4 text-center">Batas Waktu</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($forms as $idx => $form)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 text-center text-slate-400 font-mono">{{ $forms->firstItem() + $idx }}</td>
                            <td class="p-4">
                                <a href="{{ route('admin.forms.responses', $form->id) }}" class="font-bold text-slate-900 hover:text-[#0B2A43] hover:underline text-sm block leading-snug">
                                    {{ $form->title }}
                                </a>
                                @if($form->description)
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5 font-normal">{{ Str::limit(strip_tags($form->description), 80) }}</p>
                                @endif
                                <div class="flex items-center gap-2 mt-1.5 text-[10px] text-slate-400 font-mono">
                                    <span>{{ $form->created_at->format('d M Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $form->fields_count ?? $form->fields()->count() }} Kolom Pertanyaan</span>
                                    @if($form->access_code)
                                        <span>•</span>
                                        <span class="text-amber-700 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200 font-bold">🔒 Kunci: {{ $form->access_code }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-1.5" x-data="{ copied: false }">
                                    <input type="text" readonly value="{{ $form->public_url }}" class="p-1.5 bg-slate-50 border border-slate-200 rounded-lg text-[11px] font-mono text-slate-700 w-48 truncate select-all">
                                    <button type="button" @click="navigator.clipboard.writeText('{{ $form->public_url }}'); copied = true; setTimeout(() => copied = false, 2000)" title="Salin Tautan Publik" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition cursor-pointer">
                                        <span x-show="!copied">📋</span>
                                        <span x-show="copied" class="text-emerald-600 font-bold text-[10px]">✓</span>
                                    </button>
                                    <a href="{{ $form->public_url }}" target="_blank" title="Buka Form di Tab Baru" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition">
                                        ↗️
                                    </a>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.forms.toggle_status', $form->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @if($form->status === 'published')
                                        <button type="submit" title="Klik untuk menutup formulir" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 hover:bg-emerald-200 transition cursor-pointer">
                                            🟢 Aktif (Buka)
                                        </button>
                                    @elseif($form->status === 'closed')
                                        <button type="submit" title="Klik untuk membuka formulir" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200 hover:bg-rose-200 transition cursor-pointer">
                                            🔴 Ditutup
                                        </button>
                                    @else
                                        <button type="submit" title="Klik untuk mengaktifkan draft" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-300 hover:bg-slate-200 transition cursor-pointer">
                                            ⚪ Draft
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.forms.responses', $form->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 rounded-full font-bold text-xs transition">
                                    <span>📝</span>
                                    <span>{{ number_format($form->responses_count) }}</span>
                                </a>
                            </td>
                            <td class="p-4 text-center text-slate-500 font-mono text-[11px]">
                                @if($form->closes_at)
                                    <span class="{{ now()->gt($form->closes_at) ? 'text-rose-600 font-bold' : 'text-slate-700' }}">
                                        {{ $form->closes_at->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-slate-400">Tanpa Batas</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.forms.responses', $form->id) }}" class="px-2.5 py-1.5 bg-[#0B2A43] hover:bg-[#123B59] text-white rounded-lg text-xs font-semibold shadow-2xs transition flex items-center gap-1" title="Lihat Data Masuk">
                                        <span>📊 Respon</span>
                                    </a>
                                    <a href="{{ route('admin.forms.edit', $form->id) }}" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition" title="Edit Formulir">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.forms.destroy', $form->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus formulir ini beserta seluruh respon yang masuk?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition cursor-pointer" title="Hapus Formulir">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <div class="text-4xl mb-2">📋</div>
                                <h4 class="font-bold text-slate-700 text-sm">Belum ada formulir kuisioner yang dibuat</h4>
                                <p class="text-xs text-slate-400 mt-1">Buat formulir pertama Anda untuk mengumpulkan data guru, survei, evaluasi, atau pendataan khusus.</p>
                                <a href="{{ route('admin.forms.create') }}" class="inline-block mt-4 px-4 py-2 bg-[#0B2A43] text-white font-bold rounded-xl text-xs hover:bg-[#123B59] transition">
                                    + Buat Formulir Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($forms->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $forms->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
