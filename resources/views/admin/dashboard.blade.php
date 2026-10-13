@extends('layouts.admin')

@section('content')
<div class="space-y-8" x-data="{ showImportModal: false }">
    
    <!-- EXECUTIVE COMMAND CENTER HEADER -->
    <div class="bg-gradient-to-r from-[#0F2537] via-[#1E3E5B] to-[#0A1A27] text-white p-6 sm:p-7 rounded-2xl shadow-xl border border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-[#D4AF37] uppercase tracking-widest block">EXECUTIVE COMMAND CENTER</span>
            <h2 class="text-xl sm:text-2xl font-serif font-bold text-white tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-xs text-slate-300">Ringkasan agregat performa asesmen, metrik partisipasi nasional, dan tindakan cepat sistem.</p>
        </div>
        
        <div class="flex flex-wrap gap-2.5 shrink-0">
            <a href="{{ route('admin.participants.export') }}" class="px-4 py-2.5 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer" title="Ekspor seluruh data peserta ke file CSV/Excel">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export CSV Peserta</span>
            </a>
            <button type="button" @click="showImportModal = true" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl border border-slate-700 shadow-sm transition flex items-center gap-2 cursor-pointer" title="Update Wilayah & Data Peserta via CSV">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>Update Massal CSV</span>
            </button>
            <a href="{{ route('admin.participants.index') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-xs rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Kelola Peserta</span>
            </a>
        </div>
    </div>

    <!-- SYSTEM ALERTS BANNER -->
    @if(!empty($alerts))
        <div class="space-y-2.5">
            @foreach($alerts as $al)
                <div class="p-4 rounded-xl text-xs font-semibold flex items-center justify-between shadow-2xs {{ $al['type'] === 'warning' ? 'bg-amber-50 text-amber-900 border border-amber-200' : ($al['type'] === 'info' ? 'bg-sky-50 text-sky-900 border border-sky-200' : 'bg-rose-50 text-rose-900 border border-rose-200') }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>{{ $al['message'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- OVERVIEW STATS KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Total Peserta -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-sm transition">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Peserta Terdaftar</span>
                <span class="text-3xl font-extrabold text-[#0F2537] mt-1 block tracking-tight">{{ number_format($kpis['total_participants']) }}</span>
                <span class="text-[10px] text-emerald-700 font-semibold block mt-1.5 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>38 Provinsi & 514 Kab/Kota</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 text-[#0F2537] border border-slate-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>

        <!-- Rata-rata Skor RQI-15 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-sm transition">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Indeks RQI-15 Nasional</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-3xl font-extrabold text-[#0F2537] tracking-tight">{{ $kpis['avg_rq_score'] ?? 0 }}%</span>
                    <span class="text-xs font-bold text-[#8C703E]">({{ $kpis['avg_rqi_raw'] ?? 0 }} / 75)</span>
                </div>
                <span class="text-[10px] text-[#8C703E] font-semibold block mt-1.5 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#8C703E]"></span>
                    <span>Rata-rata 5 Pilar Ruhiologi</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 text-[#0F2537] border border-slate-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>

        <!-- Rata-rata Indeks WHO-5 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-sm transition">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Indeks WHO-5 Nasional</span>
                <span class="text-3xl font-extrabold text-emerald-800 mt-1 block tracking-tight">{{ $kpis['avg_who5_score'] ?? 0 }}%</span>
                <span class="text-[10px] text-emerald-700 font-semibold block mt-1.5 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Kesejahteraan Mental Peserta</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 text-emerald-800 border border-slate-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
        </div>

        <!-- Total Assessment Submissions -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-sm transition">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Sesi Asesmen</span>
                <span class="text-3xl font-extrabold text-[#0F2537] mt-1 block tracking-tight">{{ number_format($kpis['total_submissions']) }}</span>
                <a href="{{ route('admin.results.index') }}" class="text-[10px] text-slate-600 hover:text-[#0F2537] font-bold underline block mt-1.5">Lembar Evaluasi →</a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 text-[#0F2537] border border-slate-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD CONTENT GRID: DISTRIBUTION CHART + RECENT ACTIVITIES -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: RQ Distribution Analytics Card (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#8C703E] uppercase tracking-widest block">AGREGAT NASIONAL</span>
                        <h3 class="font-bold text-[#0F2537] text-sm font-serif">Distribusi Level Kecerdasan RQI-15</h3>
                    </div>
                    <span class="text-xs font-bold text-slate-500 font-mono">Total: {{ number_format(array_sum($rqDistribution)) }}</span>
                </div>

                <!-- Distribution Visual Bars -->
                <div class="space-y-4 text-xs font-medium">
                    @php
                        $totalDist = array_sum($rqDistribution) > 0 ? array_sum($rqDistribution) : 1;
                        $stPct = round(($rqDistribution['sangat_tinggi'] / $totalDist) * 100, 1);
                        $tPct = round(($rqDistribution['tinggi'] / $totalDist) * 100, 1);
                        $sPct = round(($rqDistribution['sedang'] / $totalDist) * 100, 1);
                        $pPct = round(($rqDistribution['perlu_penguatan'] / $totalDist) * 100, 1);
                    @endphp

                    <!-- Level 5 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-emerald-800 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span>Level 5: Enlightened Soul (61–75 Poin)</span>
                            </span>
                            <span class="font-mono font-bold text-slate-700">{{ number_format($rqDistribution['sangat_tinggi']) }} ({{ $stPct }}%)</span>
                        </div>
                        <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $stPct }}%"></div>
                        </div>
                    </div>

                    <!-- Level 4 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-sky-800 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                                <span>Level 4: Mindful Youth (46–60 Poin)</span>
                            </span>
                            <span class="font-mono font-bold text-slate-700">{{ number_format($rqDistribution['tinggi']) }} ({{ $tPct }}%)</span>
                        </div>
                        <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-sky-500 rounded-full" style="width: {{ $tPct }}%"></div>
                        </div>
                    </div>

                    <!-- Level 3 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-amber-800 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span>Level 3: Developing Soul (36–45 Poin)</span>
                            </span>
                            <span class="font-mono font-bold text-slate-700">{{ number_format($rqDistribution['sedang']) }} ({{ $sPct }}%)</span>
                        </div>
                        <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full" style="width: {{ $sPct }}%"></div>
                        </div>
                    </div>

                    <!-- Level 1-2 -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-rose-800 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span>Level 1–2: Perlu Penguatan (&le; 35 Poin)</span>
                            </span>
                            <span class="font-mono font-bold text-slate-700">{{ number_format($rqDistribution['perlu_penguatan']) }} ({{ $pPct }}%)</span>
                        </div>
                        <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-rose-500 rounded-full" style="width: {{ $pPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demographic Breakdown & Top Provinces Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <span class="text-[10px] font-mono font-bold text-[#8C703E] uppercase tracking-widest block">DEMOGRAFI PESERTA</span>
                    <h3 class="font-bold text-[#0F2537] text-sm font-serif">Sebaran Kategori & Top Provinsi</h3>
                </div>

                @if(isset($categoryBreakdown) && count($categoryBreakdown) > 0)
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        @foreach($categoryBreakdown as $cb)
                            <a href="{{ route('admin.participants.index', ['category' => $cb->category_label]) }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex items-center justify-between transition">
                                <span class="font-semibold text-slate-700 truncate pr-2">{{ $cb->category_label }}</span>
                                <span class="font-mono font-bold text-[#0F2537] bg-white px-2 py-0.5 rounded-lg border border-slate-200 text-[11px]">{{ number_format($cb->total) }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if(isset($topProvinces) && count($topProvinces) > 0)
                    <div class="pt-2 border-t border-slate-100 space-y-2 text-xs">
                        <span class="font-mono font-bold text-slate-400 uppercase tracking-wider text-[10px] flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>5 Provinsi Teraktif</span>
                        </span>
                        @foreach($topProvinces as $tp)
                            <div class="flex justify-between items-center text-slate-700 py-1 border-b border-slate-50 last:border-0">
                                <span class="font-medium">{{ $tp->province->name ?? 'Indonesia' }}</span>
                                <strong class="font-mono font-bold text-[#0F2537]">{{ number_format($tp->total) }} Peserta</strong>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Recent Submissions & Requests (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Assessment Terbaru Disubmit -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-emerald-700 uppercase tracking-widest block">MONITOR LANGSUNG</span>
                        <h3 class="font-bold text-[#0F2537] text-sm font-serif">Sesi Asesmen Terbaru Disubmit</h3>
                    </div>
                    <a href="{{ route('admin.results.index') }}" class="text-xs text-[#8C703E] hover:text-[#0F2537] font-bold hover:underline">Lihat Semua →</a>
                </div>
                <div class="space-y-2.5">
                    @forelse($recentSubmissions as $sub)
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-2 text-xs hover:bg-slate-100/80 transition">
                            <div>
                                <div class="flex items-center gap-2">
                                    <strong class="font-bold text-slate-900 text-xs">{{ $sub->participant->name ?? 'Peserta' }}</strong>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 font-semibold">{{ $sub->participant->category ?? 'Umum' }}</span>
                                </div>
                                <span class="text-[10px] text-slate-500 font-mono block mt-0.5">
                                    {{ strtoupper($sub->submission_type) }} • {{ $sub->submission_code }} • {{ $sub->participant->province->name ?? '-' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-3">
                                <div class="text-right">
                                    <span class="font-bold text-[#0F2537] block text-xs">RQI: {{ number_format($sub->result->rqi_score ?? 0, 0) }}/75 ({{ number_format($sub->result->percentage ?? 0, 1) }}%)</span>
                                    <span class="text-[10px] font-semibold text-emerald-700 block">WHO-5: {{ number_format($sub->result->who5_percentage ?? 0, 0) }}%</span>
                                </div>
                                <a href="{{ route('admin.results.show', $sub->id) }}" class="px-3 py-1.5 bg-[#0F2537] text-white rounded-lg text-[10px] font-semibold hover:bg-[#1E3E5B] transition shrink-0 flex items-center gap-1">
                                    <span>Detail</span>
                                    <svg class="w-3 h-3 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-4">Belum ada submission asesmen.</div>
                    @endforelse
                </div>
            </div>

            <!-- Permintaan Konsultasi Terbaru -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-[#0F2537] text-sm font-serif">Permintaan Konsultasi Terbaru</h3>
                    <a href="{{ route('admin.consultations.index') }}" class="text-xs text-[#8C703E] hover:text-[#0F2537] font-bold hover:underline">Lihat Semua →</a>
                </div>
                <div class="space-y-2.5">
                    @forelse($recentConsultations as $c)
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 flex justify-between items-center text-xs hover:bg-slate-100/80 transition">
                            <div>
                                <strong class="font-bold text-slate-900 block text-xs">{{ $c->name }}</strong>
                                <span class="text-[10px] text-slate-500">{{ $c->consultation_type }} • {{ $c->institution ?? 'Personal' }}</span>
                            </div>
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-full uppercase tracking-wider border border-slate-200">
                                {{ $c->status }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-4">Belum ada permintaan konsultasi.</div>
                    @endforelse
                </div>
            </div>

        </div>

    <!-- MODAL IMPORT / UPDATE MASSAL DATA PESERTA VIA EXCEL/CSV -->
    <div x-show="showImportModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4 border border-slate-200">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0F2537] text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span>Update Massal / Impor Data Peserta (CSV)</span>
                </h3>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">✕</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Fitur ini dapat digunakan untuk <strong>memperbarui data wilayah (Provinsi & Kota)</strong> atau profil peserta secara sekaligus.
                <br>Silakan ekspor data CSV terlebih dahulu, perbaiki kolom <code>Provinsi</code> dan <code>Kabupaten / Kota</code> di Excel, lalu unggah kembali filenya di bawah ini.
            </p>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-[11px] text-slate-700 font-medium space-y-1.5">
                <p class="font-bold text-[#0F2537] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Panduan Update Kota/Provinsi Massal:</span>
                </p>
                <ol class="list-decimal list-inside space-y-1 text-slate-600 pl-1">
                    <li>Klik <a href="{{ route('admin.participants.export') }}" class="font-bold underline text-[#0F2537]">Unduh Export CSV Peserta Saat Ini</a>.</li>
                    <li>Buka file CSV tersebut di Microsoft Excel / Google Sheets.</li>
                    <li>Perbaiki nama pada kolom <strong>Provinsi</strong> dan <strong>Kabupaten / Kota</strong>.</li>
                    <li>Simpan sebagai file CSV (*.csv) lalu unggah kembali melalui formulir di bawah ini.</li>
                </ol>
            </div>

            <form action="{{ route('admin.participants.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Pilih File CSV (*.csv) *</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs bg-slate-50 font-mono">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showImportModal = false" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold rounded-xl text-xs transition shadow-sm cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span>Proses Update / Impor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
