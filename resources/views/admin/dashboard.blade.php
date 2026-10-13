@extends('layouts.admin')

@section('content')
<div class="space-y-8" x-data="{ showImportModal: false }">
    
    <!-- EXECUTIVE COMMAND CENTER HEADER -->
    <div class="bg-gradient-to-r from-[#0B2A43] via-[#123B59] to-[#08141E] text-white p-6 rounded-3xl shadow-xl border border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">EXECUTIVE COMMAND CENTER</span>
            <h2 class="text-xl sm:text-2xl font-serif font-bold text-white tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-xs text-slate-300">Ringkasan performa asesmen, metrik partisipasi, dan tugas tindakan cepat admin.</p>
        </div>
        
        <div class="flex flex-wrap gap-2.5 shrink-0">
            <a href="{{ route('admin.participants.export') }}" class="px-3.5 py-2.5 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-extrabold text-xs rounded-xl shadow transition flex items-center gap-1.5" title="Ekspor seluruh data peserta ke file CSV/Excel">
                <span>📥</span> <span>Export CSV Peserta</span>
            </a>
            <button type="button" @click="showImportModal = true" class="px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow transition flex items-center gap-1.5 cursor-pointer" title="Update Kota/Wilayah & Data Peserta via Excel">
                <span>📤</span> <span>Update Massal CSV</span>
            </button>
            <a href="{{ route('admin.participants.index') }}" class="px-3.5 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                <span>👥</span> <span>Kelola Peserta</span>
            </a>
        </div>
    </div>

    <!-- SYSTEM ALERTS BANNER -->
    @if(!empty($alerts))
        <div class="space-y-2.5">
            @foreach($alerts as $al)
                <div class="p-4 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-sm {{ $al['type'] === 'warning' ? 'bg-amber-50 text-amber-900 border border-amber-200' : ($al['type'] === 'info' ? 'bg-sky-50 text-sky-900 border border-sky-200' : 'bg-rose-50 text-rose-900 border border-rose-200') }}">
                    <div class="flex items-center gap-2.5">
                        <span class="text-base">⚠️</span>
                        <span>{{ $al['message'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- OVERVIEW STATS KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Peserta -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Peserta Terdaftar</span>
                <span class="text-3xl font-extrabold text-[#0B2A43] mt-1 block">{{ number_format($kpis['total_participants']) }}</span>
                <span class="text-[10px] text-emerald-600 font-bold block mt-1.5 flex items-center gap-1">
                    <span>📍</span> <span>38 Provinsi & 514 Kab/Kota</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">👥</div>
        </div>

        <!-- Rata-rata Skor RQI-15 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Indeks RQI-15 Nasional</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-3xl font-extrabold text-[#0B2A43]">{{ $kpis['avg_rq_score'] ?? 0 }}%</span>
                    <span class="text-xs font-bold text-amber-700">({{ $kpis['avg_rqi_raw'] ?? 0 }} / 75)</span>
                </div>
                <span class="text-[10px] text-amber-700 font-bold block mt-1.5 flex items-center gap-1">
                    <span>✦</span> <span>Rata-rata 5 Pilar Ruhiologi</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">🎯</div>
        </div>

        <!-- Rata-rata Indeks WHO-5 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Indeks WHO-5 Nasional</span>
                <span class="text-3xl font-extrabold text-emerald-700 mt-1 block">{{ $kpis['avg_who5_score'] ?? 0 }}%</span>
                <span class="text-[10px] text-emerald-600 font-bold block mt-1.5 flex items-center gap-1">
                    <span>🌿</span> <span>Kesejahteraan Mental Peserta</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">🌱</div>
        </div>

        <!-- Total Assessment Submissions -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Sesi Asesmen</span>
                <span class="text-3xl font-extrabold text-[#0B2A43] mt-1 block">{{ number_format($kpis['total_submissions']) }}</span>
                <a href="{{ route('admin.results.index') }}" class="text-[10px] text-sky-700 font-bold hover:underline block mt-1.5">Lihat Lembar Evaluasi →</a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-700 border border-sky-200 font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">📝</div>
        </div>
    </div>

    <!-- MAIN DASHBOARD CONTENT GRID: DISTRIBUTION CHART + RECENT ACTIVITIES -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: RQ Distribution Analytics Card (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">AGREGAT NASIONAL</span>
                        <h3 class="font-bold text-slate-900 text-sm font-serif">Distribusi Level Kecerdasan RQI-15</h3>
                    </div>
                    <span class="text-xs font-bold text-slate-400 font-mono">Total: {{ number_format(array_sum($rqDistribution)) }}</span>
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
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">DEMOGRAFI PESERTA</span>
                    <h3 class="font-bold text-slate-900 text-sm font-serif">Sebaran Kategori & Top Provinsi</h3>
                </div>

                @if(isset($categoryBreakdown) && count($categoryBreakdown) > 0)
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        @foreach($categoryBreakdown as $cb)
                            <a href="{{ route('admin.participants.index', ['category' => $cb->category_label]) }}" class="p-3 bg-slate-50 hover:bg-amber-50/70 rounded-2xl border border-slate-200/80 flex items-center justify-between transition">
                                <span class="font-bold text-slate-700 truncate pr-2">{{ $cb->category_label }}</span>
                                <span class="font-mono font-black text-[#0B2A43] bg-white px-2 py-0.5 rounded-lg border border-slate-200 text-[11px]">{{ number_format($cb->total) }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if(isset($topProvinces) && count($topProvinces) > 0)
                    <div class="pt-2 border-t border-slate-100 space-y-2 text-xs">
                        <span class="font-mono font-bold text-slate-400 uppercase tracking-wider text-[10px] block">📍 5 Provinsi Teraktif</span>
                        @foreach($topProvinces as $tp)
                            <div class="flex justify-between items-center text-slate-700 py-1 border-b border-slate-50 last:border-0">
                                <span class="font-medium">{{ $tp->province->name ?? 'Indonesia' }}</span>
                                <strong class="font-mono font-bold text-[#0B2A43]">{{ number_format($tp->total) }} Peserta</strong>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Recent Submissions & Requests (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Assessment Terbaru Disubmit -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-emerald-700 uppercase tracking-widest block">REALTIME MONITOR</span>
                        <h3 class="font-bold text-slate-900 text-sm font-serif">Sesi Assessment Terbaru Disubmit</h3>
                    </div>
                    <a href="{{ route('admin.results.index') }}" class="text-xs text-amber-700 font-bold hover:underline">Lihat Semua →</a>
                </div>
                <div class="space-y-2.5">
                    @forelse($recentSubmissions as $sub)
                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 flex flex-col sm:flex-row justify-between sm:items-center gap-2 text-xs hover:bg-slate-100/80 transition">
                            <div>
                                <div class="flex items-center gap-2">
                                    <strong class="font-bold text-slate-900 text-xs">{{ $sub->participant->name ?? 'Peserta' }}</strong>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 font-bold">{{ $sub->participant->category ?? 'Umum' }}</span>
                                </div>
                                <span class="text-[10px] text-slate-500 font-mono block mt-0.5">
                                    {{ strtoupper($sub->submission_type) }} • {{ $sub->submission_code }} • 📍 {{ $sub->participant->province->name ?? '-' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-3">
                                <div class="text-right">
                                    <span class="font-extrabold text-[#0B2A43] block text-xs">RQI: {{ number_format($sub->result->rqi_score ?? 0, 0) }}/75 ({{ number_format($sub->result->percentage ?? 0, 1) }}%)</span>
                                    <span class="text-[10px] font-bold text-emerald-700 block">WHO-5: {{ number_format($sub->result->who5_percentage ?? 0, 0) }}%</span>
                                </div>
                                <a href="{{ route('admin.results.show', $sub->id) }}" class="px-3 py-1.5 bg-[#0B2A43] text-white rounded-xl text-[10px] font-bold hover:bg-[#123B59] transition shrink-0">
                                    Detail ↗
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-4">Belum ada submission asesmen.</div>
                    @endforelse
                </div>
            </div>

            <!-- Permintaan Konsultasi Terbaru -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-sm font-serif">Permintaan Konsultasi Terbaru</h3>
                    <a href="{{ route('admin.consultations.index') }}" class="text-xs text-amber-700 font-bold hover:underline">Lihat Semua →</a>
                </div>
                <div class="space-y-2.5">
                    @forelse($recentConsultations as $c)
                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 flex justify-between items-center text-xs hover:bg-slate-100/80 transition">
                            <div>
                                <strong class="font-bold text-slate-900 block text-xs">{{ $c->name }}</strong>
                                <span class="text-[10px] text-slate-500">{{ $c->consultation_type }} • {{ $c->institution ?? 'Personal' }}</span>
                            </div>
                            <span class="px-2.5 py-1 bg-sky-100 text-sky-800 text-[10px] font-bold rounded-full uppercase tracking-wider">
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
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base flex items-center gap-2">
                    <span>📤</span> <span>Update Massal / Impor Data Peserta (CSV)</span>
                </h3>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">✕</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Fitur ini dapat digunakan untuk <strong>memperbarui data wilayah (Provinsi & Kota)</strong> atau profil peserta secara sekaligus.
                <br>Silakan ekspor data CSV terlebih dahulu, perbaiki kolom <code>Provinsi</code> dan <code>Kabupaten / Kota</code> di Excel, lalu unggah kembali filenya di bawah ini.
            </p>

            <div class="p-3 bg-amber-50 rounded-2xl border border-amber-200 text-[11px] text-amber-900 font-medium space-y-1">
                <p class="font-bold text-amber-950">💡 Langkah Mudah Update Kota/Provinsi Massal:</p>
                <ol class="list-decimal list-inside space-y-0.5 text-slate-700">
                    <li>Klik <a href="{{ route('admin.participants.export') }}" class="font-bold underline text-amber-900">Unduh Export CSV Peserta Saat Ini</a>.</li>
                    <li>Buka file CSV tersebut di Microsoft Excel / Google Sheets.</li>
                    <li>Ubah isi kolom <strong>Provinsi</strong> (misal: <em>Kepulauan Riau</em>) dan <strong>Kabupaten / Kota</strong> (misal: <em>Kota Batam</em>).</li>
                    <li>Simpan (*.csv) lalu upload file tersebut melalui tombol pilih file di bawah ini.</li>
                </ol>
            </div>

            <form action="{{ route('admin.participants.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Pilih File CSV (*.csv) *</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs bg-slate-50 font-mono">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showImportModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-extrabold rounded-xl text-xs transition shadow cursor-pointer">
                        Proses Update / Impor →
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
