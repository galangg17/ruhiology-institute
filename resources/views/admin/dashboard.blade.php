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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Peserta -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Peserta Terdaftar</span>
                <span class="text-3xl font-extrabold text-[#0B2A43] mt-1 block">{{ number_format($kpis['total_participants']) }}</span>
                <span class="text-[10px] text-emerald-600 font-bold block mt-1.5 flex items-center gap-1">
                    <span>📈</span> <span>38 Provinsi Indonesia</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">👥</div>
        </div>

        <!-- Rata-rata Skor RQ -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Rata-rata Skor RQ</span>
                <span class="text-3xl font-extrabold text-[#0B2A43] mt-1 block">{{ $kpis['avg_rq_score'] ?? 0 }}%</span>
                <span class="text-[10px] text-sky-600 font-bold block mt-1.5 flex items-center gap-1">
                    <span>✨</span> <span>Indeks Capaian Nasional</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-700 border border-sky-200 font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">🎯</div>
        </div>

        <!-- Total Assessment Submissions -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Sesi Asesmen</span>
                <span class="text-3xl font-extrabold text-[#0B2A43] mt-1 block">{{ number_format($kpis['total_submissions']) }}</span>
                <span class="text-[10px] text-slate-500 font-bold block mt-1.5">Pretest & Posttest Selesai</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">📝</div>
        </div>

        <!-- Pesanan Buku Pending -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pesanan Buku Pending</span>
                <span class="text-3xl font-extrabold text-amber-600 mt-1 block">{{ number_format($kpis['pending_orders']) }}</span>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] text-amber-700 font-bold hover:underline block mt-1.5">Verifikasi Pembayaran →</a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">🛒</div>
        </div>
    </div>

    <!-- MAIN DASHBOARD CONTENT GRID: DISTRIBUTION CHART + RECENT ACTIVITIES -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: RQ Distribution Analytics Card (5 cols) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <div>
                    <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">AGREGAT NASIONAL</span>
                    <h3 class="font-bold text-slate-900 text-sm font-serif">Distribusi Level Kecerdasan RQ</h3>
                </div>
                <span class="text-xs font-bold text-slate-400 font-mono">Total: {{ array_sum($rqDistribution) }}</span>
            </div>

            <!-- Distribution Visual Bars -->
            <div class="space-y-4 text-xs font-medium">
                @php
                    $totalDist = array_sum($rqDistribution) > 0 ? array_sum($rqDistribution) : 1;
                    $stPct = round(($rqDistribution['sangat_tinggi'] / $totalDist) * 100);
                    $tPct = round(($rqDistribution['tinggi'] / $totalDist) * 100);
                    $sPct = round(($rqDistribution['sedang'] / $totalDist) * 100);
                    $pPct = round(($rqDistribution['perlu_penguatan'] / $totalDist) * 100);
                @endphp

                <!-- Sangat Tinggi -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-emerald-800 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Sangat Tinggi (Ruhiology Mastery)</span>
                        </span>
                        <span class="font-mono font-bold text-slate-700">{{ $rqDistribution['sangat_tinggi'] }} ({{ $stPct }}%)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $stPct }}%"></div>
                    </div>
                </div>

                <!-- Tinggi -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-sky-800 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                            <span>Tinggi (Ruhiology Competent)</span>
                        </span>
                        <span class="font-mono font-bold text-slate-700">{{ $rqDistribution['tinggi'] }} ({{ $tPct }}%)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-sky-500 rounded-full" style="width: {{ $tPct }}%"></div>
                    </div>
                </div>

                <!-- Sedang -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-amber-800 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <span>Sedang (Ruhiology Developing)</span>
                        </span>
                        <span class="font-mono font-bold text-slate-700">{{ $rqDistribution['sedang'] }} ({{ $sPct }}%)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $sPct }}%"></div>
                    </div>
                </div>

                <!-- Perlu Penguatan -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-rose-800 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span>Perlu Penguatan (Initial Stage)</span>
                        </span>
                        <span class="font-mono font-bold text-slate-700">{{ $rqDistribution['perlu_penguatan'] }} ({{ $pPct }}%)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-500 rounded-full" style="width: {{ $pPct }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Box -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs space-y-2 pt-3">
                <span class="font-mono font-bold text-[#0B2A43] uppercase tracking-wider text-[10px] block">📊 Metrik Ringkas</span>
                <div class="flex justify-between items-center text-slate-600">
                    <span>Program Pelatihan Aktif</span>
                    <strong class="text-slate-900 font-bold">{{ $kpis['active_programs'] }} Program</strong>
                </div>
                <div class="flex justify-between items-center text-slate-600">
                    <span>Artikel Terpublikasi</span>
                    <strong class="text-slate-900 font-bold">{{ $kpis['published_articles'] }} Artikel</strong>
                </div>
            </div>
        </div>

        <!-- Right: Recent Submissions & Requests (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Assessment Terbaru Disubmit -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-sm font-serif">Assessment Terbaru Disubmit</h3>
                    <a href="{{ route('admin.results.index') }}" class="text-xs text-amber-700 font-bold hover:underline">Lihat Semua →</a>
                </div>
                <div class="space-y-2.5">
                    @forelse($recentSubmissions as $sub)
                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 flex justify-between items-center text-xs hover:bg-slate-100/80 transition">
                            <div>
                                <strong class="font-bold text-slate-900 block text-xs">{{ $sub->participant->name ?? 'Peserta' }}</strong>
                                <span class="text-[10px] text-slate-500 font-mono">{{ strtoupper($sub->submission_type) }} • {{ $sub->submission_code }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-extrabold text-[#0B2A43] block text-sm">{{ number_format($sub->result->percentage ?? 0, 1) }}%</span>
                                <a href="{{ route('admin.results.show', $sub->id) }}" class="text-[10px] text-amber-700 font-bold hover:underline">Detail ↗</a>
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
