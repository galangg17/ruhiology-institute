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
        <button @click="currentTab = 'categories'" :class="currentTab === 'categories' ? 'bg-[#0B2A43] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl transition">
            🏷️ Kategori Peserta ({{ $participantCategories->total() }})
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
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">{{ $reg->formatted_name }}</td>
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

    <!-- TAB CONTENT 5.5: KATEGORI & SUB-KATEGORI PESERTA -->
    <div x-show="currentTab === 'categories'" class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ editCatModal: false, editCatData: {}, addSubModal: false, addSubCatId: null, addSubCatName: '', editSubModal: false, editSubData: {} }">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="font-serif font-bold text-[#0B2A43] text-base">Kelola Kategori & Sub-Kategori Peserta (Hirarki 3 Tingkat)</h3>
                <p class="text-xs text-slate-500">Kelola Kategori Utama (L1) dan Sub-Kategori / Jenis (L2) untuk asesmen umum & event.</p>
            </div>
            <button @click="showAddModal = true" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5 cursor-pointer">
                <span>🏷️</span> <span>+ Tambah Kategori Baru (L1)</span>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Urutan</th>
                        <th class="px-6 py-3.5">Kategori Utama (L1)</th>
                        <th class="px-6 py-3.5">Sub-Kategori / Jenis (L2)</th>
                        <th class="px-6 py-3.5">Total Peserta</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($participantCategories as $cat)
                        <tr class="hover:bg-slate-50/80 align-top">
                            <td class="px-6 py-4 font-mono font-bold text-slate-500">#{{ $cat->order }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                                    <span class="text-base">{{ $cat->icon ?? '🏷️' }}</span> {{ $cat->name }}
                                </div>
                                <span class="font-mono text-[10px] text-slate-400 block mt-0.5">{{ $cat->code }}</span>
                                <p class="text-[11px] text-slate-500 mt-1 max-w-xs">{{ $cat->description ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5 max-w-md">
                                    @forelse($cat->subCategories as $sub)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-800 rounded-lg text-[11px] font-semibold group">
                                            <span>{{ $sub->name }}</span>
                                            <button @click="editSubData = {
                                                id: {{ $sub->id }},
                                                category_id: {{ $cat->id }},
                                                name: '{{ addslashes($sub->name) }}',
                                                detail_label: '{{ addslashes($sub->detail_label ?? '') }}',
                                                order: {{ $sub->order }},
                                                status: '{{ $sub->status }}'
                                            }; editSubModal = true" class="text-slate-400 hover:text-amber-600 transition" title="Edit Sub-Kategori Ini">✏️</button>
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 italic">Belum ada sub-kategori</span>
                                    @endforelse
                                </div>
                                <button @click="addSubCatId = {{ $cat->id }}; addSubCatName = '{{ addslashes($cat->name) }}'; addSubModal = true" class="mt-2 text-[10px] font-bold text-emerald-700 hover:text-emerald-900 underline inline-flex items-center gap-1 cursor-pointer">
                                    <span>+ Tambah Sub-Kategori L2</span>
                                </button>
                            </td>
                            <td class="px-6 py-4 font-bold text-[#0B2A43] whitespace-nowrap">{{ $cat->participants_count ?? 0 }} Peserta</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($cat->status === 'active')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold uppercase">🟢 Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase">⚪ Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="editCatData = {
                                        id: {{ $cat->id }},
                                        name: '{{ addslashes($cat->name) }}',
                                        icon: '{{ addslashes($cat->icon ?? '') }}',
                                        description: '{{ addslashes($cat->description ?? '') }}',
                                        order: {{ $cat->order }},
                                        status: '{{ $cat->status }}'
                                    }; editCatModal = true" class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold text-xs rounded-xl transition cursor-pointer">
                                        ✏️ Edit L1
                                    </button>

                                    <form action="{{ route('admin.master_data.participant_categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ addslashes($cat->name) }}\'?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition cursor-pointer">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-center">
            {{ $participantCategories->appends(['tab' => 'categories'])->links() }}
        </div>

        <!-- EDIT MODAL FOR PARTICIPANT CATEGORY L1 -->
        <div x-show="editCatModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
            <div @click.away="editCatModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100" @click.stop>
                <button @click="editCatModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold">✕</button>

                <div class="mb-5 pb-3 border-b border-slate-100">
                    <span class="text-[10px] font-mono font-bold text-amber-600 uppercase tracking-widest block">EDIT KATEGORI UTAMA (L1)</span>
                    <h3 class="text-lg font-serif font-bold text-[#0B2A43]">Edit Data Kategori</h3>
                </div>

                <form :action="'/admin/master-data/participant-categories/' + editCatData.id" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Kategori Peserta *</label>
                        <input type="text" name="name" x-model="editCatData.name" required class="w-full p-3 rounded-xl border border-slate-300 font-bold text-slate-800">
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ikon (Emoji)</label>
                            <input type="text" name="icon" x-model="editCatData.icon" placeholder="🏫 / 🎓" class="w-full p-3 rounded-xl border border-slate-300 text-center font-bold">
                        </div>
                        <div class="col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Urutan *</label>
                            <input type="number" name="order" x-model="editCatData.order" required class="w-full p-3 rounded-xl border border-slate-300 font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                        <textarea name="description" x-model="editCatData.description" rows="2" class="w-full p-3 rounded-xl border border-slate-300 text-xs"></textarea>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Kategori *</label>
                        <select name="status" x-model="editCatData.status" required class="w-full p-3 rounded-xl border border-slate-300 font-semibold">
                            <option value="active">🟢 Aktif</option>
                            <option value="inactive">⚪ Non-Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl shadow cursor-pointer">
                        💾 Simpan Perubahan Kategori
                    </button>
                </form>
            </div>
        </div>

        <!-- ADD MODAL FOR SUB-CATEGORY L2 -->
        <div x-show="addSubModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
            <div @click.away="addSubModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100" @click.stop>
                <button @click="addSubModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold">✕</button>

                <div class="mb-5 pb-3 border-b border-slate-100">
                    <span class="text-[10px] font-mono font-bold text-emerald-600 uppercase tracking-widest block" x-text="'SUB-KATEGORI L2 FOR: ' + addSubCatName"></span>
                    <h3 class="text-lg font-serif font-bold text-[#0B2A43]">Tambah Sub-Kategori Baru</h3>
                </div>

                <form action="{{ route('admin.master_data.participant_sub_categories.store') }}" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="category_id" :value="addSubCatId">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sub-Kategori / Jenis *</label>
                        <input type="text" name="name" required placeholder="Contoh: POLRI / TNI / SMA / Kementerian" class="w-full p-3 rounded-xl border border-slate-300 font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Label Petunjuk Tingkat 3 (Detail Instansi/Tempat)</label>
                        <input type="text" name="detail_label" placeholder="Contoh: Nama Polda / Polres / Satuan Kerja" class="w-full p-3 rounded-xl border border-slate-300">
                        <p class="text-[10px] text-slate-400 mt-1">Petunjuk bagi peserta saat mengisi kolom nama tempat/satuan spesifik.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Urutan</label>
                            <input type="number" name="order" placeholder="1" class="w-full p-3 rounded-xl border border-slate-300 font-mono">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Status *</label>
                            <select name="status" required class="w-full p-3 rounded-xl border border-slate-300 font-semibold">
                                <option value="active">🟢 Aktif</option>
                                <option value="inactive">⚪ Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow cursor-pointer">
                        ➕ Simpan Sub-Kategori
                    </button>
                </form>
            </div>
        </div>

        <!-- EDIT MODAL FOR SUB-CATEGORY L2 -->
        <div x-show="editSubModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
            <div @click.away="editSubModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100" @click.stop>
                <button @click="editSubModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold">✕</button>

                <div class="mb-5 pb-3 border-b border-slate-100">
                    <span class="text-[10px] font-mono font-bold text-amber-600 uppercase tracking-widest block">EDIT SUB-KATEGORI L2</span>
                    <h3 class="text-lg font-serif font-bold text-[#0B2A43]">Edit Sub-Kategori</h3>
                </div>

                <form :action="'/admin/master-data/participant-sub-categories/' + editSubData.id" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="category_id" x-model="editSubData.category_id">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sub-Kategori *</label>
                        <input type="text" name="name" x-model="editSubData.name" required class="w-full p-3 rounded-xl border border-slate-300 font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Label Petunjuk Tingkat 3 (Detail Tempat)</label>
                        <input type="text" name="detail_label" x-model="editSubData.detail_label" class="w-full p-3 rounded-xl border border-slate-300">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Urutan *</label>
                            <input type="number" name="order" x-model="editSubData.order" required class="w-full p-3 rounded-xl border border-slate-300 font-mono">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Status *</label>
                            <select name="status" x-model="editSubData.status" required class="w-full p-3 rounded-xl border border-slate-300 font-semibold">
                                <option value="active">🟢 Aktif</option>
                                <option value="inactive">⚪ Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-3 bg-[#0B2A43] text-white font-bold rounded-xl shadow cursor-pointer">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>

                <form :action="'/admin/master-data/participant-sub-categories/' + editSubData.id" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sub-kategori ini?')" class="mt-2">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl text-xs border border-rose-200 cursor-pointer">
                        🗑️ Hapus Sub-Kategori Ini
                    </button>
                </form>
            </div>
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
                <h3 class="text-lg font-serif font-bold text-[#0B2A43]" x-text="currentTab === 'categories' ? 'Tambah Kategori & Sub-Kategori Peserta' : ('Tambah Data ' + currentTab.toUpperCase())"></h3>
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

            <template x-if="currentTab === 'categories'">
                <form action="{{ route('admin.master_data.participant_categories.store') }}" method="POST" class="space-y-3.5 text-xs" x-data="{ newSubCats: [{ name: '', detail_label: '' }] }">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Nama Kategori Utama (Level 1) *</label>
                        <input type="text" name="name" required placeholder="Contoh: APH / POLRI / ASN / GURU" class="w-full p-2.5 rounded-xl border border-slate-300 font-bold focus:ring-2 focus:ring-[#0B2A43] outline-none">
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ikon (Emoji)</label>
                            <input type="text" name="icon" placeholder="👔" class="w-full p-2.5 rounded-xl border border-slate-300 text-center font-bold">
                        </div>
                        <div class="col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Urutan (Opsional)</label>
                            <input type="number" name="order" placeholder="4" class="w-full p-2.5 rounded-xl border border-slate-300 font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat (Opsional)</label>
                        <textarea name="description" rows="2" placeholder="Jelaskan peruntukan kelompok peserta ini..." class="w-full p-2.5 rounded-xl border border-slate-300 text-xs"></textarea>
                    </div>

                    <!-- Dynamic Sub-Categories Input Section (Level 2) -->
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-1.5">
                            <label class="block font-bold text-[#0B2A43] text-xs">Tambah Sub-Kategori / Jenis (Level 2)</label>
                            <button type="button" @click="newSubCats.push({ name: '', detail_label: '' })" class="text-[10px] font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 px-2 py-0.5 rounded-lg flex items-center gap-1 cursor-pointer">
                                <span>+ Tambah Sub</span>
                            </button>
                        </div>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                            <template x-for="(sub, idx) in newSubCats" :key="idx">
                                <div class="grid grid-cols-12 gap-1.5 items-center bg-white p-2 rounded-xl border border-slate-200 shadow-2xs">
                                    <div class="col-span-6">
                                        <input type="text" :name="'sub_categories[' + idx + '][name]'" x-model="sub.name" placeholder="Nama Sub (misal: POLRI)" class="w-full p-1.5 text-xs rounded-lg border border-slate-300 font-semibold outline-none">
                                    </div>
                                    <div class="col-span-5">
                                        <input type="text" :name="'sub_categories[' + idx + '][detail_label]'" x-model="sub.detail_label" placeholder="Label L3 (misal: Nama Polda)" class="w-full p-1.5 text-xs rounded-lg border border-slate-300 outline-none">
                                    </div>
                                    <div class="col-span-1 text-center">
                                        <button type="button" @click="if (newSubCats.length > 1) newSubCats.splice(idx, 1)" class="text-rose-500 hover:text-rose-700 text-xs font-bold" title="Hapus Sub">✕</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Kategori *</label>
                        <select name="status" required class="w-full p-2.5 rounded-xl border border-slate-300 font-semibold">
                            <option value="active">🟢 Aktif</option>
                            <option value="inactive">⚪ Non-Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#0B2A43] text-white font-bold rounded-xl shadow-md cursor-pointer hover:bg-[#123B59] transition">
                        ✨ Simpan Kategori & Sub-Kategori Peserta
                    </button>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
