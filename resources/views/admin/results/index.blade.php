@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- HUB NAV BAR (UNIFIED SWITCHER FOR PESERTA & HASIL EVALUASI) -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-2 font-sans">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.participants.index') }}" 
               class="px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ request()->routeIs('admin.participants.*') ? 'bg-[#0F2537] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.participants.*') ? 'text-[#D4AF37]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Data Peserta & Demografi</span>
            </a>

            <a href="{{ route('admin.results.index') }}" 
               class="px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ request()->routeIs('admin.results.*') ? 'bg-[#0F2537] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.results.*') ? 'text-[#D4AF37]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Lembar Sesi & Hasil Evaluasi</span>
            </a>
        </div>

        <a href="{{ route('admin.reports.export_pdf') }}" target="_blank" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-[#0F2537] border border-slate-200 font-bold text-xs rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Laporan PDF Agregat</span>
        </a>
    </div>

    <!-- HEADER TITLE & DYNAMIC EXPORT BUTTONS -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold font-serif text-[#0F2537] tracking-tight">Hasil & Rekap Evaluasi Asesmen</h2>
            <p class="text-xs text-slate-500 mt-0.5">Laporan rekapitulasi skor Indeks Ruhiologi (RQI-15) & Skrining Kesejahteraan Emosional (WHO-5 Index).</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 font-sans" x-data="{ exportMenuOpen: false }">
            <!-- Dropdown Ekspor Excel (CSV Multi-Format) -->
            <div class="relative">
                <button @click="exportMenuOpen = !exportMenuOpen" @click.away="exportMenuOpen = false" type="button" class="px-4 py-2 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Opsi Ekspor Excel (.csv)</span>
                    <svg class="w-3.5 h-3.5 text-[#DFD8C8] transition-transform duration-200" :class="{ 'rotate-180': exportMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="exportMenuOpen" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 sm:right-auto sm:left-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 text-xs font-sans divide-y divide-slate-100" 
                     style="display: none;">
                    
                    <div class="px-3.5 py-1.5 text-[10px] font-mono font-bold text-slate-400 uppercase tracking-wider">
                        PILIH MODEL EKSPOR DATA
                    </div>

                    <div class="py-1">
                        <!-- Option 1: Ringkasan Nilai & Demografi -->
                        <a href="{{ route('admin.reports.export_csv', request()->all()) }}" class="flex items-start gap-3 px-3.5 py-2.5 text-slate-700 hover:bg-slate-50 transition">
                            <span class="p-2 bg-emerald-50 text-emerald-700 rounded-xl mt-0.5 shrink-0 border border-emerald-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            <div>
                                <strong class="block font-bold text-slate-900 text-xs">1. Ringkasan Skor & Demografi</strong>
                                <span class="text-[11px] text-slate-500 leading-tight block mt-0.5">Daftar nilai total RQI, kategori tingkat ruh, dan status WHO-5 peserta.</span>
                            </div>
                        </a>

                        <!-- Option 2: Capaian Per Dimensi (Matriks) -->
                        <a href="{{ route('admin.results.export_dimensions_matrix', request()->all()) }}" class="flex items-start gap-3 px-3.5 py-2.5 text-slate-700 hover:bg-amber-50/60 transition">
                            <span class="p-2 bg-amber-50 text-amber-800 rounded-xl mt-0.5 shrink-0 border border-amber-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </span>
                            <div>
                                <strong class="block font-bold text-[#8C703E] text-xs">2. Capaian Per Dimensi Ruhiologi</strong>
                                <span class="text-[11px] text-slate-500 leading-tight block mt-0.5">Matriks pilar RQI (skor, persentase, dan interpretasi 5 dimensi).</span>
                            </div>
                        </a>

                        <!-- Option 3: Jawaban Per Butir Soal (Raw & Calc) -->
                        <a href="{{ route('admin.results.export_answers_matrix', request()->all()) }}" class="flex items-start gap-3 px-3.5 py-2.5 text-slate-700 hover:bg-sky-50/60 transition">
                            <span class="p-2 bg-sky-50 text-sky-800 rounded-xl mt-0.5 shrink-0 border border-sky-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </span>
                            <div>
                                <strong class="block font-bold text-[#0F2537] text-xs">3. Jawaban Butir Soal (Raw & Calc)</strong>
                                <span class="text-[11px] text-slate-500 leading-tight block mt-0.5">Analisis butir soal: opsi jawaban, skor mentah, dan skor terhitung.</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- PDF Export Button -->
            <a href="{{ route('admin.reports.export_pdf', request()->all()) }}" target="_blank" class="px-4 py-2 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Export Laporan PDF</span>
            </a>
        </div>
    </div>

    <!-- STATS SUMMARY CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 text-xs font-sans">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1 text-center">
            <span class="text-[10px] font-bold text-slate-500 font-mono uppercase tracking-wider block">TOTAL SESI TEST</span>
            <strong class="text-2xl font-black font-mono text-[#0F2537] block">{{ number_format($stats['total']) }}</strong>
            <span class="text-[10px] text-slate-500 font-medium">Sesi Evaluasi Difilter</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1 text-center">
            <span class="text-[10px] font-bold text-[#8C703E] font-mono uppercase tracking-wider block">RATA-RATA RQI (0-100)</span>
            <strong class="text-2xl font-black font-mono text-[#8C703E] block">{{ $stats['avg_rqi'] }}</strong>
            <span class="text-[10px] text-[#8C703E] bg-[#F4F1EA] border border-[#DFD8C8] px-2 py-0.5 rounded-full font-bold inline-block">Indeks Ruhiologi</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1 text-center">
            <span class="text-[10px] font-bold text-emerald-800 font-mono uppercase tracking-wider block">RATA-RATA WHO-5 (%)</span>
            <strong class="text-2xl font-black font-mono text-emerald-700 block">{{ $stats['avg_who5'] }}%</strong>
            <span class="text-[10px] text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full font-bold inline-block">Kesejahteraan Mental</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-1 text-center">
            <span class="text-[10px] font-bold text-slate-500 font-mono uppercase tracking-wider block">STATUS WHO-5</span>
            <strong class="text-base font-bold text-slate-800 block pt-1">
                <span class="text-emerald-700 font-mono text-lg font-black">{{ $stats['who5_sehat'] }}</span> <span class="text-slate-500 font-normal">Sehat</span> / <span class="text-rose-700 font-mono text-lg font-black">{{ $stats['who5_skrining'] }}</span> <span class="text-slate-500 font-normal">Skrining</span>
            </strong>
        </div>
    </div>

    <!-- MULTI-DIMENSIONAL FILTER BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3 font-sans text-xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <h3 class="font-bold text-[#0F2537] text-xs uppercase tracking-wider flex items-center gap-2 font-mono">
                <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span>Filter Multi-Dimensi Data Hasil Evaluasi</span>
            </h3>
            <a href="{{ route('admin.results.index') }}" class="text-[11px] font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Reset Filter</span>
            </a>
        </div>

        <form action="{{ route('admin.results.index') }}" method="GET" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                
                <!-- 1. Search Query -->
                <div class="lg:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Cari Peserta atau Kode Sesi</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama peserta, kode submission SUB-..., kode asesmen..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:bg-white focus:border-[#0F2537] outline-none transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- 2. Event / Program -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Event / Akses Pendaftaran</label>
                    <select name="event_id" class="w-full p-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 focus:bg-white outline-none">
                        <option value="">Semua Event & Pendaftaran</option>
                        <option value="PUBLIC_SELF" {{ request('event_id') === 'PUBLIC_SELF' ? 'selected' : '' }}>Mandiri Publik (Umum)</option>
                        @foreach($events as $e)
                            <option value="{{ $e->id }}" {{ request('event_id') == $e->id ? 'selected' : '' }}>{{ $e->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Provinsi -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Provinsi (Demografi)</label>
                    <select name="province_id" onchange="this.form.submit()" class="w-full p-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 focus:bg-white outline-none">
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
                    <select name="regency_id" onchange="this.form.submit()" {{ !request('province_id') ? 'disabled' : '' }} class="w-full p-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 focus:bg-white outline-none disabled:bg-slate-100 disabled:text-slate-400">
                        <option value="">{{ request('province_id') ? 'Semua Kota/Kabupaten' : 'Pilih Provinsi Dahulu' }}</option>
                        @foreach($regencies as $reg)
                            <option value="{{ $reg->id }}" {{ request('regency_id') == $reg->id ? 'selected' : '' }}>{{ $reg->formatted_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 5. Kategori Peserta -->
                <div>
                    <select name="category" class="w-full p-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 focus:bg-white outline-none">
                        <option value="">Semua Kategori</option>
                        <option value="Pelajar" {{ request('category') === 'Pelajar' ? 'selected' : '' }}>Pelajar</option>
                        <option value="Mahasiswa/i" {{ request('category') === 'Mahasiswa/i' ? 'selected' : '' }}>Mahasiswa/i</option>
                        <option value="Umum" {{ request('category') === 'Umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>

                <!-- 6. WHO-5 Status -->
                <div>
                    <select name="who5_status" class="w-full p-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 focus:bg-white outline-none">
                        <option value="">Semua Status WHO-5</option>
                        <option value="sehat" {{ request('who5_status') === 'sehat' ? 'selected' : '' }}>Sehat (≥50%)</option>
                        <option value="skrining" {{ request('who5_status') === 'skrining' ? 'selected' : '' }}>Perlu Skrining (&lt;50%)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full py-2 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold text-xs rounded-xl transition shadow-xs cursor-pointer flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden font-sans">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs font-mono font-bold text-slate-600">
            <span>HASIL ASESMEN TERTERA ({{ $submissions->total() }} Sesi Evaluasi)</span>
            <span class="text-[11px] text-slate-500 font-normal">Halaman {{ $submissions->currentPage() }} dari {{ $submissions->lastPage() }}</span>
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
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Skor & Level RQI</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Skor & Level WHO-5</th>
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
                                <span class="font-mono font-bold text-[#8C703E] text-xs block">{{ $sub->submission_code }}</span>
                                <span class="text-[10px] text-slate-500 font-mono block mt-0.5">{{ $sub->submitted_at ? $sub->submitted_at->format('d/m/Y H:i') : '-' }} WIB</span>
                            </td>

                            <!-- Participant -->
                            <td class="p-4">
                                <a href="{{ route('admin.participants.index', ['q' => $p?->name]) }}" class="font-bold text-slate-900 hover:text-[#0F2537] hover:underline text-xs block leading-snug">
                                    {{ $p?->name ?? 'Anonim' }}
                                </a>
                                <span class="text-[11px] text-slate-500 font-normal">
                                    {{ $p?->category === 'Umum' ? 'Personal / Mandiri' : ($p?->category ?? 'Mandiri') }}
                                    @if($p?->sub_category)
                                        <span class="inline-block bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-semibold px-1.5 py-0.2 rounded ml-1">{{ $p->sub_category }}</span>
                                    @endif
                                </span>
                            </td>

                            <!-- Event / Access -->
                            <td class="p-4">
                                <span class="font-bold text-[#0F2537] block text-xs">
                                    {{ $sub->event->title ?? ($sub->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Umum') }}
                                </span>
                                <span class="text-[10px] text-slate-500 font-mono block">
                                    {{ $sub->event->event_code ?? 'PUBLIC' }}
                                </span>
                            </td>

                            <!-- Session Type (Pretest vs Posttest) -->
                            <td class="p-4">
                                @if(strtolower($sub->submission_type) === 'pretest')
                                    <span class="px-2.5 py-0.5 bg-sky-50 text-sky-800 text-[10px] font-bold uppercase rounded-md border border-sky-200">
                                        PRETEST
                                    </span>
                                @elseif(strtolower($sub->submission_type) === 'posttest')
                                    <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-800 text-[10px] font-bold uppercase rounded-md border border-emerald-200">
                                        POSTTEST
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-bold uppercase rounded-md border border-slate-200">
                                        {{ strtoupper($sub->submission_type ?? 'MANDIRI') }}
                                    </span>
                                @endif
                            </td>

                            <!-- Region -->
                            <td class="p-4 text-slate-600">
                                <span class="font-bold text-slate-800 text-xs block">{{ $p?->province?->name ?? '-' }}</span>
                                <span class="block text-[10px] text-slate-500">{{ $p?->regency?->name ?? '-' }}</span>
                            </td>

                            <!-- RQI Score -->
                            <td class="p-4 text-center">
                                <span class="font-mono font-bold text-sm text-[#0F2537] block">{{ $res->rqi_score ?? '-' }}</span>
                                <span class="text-[10px] font-semibold text-[#8C703E] bg-[#F4F1EA] px-2 py-0.5 rounded border border-[#DFD8C8] inline-block">{{ $res->category_name ?? '-' }}</span>
                            </td>

                            <!-- WHO-5 Score & Level -->
                            <td class="p-4 text-center">
                                @if($res && $res->who5_percentage !== null)
                                    @php
                                        $who5Raw = $res->who5_raw_score ?? 0;
                                        $who5Pct = $res->who5_percentage;
                                        $who5Level = $res->snapshot_data['who5']['name'] ?? null;
                                        if (!$who5Level) {
                                            if ($who5Pct >= 80) $who5Level = 'Level 5: Thriving & Energetic';
                                            elseif ($who5Pct >= 64) $who5Level = 'Level 4: Good Vibe & Fresh';
                                            elseif ($who5Pct >= 44) $who5Level = 'Level 3: Moderate Well-Being';
                                            elseif ($who5Pct >= 24) $who5Level = 'Level 2: Low Energy';
                                            else $who5Level = 'Level 1: Mental Exhausted';
                                        }
                                    @endphp
                                    <span class="font-mono font-bold text-sm block {{ $res->who5_percentage >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $res->who5_percentage }}%
                                    </span>
                                    <span class="text-[10px] font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 inline-block">
                                        {{ $who5Level }}
                                    </span>
                                    <span class="text-[9px] font-bold block mt-1 {{ $res->who5_percentage >= 50 ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $res->who5_percentage >= 50 ? '🌱 Sehat' : '⚠️ Skrining' }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.results.show', $sub) }}" class="px-3 py-1.5 bg-[#0F2537] hover:bg-[#1E3E5B] text-white text-[11px] font-bold rounded-xl shadow-xs transition flex items-center gap-1">
                                        <span>Detail</span>
                                        <svg class="w-3 h-3 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('assessment.certificate', $sub->submission_code) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-[#0F2537] rounded-xl text-xs font-bold transition border border-slate-200" title="Cetak Sertifikat Digital RQI">
                                        <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 font-sans">
                                <span>Tidak ada data hasil asesmen yang cocok dengan filter.</span>
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
