@extends('layouts.admin')

@section('content')
<script>
    window.__participantsMap = @json($participants->getCollection()->keyBy('id'));
    window.__regenciesMap = @json($regenciesMap ?? []);
    window.__allPageIds = @json($participants->pluck('id'));

    function participantManager() {
        return {
            showDemographics: true,
            createModal: false,
            importModal: false,
            importServerModal: false,
            editModal: false,
            drawerOpen: false,
            drawerTab: 'profile',
            selectedParticipant: null,
            participantsMap: window.__participantsMap || {},
            regenciesMap: window.__regenciesMap || {},
            allPageIds: window.__allPageIds || [],
            selectedIds: [],
            selectAll: false,
            createProvinceId: '',
            createRegencyId: '',
            filterProvinceId: '{{ request('province_id', '') }}',
            filterRegencyId: '{{ request('regency_id', '') }}',
            
            toggleSelectAll() {
                if (this.selectAll) {
                    this.selectedIds = [...this.allPageIds];
                } else {
                    this.selectedIds = [];
                }
            },
            updateSelectAllState() {
                this.selectAll = this.allPageIds.length > 0 && this.selectedIds.length === this.allPageIds.length;
            },
            getRegencies(provId) {
                if (!provId) return [];
                const pid = String(provId);
                return this.regenciesMap[pid] || this.regenciesMap[Number(pid)] || [];
            },
            editData: {
                id: '',
                participant_code: '',
                name: '',
                email: '',
                phone: '',
                category: 'Umum',
                sub_category: '',
                school_class: '',
                event_id: '',
                batch: '',
                gender: 'Laki-laki',
                institution_id: '',
                program_id: '',
                province_id: '',
                regency_id: '',
                school_custom: '',
                university_custom: '',
                occupation_custom: '',
                status: 'active'
            },
            openEdit(partOrId) {
                const part = (typeof partOrId === 'object' && partOrId !== null)
                    ? partOrId
                    : (this.participantsMap[partOrId] || this.participantsMap[String(partOrId)] || {});
                this.editData = {
                    id: part.id || '',
                    participant_code: part.participant_code || '',
                    name: part.name || '',
                    email: part.email || '',
                    phone: part.phone || '',
                    category: part.category || 'Umum',
                    sub_category: part.sub_category || '',
                    school_class: part.school_class || '',
                    event_id: part.event_id || '',
                    batch: part.batch || 'Angkatan {{ date('Y') }}',
                    gender: part.gender || 'Laki-laki',
                    institution_id: part.institution_id || '',
                    program_id: part.program_id || '',
                    province_id: part.province_id ? String(part.province_id) : '',
                    regency_id: part.regency_id ? String(part.regency_id) : '',
                    school_custom: part.school_custom || '',
                    university_custom: part.university_custom || '',
                    occupation_custom: part.occupation_custom || '',
                    status: part.status || 'active'
                };
                this.editModal = true;
            },
            openDrawer(partOrId, tab = 'profile') {
                const part = (typeof partOrId === 'object' && partOrId !== null)
                    ? partOrId
                    : (this.participantsMap[partOrId] || this.participantsMap[String(partOrId)] || null);
                if (!part) return;
                this.selectedParticipant = part;
                this.drawerTab = tab;
                this.drawerOpen = true;
            }
        };
    }
</script>

<div class="space-y-6" x-data="participantManager()">

    <!-- 1. HUB NAVIGATION BAR (Peserta, Sesi Evaluasi, & Laporan PDF) -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-2 font-sans">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.participants.index') }}" 
               class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ request()->routeIs('admin.participants.*') ? 'bg-[#0F2537] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.participants.*') ? 'text-[#C5A059]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Data Peserta & Demografi</span>
            </a>

            <a href="{{ route('admin.results.index') }}" 
               class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ request()->routeIs('admin.results.*') ? 'bg-[#0F2537] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.results.*') ? 'text-[#C5A059]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Lembar Sesi & Hasil Evaluasi</span>
            </a>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" @click="showDemographics = !showDemographics" class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-semibold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                <span x-text="showDemographics ? 'Tutup Analisis Demografi' : 'Buka Analisis Demografi'"></span>
            </button>

            <a href="{{ route('admin.reports.export_pdf', request()->all()) }}" target="_blank" class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 text-[#0F2537] border border-slate-200 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Laporan PDF A4</span>
            </a>
        </div>
    </div>

    <!-- 2. PAGE TITLE & TOP ACTION BUTTONS -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl sm:text-2xl font-bold font-serif text-[#0F2537] tracking-tight">Manajemen Data Peserta & Demografi</h2>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-[#FAF7F0] text-[#8C703E] border border-[#E8DFC8]">
                    {{ number_format($stats['total_participants'] ?? 0) }} Data
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Pengelolaan basis data peserta, pemetaan demografi 38 provinsi, riwayat pengerjaan asesmen, dan sinkronisasi ekspor-impor.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Export CSV -->
            <a href="{{ route('admin.participants.export', request()->all()) }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-semibold text-xs rounded-xl shadow-2xs transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export CSV</span>
            </a>

            <!-- Impor Server Online -->
            <button type="button" @click="importServerModal = true" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl shadow-2xs transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-[#C5A059]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                <span>Impor Server Online</span>
            </button>

            <!-- Impor Template CSV -->
            <button type="button" @click="importModal = true" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-300 transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Import Template</span>
            </button>

            <!-- Tambah Peserta Baru -->
            <button type="button" @click="createModal = true" class="px-4 py-2 bg-[#0F2537] hover:bg-[#1A3A54] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-[#C5A059]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Peserta</span>
            </button>
        </div>
    </div>

    <!-- 3. DEMOGRAPHIC ANALYTICS & INSIGHTS DASHBOARD (EXPANDABLE) -->
    <div x-show="showDemographics" x-transition.opacity.duration.250ms class="space-y-4">
        
        <!-- 4 Top Headline Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-xs font-sans">
            <!-- Card 1: Total Peserta -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1.5">
                <div class="flex items-center justify-between text-slate-500 font-mono text-[10px] uppercase tracking-wider font-semibold">
                    <span>TOTAL DATABASE</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-[#0F2537]">
                    {{ number_format($stats['total_participants'] ?? 0) }}
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1 border-t border-slate-100">
                    <span>Status Aktif</span>
                    <span class="font-bold text-emerald-700 font-mono">{{ number_format($stats['active_participants'] ?? 0) }} Aktif</span>
                </div>
            </div>

            <!-- Card 2: Cakupan Demografi Wilayah -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1.5">
                <div class="flex items-center justify-between text-slate-500 font-mono text-[10px] uppercase tracking-wider font-semibold">
                    <span>SEBARAN WILAYAH</span>
                    <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-[#8C703E]">
                    {{ number_format($stats['total_provinces'] ?? 0) }} <span class="text-xs text-slate-400 font-normal">/ 38 Provinsi</span>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1 border-t border-slate-100">
                    <span>Cakupan Nasional</span>
                    <span class="font-bold text-[#8C703E] font-mono">{{ round((($stats['total_provinces'] ?? 0) / 38) * 100) }}% Terpetakan</span>
                </div>
            </div>

            <!-- Card 3: Partisipasi Sesi Asesmen -->
            @php
                $testedPct = ($stats['total_participants'] ?? 0) > 0 ? round((($stats['has_submissions'] ?? 0) / $stats['total_participants']) * 100) : 0;
            @endphp
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1.5">
                <div class="flex items-center justify-between text-slate-500 font-mono text-[10px] uppercase tracking-wider font-semibold">
                    <span>PARTISIPASI ASESMEN</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-emerald-700">
                    {{ number_format($stats['has_submissions'] ?? 0) }} <span class="text-xs text-slate-400 font-normal">Peserta</span>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1 border-t border-slate-100">
                    <span>Rasio Mengerjakan</span>
                    <span class="font-bold text-emerald-700 font-mono">{{ $testedPct }}% ({{ number_format($stats['total_submissions'] ?? 0) }} Sesi)</span>
                </div>
            </div>

            <!-- Card 4: Segmentasi Pendaftaran -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1.5">
                <div class="flex items-center justify-between text-slate-500 font-mono text-[10px] uppercase tracking-wider font-semibold">
                    <span>SEGMENTASI AKSES</span>
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                </div>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="text-base font-bold font-mono text-emerald-700">{{ number_format($stats['public_participants'] ?? 0) }}</span>
                    <span class="text-[10px] text-slate-400">Mandiri</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-base font-bold font-mono text-[#8C703E]">{{ number_format($stats['event_participants'] ?? 0) }}</span>
                    <span class="text-[10px] text-slate-400">Event</span>
                </div>
                <!-- Segmented Mini Bar -->
                @php
                    $pubPct = ($stats['total_participants'] ?? 0) > 0 ? round((($stats['public_participants'] ?? 0) / $stats['total_participants']) * 100) : 0;
                    $evPct = 100 - $pubPct;
                @endphp
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden flex mt-2">
                    <div class="bg-emerald-600 h-full" style="width: {{ $pubPct }}%"></div>
                    <div class="bg-[#8C703E] h-full" style="width: {{ $evPct }}%"></div>
                </div>
            </div>
        </div>

        <!-- 3 Demographic Breakdown Visuals (Top Provinces, Categories, & Gender) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4 font-sans text-xs">
            
            <!-- Breakdown 1: Top 5 Sebaran Provinsi -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="font-bold text-slate-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                        </svg>
                        <span>Top 5 Sebaran Provinsi</span>
                    </h3>
                    <span class="text-[10px] font-mono text-slate-400 font-semibold">Peserta Terdaftar</span>
                </div>

                <div class="space-y-2">
                    @forelse($topProvinces as $tp)
                        @php
                            $tpCount = $tp->count ?? 0;
                            $tpPct = ($stats['total_participants'] ?? 0) > 0 ? round(($tpCount / $stats['total_participants']) * 100, 1) : 0;
                        @endphp
                        <a href="{{ route('admin.participants.index', array_merge(request()->except('province_id', 'page'), ['province_id' => $tp->province_id])) }}" 
                           class="group block p-1.5 rounded-xl hover:bg-slate-50 transition">
                            <div class="flex justify-between items-center text-[11px] mb-1">
                                <span class="font-medium text-slate-800 group-hover:text-[#0F2537] flex items-center gap-1">
                                    <span>📍</span>
                                    <span>{{ $tp->province->name ?? 'Luar Wilayah' }}</span>
                                </span>
                                <span class="font-mono text-slate-600 font-bold">
                                    {{ number_format($tpCount) }} <span class="text-slate-400 font-normal">({{ $tpPct }}%)</span>
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-[#0F2537] h-full rounded-full transition-all group-hover:bg-[#8C703E]" style="width: {{ $tpPct }}%"></div>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-slate-400 text-xs">Belum ada data sebaran provinsi.</div>
                    @endforelse
                </div>
            </div>

            <!-- Breakdown 2: Segmentasi Kategori Peserta -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="font-bold text-slate-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>Komposisi Kategori Peserta</span>
                    </h3>
                    <span class="text-[10px] font-mono text-slate-400 font-semibold">Sasaran</span>
                </div>

                <div class="space-y-2">
                    @forelse($categoryStats as $cs)
                        @php
                            $cCount = $cs->count ?? 0;
                            $cPct = ($stats['total_participants'] ?? 0) > 0 ? round(($cCount / $stats['total_participants']) * 100, 1) : 0;
                            $catName = strtoupper($cs->category ?? 'UMUM');
                            $catColor = match($catName) {
                                'PELAJAR', 'MURID' => 'bg-sky-50 text-sky-800 border-sky-200',
                                'MAHASISWA' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                'ASN', 'GURU' => 'bg-amber-50 text-amber-800 border-amber-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200'
                            };
                        @endphp
                        <a href="{{ route('admin.participants.index', array_merge(request()->except('category', 'page'), ['category' => $cs->category])) }}" 
                           class="flex items-center justify-between p-2 rounded-xl border {{ $catColor }} hover:shadow-xs transition">
                            <span class="font-bold text-xs uppercase tracking-wide">{{ $cs->category ?: 'Umum' }}</span>
                            <span class="font-mono font-bold">{{ number_format($cCount) }} <span class="text-slate-400 font-normal">({{ $cPct }}%)</span></span>
                        </a>
                    @empty
                        <div class="p-4 text-center text-slate-400 text-xs">Belum ada kategori yang diklasifikasikan.</div>
                    @endforelse
                </div>
            </div>

            <!-- Breakdown 3: Demografi Gender & Status Partisipasi -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <h3 class="font-bold text-slate-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Proporsi Jenis Kelamin</span>
                        </h3>
                        <span class="text-[10px] font-mono text-slate-400 font-semibold">Demografi</span>
                    </div>

                    @php
                        $maleCount = $genderStats['male'] ?? 0;
                        $femaleCount = $genderStats['female'] ?? 0;
                        $totalGender = $maleCount + $femaleCount;
                        $malePct = $totalGender > 0 ? round(($maleCount / $totalGender) * 100) : 50;
                        $femalePct = 100 - $malePct;
                    @endphp
                    <div class="pt-3 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-700 flex items-center gap-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#0F2537]"></span> Laki-laki: <strong>{{ number_format($maleCount) }}</strong>
                            </span>
                            <span class="font-medium text-slate-700 flex items-center gap-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> Perempuan: <strong>{{ number_format($femaleCount) }}</strong>
                            </span>
                        </div>

                        <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden flex shadow-inner">
                            <div class="bg-[#0F2537] h-full" style="width: {{ $malePct }}%" title="Laki-laki {{ $malePct }}%"></div>
                            <div class="bg-rose-400 h-full" style="width: {{ $femalePct }}%" title="Perempuan {{ $femalePct }}%"></div>
                        </div>

                        <div class="flex justify-between text-[10.5px] font-mono text-slate-400">
                            <span>{{ $malePct }}% Laki-laki</span>
                            <span>{{ $femalePct }}% Perempuan</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Belum Pernah Tes:</span>
                    <strong class="font-mono text-amber-800">{{ number_format($stats['no_submissions'] ?? 0) }} Peserta</strong>
                </div>
            </div>

        </div>

    </div>

    <!-- 4. SUB-TAB STATUS QUICK SWITCHER -->
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 pb-2">
        <div class="flex flex-wrap items-center gap-1.5">
            <!-- Semua Peserta -->
            <a href="{{ route('admin.participants.index', array_merge(request()->except(['access_type', 'test_status', 'page']), [])) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5 {{ empty(request('access_type')) && empty(request('test_status')) ? 'bg-[#0F2537] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>Semua Peserta</span>
                <span class="px-2 py-0.2 rounded-full text-[10px] font-mono {{ empty(request('access_type')) && empty(request('test_status')) ? 'bg-[#C5A059] text-[#0F2537] font-black' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($stats['total_participants'] ?? 0) }}
                </span>
            </a>

            <!-- Mandiri Publik -->
            <a href="{{ route('admin.participants.index', array_merge(request()->except(['access_type', 'test_status', 'page']), ['access_type' => 'public'])) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5 {{ request('access_type') === 'public' ? 'bg-[#0F2537] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>Mandiri (Publik)</span>
                <span class="px-2 py-0.2 rounded-full text-[10px] font-mono {{ request('access_type') === 'public' ? 'bg-emerald-400 text-slate-950 font-black' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($stats['public_participants'] ?? 0) }}
                </span>
            </a>

            <!-- Event Khusus -->
            <a href="{{ route('admin.participants.index', array_merge(request()->except(['access_type', 'test_status', 'page']), ['access_type' => 'event_only'])) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5 {{ request('access_type') === 'event_only' ? 'bg-[#0F2537] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>Event / Institusi</span>
                <span class="px-2 py-0.2 rounded-full text-[10px] font-mono {{ request('access_type') === 'event_only' ? 'bg-[#C5A059] text-[#0F2537] font-black' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($stats['event_participants'] ?? 0) }}
                </span>
            </a>

            <!-- Sudah Tes -->
            <a href="{{ route('admin.participants.index', array_merge(request()->except(['access_type', 'test_status', 'page']), ['test_status' => 'tested'])) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5 {{ request('test_status') === 'tested' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>Sudah Asesmen</span>
                <span class="px-2 py-0.2 rounded-full text-[10px] font-mono {{ request('test_status') === 'tested' ? 'bg-emerald-300 text-emerald-950 font-black' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($stats['has_submissions'] ?? 0) }}
                </span>
            </a>

            <!-- Belum Tes -->
            <a href="{{ route('admin.participants.index', array_merge(request()->except(['access_type', 'test_status', 'page']), ['test_status' => 'untested'])) }}" 
               class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5 {{ request('test_status') === 'untested' ? 'bg-amber-800 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>Belum Asesmen</span>
                <span class="px-2 py-0.2 rounded-full text-[10px] font-mono {{ request('test_status') === 'untested' ? 'bg-amber-300 text-amber-950 font-black' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($stats['no_submissions'] ?? 0) }}
                </span>
            </a>
        </div>

        <span class="text-[11px] font-mono text-slate-400">
            Klik baris untuk membuka panel rincian & hasil tes
        </span>
    </div>

    <!-- 5. ADVANCED MULTI-CRITERIA FILTER BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3 font-sans text-xs">
        <form action="{{ route('admin.participants.index') }}" method="GET" class="space-y-3">
            @if(request('access_type'))
                <input type="hidden" name="access_type" value="{{ request('access_type') }}">
            @endif
            @if(request('test_status'))
                <input type="hidden" name="test_status" value="{{ request('test_status') }}">
            @endif

            <!-- Primary Search Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5">
                
                <!-- Keyword Search (5 cols) -->
                <div class="lg:col-span-5">
                    <label class="block font-bold text-slate-700 mb-1">Cari Peserta (Nama, Kode, Email, No. HP, Sekolah)</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik kata kunci pencarian..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#0F2537] focus:border-transparent">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Event Filter (4 cols) -->
                <div class="lg:col-span-4">
                    <label class="block font-bold text-slate-700 mb-1">Filter Event / Kegiatan Asesmen</label>
                    <select name="event_id" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Event / Kegiatan</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>
                                {{ $ev->title }} ({{ $ev->event_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori Filter (3 cols) -->
                <div class="lg:col-span-3">
                    <label class="block font-bold text-slate-700 mb-1">Kategori Sasaran</label>
                    <select name="category" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white font-medium">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}" {{ strtoupper((string) request('category')) == strtoupper($cat->name) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Secondary Demographics Row -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5 pt-1">
                <!-- Provinsi -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Provinsi</label>
                    <select name="province_id" x-model="filterProvinceId" @change="filterRegencyId = ''" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white font-medium">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kab/Kota -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Kabupaten / Kota</label>
                    <select name="regency_id" x-model="filterRegencyId" :disabled="!filterProvinceId" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white font-medium disabled:bg-slate-100 disabled:text-slate-400">
                        <option value="" x-text="filterProvinceId ? 'Semua Kab/Kota' : 'Pilih Provinsi Dahulu'"></option>
                        <template x-for="reg in getRegencies(filterProvinceId)" :key="reg.id">
                            <option :value="reg.id" x-text="reg.formatted_name || reg.name" :selected="String(reg.id) === String(filterRegencyId)"></option>
                        </template>
                    </select>
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Jenis Kelamin</label>
                    <select name="gender" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Gender</option>
                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- Status Akun -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Status Akun</label>
                    <select name="status" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>

                <!-- Institusi Mitra -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Institusi Mitra</label>
                    <select name="institution_id" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Institusi</option>
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}" {{ request('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter Buttons -->
                <div class="flex items-end gap-1.5">
                    <button type="submit" class="w-full py-2 bg-[#0F2537] hover:bg-[#1A3A54] text-white font-bold text-xs rounded-xl transition shadow-xs cursor-pointer">
                        Filter Data
                    </button>
                    @if(request()->anyFilled(['q', 'category', 'event_id', 'institution_id', 'program_id', 'province_id', 'regency_id', 'gender', 'status']))
                        <a href="{{ route('admin.participants.index', request()->only(['access_type', 'test_status'])) }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- 6. FLOATING BULK ACTIONS BAR (Muncul saat checkbox peserta dipilih) -->
    <div x-show="selectedIds.length > 0" x-cloak x-transition.opacity.duration.200ms
         class="bg-[#0F2537] text-white p-3 rounded-2xl shadow-xl border border-[#C5A059]/40 flex flex-wrap items-center justify-between gap-3 font-sans text-xs sticky top-4 z-40">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#C5A059] animate-pulse"></span>
            <span class="font-bold text-slate-200" x-text="selectedIds.length + ' Peserta Dipilih'"></span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Form Export Terpilih -->
            <form action="{{ route('admin.participants.bulk_action') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="export">
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="px-3 py-1.5 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold text-xs rounded-lg transition flex items-center gap-1 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Export Terpilih</span>
                </button>
            </form>

            <!-- Form Aktifkan Terpilih -->
            <form action="{{ route('admin.participants.bulk_action') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="activate">
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-600 text-white font-semibold text-xs rounded-lg transition cursor-pointer">
                    Set Aktif
                </button>
            </form>

            <!-- Form Nonaktifkan Terpilih -->
            <form action="{{ route('admin.participants.bulk_action') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="deactivate">
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white font-semibold text-xs rounded-lg transition cursor-pointer">
                    Set Nonaktif
                </button>
            </form>

            <!-- Form Hapus Terpilih -->
            <form action="{{ route('admin.participants.bulk_action') }}" method="POST" class="inline" onsubmit="return confirm('Hapus seluruh peserta yang dipilih secara permanen?')">
                @csrf
                <input type="hidden" name="action" value="delete">
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="px-3 py-1.5 bg-rose-700 hover:bg-rose-600 text-white font-semibold text-xs rounded-lg transition cursor-pointer">
                    Hapus
                </button>
            </form>

            <!-- Batal Pilih -->
            <button type="button" @click="selectedIds = []; selectAll = false" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-lg transition cursor-pointer">
                Batal
            </button>
        </div>
    </div>

    <!-- 7. MAIN PARTICIPANTS DATA TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden font-sans">
        
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs font-mono font-bold text-slate-600">
            <span>DAFTAR PESERTA TERDAFTAR ({{ number_format($participants->total()) }} Peserta Ditemukan)</span>
            <span class="text-[11px] text-slate-400 font-normal">Halaman {{ $participants->currentPage() }} dari {{ $participants->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[1100px]">
                <thead class="bg-slate-100 text-slate-700 uppercase font-bold border-b border-slate-200 text-[10px] tracking-wider font-mono">
                    <tr>
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="rounded border-slate-300 text-[#0F2537] focus:ring-[#0F2537] cursor-pointer">
                        </th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Profil & Kode Peserta</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Kategori & Sasaran</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Demografi Wilayah</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Event / Institusi</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Kontak Peserta</th>
                        <th class="px-4 py-3.5 whitespace-nowrap text-center">Riwayat Asesmen</th>
                        <th class="px-4 py-3.5 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($participants as $part)
                        @php
                            $hasTest = $part->submissions_count > 0;
                            $latestSub = $part->submissions->sortByDesc('created_at')->first();
                            $latestRes = $latestSub?->result;
                        @endphp
                        <tr class="hover:bg-amber-50/40 transition cursor-pointer" @click="openDrawer({{ $part->id }}, 'profile')">
                            
                            <!-- Checkbox Column -->
                            <td class="p-4 text-center" @click.stop>
                                <input type="checkbox" :value="{{ $part->id }}" x-model="selectedIds" @change="updateSelectAllState()" class="rounded border-slate-300 text-[#0F2537] focus:ring-[#0F2537] cursor-pointer">
                            </td>

                            <!-- Profil & Identitas -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#0F2537] text-[#C5A059] font-bold text-xs flex items-center justify-center font-mono shrink-0 shadow-2xs border border-[#C5A059]/30">
                                        {{ strtoupper(substr($part->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <button type="button" class="font-bold text-slate-900 hover:text-[#0F2537] hover:underline text-xs text-left block leading-snug cursor-pointer">
                                            {{ $part->name }}
                                        </button>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="font-mono text-[10px] text-amber-900 font-bold bg-[#FAF7F0] px-1.5 py-0.2 rounded border border-[#DFD8C8]">
                                                {{ $part->participant_code }}
                                            </span>
                                            @if($part->status === 'active')
                                                <span class="inline-flex items-center gap-1 text-[9.5px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[9.5px] font-semibold text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori & Sasaran -->
                            <td class="p-4" @click.stop>
                                @php
                                    $catName = strtoupper($part->category ?? 'UMUM');
                                    $catPill = match($catName) {
                                        'PELAJAR', 'MURID' => 'bg-sky-50 text-sky-800 border-sky-200',
                                        'MAHASISWA' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                        'ASN', 'GURU' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border inline-block {{ $catPill }}">
                                    {{ $part->category ?: 'Umum' }}
                                </span>
                                @if($part->sub_category || $part->school_class)
                                    <span class="block text-[10px] text-slate-500 font-mono mt-0.5">
                                        {{ $part->sub_category ?: $part->school_class }}
                                    </span>
                                @endif
                            </td>

                            <!-- Demografi Wilayah -->
                            <td class="p-4">
                                <span class="font-bold text-slate-800 text-xs flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-[#8C703E] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $part->province?->name ?: 'Tidak Diisi' }}</span>
                                </span>
                                <span class="text-slate-500 text-[11px] block pl-4.5 font-normal">
                                    {{ $part->regency?->name ?: '-' }}
                                </span>
                            </td>

                            <!-- Event / Asal Lembaga -->
                            <td class="p-4">
                                @if($part->event)
                                    <span class="font-bold text-[#0F2537] text-xs block truncate max-w-[170px]" title="{{ $part->event->title }}">
                                        {{ $part->event->title }}
                                    </span>
                                    <span class="text-[10px] font-mono text-[#8C703E] block font-semibold">
                                        Kode: {{ $part->event->event_code }}
                                    </span>
                                @elseif($part->access_type === 'event_only')
                                    <span class="font-semibold text-amber-800 text-xs block">Event Khusus</span>
                                @else
                                    <span class="font-semibold text-emerald-700 text-xs block">Mandiri (Publik)</span>
                                @endif

                                @php
                                    $schoolOrUni = $part->school_custom ?: ($part->university_custom ?: ($part->occupation_custom ?: ($part->school?->name ?: ($part->university?->name ?: '-'))));
                                @endphp
                                @if($schoolOrUni && $schoolOrUni !== '-')
                                    <span class="text-slate-500 text-[10.5px] block truncate max-w-[170px] mt-0.5" title="{{ $schoolOrUni }}">
                                        {{ $schoolOrUni }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kontak Peserta -->
                            <td class="p-4">
                                <span class="font-medium text-slate-800 block text-xs truncate max-w-[160px]" title="{{ $part->email }}">{{ $part->email }}</span>
                                @if($part->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $part->phone) }}" target="_blank" @click.stop class="text-emerald-700 hover:text-emerald-800 text-[11px] font-mono inline-flex items-center gap-1 mt-0.5 font-semibold">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.997.551 1.942.849 2.801.849 3.181 0 5.767-2.586 5.768-5.766.001-3.18-2.585-5.766-5.768-5.766zm-7.031 5.766c0-3.878 3.153-7.031 7.031-7.031 3.879 0 7.031 3.153 7.031 7.031 0 3.879-3.152 7.031-7.031 7.031-.89 0-1.896-.242-2.928-.716l-3.328.871.887-3.247c-.559-1.077-.662-2.061-.662-3.939z"/>
                                        </svg>
                                        <span>{{ $part->phone }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] font-mono block mt-0.5">-</span>
                                @endif
                            </td>

                            <!-- Riwayat Asesmen -->
                            <td class="p-4 text-center" @click.stop>
                                @if($hasTest && $latestRes)
                                    <button type="button" @click="openDrawer({{ $part->id }}, 'submissions')" class="inline-block p-1.5 bg-[#FAF7F0] hover:bg-[#F4EEDC] border border-[#DFD8C8] rounded-xl text-center transition cursor-pointer">
                                        <div class="flex items-center justify-center gap-1 font-mono text-xs font-bold text-[#8C703E]">
                                            <span>RQI: {{ $latestRes->rqi_score ?? $latestRes->total_score }}</span>
                                            @if($latestRes->who5_percentage !== null)
                                                <span class="text-emerald-700">| WHO: {{ $latestRes->who5_percentage }}%</span>
                                            @endif
                                        </div>
                                        <span class="text-[9.5px] text-slate-500 font-semibold block mt-0.5">
                                            {{ $part->submissions_count }} Sesi Asesmen
                                        </span>
                                    </button>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-400 font-medium text-[10px] rounded-full inline-block">
                                        Belum Asesmen
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right" @click.stop>
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" @click="openDrawer({{ $part->id }}, 'profile')" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition cursor-pointer" title="Lihat Profil & Hasil">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button type="button" @click="openEdit({{ $part->id }})" class="p-2 bg-[#FAF7F0] hover:bg-[#F4EEDC] text-[#8C703E] border border-[#DFD8C8] rounded-lg text-xs font-bold transition cursor-pointer" title="Edit Peserta">
                                        <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.participants.destroy', $part->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus peserta ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition cursor-pointer" title="Hapus Peserta">
                                            <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center text-slate-400 font-sans space-y-2">
                                <svg class="w-10 h-10 text-slate-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div class="font-bold text-slate-700 text-sm">Tidak ada data peserta ditemukan</div>
                                <p class="text-xs text-slate-500">Coba sesuaikan kata kunci pencarian atau ubah kriteria filter yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200">
            {{ $participants->links() }}
        </div>
    </div>

    <!-- 8. SLIDE-OVER DRAWER (RIGHT PANEL FOR PARTICIPANT PROFILE & EVALUATION) -->
    <div x-show="drawerOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden">
        <!-- Backdrop -->
        <div x-show="drawerOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="drawerOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="drawerOpen" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-xl bg-white shadow-2xl border-l border-slate-200 flex flex-col justify-between">
                
                <!-- Drawer Header -->
                <div class="p-5 bg-[#0F2537] text-white space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 text-[#C5A059] font-bold text-base flex items-center justify-center font-mono border border-white/20">
                                <span x-text="selectedParticipant ? selectedParticipant.name.substring(0, 2).toUpperCase() : 'PS'"></span>
                            </div>
                            <div>
                                <h3 class="font-bold font-serif text-white text-lg leading-snug" x-text="selectedParticipant?.name"></h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-xs text-[#C5A059] font-bold bg-white/10 px-2 py-0.5 rounded border border-[#C5A059]/30" x-text="selectedParticipant?.participant_code"></span>
                                    <span class="text-xs text-slate-300 font-mono truncate max-w-[200px]" x-text="selectedParticipant?.email"></span>
                                </div>
                            </div>
                        </div>
                        <button @click="drawerOpen = false" class="text-slate-400 hover:text-white text-xl font-bold p-1 cursor-pointer">✕</button>
                    </div>

                    <!-- Drawer Sub-Tab Switcher -->
                    <div class="flex items-center gap-2 pt-2 border-t border-white/15">
                        <button type="button" @click="drawerTab = 'profile'" :class="drawerTab === 'profile' ? 'bg-[#C5A059] text-[#0F2537] font-black' : 'bg-white/10 text-white hover:bg-white/20 font-bold'" class="px-4 py-1.5 rounded-lg text-xs transition cursor-pointer">
                            Biodata & Demografi
                        </button>
                        <button type="button" @click="drawerTab = 'submissions'" :class="drawerTab === 'submissions' ? 'bg-[#C5A059] text-[#0F2537] font-black' : 'bg-white/10 text-white hover:bg-white/20 font-bold'" class="px-4 py-1.5 rounded-lg text-xs transition cursor-pointer flex items-center gap-1.5">
                            <span>Sesi & Hasil Asesmen</span>
                            <span class="px-1.5 py-0.2 rounded-full bg-white/20 text-[10px]" x-text="selectedParticipant?.submissions ? selectedParticipant.submissions.length : 0"></span>
                        </button>
                    </div>
                </div>

                <!-- Drawer Main Body -->
                <div class="p-6 overflow-y-auto space-y-5 flex-1 font-sans text-xs">
                    
                    <!-- TAB 1: BIODATA & DEMOGRAFI -->
                    <div x-show="drawerTab === 'profile'" class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider border-b border-slate-200 pb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Identitas Utama Peserta</span>
                            </h4>
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-slate-400 font-mono text-[10px] block">KODE PESERTA</span>
                                    <strong class="text-slate-800 font-mono font-bold text-xs" x-text="selectedParticipant?.participant_code"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-mono text-[10px] block">KODE ASESMEN</span>
                                    <strong class="text-slate-800 font-mono font-bold text-xs" x-text="selectedParticipant?.assessment_code || '-'"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-mono text-[10px] block">JENIS KELAMIN</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.gender || '-'"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-mono text-[10px] block">STATUS AKUN</span>
                                    <strong class="uppercase font-bold text-xs" :class="selectedParticipant?.status === 'active' ? 'text-emerald-700' : 'text-rose-700'" x-text="selectedParticipant?.status"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Demografi & Pendidikan -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider border-b border-slate-200 pb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Demografi & Asal Pendidikan</span>
                            </h4>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                    <span class="text-slate-500">Provinsi</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.province?.name || 'Tidak Diisi'"></strong>
                                </div>
                                <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                    <span class="text-slate-500">Kabupaten / Kota</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.regency?.name || '-'"></strong>
                                </div>
                                <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                    <span class="text-slate-500">Kategori / Sasaran</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.category || 'Umum'"></strong>
                                </div>
                                <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                    <span class="text-slate-500">Sub-Kategori / Kelas</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.sub_category || selectedParticipant?.school_class || '-'"></strong>
                                </div>
                                <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                    <span class="text-slate-500">Sekolah / Kampus</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.school_custom || selectedParticipant?.university_custom || selectedParticipant?.school?.name || selectedParticipant?.university?.name || '-'"></strong>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <span class="text-slate-500">Event / Kegiatan</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.event?.title || (selectedParticipant?.access_type === 'event_only' ? 'Event Khusus' : 'Mandiri (Publik)')"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Kontak & WhatsApp -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider border-b border-slate-200 pb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Informasi Kontak & Komunikasi</span>
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                    <span class="text-slate-500">Email</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.email"></strong>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <span class="text-slate-500">Nomor Telepon / WhatsApp</span>
                                    <template x-if="selectedParticipant?.phone">
                                        <a :href="'https://wa.me/' + selectedParticipant.phone.replace(/[^0-9]/g, '')" target="_blank" class="px-2.5 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold rounded-lg text-xs transition flex items-center gap-1">
                                            <span>💬 Chat WA:</span>
                                            <span x-text="selectedParticipant.phone"></span>
                                        </a>
                                    </template>
                                    <template x-if="!selectedParticipant?.phone">
                                        <span class="text-slate-400">-</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: RIWAYAT SESI & HASIL EVALUASI TES -->
                    <div x-show="drawerTab === 'submissions'" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Riwayat Sesi & Hasil Asesmen</span>
                            </h4>
                            <span class="text-xs font-mono font-bold bg-[#FAF7F0] text-[#8C703E] border border-[#DFD8C8] px-2.5 py-0.5 rounded-full" x-text="(selectedParticipant?.submissions ? selectedParticipant.submissions.length : 0) + ' Sesi Pengerjaan'"></span>
                        </div>

                        <template x-if="selectedParticipant && selectedParticipant.submissions && selectedParticipant.submissions.length > 0">
                            <div class="space-y-3">
                                <template x-for="sub in selectedParticipant.submissions" :key="sub.id">
                                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-3">
                                        <div class="flex justify-between items-start border-b border-slate-100 pb-2">
                                            <div>
                                                <strong class="text-slate-900 font-bold text-xs block" x-text="(sub.period && sub.period.instrument) ? sub.period.instrument.name : (sub.period ? sub.period.title : 'Instrumen Asesmen Ruhiologi')"></strong>
                                                <span class="text-[10px] text-slate-400 font-mono" x-text="sub.created_at ? new Date(sub.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-'"></span>
                                            </div>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="(sub.submission_type || sub.type) === 'pretest' ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800'" x-text="sub.submission_type || sub.type || 'Evaluasi'"></span>
                                        </div>

                                        <!-- Dual Score Badges (RQI + WHO-5) -->
                                        <div class="grid grid-cols-2 gap-2 text-center bg-[#FAF7F0] p-3 rounded-xl border border-[#E8DFC8]">
                                            <div>
                                                <span class="text-[9.5px] text-slate-500 font-mono block">INDEKS RUHIOLOGI (RQI)</span>
                                                <span class="text-xl font-bold font-mono text-[#8C703E]" x-text="sub.result ? (sub.result.rqi_score || sub.result.total_score || '0') : (sub.total_score || '0')"></span>
                                                <span class="text-[10px] font-bold text-[#0F2537] block mt-0.5" x-text="sub.result ? (sub.result.category_name || 'Tervalidasi') : 'Tervalidasi'"></span>
                                            </div>
                                            <div class="border-l border-[#DFD8C8] pl-2">
                                                <span class="text-[9.5px] text-slate-500 font-mono block">WHO-5 MENTAL WELL-BEING</span>
                                                <span class="text-xl font-bold font-mono text-emerald-700" x-text="sub.result && sub.result.who5_percentage !== null ? sub.result.who5_percentage + '%' : '-'"></span>
                                                <span class="text-[10px] font-bold block mt-0.5" :class="(sub.result && sub.result.who5_percentage >= 50) ? 'text-emerald-700' : 'text-amber-700'" x-text="(sub.result && sub.result.who5_percentage >= 50) ? 'Kesejahteraan Baik' : 'Perlu Perhatian'"></span>
                                            </div>
                                        </div>

                                        <!-- Action Links -->
                                        <div class="pt-1 flex flex-wrap justify-end gap-2">
                                            <template x-if="sub.result && sub.result.id">
                                                <a :href="'{{ url('admin/results') }}/' + sub.result.id" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-xl border border-slate-200">
                                                    Audit Detail
                                                </a>
                                            </template>
                                            <a :href="'{{ url('assessment/result') }}/' + sub.submission_code" target="_blank" class="px-3 py-1.5 bg-[#0F2537] hover:bg-[#1A3A54] text-white font-bold text-xs rounded-xl shadow-2xs">
                                                Laporan Hasil →
                                            </a>
                                            <a :href="'{{ url('assessment/result') }}/' + sub.submission_code + '/certificate'" target="_blank" class="px-3 py-1.5 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold text-xs rounded-xl shadow-2xs">
                                                Sertifikat Digital
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="!selectedParticipant || !selectedParticipant.submissions || selectedParticipant.submissions.length === 0">
                            <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                                <svg class="w-10 h-10 text-slate-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <strong class="text-slate-700 font-bold text-xs block">Belum Ada Sesi Pengerjaan Asesmen</strong>
                                <p class="text-[11px] text-slate-500">Peserta ini belum pernah mengerjakan sesi asesmen RQI atau WHO-5 Index.</p>
                            </div>
                        </template>
                    </div>

                </div>

                <!-- Drawer Footer Actions -->
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                    <button type="button" @click="openEdit(selectedParticipant); drawerOpen = false;" class="px-4 py-2 bg-[#FAF7F0] hover:bg-[#F4EEDC] text-[#8C703E] border border-[#DFD8C8] font-bold text-xs rounded-xl transition cursor-pointer">
                        Edit Data Peserta
                    </button>
                    <button type="button" @click="drawerOpen = false" class="px-5 py-2 bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs cursor-pointer">
                        Tutup Panel
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- 9. MODAL: REGISTRASI PESERTA BARU -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0F2537] text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span>Registrasi Peserta Baru</span>
                </h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.participants.store') }}" method="POST" class="space-y-3.5 text-xs font-sans">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Peserta (Unique) *</label>
                        <input type="text" name="participant_code" required value="PST-{{ date('Ym') }}-{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}" class="w-full p-2.5 rounded-xl border border-slate-300 font-mono font-bold text-[#8C703E]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Peserta *</label>
                        <input type="text" name="name" required placeholder="Nama lengkap beserta gelar..." class="w-full p-2.5 rounded-xl border border-slate-300 font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email Official *</label>
                        <input type="email" name="email" required placeholder="peserta@domain.com" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" placeholder="0812xxxxxxx" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Peserta *</label>
                        <select name="category" required class="w-full p-2.5 rounded-xl border border-slate-300 bg-white font-bold">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                            <option value="Umum" selected>Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Sub-Kategori / Kelas / Pekerjaan</label>
                        <input type="text" name="sub_category" placeholder="Contoh: Kelas 12 IPA 1 / Guru BK" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Event / Kegiatan (Opsional)</label>
                        <select name="event_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Pendaftaran Mandiri (Publik) --</option>
                            @foreach($events as $ev)
                                <option value="{{ $ev->id }}">{{ $ev->title }} ({{ $ev->event_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Institusi Mitra</label>
                        <select name="institution_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Tanpa Mitra Khusus --</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Demografi Wilayah -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provinsi (Demografi)</label>
                        <select name="province_id" x-model="createProvinceId" @change="createRegencyId = ''" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kabupaten / Kota</label>
                        <select name="regency_id" x-model="createRegencyId" :disabled="!createProvinceId" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="" x-text="createProvinceId ? '-- Pilih Kab/Kota --' : 'Pilih Provinsi Dahulu'"></option>
                            <template x-for="reg in getRegencies(createProvinceId)" :key="reg.id">
                                <option :value="reg.id" x-text="reg.formatted_name || reg.name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white font-medium">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sekolah / Kampus (Custom)</label>
                        <input type="text" name="school_custom" placeholder="Contoh: SMAN 1 Jambi / UIN STS Jambi" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Angkatan / Batch *</label>
                        <input type="text" name="batch" value="Angkatan {{ date('Y') }}" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded-xl font-bold text-slate-600">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-[#0F2537] hover:bg-[#1A3A54] text-white font-bold rounded-xl shadow-md cursor-pointer">Simpan & Daftarkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 10. MODAL: EDIT DATA PESERTA -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0F2537] text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Data Peserta</span>
                </h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form :action="'{{ url('admin/participants') }}/' + editData.id" method="POST" class="space-y-3.5 text-xs font-sans">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Peserta *</label>
                        <input type="text" name="participant_code" x-model="editData.participant_code" required class="w-full p-2.5 rounded-xl border border-slate-300 font-mono font-bold text-[#8C703E]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Peserta *</label>
                        <input type="text" name="name" x-model="editData.name" required class="w-full p-2.5 rounded-xl border border-slate-300 font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email *</label>
                        <input type="email" name="email" x-model="editData.email" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" x-model="editData.phone" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Peserta *</label>
                        <select name="category" x-model="editData.category" required class="w-full p-2.5 rounded-xl border border-slate-300 bg-white font-bold">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                            <option value="Umum">Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Sub-Kategori / Kelas</label>
                        <input type="text" name="sub_category" x-model="editData.sub_category" placeholder="Contoh: Kelas 12 IPA 1 / Dosen" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Event / Kegiatan</label>
                        <select name="event_id" x-model="editData.event_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Pendaftaran Mandiri (Publik) --</option>
                            @foreach($events as $ev)
                                <option value="{{ $ev->id }}">{{ $ev->title }} ({{ $ev->event_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Institusi Mitra</label>
                        <select name="institution_id" x-model="editData.institution_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Tanpa Mitra Khusus --</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Demografi Wilayah -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provinsi (Demografi)</label>
                        <select name="province_id" x-model="editData.province_id" @change="editData.regency_id = ''" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kabupaten / Kota</label>
                        <select name="regency_id" x-model="editData.regency_id" :disabled="!editData.province_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="" x-text="editData.province_id ? '-- Pilih Kab/Kota --' : 'Pilih Provinsi Dahulu'"></option>
                            <template x-for="reg in getRegencies(editData.province_id)" :key="reg.id">
                                <option :value="reg.id" x-text="reg.formatted_name || reg.name" :selected="String(reg.id) === String(editData.regency_id)"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" x-model="editData.gender" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Nama Sekolah / Kampus</label>
                        <input type="text" name="school_custom" x-model="editData.school_custom" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Keaktifan *</label>
                        <select name="status" x-model="editData.status" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white font-bold">
                            <option value="active">Active (Aktif)</option>
                            <option value="inactive">Inactive (Non-aktif)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border rounded-xl font-bold text-slate-600">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-[#0F2537] hover:bg-[#1A3A54] text-white font-bold rounded-xl shadow-md cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 11. MODAL: IMPORT CSV BATCH -->
    <div x-show="importModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0F2537] text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span>Import Data Peserta Massal (.csv)</span>
                </h3>
                <button @click="importModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <div class="p-3 bg-[#FAF7F0] rounded-xl border border-[#DFD8C8] text-xs text-slate-800 space-y-1">
                <strong class="font-bold block text-[#8C703E]">Petunjuk Format CSV:</strong>
                <p class="text-[11px] leading-relaxed text-slate-600">
                    Unggah file CSV dengan header: <code>participant_code, name, email, phone, gender, batch, institution_id, program_id, school_custom, university_custom, status</code>.
                </p>
                <div class="pt-1">
                    <a href="{{ route('admin.participants.template.download') }}" class="text-[#8C703E] font-bold underline hover:text-[#72592F] text-[11px] flex items-center gap-1">
                        <span>📥</span> Unduh Template CSV Peserta Standar
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.participants.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-sans">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih File CSV (.csv)</label>
                    <input type="file" name="csv_file" accept=".csv,text/csv" required class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50">
                </div>

                <div class="pt-2 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="importModal = false" class="px-4 py-2 border rounded-xl font-bold text-slate-600">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-[#0F2537] text-white font-bold rounded-xl shadow-md cursor-pointer">Mulai Import Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 12. MODAL: IMPOR DATA SERVER ONLINE (.CSV) -->
    <div x-show="importServerModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white max-w-xl w-full rounded-3xl p-6 border border-slate-200 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-serif font-bold text-lg text-[#0F2537] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    <span>Impor Data Asesmen Server Online (.csv)</span>
                </h3>
                <button @click="importServerModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <div class="p-3.5 bg-[#FAF7F0] rounded-2xl border border-[#DFD8C8] text-xs text-slate-800 space-y-1.5">
                <strong class="font-bold block text-[#8C703E]">
                    Sinkronisasi Otomatis Bebas Duplikasi:
                </strong>
                <ul class="list-disc list-inside text-[11px] leading-relaxed space-y-0.5 text-slate-600">
                    <li>Unggah file CSV hasil ekspor rekap dari server online.</li>
                    <li>Sistem mengecek <strong>Kode Sesi Assessment</strong> (`SUB-...`). Data duplikat otomatis dilewati (*skip*).</li>
                    <li>Peserta tanpa nama otomatis dikategorikan sebagai <strong>Peserta Mandiri (Publik)</strong>.</li>
                    <li>Otomatis memetakan 38 Provinsi, Kabupaten/Kota, Skor RQI (0-100), dan Indeks WHO-5 (%).</li>
                </ul>
            </div>

            <form action="{{ route('admin.participants.import_server_csv') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-sans">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Opsi 1: Unggah File CSV Server (.csv)</label>
                    <input type="file" name="csv_file" accept=".csv,text/csv" class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50">
                </div>

                <div class="text-center font-bold text-slate-400 text-[10px] uppercase tracking-wider">— ATAU —</div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Opsi 2: Tempelkan Teks CSV Langsung</label>
                    <textarea name="csv_text" rows="4" placeholder="Tempelkan baris teks CSV dari server di sini..." class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50 font-mono text-[11px]"></textarea>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="importServerModal = false" class="px-4 py-2 border rounded-xl font-bold text-slate-600">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold rounded-xl shadow-md cursor-pointer flex items-center gap-1.5">
                        <span>Proses Impor & Sinkronisasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
