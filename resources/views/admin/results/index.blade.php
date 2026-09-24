@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- HEADER TITLE & DYNAMIC EXPORT BUTTONS -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 bg-[#0B2A43]/10 text-[#0B2A43] text-[10px] font-mono font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <span>📊 INTEGRATED ASSESSMENT ANALYTICS & EXPORT</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-[#0B2A43]">Hasil & Rekap Asesmen Ruhiologi</h1>
            <p class="text-xs text-slate-500 mt-1">Laporan rekapitulasi nilai Indeks Ruhiologi (RQI-15) & Skrining Kesejahteraan Emosional (WHO-5).</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('admin.reports.export_csv', request()->all()) }}" class="px-4 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-2xl shadow-md transition flex items-center gap-2">
                <span>📥 Export Excel (.xlsx)</span>
            </a>
            <a href="{{ route('admin.reports.export_pdf', request()->all()) }}" target="_blank" class="px-4 py-3 bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs rounded-2xl shadow-md transition flex items-center gap-2">
                <span>📄 Export Laporan PDF</span>
            </a>
        </div>
    </div>

    <!-- STATS SUMMARY CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-sans">
        <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-2xs text-center">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Hasil (Filtered)</span>
            <strong class="text-2xl font-bold text-[#0B2A43]">{{ $stats['total'] }}</strong>
        </div>

        <div class="p-4 bg-amber-50/80 rounded-2xl border border-amber-200/80 shadow-2xs text-center">
            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Rata-Rata RQI (0-100)</span>
            <strong class="text-2xl font-bold text-[#B48A16]">{{ $stats['avg_rqi'] }}</strong>
        </div>

        <div class="p-4 bg-emerald-50/80 rounded-2xl border border-emerald-200/80 shadow-2xs text-center">
            <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Rata-Rata WHO-5 (%)</span>
            <strong class="text-2xl font-bold text-emerald-600">{{ $stats['avg_who5'] }}%</strong>
        </div>

        <div class="p-4 bg-blue-50/80 rounded-2xl border border-blue-200/80 shadow-2xs text-center">
            <span class="text-[10px] font-bold text-blue-800 uppercase tracking-wider block">WHO-5 Sehat / Skrining</span>
            <strong class="text-base font-bold text-slate-800">
                <span class="text-emerald-600">{{ $stats['who5_sehat'] }}</span> / <span class="text-rose-600">{{ $stats['who5_skrining'] }}</span>
            </strong>
        </div>
    </div>

    <!-- MULTI-DIMENSIONAL FILTER BAR -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-serif font-bold text-[#0B2A43] text-xs uppercase tracking-wider flex items-center gap-1.5">
                <span>🔍 Filter Multi-Dimensi Data</span>
            </h3>
            <a href="{{ route('admin.results.index') }}" class="text-[11px] font-bold text-slate-400 hover:text-slate-700">
                🔄 Reset Filter
            </a>
        </div>

        <form action="{{ route('admin.results.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 text-xs">
            
            <!-- 1. Search Query -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Cari Peserta / Kode</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama / Kode..." class="w-full p-2.5 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:ring-[#0B2A43]">
            </div>

            <!-- 2. Event / Program -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Event / Kegiatan</label>
                <select name="event_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50 outline-none">
                    <option value="">Semua Event</option>
                    <option value="PUBLIC_SELF" {{ request('event_id') === 'PUBLIC_SELF' ? 'selected' : '' }}>🟢 Mandiri Publik (Umum)</option>
                    @foreach($events as $e)
                        <option value="{{ $e->id }}" {{ request('event_id') == $e->id ? 'selected' : '' }}>🔵 {{ $e->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Provinsi -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Provinsi</label>
                <select name="province_id" onchange="this.form.submit()" class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50 outline-none">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinces as $prov)
                        <option value="{{ $prov->id }}" {{ request('province_id') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 4. Kabupaten / Kota -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kabupaten/Kota</label>
                <select name="regency_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50 outline-none">
                    <option value="">Semua Kota/Kab</option>
                    @foreach($regencies as $reg)
                        <option value="{{ $reg->id }}" {{ request('regency_id') == $reg->id ? 'selected' : '' }}>{{ $reg->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 5. Kategori Peserta -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kategori Peserta</label>
                <select name="category" class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50 outline-none">
                    <option value="">Semua Kategori</option>
                    <option value="Pelajar" {{ request('category') === 'Pelajar' ? 'selected' : '' }}>Pelajar</option>
                    <option value="Mahasiswa/i" {{ request('category') === 'Mahasiswa/i' ? 'selected' : '' }}>Mahasiswa/i</option>
                    <option value="Umum" {{ request('category') === 'Umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>

            <!-- 6. WHO-5 Status -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Status WHO-5</label>
                <select name="who5_status" class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50 outline-none">
                    <option value="">Semua Status</option>
                    <option value="sehat" {{ request('who5_status') === 'sehat' ? 'selected' : '' }}>🟢 Sehat (≥50%)</option>
                    <option value="skrining" {{ request('who5_status') === 'skrining' ? 'selected' : '' }}>🔴 Perlu Skrining (&lt;50%)</option>
                </select>
            </div>

            <div class="col-span-full pt-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl shadow transition cursor-pointer flex items-center gap-1.5">
                    <span>⚡ Terapkan Filter Hasil</span>
                </button>
            </div>
        </form>
    </div>

    <!-- DATA TABLE -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-sans">
                <thead class="bg-[#0B2A43] text-white font-bold uppercase text-[11px] tracking-wider">
                    <tr>
                        <th class="p-4">Kode Sesi</th>
                        <th class="p-4">Peserta & Identitas</th>
                        <th class="p-4">Event / Kegiatan</th>
                        <th class="p-4">Daerah (Wilayah)</th>
                        <th class="p-4 text-center">Skor RQI (0-100)</th>
                        <th class="p-4 text-center">WHO-5 Index (%)</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($submissions as $sub)
                        @php
                            $p = $sub->participant;
                            $res = $sub->result;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 font-mono font-bold text-[#B48A16]">
                                {{ $sub->submission_code }}
                                <span class="block text-[10px] text-slate-400 font-sans font-normal">{{ $sub->submitted_at ? $sub->submitted_at->format('d/m/Y H:i') : '-' }}</span>
                            </td>

                            <td class="p-4">
                                <strong class="text-slate-900 block font-bold text-sm">{{ $p->name ?? 'Anonim' }}</strong>
                                <span class="text-[11px] text-slate-500 font-normal">
                                    {{ $p->category ?? 'Umum' }} • {{ $p->birth_date ? \Carbon\Carbon::parse($p->birth_date)->age . ' Thn' : '' }}
                                </span>
                            </td>

                            <td class="p-4">
                                <span class="font-bold text-[#0B2A43] block">
                                    {{ $sub->event->title ?? ($sub->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Umum') }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono block">
                                    {{ $sub->event->event_code ?? 'PUBLIC' }}
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
                                <a href="{{ route('admin.results.show', $sub) }}" class="px-3.5 py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white text-[11px] font-bold rounded-xl shadow transition">
                                    Detail Hasil →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400 font-medium">
                                Tidak ada data hasil asesmen yang cocok dengan filter.
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
