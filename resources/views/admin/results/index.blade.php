@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- HUB NAV BAR (UNIFIED SWITCHER FOR PESERTA & HASIL EVALUASI) -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-2 font-sans">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.participants.index') }}" 
               class="px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ request()->routeIs('admin.participants.*') ? 'bg-[#0B2A43] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>👥</span>
                <span>Tab 1: Data Peserta & Demografi</span>
            </a>

            <a href="{{ route('admin.results.index') }}" 
               class="px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ request()->routeIs('admin.results.*') ? 'bg-[#0B2A43] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>📈</span>
                <span>Tab 2: Lembar Sesi & Hasil Evaluasi</span>
            </a>
        </div>

        <a href="{{ route('admin.reports.export_pdf') }}" target="_blank" class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
            <span>📄</span>
            <span>Laporan PDF Agregat</span>
        </a>
    </div>

    <!-- HEADER TITLE & DYNAMIC EXPORT BUTTONS -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold font-serif text-slate-900 tracking-tight">Hasil & Rekap Evaluasi Asesmen Ruhiologi</h2>
            <p class="text-xs text-slate-500 mt-0.5">Laporan rekapitulasi skor Indeks Ruhiologi (RQI-15) & Skrining Kesejahteraan Emosional (WHO-5 Index).</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.reports.export_csv', request()->all()) }}" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                <span>📥</span> <span>Export Excel (.csv)</span>
            </a>
            <a href="{{ route('admin.reports.export_pdf', request()->all()) }}" target="_blank" class="px-4 py-2 bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                <span>📄</span> <span>Export Laporan PDF</span>
            </a>
        </div>
    </div>

    <!-- STATS SUMMARY CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 text-xs font-sans">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1 text-center">
            <span class="text-[10px] font-bold text-slate-400 font-mono uppercase tracking-wider block">TOTAL SESI TEST</span>
            <strong class="text-2xl font-black font-mono text-[#0B2A43] block">{{ number_format($stats['total']) }}</strong>
            <span class="text-[10px] text-slate-500 font-medium">Sesi Evaluasi Difilter</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1 text-center">
            <span class="text-[10px] font-bold text-amber-800 font-mono uppercase tracking-wider block">RATA-RATA RQI (0-100)</span>
            <strong class="text-2xl font-black font-mono text-[#B48A16] block">{{ $stats['avg_rqi'] }}</strong>
            <span class="text-[10px] text-amber-900 bg-amber-50 px-2 py-0.5 rounded-full font-bold inline-block">Indeks Ruhiologi</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1 text-center">
            <span class="text-[10px] font-bold text-emerald-800 font-mono uppercase tracking-wider block">RATA-RATA WHO-5 (%)</span>
            <strong class="text-2xl font-black font-mono text-emerald-600 block">{{ $stats['avg_who5'] }}%</strong>
            <span class="text-[10px] text-emerald-900 bg-emerald-50 px-2 py-0.5 rounded-full font-bold inline-block">Kesejahteraan Mental</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1 text-center">
            <span class="text-[10px] font-bold text-blue-800 font-mono uppercase tracking-wider block">STATUS WHO-5</span>
            <strong class="text-base font-bold text-slate-800 block pt-1">
                <span class="text-emerald-600 font-mono text-lg font-black">{{ $stats['who5_sehat'] }}</span> <span class="text-slate-400 font-normal">Sehat</span> / <span class="text-rose-600 font-mono text-lg font-black">{{ $stats['who5_skrining'] }}</span> <span class="text-slate-400 font-normal">Skrining</span>
            </strong>
        </div>
    </div>

    <!-- MULTI-DIMENSIONAL FILTER BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3 font-sans text-xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <h3 class="font-bold text-[#0B2A43] text-xs uppercase tracking-wider flex items-center gap-1.5 font-mono">
                <span>🔍 Filter Multi-Dimensi Data Hasil Evaluasi</span>
            </h3>
            <a href="{{ route('admin.results.index') }}" class="text-[11px] font-bold text-slate-400 hover:text-slate-700">
                🔄 Reset Filter
            </a>
        </div>

        <form action="{{ route('admin.results.index') }}" method="GET" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                
                <!-- 1. Search Query -->
                <div class="lg:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Cari Peserta atau Kode Sesi</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama peserta, kode submission SUB-..., kode asesmen..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#0B2A43]">
                        <span class="absolute left-3 top-2.5 text-slate-400">🔍</span>
                    </div>
                </div>

                <!-- 2. Event / Program -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Event / Akses Pendaftaran</label>
                    <select name="event_id" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Event & Pendaftaran</option>
                        <option value="PUBLIC_SELF" {{ request('event_id') === 'PUBLIC_SELF' ? 'selected' : '' }}>🌐 Mandiri Publik (Umum)</option>
                        @foreach($events as $e)
                            <option value="{{ $e->id }}" {{ request('event_id') == $e->id ? 'selected' : '' }}>🔒 {{ $e->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Provinsi -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Provinsi (Demografi)</label>
                    <select name="province_id" onchange="this.form.submit()" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}" {{ request('province_id') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1">
                <!-- 4. Kabupaten / Kota -->
                <div>
                    <select name="regency_id" onchange="this.form.submit()" {{ !request('province_id') ? 'disabled' : '' }} class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white disabled:bg-slate-100 disabled:text-slate-400">
                        <option value="">{{ request('province_id') ? 'Semua Kota/Kabupaten' : 'Pilih Provinsi Dahulu' }}</option>
                        @foreach($regencies as $reg)
                            <option value="{{ $reg->id }}" {{ request('regency_id') == $reg->id ? 'selected' : '' }}>{{ $reg->formatted_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 5. Kategori Peserta -->
                <div>
                    <select name="category" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Kategori</option>
                        <option value="Pelajar" {{ request('category') === 'Pelajar' ? 'selected' : '' }}>Pelajar</option>
                        <option value="Mahasiswa/i" {{ request('category') === 'Mahasiswa/i' ? 'selected' : '' }}>Mahasiswa/i</option>
                        <option value="Umum" {{ request('category') === 'Umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>

                <!-- 6. WHO-5 Status -->
                <div>
                    <select name="who5_status" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Status WHO-5</option>
                        <option value="sehat" {{ request('who5_status') === 'sehat' ? 'selected' : '' }}>🟢 Sehat (≥50%)</option>
                        <option value="skrining" {{ request('who5_status') === 'skrining' ? 'selected' : '' }}>🔴 Perlu Skrining (&lt;50%)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl transition shadow-sm cursor-pointer">
                        ⚡ Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden font-sans">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs font-mono font-bold text-slate-600">
            <span>HASIL ASESMEN TERTERA ({{ $submissions->total() }} Sesi Evaluasi)</span>
            <span class="text-[11px] text-slate-400 font-normal">Halaman {{ $submissions->currentPage() }} dari {{ $submissions->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[1050px]">
                <thead class="bg-slate-100 text-slate-700 uppercase font-bold border-b border-slate-200 text-[11px] tracking-wider font-mono">
                    <tr>
                        <th class="px-4 py-3.5 whitespace-nowrap">Kode Sesi & Tanggal</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Nama Peserta</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Akses & Event</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Tipe Sesi</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Demografi Wilayah</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Skor RQI (0-100)</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">WHO-5 (%)</th>
                        <th class="px-4 py-3.5 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($submissions as $sub)
                        @php
                            $p = $sub->participant;
                            $res = $sub->result;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Submission Code & Date -->
                            <td class="p-4">
                                <span class="font-mono font-bold text-amber-800 text-xs block">{{ $sub->submission_code }}</span>
                                <span class="text-[10px] text-slate-400 font-mono block mt-0.5">⏰ {{ $sub->submitted_at ? $sub->submitted_at->format('d/m/Y H:i') : '-' }} WIB</span>
                            </td>

                            <!-- Participant -->
                            <td class="p-4">
                                <a href="{{ route('admin.participants.index', ['q' => $p?->name]) }}" class="font-bold text-slate-900 hover:text-[#0B2A43] hover:underline text-xs block leading-snug">
                                    {{ $p?->name ?? 'Anonim' }}
                                </a>
                                <span class="text-[11px] text-slate-500 font-normal">
                                    {{ $p?->category === 'Umum' ? 'Personal / Mandiri' : ($p?->category ?? 'Mandiri') }}
                                    @if($p?->sub_category)
                                        <span class="inline-block bg-blue-50 text-blue-900 border border-blue-200 text-[10px] font-bold px-1.5 py-0.2 rounded ml-1">{{ $p->sub_category }}</span>
                                    @endif
                                </span>
                            </td>

                            <!-- Event / Access -->
                            <td class="p-4">
                                <span class="font-bold text-[#0B2A43] block text-xs">
                                    {{ $sub->event->title ?? ($sub->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Umum') }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono block">
                                    {{ $sub->event->event_code ?? 'PUBLIC' }}
                                </span>
                            </td>

                            <!-- Session Type (Pretest vs Posttest) -->
                            <td class="p-4">
                                @if(strtolower($sub->submission_type) === 'pretest')
                                    <span class="px-2.5 py-0.5 bg-sky-100 text-sky-800 text-[10px] font-extrabold uppercase rounded-full border border-sky-300">
                                        PRETEST
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase rounded-full border border-emerald-300">
                                        POSTTEST
                                    </span>
                                @endif
                            </td>

                            <!-- Region -->
                            <td class="p-4 text-slate-600">
                                <span class="font-bold text-slate-800 text-xs block">📍 {{ $p?->province?->name ?? '-' }}</span>
                                <span class="block text-[10px] text-slate-400">{{ $p?->regency?->name ?? '-' }}</span>
                            </td>

                            <!-- RQI Score -->
                            <td class="p-4 text-center">
                                <span class="font-mono font-bold text-sm text-[#0B2A43] block">{{ $res->rqi_score ?? '-' }}</span>
                                <span class="text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.2 rounded border border-amber-200 inline-block">{{ $res->category_name ?? '-' }}</span>
                            </td>

                            <!-- WHO-5 Score -->
                            <td class="p-4 text-center">
                                @if($res && $res->who5_percentage !== null)
                                    <span class="font-mono font-bold text-sm block {{ $res->who5_percentage >= 50 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $res->who5_percentage }}%
                                    </span>
                                    <span class="text-[10px] font-bold inline-block px-2 py-0.2 rounded {{ $res->who5_percentage >= 50 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                                        {{ $res->who5_percentage >= 50 ? '🟢 Sehat' : '🔴 Skrining' }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.results.show', $sub) }}" class="px-3 py-1.5 bg-[#0B2A43] hover:bg-[#123B59] text-white text-[11px] font-bold rounded-xl shadow-xs transition">
                                        Detail Hasil →
                                    </a>
                                    <a href="{{ route('assessment.certificate', $sub->submission_code) }}" target="_blank" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-lg text-xs font-bold transition" title="Cetak Sertifikat Digital RQI">
                                        📜
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 font-sans">
                                <span>🔍 Tidak ada data hasil asesmen yang cocok dengan filter.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200">
            {{ $submissions->links() }}
        </div>
    </div>

</div>
@endsection
