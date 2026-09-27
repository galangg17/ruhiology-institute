@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ 
    createModal: false, 
    importModal: false,
    editModal: false,
    drawerOpen: false,
    drawerTab: 'profile',
    selectedParticipant: null,
    editData: {
        id: '',
        participant_code: '',
        name: '',
        email: '',
        phone: '',
        batch: '',
        gender: 'Laki-laki',
        institution_id: '',
        program_id: '',
        province_id: '',
        school_custom: '',
        university_custom: '',
        status: 'active'
    },
    openEdit(part) {
        this.editData = {
            id: part.id,
            participant_code: part.participant_code,
            name: part.name,
            email: part.email,
            phone: part.phone || '',
            batch: part.batch || 'Angkatan 2026',
            gender: part.gender || 'Laki-laki',
            institution_id: part.institution_id || '',
            program_id: part.program_id || '',
            province_id: part.province_id || '',
            school_custom: part.school_custom || '',
            university_custom: part.university_custom || '',
            status: part.status || 'active'
        };
        this.editModal = true;
    },
    openDrawer(part, tab = 'profile') {
        this.selectedParticipant = part;
        this.drawerTab = tab;
        this.drawerOpen = true;
    }
}">

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

    <!-- Page Title & Top Actions -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold font-serif text-slate-900 tracking-tight">Manajemen Data Peserta & Demografi Terpadu</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola registrasi peserta, demografi wilayah 38 provinsi, riwayat asesmen RQI & WHO-5, serta impor/ekspor data.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.participants.export', request()->all()) }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                <span>📊</span> <span>Export CSV</span>
            </a>
            <button @click="importModal = true" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                <span>📥</span> <span>Import Excel / CSV</span>
            </button>
            <button @click="createModal = true" class="px-4 py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-md border border-[#C9A24D]/30 transition flex items-center gap-1.5 cursor-pointer">
                <span>+</span> <span>Tambah Peserta Baru</span>
            </button>
        </div>
    </div>

    <!-- Sub-Tab Switcher Bar (Semua vs Peserta Mandiri/Publik vs Peserta Event/Institusi) -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-1">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.participants.index', array_merge(request()->except('access_type'), ['access_type' => ''])) }}" 
               class="px-4 py-2 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ empty(request('access_type')) ? 'bg-[#0B2A43] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>👥 Semua Peserta</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ empty(request('access_type')) ? 'bg-amber-400 text-slate-950 font-black' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($stats['total_participants'] ?? 0) }}
                </span>
            </a>

            <a href="{{ route('admin.participants.index', array_merge(request()->except('access_type'), ['access_type' => 'public'])) }}" 
               class="px-4 py-2 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ request('access_type') === 'public' ? 'bg-[#0B2A43] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>🌐 Peserta Mandiri (Publik)</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ request('access_type') === 'public' ? 'bg-emerald-400 text-slate-950 font-black' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($stats['public_participants'] ?? 0) }}
                </span>
            </a>

            <a href="{{ route('admin.participants.index', array_merge(request()->except('access_type'), ['access_type' => 'event_only'])) }}" 
               class="px-4 py-2 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ request('access_type') === 'event_only' ? 'bg-[#0B2A43] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>🔒 Peserta Event / Institusi</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ request('access_type') === 'event_only' ? 'bg-amber-400 text-slate-950 font-black' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($stats['event_participants'] ?? 0) }}
                </span>
            </a>
        </div>

        <span class="hidden md:inline-block text-[11px] font-mono text-slate-400">
            Klik baris peserta untuk membuka Drawer Detail (Opsi A)
        </span>
    </div>

    <!-- Top Statistics Metrics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 text-xs font-sans">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-mono text-[10px] uppercase tracking-wider font-bold">
                <span>TOTAL PESERTA</span>
                <span>👥</span>
            </div>
            <div class="text-2xl font-black font-mono text-[#0B2A43]">
                {{ number_format($stats['total_participants'] ?? 0) }}
            </div>
            <span class="text-[10px] text-slate-500 font-medium block">Terdaftar dalam Database</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-mono text-[10px] uppercase tracking-wider font-bold">
                <span>PESERTA AKTIF</span>
                <span>✅</span>
            </div>
            <div class="text-2xl font-black font-mono text-emerald-600">
                {{ number_format($stats['active_participants'] ?? 0) }}
            </div>
            <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-bold inline-block">Status Active</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-mono text-[10px] uppercase tracking-wider font-bold">
                <span>CAKUPAN WILAYAH</span>
                <span>📍</span>
            </div>
            <div class="text-2xl font-black font-mono text-amber-600">
                {{ number_format($stats['total_provinces'] ?? 0) }} <span class="text-xs text-slate-400 font-normal">Provinsi</span>
            </div>
            <span class="text-[10px] text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full font-bold inline-block">Demografi Indonesia</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-mono text-[10px] uppercase tracking-wider font-bold">
                <span>TOTAL SESI TEST</span>
                <span>📝</span>
            </div>
            <div class="text-2xl font-black font-mono text-sky-700">
                {{ number_format($stats['total_submissions'] ?? 0) }}
            </div>
            <span class="text-[10px] text-sky-800 bg-sky-50 px-2 py-0.5 rounded-full font-bold inline-block">Pretest & Posttest</span>
        </div>
    </div>

    <!-- Advanced Filter & Multi-Criteria Search Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3 font-sans text-xs">
        <form action="{{ route('admin.participants.index') }}" method="GET" class="space-y-3">
            @if(request('access_type'))
                <input type="hidden" name="access_type" value="{{ request('access_type') }}">
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Cari Kata Kunci (Nama / Kode / Email / HP / Sekolah)</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama peserta, kode unik, email, sekolah..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#0B2A43] focus:border-transparent">
                        <span class="absolute left-3 top-2.5 text-slate-400">🔍</span>
                    </div>
                </div>

                <!-- Institution Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Filter Institusi Mitra</label>
                    <select name="institution_id" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Institusi Mitra</option>
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}" {{ request('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Program Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Filter Program Asesmen</label>
                    <select name="program_id" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Program</option>
                        @foreach($programs as $p)
                            <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Secondary Filters row -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1">
                <div>
                    <select name="province_id" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Provinsi (Demografi)</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}" {{ request('province_id') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="gender" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <select name="status" class="w-full p-2 rounded-xl border border-slate-300 text-xs bg-white">
                        <option value="">Semua Status (Active/Inactive)</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl transition shadow-sm cursor-pointer">
                        Filter Data
                    </button>
                    @if(request()->anyFilled(['q', 'institution_id', 'program_id', 'province_id', 'gender', 'status']))
                        <a href="{{ route('admin.participants.index', request()->only('access_type')) }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden font-sans">
        
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs font-mono font-bold text-slate-600">
            <span>DAFTAR PESERTA TERKINI ({{ $participants->total() }} Peserta Ditemukan)</span>
            <span class="text-[11px] text-slate-400 font-normal">Halaman {{ $participants->currentPage() }} dari {{ $participants->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[1050px]">
                <thead class="bg-slate-100 text-slate-700 uppercase font-bold border-b border-slate-200 text-[11px] tracking-wider font-mono">
                    <tr>
                        <th class="px-4 py-3.5 whitespace-nowrap">Profil & Kode Peserta</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Akses Pendaftaran</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Kontak Peserta</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Demografi Wilayah</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Sekolah / Kampus</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Institusi & Program</th>
                        <th class="px-4 py-3.5 whitespace-nowrap text-center">Riwayat Test</th>
                        <th class="px-4 py-3.5 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($participants as $part)
                        <tr class="hover:bg-amber-50/50 transition cursor-pointer" @click="openDrawer({{ json_encode($part) }}, 'profile')">
                            
                            <!-- Participant Info & Avatar -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#0B2A43] text-[#C9A24D] font-bold text-xs flex items-center justify-center font-mono shrink-0 shadow-2xs border border-[#C9A24D]/30">
                                        {{ strtoupper(substr($part->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <button type="button" class="font-bold text-slate-900 hover:text-[#0B2A43] hover:underline text-xs text-left block leading-snug cursor-pointer">
                                            {{ $part->name }}
                                        </button>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="font-mono text-[11px] text-amber-800 font-bold bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200">
                                                {{ $part->participant_code }}
                                            </span>
                                            @if($part->assessment_code)
                                                <span class="font-mono text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded">
                                                    {{ $part->assessment_code }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Access Type Badge -->
                            <td class="p-4" @click.stop>
                                @if($part->access_type === 'event_only' || $part->event_id)
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-900 text-[10px] font-bold rounded-full border border-amber-300 inline-flex items-center gap-1">
                                        🔒 Event Khusus
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-900 text-[10px] font-bold rounded-full border border-emerald-300 inline-flex items-center gap-1">
                                        🌐 Mandiri (Publik)
                                    </span>
                                @endif
                            </td>

                            <!-- Contact -->
                            <td class="p-4">
                                <span class="font-medium text-slate-800 block text-xs">{{ $part->email }}</span>
                                <span class="text-slate-400 text-[11px] font-mono block mt-0.5">{{ $part->phone ?: '-' }}</span>
                            </td>

                            <!-- Demographics / Region -->
                            <td class="p-4">
                                <span class="font-bold text-slate-800 text-xs block">
                                    📍 {{ $part->province?->name ?: 'Tidak diisi' }}
                                </span>
                                <span class="text-slate-500 text-[11px] block font-normal">
                                    {{ $part->regency?->name ?: '-' }}
                                </span>
                            </td>

                            <!-- School / University Custom -->
                            <td class="p-4">
                                @php
                                    $schoolOrUni = $part->school_custom ?: ($part->university_custom ?: ($part->school?->name ?: ($part->university?->name ?: '-')));
                                @endphp
                                <span class="font-bold text-slate-800 text-xs block line-clamp-1" title="{{ $schoolOrUni }}">
                                    🏛️ {{ $schoolOrUni }}
                                </span>
                                <span class="text-slate-400 text-[10px] block font-mono">
                                    {{ $part->gender ?: 'Gender -' }}
                                </span>
                            </td>

                            <!-- Institution & Program -->
                            <td class="p-4">
                                <span class="font-bold text-slate-800 text-xs block">{{ $part->institution?->name ?: '-' }}</span>
                                <span class="text-slate-500 text-[10px] block font-mono mt-0.5">{{ $part->program?->name ?: '-' }} · {{ $part->batch }}</span>
                            </td>

                            <!-- Test Submissions History -->
                            <td class="p-4 text-center" @click.stop>
                                <button type="button" @click="openDrawer({{ json_encode($part) }}, 'submissions')" class="px-2.5 py-1 bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold text-xs rounded-full border border-amber-300 transition inline-flex items-center gap-1 cursor-pointer">
                                    <span>📝</span>
                                    <span>{{ $part->submissions_count }} Sesi</span>
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right" @click.stop>
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" @click="openDrawer({{ json_encode($part) }}, 'profile')" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition cursor-pointer" title="Buka Slide Drawer Detail & Tes">
                                        👁️ Drawer
                                    </button>
                                    <button type="button" @click="openEdit({{ json_encode($part) }})" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-lg text-xs font-bold transition cursor-pointer" title="Edit Peserta">
                                        ✏️ Edit
                                    </button>
                                    <form action="{{ route('admin.participants.destroy', $part->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus peserta ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition cursor-pointer" title="Hapus Peserta">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 font-sans">
                                <span>🔍 Tidak ada data peserta yang memenuhi kriteria pencarian / filter.</span>
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

    <!-- OPSI A: SLIDE-OVER DRAWER (RIGHT PANEL FOR UNIFIED PARTICIPANT & EVALUATION) -->
    <div x-show="drawerOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden">
        <!-- Backdrop -->
        <div x-show="drawerOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="drawerOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="drawerOpen" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-xl bg-white shadow-2xl border-l border-slate-200 flex flex-col justify-between">
                
                <!-- Drawer Header -->
                <div class="p-5 bg-[#0B2A43] text-white space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 text-[#C9A24D] font-bold text-base flex items-center justify-center font-mono border border-white/20">
                                <span x-text="selectedParticipant ? selectedParticipant.name.substring(0, 2).toUpperCase() : 'PS'"></span>
                            </div>
                            <div>
                                <h3 class="font-bold font-serif text-white text-lg leading-snug" x-text="selectedParticipant?.name"></h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-xs text-[#C9A24D] font-bold bg-white/10 px-2 py-0.5 rounded border border-[#C9A24D]/30" x-text="selectedParticipant?.participant_code"></span>
                                    <span class="text-xs text-slate-300 font-mono" x-text="selectedParticipant?.email"></span>
                                </div>
                            </div>
                        </div>
                        <button @click="drawerOpen = false" class="text-slate-300 hover:text-white text-xl font-bold p-1 cursor-pointer">✕</button>
                    </div>

                    <!-- Drawer Sub-Tab Navigation Switcher -->
                    <div class="flex items-center gap-2 pt-2 border-t border-white/15">
                        <button type="button" @click="drawerTab = 'profile'" :class="drawerTab === 'profile' ? 'bg-[#C9A24D] text-[#0B2A43] font-black' : 'bg-white/10 text-white hover:bg-white/20 font-bold'" class="px-4 py-1.5 rounded-lg text-xs transition cursor-pointer">
                            👤 Biodata & Demografi
                        </button>
                        <button type="button" @click="drawerTab = 'submissions'" :class="drawerTab === 'submissions' ? 'bg-[#C9A24D] text-[#0B2A43] font-black' : 'bg-white/10 text-white hover:bg-white/20 font-bold'" class="px-4 py-1.5 rounded-lg text-xs transition cursor-pointer flex items-center gap-1.5">
                            <span>📝 Sesi & Hasil Tes</span>
                            <span class="px-1.5 py-0.2 rounded-full bg-white/20 text-[10px]" x-text="selectedParticipant?.submissions ? selectedParticipant.submissions.length : 0"></span>
                        </button>
                    </div>
                </div>

                <!-- Drawer Main Body -->
                <div class="p-6 overflow-y-auto space-y-5 flex-1 font-sans text-xs">
                    
                    <!-- DRAWER TAB 1: BIODATA & DEMOGRAFI -->
                    <div x-show="drawerTab === 'profile'" class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <h4 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-200 pb-2 flex items-center gap-2">
                                <span>📌</span> <span>Identitas Utama Peserta</span>
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
                                    <strong class="uppercase font-bold" :class="selectedParticipant?.status === 'active' ? 'text-emerald-600' : 'text-rose-600'" x-text="selectedParticipant?.status"></strong>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <h4 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-200 pb-2 flex items-center gap-2">
                                <span>📍</span> <span>Demografi & Asal Instansi</span>
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
                                    <span class="text-slate-500">Sekolah / Kampus</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.school_custom || selectedParticipant?.university_custom || selectedParticipant?.school?.name || selectedParticipant?.university?.name || '-'"></strong>
                                </div>
                                <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                    <span class="text-slate-500">Institusi Mitra</span>
                                    <strong class="text-slate-800 font-bold" x-text="selectedParticipant?.institution?.name || '-'"></strong>
                                </div>
                                <div class="flex justify-between items-center py-1">
                                    <span class="text-slate-500">Program & Batch</span>
                                    <strong class="text-slate-800 font-bold" x-text="(selectedParticipant?.program?.name || '-') + ' (' + (selectedParticipant?.batch || '2026') + ')'"></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DRAWER TAB 2: SESI & HASIL EVALUASI TES -->
                    <div x-show="drawerTab === 'submissions'" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold font-serif text-slate-900 text-sm flex items-center gap-2">
                                <span>📜</span> <span>Riwayat Sesi Tes & Evaluasi RQI</span>
                            </h4>
                            <span class="text-xs font-mono font-bold bg-amber-100 text-amber-900 px-2.5 py-0.5 rounded-full" x-text="(selectedParticipant?.submissions ? selectedParticipant.submissions.length : 0) + ' Sesi Mengerjakan'"></span>
                        </div>

                        <template x-if="selectedParticipant && selectedParticipant.submissions && selectedParticipant.submissions.length > 0">
                            <div class="space-y-3">
                                <template x-for="sub in selectedParticipant.submissions" :key="sub.id">
                                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-3">
                                        <div class="flex justify-between items-start border-b border-slate-100 pb-2">
                                            <div>
                                                <strong class="text-slate-900 font-bold text-xs block" x-text="sub.instrument ? sub.instrument.title : 'Paket Soal'"></strong>
                                                <span class="text-[10px] text-slate-400 font-mono" x-text="sub.created_at ? new Date(sub.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-'"></span>
                                            </div>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider" :class="sub.type === 'pretest' ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800'" x-text="sub.type || 'evaluasi'"></span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 text-center bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                            <div>
                                                <span class="text-[10px] text-slate-400 font-mono block">SKOR TOTAL RQI</span>
                                                <span class="text-lg font-black font-mono text-amber-800" x-text="sub.total_score || '0.0'"></span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] text-slate-400 font-mono block">KATEGORI RUHIOLOGI</span>
                                                <span class="text-xs font-bold text-emerald-700 block" x-text="sub.rq_category || 'Terhitung'"></span>
                                            </div>
                                        </div>

                                        <div class="pt-1 flex justify-end gap-2">
                                            <a :href="'{{ url('assessment/result') }}/' + sub.submission_code" target="_blank" class="px-3 py-1.5 bg-[#0B2A43] text-white font-bold text-xs rounded-xl shadow-xs hover:bg-[#123B59]">
                                                Lihat Laporan Hasil ➔
                                            </a>
                                            <a :href="'{{ url('assessment/result') }}/' + sub.submission_code + '/certificate'" target="_blank" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs">
                                                📜 Sertifikat
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="!selectedParticipant || !selectedParticipant.submissions || selectedParticipant.submissions.length === 0">
                            <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                                <span class="text-2xl block">📝</span>
                                <strong class="text-slate-700 font-bold text-xs block">Belum Ada Sesi Pengerjaan Tes</strong>
                                <p class="text-[11px] text-slate-500">Peserta ini belum mengerjakan sesi asesmen RQI atau WHO-5 Index.</p>
                            </div>
                        </template>
                    </div>

                </div>

                <!-- Drawer Footer Actions -->
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                    <button type="button" @click="openEdit(selectedParticipant); drawerOpen = false;" class="px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold text-xs rounded-xl transition cursor-pointer">
                        ✏️ Edit Data Peserta Ini
                    </button>
                    <button type="button" @click="drawerOpen = false" class="px-5 py-2 bg-slate-800 text-white font-bold text-xs rounded-xl shadow cursor-pointer">
                        Tutup Drawer
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL 1: CREATE NEW PARTICIPANT -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-lg flex items-center gap-2">
                    <span>👤</span> <span>Registrasi Peserta Baru</span>
                </h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.participants.store') }}" method="POST" class="space-y-3.5 text-xs font-sans">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Institusi Mitra *</label>
                        <select name="institution_id" required class="w-full p-2.5 rounded-xl border border-slate-300">
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Program Asesmen *</label>
                        <select name="program_id" required class="w-full p-2.5 rounded-xl border border-slate-300">
                            @foreach($programs as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Peserta (Unique Code) *</label>
                        <input type="text" name="participant_code" required value="PST-{{ date('Ym') }}-{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}" class="w-full p-2.5 rounded-xl border border-slate-300 font-mono font-bold text-amber-800">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Peserta *</label>
                        <input type="text" name="name" required placeholder="Nama lengkap beserta gelar..." class="w-full p-2.5 rounded-xl border border-slate-300">
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
                        <label class="block font-bold text-slate-700 mb-1">Provinsi (Demografi)</label>
                        <select name="province_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sekolah / SMA (Opsional)</label>
                        <input type="text" name="school_custom" placeholder="Contoh: SMAN 1 Jambi" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Universitas / Kampus (Opsional)</label>
                        <input type="text" name="university_custom" placeholder="Contoh: UIN STS Jambi" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Angkatan / Batch *</label>
                        <input type="text" name="batch" value="Angkatan {{ date('Y') }}" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Keaktifan *</label>
                        <select name="status" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white font-bold">
                            <option value="active">Active (Aktif)</option>
                            <option value="inactive">Inactive (Non-aktif)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded-xl font-bold text-slate-600">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow-md cursor-pointer">Simpan & Daftarkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT PARTICIPANT -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-lg flex items-center gap-2">
                    <span>✏️</span> <span>Edit Data Peserta</span>
                </h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form :action="'{{ url('admin/participants') }}/' + editData.id" method="POST" class="space-y-3.5 text-xs font-sans">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Peserta *</label>
                        <input type="text" name="participant_code" x-model="editData.participant_code" required class="w-full p-2.5 rounded-xl border border-slate-300 font-mono font-bold text-amber-800">
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
                        <label class="block font-bold text-slate-700 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" x-model="editData.phone" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Institusi Mitra *</label>
                        <select name="institution_id" x-model="editData.institution_id" required class="w-full p-2.5 rounded-xl border border-slate-300">
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Program Asesmen *</label>
                        <select name="program_id" x-model="editData.program_id" required class="w-full p-2.5 rounded-xl border border-slate-300">
                            @foreach($programs as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provinsi (Demografi)</label>
                        <select name="province_id" x-model="editData.province_id" class="w-full p-2.5 rounded-xl border border-slate-300">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" x-model="editData.gender" class="w-full p-2.5 rounded-xl border border-slate-300">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sekolah (Custom)</label>
                        <input type="text" name="school_custom" x-model="editData.school_custom" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Universitas (Custom)</label>
                        <input type="text" name="university_custom" x-model="editData.university_custom" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Angkatan / Batch *</label>
                        <input type="text" name="batch" x-model="editData.batch" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Keaktifan *</label>
                        <select name="status" x-model="editData.status" class="w-full p-2.5 rounded-xl border border-slate-300 font-bold">
                            <option value="active">Active (Aktif)</option>
                            <option value="inactive">Inactive (Non-aktif)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border rounded-xl font-bold text-slate-600">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow-md cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: BATCH IMPORT CSV -->
    <div x-show="importModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base flex items-center gap-2">
                    <span>📥</span> <span>Import Data Peserta Massal (CSV)</span>
                </h3>
                <button @click="importModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 space-y-1">
                <strong class="font-bold block">💡 Petunjuk Format CSV:</strong>
                <p class="text-[11px] leading-relaxed">
                    Unggah file CSV dengan kolom header: <code>participant_code, name, email, phone, gender, batch, institution_id, program_id, school_custom, university_custom, status</code>.
                </p>
                <div class="pt-1">
                    <a href="{{ route('admin.participants.template.download') }}" class="text-amber-900 font-bold underline hover:text-amber-700 text-[11px]">
                        📥 Unduh Template Contoh CSV Peserta (.csv)
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
                    <button type="submit" class="px-6 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow-md cursor-pointer">Mulai Import Data</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
