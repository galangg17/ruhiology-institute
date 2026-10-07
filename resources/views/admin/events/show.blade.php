@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="adminEventsManager()">

    <!-- BREADCRUMB & EVENT HEADER -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#0B2A43] flex items-center gap-1">
                <span>← Kembali ke Daftar Event</span>
            </a>
            <span class="font-mono text-xs font-extrabold text-[#C9A24D] bg-[#0B2A43] px-3 py-1 rounded-full uppercase">
                KODE: {{ $event->event_code }}
            </span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h1 class="text-2xl font-bold font-serif text-[#0B2A43]">{{ $event->title }}</h1>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                    <span>🏛️ {{ $event->institution_name ?? 'Umum' }}</span>
                    <span>•</span>
                    <span>📅 {{ $event->start_date ? $event->start_date->format('d M Y') : 'Kapan Saja' }}</span>
                </p>
                @if(!empty($event->custom_subcategories) && is_array($event->custom_subcategories))
                    <div class="mt-2.5 flex items-center gap-1.5 flex-wrap" x-data="{ showAllTags: false }">
                        <span class="text-[11px] font-bold text-slate-600">🏷️ {{ $event->group_label ?: 'Opsi Pilihan Peserta' }}:</span>
                        @foreach($event->custom_subcategories as $idx => $subCat)
                            <span x-show="showAllTags || {{ $idx }} < 6" class="px-2.5 py-0.5 bg-blue-50 text-blue-800 border border-blue-200/80 rounded-full text-[10px] font-semibold transition">
                                {{ $subCat }}
                            </span>
                        @endforeach
                        @if(count($event->custom_subcategories) > 6)
                            <button type="button" @click="showAllTags = !showAllTags" class="px-2.5 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-full text-[10px] font-bold transition cursor-pointer">
                                <span x-text="showAllTags ? '▲ Sembunyikan' : '+{{ count($event->custom_subcategories) - 6 }} Opsi Lainnya'"></span>
                            </button>
                        @endif
                    </div>
                @endif
            </div>

            <!-- OPTION A: REORGANIZED ACTION BUTTONS HIERARCHY -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Primary CTA: Edit Event -->
                <button @click="openEdit({{ json_encode($event) }}, '{{ route('admin.events.update', $event) }}')" type="button" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5 cursor-pointer">
                    <span>✏️ Edit Event & Opsi</span>
                </button>

                <!-- Primary CTA: PDF Report -->
                <a href="{{ route('admin.reports.export_pdf', ['event_id' => $event->id]) }}" target="_blank" class="px-4 py-2.5 bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                    <span>📄 PDF Laporan</span>
                </a>

                <!-- Secondary Dropdown CTA: Alat Data Peserta -->
                <div class="relative" x-data="{ openDataMenu: false }" @click.away="openDataMenu = false">
                    <button @click="openDataMenu = !openDataMenu" type="button" class="px-4 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2 cursor-pointer">
                        <span>⚙️ Kelola Data ▾</span>
                    </button>

                    <div x-show="openDataMenu" x-cloak x-transition.origin.top.right class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/80 py-2 z-30 space-y-1">
                        <button @click="showBulkRegionModal = true; openDataMenu = false" type="button" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 flex items-center gap-2 transition cursor-pointer">
                            <span>⚡</span> <span>Ganti Wilayah Massal</span>
                        </button>
                        <a href="{{ route('admin.events.export_csv', $event) }}" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 flex items-center gap-2 transition">
                            <span>📥</span> <span>Export Data CSV</span>
                        </a>
                        <button @click="showImportModal = true; openDataMenu = false" type="button" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-purple-50 hover:text-purple-700 flex items-center gap-2 transition cursor-pointer">
                            <span>📤</span> <span>Update Data CSV</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 text-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Submissions</span>
                <strong class="text-2xl font-bold text-[#0B2A43]">{{ $totalSubmissions }}</strong>
            </div>

            <div class="p-4 bg-amber-50/70 rounded-2xl border border-amber-200/80 text-center">
                <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Rata-Rata RQI (0-100)</span>
                <strong class="text-2xl font-bold text-[#B48A16]">{{ $avgRqi }}</strong>
            </div>

            <div class="p-4 bg-emerald-50/70 rounded-2xl border border-emerald-200/80 text-center">
                <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Rata-Rata WHO-5 (%)</span>
                <strong class="text-2xl font-bold text-emerald-600">{{ $avgWho5 }}%</strong>
            </div>

            <div class="p-4 bg-blue-50/70 rounded-2xl border border-blue-200/80 text-center">
                <span class="text-[10px] font-bold text-blue-800 uppercase tracking-wider block">WHO-5 Sehat / Skrining</span>
                <strong class="text-lg font-bold text-slate-800">
                    <span class="text-emerald-600">{{ $who5SehatCount }}</span> / <span class="text-rose-600">{{ $who5PerluSkriningCount }}</span>
                </strong>
            </div>
        </div>
    </div>

    <!-- SUBMISSIONS TABLE FOR THIS EVENT -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h3 class="font-serif font-bold text-[#0B2A43] text-base">Daftar Hasil Peserta Event Ini</h3>
                <span class="text-xs text-slate-500 font-medium">Total: {{ $submissions->total() }} data peserta</span>
            </div>

            <!-- TABLE INTERACTIVE SEARCH & FILTERS -->
            <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto text-xs">
                <!-- Search Box -->
                <div class="relative flex-1 md:w-56">
                    <input type="text" x-model="tableSearch" placeholder="Cari nama, kode sesi..." class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none text-xs">
                    <span class="absolute left-2.5 top-2 text-slate-400">🔍</span>
                </div>

                <!-- Subcategory / Class Filter -->
                @if(!empty($event->custom_subcategories) && is_array($event->custom_subcategories))
                    <select x-model="filterSubCat" class="py-2 px-3 rounded-xl border border-slate-300 bg-slate-50 font-medium outline-none text-xs">
                        <option value="">Semua {{ $event->group_label ?: 'Kelas' }}</option>
                        @foreach($event->custom_subcategories as $subCat)
                            <option value="{{ $subCat }}">{{ $subCat }}</option>
                        @endforeach
                    </select>
                @endif

                <!-- WHO-5 Filter -->
                <select x-model="filterWho5" class="py-2 px-3 rounded-xl border border-slate-300 bg-slate-50 font-medium outline-none text-xs">
                    <option value="">Semua Status WHO-5</option>
                    <option value="sehat">🟢 Sehat (≥50%)</option>
                    <option value="skrining">🔴 Skrining (&lt;50%)</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-sans">
                <thead class="bg-[#0B2A43] text-white uppercase font-bold text-[11px] tracking-wider">
                    <tr>
                        <th class="p-4">Kode Sesi</th>
                        <th class="p-4">Peserta</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Daerah</th>
                        <th class="p-4 text-center">Indeks RQI (0-100)</th>
                        <th class="p-4 text-center">Kesejahteraan WHO-5 (%)</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($submissions as $sub)
                        @php
                            $res = $sub->result;
                            $p = $sub->participant;
                        @endphp
                        <tr x-show="matchesFilter('{{ addslashes($p->name ?? '') }}', '{{ addslashes($sub->submission_code ?? '') }}', '{{ addslashes($p->sub_category ?? '') }}', '{{ $res ? $res->who5_percentage : '' }}')" class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 font-mono font-bold text-[#B48A16]">{{ $sub->submission_code }}</td>
                            <td class="p-4">
                                <strong class="text-slate-900 block font-bold text-sm">{{ $p->name ?? 'Anonim' }}</strong>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $p->assessment_code ?? '-' }}</span>
                                @if(!empty($p->sub_category))
                                    <span class="inline-block mt-0.5 px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-[10px] font-semibold border border-blue-200/60">{{ $p->sub_category }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $p->category ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $p->province->name ?? '-' }}
                                <span class="block text-[10px] text-slate-400">{{ $p->regency ? $p->regency->formatted_name : '' }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="font-bold text-sm text-[#0B2A43] block">{{ $res->rqi_score ?? '-' }}</span>
                                @if($res && $res->category_name)
                                    <span class="inline-block mt-0.5 px-2 py-0.5 bg-amber-50 text-amber-900 border border-amber-200/80 rounded-full text-[10px] font-semibold">
                                        {{ $res->category_name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[10px]">-</span>
                                @endif
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
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.results.show', $sub) }}" class="px-2.5 py-1.5 bg-[#0B2A43] hover:bg-[#123B59] text-white text-[11px] font-bold rounded-lg shadow transition inline-flex items-center gap-1" title="Lihat detail laporan hasil peserta ini">
                                        <span>📊 Detail</span>
                                    </a>
                                    <form action="{{ route('admin.results.destroy', $sub) }}" method="POST" onsubmit="return confirm('Yakin menghapus data peserta {{ addslashes($p->name ?? 'Anonim') }}? Data jawaban & hasil RQI peserta ini akan terhapus.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 font-bold rounded-lg text-[11px] border border-rose-200 transition cursor-pointer flex items-center gap-1" title="Hapus data peserta dari event ini">
                                            <span>🗑️ Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                Belum ada jawaban peserta yang masuk pada event ini.
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

    <!-- EDIT EVENT MODAL WITH FIELD LOCKING & INTERACTIVE TAG MANAGER -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl relative border border-slate-100 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Edit Modal Header -->
            <div class="px-6 py-4 bg-[#0B2A43] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white font-bold flex items-center justify-center text-base shadow">
                        ✏️
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">SAFE EVENT EDIT & OPTIONS MANAGER</span>
                        <h3 class="text-base font-serif font-bold text-white">Edit Event & Kelola Opsi Kelas</h3>
                    </div>
                </div>
                <button @click="editModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">✕</button>
            </div>

            <!-- Edit Modal Body -->
            <form :action="editUrl" method="POST" class="p-6 sm:p-7 text-xs overflow-y-auto flex-1 space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="access_type" :value="editData.access_type">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- LEFT COLUMN: Informasi Utama & Akses (Locked Vital Fields) -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-slate-100 font-bold text-[#0B2A43] flex items-center gap-1.5 text-xs">
                            <span>📌 1. Identitas & Target Event</span>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Nama Event / Kegiatan *</label>
                            <input type="text" name="title" x-model="editData.title" required placeholder="Contoh: Uji Ruhiologi Pemda Jambi 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium text-xs">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Tempat / Instansi Kegiatan *</label>
                            <input type="text" name="institution_name" x-model="editData.institution_name" required placeholder="Contoh: SMAN Titian Teras Jambi / Aula Pemda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1 flex items-center justify-between">
                                    <span>Kode Event</span>
                                    <span class="text-[10px] font-bold text-slate-400">🔒 Dikunci</span>
                                </label>
                                <input type="text" name="event_code" :value="editData.event_code" readonly class="w-full px-3 py-2.5 rounded-xl border border-slate-200 font-mono bg-slate-100/80 text-slate-500 uppercase font-bold text-xs outline-none cursor-not-allowed">
                                <span class="text-[9px] text-slate-400 mt-0.5 block">Kode event dikunci agar Link & QR Code tidak rusak.</span>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Status Event *</label>
                                <select name="status" x-model="editData.status" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none text-xs">
                                    <option value="active">🟢 Active</option>
                                    <option value="draft">⚪ Draft</option>
                                    <option value="completed">🔵 Completed</option>
                                    <option value="archived">🔴 Archived</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Paket Soal / Instrumen *</label>
                            <select name="instrument_id" x-model="editData.instrument_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                                <option value="">-- Pilih Paket Soal --</option>
                                @foreach($instruments as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->code }} - {{ $inst->name }} ({{ $inst->questions_count ?? $inst->questions->count() }} Soal)</option>
                                @endforeach
                            </select>
                            <span class="text-[9px] text-amber-700 font-medium mt-0.5 block">Pilih paket soal / instrumen yang digunakan untuk event ini.</span>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Preset Target Kategori Peserta</label>
                            <select name="target_category" x-model="editData.target_category" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                                <option value="">🔘 Bebas / Fleksibel (Peserta memilih sendiri)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->icon ?? '🏷️' }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" x-model="editData.start_date" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white text-xs outline-none">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" x-model="editData.end_date" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white text-xs outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Target Kuota Peserta</label>
                            <input type="number" name="quota" x-model="editData.quota" placeholder="Contoh: 250" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white outline-none font-medium text-xs">
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Interactive Subcategory Tag Manager & Dates -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-slate-100 font-bold text-[#0B2A43] flex items-center gap-1.5 text-xs">
                            <span>⚙️ 2. Mode Asesmen & Interactive Opsi Kelompok</span>
                        </div>

                        <!-- Mode Asesmen (Locked Indicator) -->
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-[#0B2A43] text-xs">Mode Asesmen Event</label>
                                <span class="text-[10px] font-bold text-slate-400">🔒 Dikunci</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-[#0B2A43] flex items-center gap-2">
                                <span x-text="editData.assessment_type === 'prepost' ? '🔄 Pretest & Posttest' : '📝 Sekali Tes (Single Test)'"></span>
                            </div>
                            <input type="hidden" name="assessment_type" :value="editData.assessment_type">

                            <template x-if="editData.assessment_type === 'prepost'">
                                <div class="pt-2 space-y-2 border-t border-slate-200/60 mt-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Mulai Pretest</label>
                                            <input type="datetime-local" name="pretest_start" x-model="editData.pretest_start" class="w-full p-2 rounded-lg border border-slate-300 bg-white font-medium text-[11px]">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Selesai Pretest</label>
                                            <input type="datetime-local" name="pretest_end" x-model="editData.pretest_end" class="w-full p-2 rounded-lg border border-slate-300 bg-white font-medium text-[11px]">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Mulai Posttest</label>
                                            <input type="datetime-local" name="posttest_start" x-model="editData.posttest_start" class="w-full p-2 rounded-lg border border-slate-300 bg-white font-medium text-[11px]">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Selesai Posttest</label>
                                            <input type="datetime-local" name="posttest_end" x-model="editData.posttest_end" class="w-full p-2 rounded-lg border border-slate-300 bg-white font-medium text-[11px]">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Custom Group / Interactive Subcategory Tag Manager -->
                        <div class="p-4 bg-blue-50/70 rounded-2xl border border-blue-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-[#0B2A43] text-xs">🏷️ Opsi Kelompok / Kelas / Divisi (Custom)</label>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Dapat Ditambah / Dihapus Kapan Saja</span>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 text-[10px] mb-1">Judul Label Pada Form Peserta</label>
                                <input type="text" name="group_label" x-model="editData.group_label" placeholder="Contoh: Pilih Kelas / Pilih Divisi" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]">
                            </div>

                            <!-- Interactive Tags Display -->
                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-700 text-[10px]">Daftar Opsi Pilihan Aktif Saat Ini:</label>
                                <div class="flex flex-wrap gap-1.5 p-3 bg-white rounded-xl border border-slate-200 min-h-[52px] items-center">
                                    <template x-for="(opt, idx) in editSubcategoriesList" :key="idx">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-800 border border-blue-200 font-bold rounded-lg text-xs shadow-2xs">
                                            <span x-text="opt"></span>
                                            <button type="button" @click="removeOption(idx)" class="text-rose-500 hover:text-rose-700 hover:bg-rose-100 rounded-full w-4 h-4 inline-flex items-center justify-center font-bold text-[11px] transition" title="Hapus opsi ini">✕</button>
                                        </span>
                                    </template>
                                    <template x-if="editSubcategoriesList.length === 0">
                                        <span class="text-xs text-slate-400 italic">Belum ada opsi khusus yang ditambahkan.</span>
                                    </template>
                                </div>
                            </div>

                            <!-- Add New Option Input Field -->
                            <div class="space-y-1 pt-1">
                                <label class="block font-bold text-slate-700 text-[10px]">+ Tambah Opsi Pilihan Baru:</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="newOptionText" @keydown.enter.prevent="addOption()" placeholder="Ketik opsi (misal: Kelas XI IPA 2) lalu tekan Enter..." class="flex-1 px-3 py-2 rounded-xl border border-slate-300 bg-white text-xs font-medium outline-none focus:ring-2 focus:ring-[#0B2A43]">
                                    <button type="button" @click="addOption()" class="px-4 py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl text-xs transition cursor-pointer shrink-0">
                                        + Tambah
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-500">Opsi baru yang ditambahkan di sini akan **langsung otomatis muncul** di form pendaftaran peserta.</p>
                            </div>

                            <input type="hidden" name="custom_subcategories" :value="customSubcategoriesSubmittedString">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Catatan / Deskripsi Event</label>
                            <textarea name="description" x-model="editData.description" rows="2" placeholder="Catatan peruntukan kegiatan..." class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white outline-none text-xs font-medium"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                    <button @click="editModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer text-xs flex items-center gap-2">
                        <span>💾 Simpan Perubahan Event →</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL GANTI WILAYAH MASSAL KHUSUS EVENT INI -->
    <div x-show="showBulkRegionModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base flex items-center gap-2">
                    <span>⚡</span> <span>Ganti Wilayah Massal Peserta Event</span>
                </h3>
                <button @click="showBulkRegionModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">✕</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Fitur ini akan secara instan memperbarui wilayah asal <strong>seluruh peserta</strong> yang terdaftar pada Event <strong>"{{ $event->title }}"</strong> (KODE: {{ $event->event_code }}) menjadi Provinsi & Kota yang Anda pilih di bawah ini.
            </p>

            <form action="{{ route('admin.events.bulk_region', $event) }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Provinsi Baru *</label>
                    <select name="province_id" x-model="bulkProvinceId" @change="onBulkProvinceChange()" required class="w-full p-2.5 rounded-xl border border-slate-300 font-bold bg-white text-slate-800 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                        <option value="">-- Pilih Provinsi --</option>
                        <template x-for="p in provincesList" :key="p.id">
                            <option :value="p.id" x-text="p.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kabupaten / Kota Baru *</label>
                    <select name="regency_id" x-model="bulkRegencyId" :disabled="!bulkProvinceId" required class="w-full p-2.5 rounded-xl border border-slate-300 font-bold bg-white text-slate-800 disabled:bg-slate-100 disabled:text-slate-400 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                        <option value="" x-text="bulkProvinceId ? '-- Pilih Kab/Kota --' : 'Pilih Provinsi Dahulu'"></option>
                        <template x-for="r in filteredBulkRegencies" :key="r.id">
                            <option :value="r.id" x-text="r.formatted_name || ((r.name.startsWith('Kota') || r.name.startsWith('Kabupaten') || !r.type) ? r.name : (r.type + ' ' + r.name))"></option>
                        </template>
                    </select>
                </div>

                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-900">
                    ⚠️ <strong>Perhatian:</strong> Tindakan ini akan secara otomatis mengubah domisili seluruh peserta pada event ini secara langsung.
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showBulkRegionModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-extrabold rounded-xl transition shadow cursor-pointer">
                        ⚡ Terapkan Perubahan Massal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL IMPORT / UPDATE CSV KHUSUS EVENT INI -->
    <div x-show="showImportModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base flex items-center gap-2">
                    <span>📤</span> <span>Update / Impor CSV Peserta Event Ini</span>
                </h3>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">✕</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Unduh file CSV peserta khusus event ini melalui tombol <strong>Export CSV</strong>, perbaiki data di Excel, lalu unggah kembali filenya di bawah ini untuk memperbarui data peserta Event <strong>"{{ $event->title }}"</strong>.
            </p>

            <form action="{{ route('admin.events.import_csv', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih File CSV (*.csv) *</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs bg-slate-50 font-mono">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showImportModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-extrabold rounded-xl transition shadow cursor-pointer">
                        Proses Update CSV Event →
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function adminEventsManager() {
    return {
        editModal: false,
        showBulkRegionModal: false,
        showImportModal: false,
        bulkProvinceId: '',
        bulkRegencyId: '',
        provincesList: @json($provinces ?? []),
        regenciesMap: @json($regenciesMap ?? []),
        filteredBulkRegencies: [],
        editUrl: '',
        newOptionText: '',
        editSubcategoriesList: [],

        tableSearch: '',
        filterSubCat: '',
        filterWho5: '',

        matchesFilter(name, code, subCat, who5Pct) {
            if (this.tableSearch) {
                const q = this.tableSearch.toLowerCase().trim();
                const n = (name || '').toLowerCase();
                const c = (code || '').toLowerCase();
                const s = (subCat || '').toLowerCase();
                if (!n.includes(q) && !c.includes(q) && !s.includes(q)) return false;
            }
            if (this.filterSubCat && subCat !== this.filterSubCat) {
                return false;
            }
            if (this.filterWho5 === 'sehat' && (who5Pct === '' || Number(who5Pct) < 50)) {
                return false;
            }
            if (this.filterWho5 === 'skrining' && (who5Pct === '' || Number(who5Pct) >= 50)) {
                return false;
            }
            return true;
        },

        onBulkProvinceChange() {
            this.bulkRegencyId = '';
            if (!this.bulkProvinceId) {
                this.filteredBulkRegencies = [];
                return;
            }
            const pid = String(this.bulkProvinceId);
            this.filteredBulkRegencies = this.regenciesMap[pid] || this.regenciesMap[Number(pid)] || [];
        },

        editData: {
            title: '',
            institution_name: '',
            event_code: '',
            status: 'active',
            instrument_id: '',
            target_category: '',
            start_date: '',
            end_date: '',
            quota: '',
            assessment_type: 'single',
            pretest_start: '',
            pretest_end: '',
            posttest_start: '',
            posttest_end: '',
            group_label: '',
            description: '',
            access_type: 'EVENT_PROGRAM',
        },

        openEdit(eventData, updateUrl) {
            this.editUrl = updateUrl;
            
            if (Array.isArray(eventData.custom_subcategories)) {
                this.editSubcategoriesList = [...eventData.custom_subcategories];
            } else if (typeof eventData.custom_subcategories === 'string' && eventData.custom_subcategories.trim() !== '') {
                this.editSubcategoriesList = eventData.custom_subcategories.split(',').map(s => s.trim()).filter(Boolean);
            } else {
                this.editSubcategoriesList = [];
            }

            this.newOptionText = '';

            this.editData = {
                title: eventData.title || '',
                institution_name: eventData.institution_name || '',
                event_code: eventData.event_code || '',
                status: eventData.status || 'active',
                instrument_id: eventData.instrument_id || '',
                target_category: eventData.target_category || '',
                start_date: eventData.start_date ? eventData.start_date.substring(0, 10) : '',
                end_date: eventData.end_date ? eventData.end_date.substring(0, 10) : '',
                quota: eventData.quota || '',
                assessment_type: eventData.assessment_type || 'single',
                pretest_start: eventData.pretest_start ? eventData.pretest_start.replace(' ', 'T').substring(0, 16) : '',
                pretest_end: eventData.pretest_end ? eventData.pretest_end.replace(' ', 'T').substring(0, 16) : '',
                posttest_start: eventData.posttest_start ? eventData.posttest_start.replace(' ', 'T').substring(0, 16) : '',
                posttest_end: eventData.posttest_end ? eventData.posttest_end.replace(' ', 'T').substring(0, 16) : '',
                group_label: eventData.group_label || '',
                description: eventData.description || '',
                access_type: eventData.access_type || 'EVENT_PROGRAM',
            };
            this.editModal = true;
        },

        addOption() {
            const val = this.newOptionText.trim();
            if (val && !this.editSubcategoriesList.includes(val)) {
                this.editSubcategoriesList.push(val);
                this.newOptionText = '';
            }
        },

        removeOption(index) {
            this.editSubcategoriesList.splice(index, 1);
        },

        get customSubcategoriesSubmittedString() {
            return this.editSubcategoriesList.join(', ');
        }
    }
}
</script>
@endpush
@endsection
