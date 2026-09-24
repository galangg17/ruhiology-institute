@extends('layouts.admin')

@section('content')
<div x-data="{ currentTab: '{{ $tab }}', showAddModal: false }" class="space-y-6">

    <!-- PAGE HEADER BAR -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">MASTER DATA MANAGEMENT</span>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-[#0B2A43]">Kelola Data Wilayah & Akademik</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data master Provinsi, Kabupaten/Kota, Sekolah, Kampus, Sektor Pekerjaan, serta Import Batch CSV.</p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <button @click="showAddModal = true" class="px-5 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow transition cursor-pointer flex items-center gap-1.5">
                <span>+</span> <span>Tambah Master Data</span>
            </button>
            <button @click="currentTab = 'importer'" class="px-5 py-2.5 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-bold text-xs rounded-xl shadow transition cursor-pointer flex items-center gap-1.5">
                <span>📥</span> <span>Batch CSV Importer</span>
            </button>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-900 text-xs font-bold rounded-2xl border border-emerald-200 shadow-sm flex items-center justify-between">
            <span>✨ {{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 text-rose-900 text-xs font-bold rounded-2xl border border-rose-200 shadow-sm flex items-center justify-between">
            <span>⚠️ {{ session('error') }}</span>
        </div>
    @endif

    <!-- NAVIGATION TABS PILL BAR -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap gap-1 text-xs font-bold">
        <button @click="currentTab = 'provinces'" :class="currentTab === 'provinces' ? 'bg-[#0B2A43] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition">
            Provinsi ({{ $provinces->total() }})
        </button>
        <button @click="currentTab = 'regencies'" :class="currentTab === 'regencies' ? 'bg-[#0B2A43] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition">
            Kabupaten/Kota ({{ $regencies->total() }})
        </button>
        <button @click="currentTab = 'schools'" :class="currentTab === 'schools' ? 'bg-[#0B2A43] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition">
            Sekolah ({{ $schools->total() }})
        </button>
        <button @click="currentTab = 'universities'" :class="currentTab === 'universities' ? 'bg-[#0B2A43] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition">
            Perguruan Tinggi ({{ $universities->total() }})
        </button>
        <button @click="currentTab = 'occupations'" :class="currentTab === 'occupations' ? 'bg-[#0B2A43] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition">
            Pekerjaan ({{ $occupations->total() }})
        </button>
        <button @click="currentTab = 'pending'" :class="currentTab === 'pending' ? 'bg-amber-600 text-white shadow-md' : 'text-amber-900 hover:bg-amber-50'" class="px-4 py-2.5 rounded-xl transition">
            Usulan Baru ({{ $pendingInstitutions->total() }})
        </button>
        <button @click="currentTab = 'importer'" :class="currentTab === 'importer' ? 'bg-emerald-700 text-white shadow-md' : 'text-emerald-900 hover:bg-emerald-50'" class="px-4 py-2.5 rounded-xl transition">
            📂 Import CSV
        </button>
    </div>

    <!-- TAB CONTENT 1: PROVINSI -->
    <div x-show="currentTab === 'provinces'" class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-serif font-bold text-[#0B2A43] text-base">Daftar Provinsi Master</h3>
            <span class="text-xs text-slate-400 font-mono">Total: {{ $provinces->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Kode</th>
                        <th class="px-6 py-3.5">Nama Provinsi</th>
                        <th class="px-6 py-3.5">Cakupan Wilayah & Institusi</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($provinces as $prov)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-mono font-bold text-[#0B2A43]">{{ $prov->code }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $prov->name }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-700 font-semibold">{{ $prov->regencies_count }} Kab/Kota</span>
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-700 font-semibold ml-1">{{ $prov->schools_count }} Sekolah</span>
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-700 font-semibold ml-1">{{ $prov->universities_count }} Kampus</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold uppercase">Aktif</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-center">
            {{ $provinces->appends(['tab' => 'provinces'])->links() }}
        </div>
    </div>

    <!-- TAB CONTENT 2: KABUPATEN / KOTA -->
    <div x-show="currentTab === 'regencies'" class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-serif font-bold text-[#0B2A43] text-base">Daftar Kabupaten/Kota Master</h3>
            <span class="text-xs text-slate-400 font-mono">Total: {{ $regencies->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Kode</th>
                        <th class="px-6 py-3.5">Jenis & Nama Kabupaten/Kota</th>
                        <th class="px-6 py-3.5">Provinsi</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($regencies as $reg)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-mono font-bold text-[#0B2A43]">{{ $reg->code }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $reg->type }} {{ $reg->name }}</td>
                            <td class="px-6 py-4 text-slate-600 font-semibold">{{ $reg->province->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold uppercase">Aktif</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-center">
            {{ $regencies->appends(['tab' => 'regencies'])->links() }}
        </div>
    </div>

    <!-- TAB CONTENT 3: SEKOLAH -->
    <div x-show="currentTab === 'schools'" class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-serif font-bold text-[#0B2A43] text-base">Daftar Sekolah Master</h3>
            <span class="text-xs text-slate-400 font-mono">Total: {{ $schools->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Jenjang</th>
                        <th class="px-6 py-3.5">Nama Sekolah</th>
                        <th class="px-6 py-3.5">Kabupaten/Kota & Provinsi</th>
                        <th class="px-6 py-3.5">NPSN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($schools as $sch)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-bold text-amber-700">
                                <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 rounded-lg text-amber-900 font-bold">{{ $sch->level }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-[#0B2A43] text-sm">{{ $sch->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $sch->regency->name ?? '-' }}, {{ $sch->province->name ?? '-' }}</td>
                            <td class="px-6 py-4 font-mono text-slate-500">{{ $sch->npsn ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-center">
            {{ $schools->appends(['tab' => 'schools'])->links() }}
        </div>
    </div>

    <!-- TAB CONTENT 4: PERGURUAN TINGGI -->
    <div x-show="currentTab === 'universities'" class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-serif font-bold text-[#0B2A43] text-base">Daftar Perguruan Tinggi Master</h3>
            <span class="text-xs text-slate-400 font-mono">Total: {{ $universities->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Nama Kampus</th>
                        <th class="px-6 py-3.5">Lokasi Wilayah</th>
                        <th class="px-6 py-3.5">Fakultas & Prodi</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($universities as $uni)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 font-bold text-[#0B2A43] text-sm">{{ $uni->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $uni->regency->name ?? '-' }}, {{ $uni->province->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600 font-semibold">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-900 border border-blue-200 rounded-lg">{{ $uni->faculties_count }} Fakultas</span>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-900 border border-blue-200 rounded-lg ml-1">{{ $uni->study_programs_count }} Prodi</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold uppercase">Aktif</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-center">
            {{ $universities->appends(['tab' => 'universities'])->links() }}
        </div>
    </div>

    <!-- TAB CONTENT 5: PEKERJAAN -->
    <div x-show="currentTab === 'occupations'" class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden max-w-3xl">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-serif font-bold text-[#0B2A43] text-base">Daftar Sektor Pekerjaan / Karir</h3>
            <span class="text-xs text-slate-400 font-mono">Total: {{ $occupations->total() }}</span>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3.5">ID</th>
                    <th class="px-6 py-3.5">Nama Sektor / Karir</th>
                    <th class="px-6 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($occupations as $occ)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-mono text-slate-400">#{{ $occ->id }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $occ->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold uppercase">Aktif</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100 flex justify-center">
            {{ $occupations->appends(['tab' => 'occupations'])->links() }}
        </div>
    </div>

    <!-- TAB CONTENT 6: USULAN BARU (PENDING VERIFICATION) -->
    <div x-show="currentTab === 'pending'" class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="font-serif font-bold text-amber-900 text-base">Verifikasi Usulan Institusi Baru</h3>
                <p class="text-xs text-slate-500">Usulan institusi dari peserta saat nama sekolah/kampus belum terdaftar di database master.</p>
            </div>
            <span class="px-3 py-1 bg-amber-100 text-amber-900 text-xs font-bold rounded-full border border-amber-300">Pending Review</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal Usulan</th>
                        <th class="px-6 py-3.5">Nama Usulan Institusi</th>
                        <th class="px-6 py-3.5">Kategori</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Aksi Verifikasi Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($pendingInstitutions as $pInst)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 text-slate-500 font-mono">{{ $pInst->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-bold text-[#0B2A43] text-sm">{{ $pInst->name }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $pInst->category }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $pInst->status === 'verified' ? 'bg-emerald-100 text-emerald-800' : ($pInst->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $pInst->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($pInst->status === 'pending')
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.master_data.pending.update', $pInst->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="verified">
                                            <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-bold shadow cursor-pointer">
                                                ✓ Verifikasi
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.master_data.pending.update', $pInst->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-bold shadow cursor-pointer">
                                                ✕ Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium">Telah Diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 font-medium">Belum ada usulan institusi baru yang perlu diverifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-center">
            {{ $pendingInstitutions->appends(['tab' => 'pending'])->links() }}
        </div>
    </div>

    <!-- TAB CONTENT 7: BATCH CSV IMPORTER -->
    <div x-show="currentTab === 'importer'" class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm max-w-2xl mx-auto space-y-6">
        <div>
            <span class="text-[10px] font-mono font-bold text-emerald-700 uppercase tracking-widest block">MASSIVE MASTER DATA IMPORTER</span>
            <h3 class="text-xl font-serif font-bold text-[#0B2A43]">Unggah Batch Master Data via File CSV</h3>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Sistem mendukung pengunggahan data masal resmi untuk Provinsi, Kabupaten/Kota, Sekolah, Perguruan Tinggi, dan Pekerjaan menggunakan format CSV terstruktur.
            </p>
        </div>

        <form action="{{ route('admin.master_data.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-sans">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Target Entitas Master Data *</label>
                <select name="type" required class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-semibold bg-slate-50">
                    <option value="provinces">Provinsi (header: code, name, country_id)</option>
                    <option value="regencies">Kabupaten/Kota (header: code, province_id, name, type)</option>
                    <option value="schools">Sekolah (header: province_id, regency_id, level, name, npsn)</option>
                    <option value="universities">Perguruan Tinggi (header: province_id, regency_id, name, code)</option>
                    <option value="occupations">Pekerjaan (header: name)</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih File CSV (.csv) *</label>
                <input type="file" name="csv_file" accept=".csv,.txt" required class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                <p class="text-[11px] text-slate-400 mt-1">Pastikan baris pertama berisi nama kolom (*header*). Format enkoding UTF-8.</p>
            </div>

            <div class="pt-2">
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memulai proses import data masal CSV?')" class="w-full py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg transition-colors cursor-pointer">
                    🚀 Mulai Proses Batch Import CSV →
                </button>
            </div>
        </form>
    </div>

    <!-- MODAL POPUP DIALOG FOR ADDING NEW MASTER RECORD -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
        <div @click.away="showAddModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100" @click.stop>
            <button @click="showAddModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold">✕</button>

            <div class="mb-5 pb-3 border-b border-slate-100">
                <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">TAMBAH REKORD MASTER</span>
                <h3 class="text-lg font-serif font-bold text-[#0B2A43]" x-text="'Tambah Data ' + currentTab.toUpperCase()"></h3>
            </div>

            <!-- Form Dynamic based on currentTab -->
            <template x-if="currentTab === 'provinces'">
                <form action="{{ route('admin.master_data.provinces.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Provinsi *</label>
                        <input type="text" name="code" required placeholder="Contoh: 15" class="w-full p-3 rounded-xl border border-slate-300 font-mono">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Provinsi *</label>
                        <input type="text" name="name" required placeholder="Contoh: Jambi" class="w-full p-3 rounded-xl border border-slate-300">
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#0B2A43] text-white font-bold rounded-xl shadow">Simpan Provinsi</button>
                </form>
            </template>

            <template x-if="currentTab === 'regencies'">
                <form action="{{ route('admin.master_data.regencies.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provinsi *</label>
                        <select name="province_id" required class="w-full p-3 rounded-xl border border-slate-300">
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis *</label>
                        <select name="type" required class="w-full p-3 rounded-xl border border-slate-300">
                            <option value="Kabupaten">Kabupaten</option>
                            <option value="Kota">Kota</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode *</label>
                        <input type="text" name="code" required placeholder="Contoh: 1505" class="w-full p-3 rounded-xl border border-slate-300 font-mono">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Kabupaten/Kota *</label>
                        <input type="text" name="name" required placeholder="Contoh: Muaro Jambi" class="w-full p-3 rounded-xl border border-slate-300">
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#0B2A43] text-white font-bold rounded-xl shadow">Simpan Kab/Kota</button>
                </form>
            </template>

            <template x-if="currentTab === 'schools'">
                <form action="{{ route('admin.master_data.schools.store') }}" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sekolah *</label>
                        <input type="text" name="name" required placeholder="Contoh: SMA Negeri 1 Jambi" class="w-full p-3 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenjang (Opsional)</label>
                        <select name="level" class="w-full p-3 rounded-xl border border-slate-300">
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                            <option value="SMP">SMP</option>
                            <option value="SD">SD</option>
                            <option value="Sederajat">Sederajat</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#0B2A43] text-white font-bold rounded-xl shadow cursor-pointer">Simpan Sekolah</button>
                </form>
            </template>

            <template x-if="currentTab === 'universities'">
                <form action="{{ route('admin.master_data.universities.store') }}" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Perguruan Tinggi / Kampus *</label>
                        <input type="text" name="name" required placeholder="Contoh: UIN Sulthan Thaha Saifuddin Jambi" class="w-full p-3 rounded-xl border border-slate-300">
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#0B2A43] text-white font-bold rounded-xl shadow cursor-pointer">Simpan Kampus</button>
                </form>
            </template>

            <template x-if="currentTab === 'occupations'">
                <form action="{{ route('admin.master_data.occupations.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sektor Pekerjaan *</label>
                        <input type="text" name="name" required placeholder="Contoh: Guru / Pendidik" class="w-full p-3 rounded-xl border border-slate-300">
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#0B2A43] text-white font-bold rounded-xl shadow">Simpan Pekerjaan</button>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
