@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="adminEventsManager()">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 bg-[#0B2A43]/10 text-[#0B2A43] text-[10px] font-mono font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <span>🎯 EVENT & PROGRAM MANAGEMENT</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-[#0B2A43]">Manajemen Event & Kegiatan Uji Ruhiologi</h1>
            <p class="text-xs text-slate-500 mt-1">Buat dan kelola event khusus (workshop/uji instansi/batch) beserta jadwal Pretest & Posttest terintegrasi.</p>
        </div>
        <div>
            <button @click="createModal = true" type="button" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold text-xs rounded-2xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                <span>✨ + Buat Event / Kegiatan Baru</span>
            </button>
        </div>
    </div>

    <!-- EXECUTIVE QUICK STATS BAR -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-lg font-bold">🎯</div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block tracking-wider">Total Event</span>
                <strong class="text-lg font-bold text-[#0B2A43]">{{ $stats['total_events'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg font-bold">🟢</div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block tracking-wider">Event Aktif</span>
                <strong class="text-lg font-bold text-emerald-600">{{ $stats['active_events'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg font-bold">👥</div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block tracking-wider">Total Peserta</span>
                <strong class="text-lg font-bold text-[#0B2A43]">{{ $stats['event_participants'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-lg font-bold">📝</div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block tracking-wider">Submissions</span>
                <strong class="text-lg font-bold text-purple-700">{{ $stats['event_submissions'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3 col-span-2 md:col-span-1">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-lg font-bold">🏛️</div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block tracking-wider">Instansi Mitra</span>
                <strong class="text-lg font-bold text-indigo-700">{{ $stats['total_institutions'] ?? 0 }}</strong>
            </div>
        </div>
    </div>

    <!-- SEARCH & STATUS FILTER BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-3 text-xs">
        <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-72">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama event, kode, instansi..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                <span class="absolute left-3 top-3 text-slate-400">🔍</span>
            </div>
            <select name="status" onchange="this.form.submit()" class="w-full sm:w-44 py-2.5 px-3 rounded-xl border border-slate-300 bg-slate-50 font-medium outline-none">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Active</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>⚪ Draft</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>🔵 Completed</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>🔴 Archived</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-[#0B2A43] text-white font-bold rounded-xl hover:bg-[#123B59] transition">Filter</button>
        </form>

        <div class="text-slate-500 font-medium text-right w-full md:w-auto">
            Total Event: <strong class="text-[#0B2A43]">{{ $events->total() }}</strong>
        </div>
    </div>

    <!-- EVENTS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            @php
                $timeStatus = 'ongoing';
                if ($event->start_date && $event->start_date->isFuture()) {
                    $timeStatus = 'upcoming';
                } elseif ($event->end_date && $event->end_date->isPast()) {
                    $timeStatus = 'finished';
                }
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden relative group">
                <!-- Status Badge Header -->
                <div class="p-5 border-b border-slate-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[11px] font-extrabold text-[#C9A24D] bg-[#0B2A43] px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $event->event_code }}
                        </span>
                        <div class="flex items-center gap-1.5 flex-wrap justify-end">
                            @if($event->assessment_type === 'prepost')
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-300 rounded-full text-[10px] font-bold">Pre & Post</span>
                            @endif
                            
                            @if($timeStatus === 'upcoming')
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 border border-amber-300 rounded-full text-[10px] font-extrabold flex items-center gap-1" title="Event Belum Dimulai">
                                    <span>🟡</span> Belum Dimulai
                                </span>
                            @elseif($timeStatus === 'finished')
                                <span class="px-2 py-0.5 bg-rose-100 text-rose-800 border border-rose-300 rounded-full text-[10px] font-extrabold flex items-center gap-1" title="Event Sudah Selesai">
                                    <span>🔴</span> Selesai
                                </span>
                            @else
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-full text-[10px] font-extrabold flex items-center gap-1 animate-pulse" title="Event Sedang Berlangsung">
                                    <span>🟢</span> Berlangsung
                                </span>
                            @endif

                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                {{ $event->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $event->status === 'draft' ? 'bg-slate-100 text-slate-700 border border-slate-300' : '' }}
                                {{ $event->status === 'completed' ? 'bg-blue-100 text-blue-800 border border-blue-300' : '' }}
                                {{ $event->status === 'archived' ? 'bg-rose-100 text-rose-800 border border-rose-300' : '' }}">
                                {{ $event->status }}
                            </span>
                        </div>
                    </div>

                    <h3 class="font-serif font-bold text-lg text-[#0B2A43] group-hover:text-[#C9A24D] transition-colors leading-snug">
                        <a href="{{ route('admin.events.show', $event) }}">{{ $event->title }}</a>
                    </h3>

                    <p class="text-xs text-slate-500 flex items-center gap-1.5 font-medium flex-wrap">
                        <span>🏛️</span> <span>{{ $event->institution_name ?? 'Instansi Internal' }}</span>
                        @if($event->regency || $event->province)
                            <span class="text-slate-300">•</span>
                            <span>📍 {{ $event->regency ? $event->regency->formatted_name : '' }}{{ $event->province ? ', ' . $event->province->name : '' }}</span>
                        @endif
                    </p>
                </div>

                <!-- Event Details & Stats -->
                <div class="p-5 bg-slate-50/50 space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">Peserta</span>
                            <strong class="text-base font-bold text-[#0B2A43]">{{ $event->participants_count }}</strong>
                        </div>
                        <div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">Submissions</span>
                            <strong class="text-base font-bold text-emerald-600">{{ $event->submissions_count }}</strong>
                        </div>
                    </div>

                    @if($event->quota)
                        @php
                            $quotaPct = min(100, round(($event->participants_count / $event->quota) * 100));
                        @endphp
                        <div class="pt-2 border-t border-slate-200/60 space-y-1">
                            <div class="flex justify-between text-[10px] font-bold">
                                <span class="text-slate-500">Kuota Terisi:</span>
                                <span class="{{ $quotaPct >= 90 ? 'text-rose-600 font-extrabold' : 'text-slate-800' }}">{{ $event->participants_count }} / {{ $event->quota }} ({{ $quotaPct }}%)</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full {{ $quotaPct >= 90 ? 'bg-rose-500' : ($quotaPct >= 75 ? 'bg-amber-500' : 'bg-emerald-500') }} rounded-full transition-all duration-500" style="width: {{ $quotaPct }}%"></div>
                            </div>
                        </div>
                    @endif

                    <div class="text-[11px] text-slate-500 space-y-1">
                        <div class="flex justify-between">
                            <span>Preset Kategori:</span>
                            <strong class="text-slate-800">{{ $event->target_category ?? 'Bebas / Fleksibel' }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Jadwal:</span>
                            <strong class="text-slate-800">
                                {{ $event->start_date ? $event->start_date->format('d M Y') : 'Kapan Saja' }}
                            </strong>
                        </div>
                        @if($event->assessment_type === 'prepost')
                            <div class="pt-1 text-[10px] border-t border-slate-200/60 space-y-0.5 text-slate-600">
                                <div>📅 Pretest: <strong>{{ $event->pretest_start ? $event->pretest_start->format('d/m/Y') : '-' }} s/d {{ $event->pretest_end ? $event->pretest_end->format('d/m/Y') : '-' }}</strong></div>
                                <div>📅 Posttest: <strong>{{ $event->posttest_start ? $event->posttest_start->format('d/m/Y') : '-' }} s/d {{ $event->posttest_end ? $event->posttest_end->format('d/m/Y') : '-' }}</strong></div>
                            </div>
                        @endif

                        <!-- Display Dynamic Subcategory / Class Badges Box -->
                        @if(!empty($event->custom_subcategories) && is_array($event->custom_subcategories))
                            <div class="pt-2.5 border-t border-slate-200/60 space-y-1">
                                <span class="text-[10px] font-bold text-slate-600 block">🏷️ {{ $event->group_label ?: 'Opsi Pilihan Peserta' }}:</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($event->custom_subcategories, 0, 5) as $subCat)
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-800 border border-blue-200/80 rounded-md text-[10px] font-semibold">{{ $subCat }}</span>
                                    @endforeach
                                    @if(count($event->custom_subcategories) > 5)
                                        <span class="px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">+{{ count($event->custom_subcategories) - 5 }} opsi</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="p-3.5 bg-white border-t border-slate-100 flex items-center justify-between gap-1.5 text-xs">
                    <a href="{{ route('admin.events.show', $event) }}" class="flex-1 py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-center rounded-xl transition text-[11px]">
                        📊 Analytic
                    </a>

                    <button @click="openEdit({{ json_encode($event) }}, '{{ route('admin.events.update', $event) }}')" type="button" title="Edit Event & Kelola Opsi Kelas" class="px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold rounded-xl transition cursor-pointer text-[11px] flex items-center gap-1">
                        <span>✏️ Edit</span>
                    </button>

                    <button @click="activeQrUrl = '{{ $event->direct_access_url }}'; activeQrTitle = '{{ addslashes($event->title) }}'; activeInstitution = '{{ addslashes($event->institution_name ?? '') }}'; activeStartDate = '{{ $event->start_date ? $event->start_date->format('d M Y') : 'Kapan Saja' }}'; qrModal = true" type="button" title="QR Code & Broadcast WA" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer text-[11px]">
                        📱
                    </button>

                    <a href="{{ route('admin.reports.export_pdf', ['event_id' => $event->id]) }}" target="_blank" title="Export Laporan PDF Event" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl transition text-[11px] flex items-center justify-center">
                        📄
                    </a>

                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Yakin menghapus event ini? Data peserta terkait tidak akan terhapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Event" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl transition cursor-pointer text-[11px]">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm space-y-3">
                <span class="text-4xl block">🎯</span>
                <h3 class="font-serif font-bold text-lg text-slate-800">Belum Ada Event / Kegiatan Asesmen</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">Klik tombol "+ Buat Event / Kegiatan Baru" di atas untuk menambahkan acara uji ruhiologi resmi pertama Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="pt-4">
        {{ $events->links() }}
    </div>

    <!-- CREATE EVENT MODAL -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="createModal = false" class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl relative border border-slate-100 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0B2A43] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#C9A24D] text-[#0B2A43] font-bold flex items-center justify-center text-base shadow">
                        ✨
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">INTEGRATED EVENT SETUP</span>
                        <h3 class="text-base font-serif font-bold text-white">Buat Event Asesmen Baru</h3>
                    </div>
                </div>
                <button @click="createModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">✕</button>
            </div>

            <!-- Modal Body (Compact 2 Columns Grid) -->
            <form action="{{ route('admin.events.store') }}" method="POST" class="p-6 sm:p-7 text-xs overflow-y-auto flex-1 space-y-5">
                @csrf
                <input type="hidden" name="access_type" value="EVENT_PROGRAM">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- LEFT COLUMN: Informasi Utama & Akses -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-slate-100 font-bold text-[#0B2A43] flex items-center gap-1.5 text-xs">
                            <span>📌 1. Identitas & Target Event</span>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Nama Event / Kegiatan *</label>
                            <input type="text" name="title" required placeholder="Contoh: Uji Ruhiologi Pemda Jambi 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium text-xs">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Tempat / Instansi Kegiatan *</label>
                            <input type="text" name="institution_name" required placeholder="Contoh: SMAN Titian Teras Jambi / Aula Pemda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Provinsi Event</label>
                                <select name="province_id" x-model="createProvinceId" @change="onCreateProvinceChange()" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-medium text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                                    <option value="">-- Pilih Provinsi --</option>
                                    <template x-for="prov in provincesList" :key="prov.id">
                                        <option :value="prov.id" x-text="prov.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Kabupaten / Kota Event</label>
                                <select name="regency_id" x-model="createRegencyId" :disabled="!createProvinceId" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-medium text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs disabled:opacity-50">
                                    <option value="">-- Pilih Kab/Kota --</option>
                                    <template x-for="reg in filteredCreateRegencies" :key="reg.id">
                                        <option :value="reg.id" x-text="reg.formatted_name || ((reg.name.startsWith('Kota') || reg.name.startsWith('Kabupaten') || !reg.type) ? reg.name : (reg.type + ' ' + reg.name))"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Kode Event (Unique)</label>
                                <input type="text" name="event_code" placeholder="Contoh: RQ-JAMBI-26" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-mono bg-slate-50/50 focus:bg-white uppercase font-bold text-xs outline-none">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Status Event *</label>
                                <select name="status" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none text-xs">
                                    <option value="active">🟢 Active</option>
                                    <option value="draft">⚪ Draft</option>
                                    <option value="completed">🔵 Completed</option>
                                    <option value="archived">🔴 Archived</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Pilih Paket Soal / Instrumen *</label>
                            <select name="instrument_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                                <option value="">-- Pilih Paket Soal --</option>
                                @foreach($instruments as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->code }} - {{ $inst->name }} ({{ $inst->questions_count ?? $inst->questions->count() }} Soal)</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-bold text-slate-800 text-xs">Preset Target Kategori Peserta</label>
                                <button type="button" @click="autoLoadSubCategories('create')" class="text-[10px] font-bold text-[#0B2A43] bg-blue-50 hover:bg-blue-100 px-2 py-0.5 rounded-md border border-blue-200 transition cursor-pointer">
                                    ⚡ Auto-isi Sub-Kategori
                                </button>
                            </div>
                            <select name="target_category" x-model="createTargetCategory" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                                <option value="">🔘 Bebas / Fleksibel (Peserta memilih sendiri)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->icon ?? '🏷️' }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white text-xs outline-none">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white text-xs outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Target Kuota Peserta</label>
                            <input type="number" name="quota" placeholder="Contoh: 250" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white outline-none font-medium text-xs">
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Pengaturan Mode & Kelompok Custom -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-slate-100 font-bold text-[#0B2A43] flex items-center gap-1.5 text-xs">
                            <span>⚙️ 2. Mode Asesmen & Opsi Kelompok</span>
                        </div>

                        <!-- Mode Asesmen -->
                        <div class="p-3 bg-amber-50/60 rounded-2xl border border-amber-200/80 space-y-2">
                            <label class="block font-bold text-[#0B2A43] text-xs">Mode Asesmen Event *</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" @click="assessmentType = 'single'" :class="assessmentType === 'single' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'" class="p-2.5 rounded-xl border text-[11px] text-center transition">
                                    <span>📝 Sekali Tes</span>
                                </button>
                                <button type="button" @click="assessmentType = 'prepost'" :class="assessmentType === 'prepost' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'" class="p-2.5 rounded-xl border text-[11px] text-center transition">
                                    <span>🔄 Pre & Posttest</span>
                                </button>
                            </div>
                            <input type="hidden" name="assessment_type" :value="assessmentType">

                            <template x-if="assessmentType === 'prepost'">
                                <div class="pt-2 space-y-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Mulai Pretest</label>
                                            <input type="datetime-local" name="pretest_start" class="w-full p-2 rounded-lg border border-slate-300 bg-white font-medium text-[11px]">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Selesai Pretest</label>
                                            <input type="datetime-local" name="pretest_end" class="w-full p-2 rounded-lg border border-slate-300 bg-white font-medium text-[11px]">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Mulai Posttest</label>
                                            <input type="datetime-local" name="posttest_start" class="w-full p-2 rounded-lg border border-slate-300 bg-white font-medium text-[11px]">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Selesai Posttest</label>
                                            <input type="datetime-local" name="posttest_end" class="w-full p-2 rounded-lg border border-slate-300 bg-white font-medium text-[11px]">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Custom Group / Subcategories -->
                        <div class="p-3 bg-blue-50/60 rounded-2xl border border-blue-200/80 space-y-2">
                            <label class="block font-bold text-[#0B2A43] text-xs">Opsi Kelompok / Kelas / Divisi (Custom)</label>

                            <div>
                                <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Judul Label Form Peserta</label>
                                <input type="text" name="group_label" placeholder="Contoh: Pilih Kelas / Pilih Divisi" class="w-full p-2 rounded-xl border border-slate-300 bg-white text-xs">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 text-[10px] mb-0.5">Opsi Pilihan (Pisahkan Koma)</label>
                                <input type="text" name="custom_subcategories" x-model="createCustomSubcategories" placeholder="Contoh: Kelas X-A, Kelas X-B, Kelas XI IPA 1" class="w-full p-2 rounded-xl border border-slate-300 bg-white text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 text-xs mb-1">Catatan / Deskripsi Event</label>
                            <textarea name="description" rows="2" placeholder="Catatan peruntukan kegiatan..." class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white outline-none text-xs font-medium"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                    <button @click="createModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer text-xs flex items-center gap-2">
                        <span>🚀 Simpan & Terbitkan Event →</span>
                    </button>
                </div>
            </form>
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
                                <label class="block font-bold text-slate-800 text-xs mb-1">Provinsi Event</label>
                                <select name="province_id" x-model="editProvinceId" @change="onEditProvinceChange(true)" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-medium text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                                    <option value="">-- Pilih Provinsi --</option>
                                    <template x-for="prov in provincesList" :key="prov.id">
                                        <option :value="prov.id" x-text="prov.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1">Kabupaten / Kota Event</label>
                                <select name="regency_id" x-model="editRegencyId" :disabled="!editProvinceId" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-medium text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs disabled:opacity-50">
                                    <option value="">-- Pilih Kab/Kota --</option>
                                    <template x-for="reg in filteredEditRegencies" :key="reg.id">
                                        <option :value="reg.id" x-text="reg.formatted_name || ((reg.name.startsWith('Kota') || reg.name.startsWith('Kabupaten') || !reg.type) ? reg.name : (reg.type + ' ' + reg.name))"></option>
                                    </template>
                                </select>
                            </div>
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
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-bold text-slate-800 text-xs">Preset Target Kategori Peserta</label>
                                <button type="button" @click="autoLoadSubCategories('edit')" class="text-[10px] font-bold text-[#0B2A43] bg-blue-50 hover:bg-blue-100 px-2 py-0.5 rounded-md border border-blue-200 transition cursor-pointer">
                                    ⚡ Impor Sub-Kategori Kategori
                                </button>
                            </div>
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

    <!-- QR CODE & SHARE MODAL -->
    <div x-show="qrModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
        <div @click.away="qrModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 text-center space-y-4 border border-slate-100 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button @click="qrModal = false" type="button" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 font-bold">✕</button>

            <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-wider block">QR CODE & BROADCAST EVENT</span>
            <h3 class="font-serif font-bold text-lg text-[#0B2A43]" x-text="activeQrTitle"></h3>

            <!-- QR Image via API -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 inline-block mx-auto">
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(activeQrUrl)" alt="QR Code Event" class="w-44 h-44 mx-auto rounded-lg shadow-sm">
            </div>

            <div class="space-y-2 text-left">
                <span class="text-[11px] font-bold text-slate-600 block">🔗 Link Akses Langsung Peserta:</span>
                <input type="text" :value="activeQrUrl" readonly class="w-full p-2.5 text-xs text-center font-mono bg-slate-100 rounded-xl border border-slate-300 select-all font-bold text-[#0B2A43]">
                <button type="button" @click="navigator.clipboard.writeText(activeQrUrl); alert('Link pendaftaran berhasil disalin!')" class="w-full py-2.5 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-bold text-xs rounded-xl transition shadow cursor-pointer">
                    📋 Salin Link Pendaftaran
                </button>
            </div>

            <div class="pt-3 border-t border-slate-200 text-left space-y-2">
                <span class="text-[11px] font-bold text-[#0B2A43] flex items-center gap-1.5">
                    <span>💬</span> Format Broadcast WhatsApp Panitia:
                </span>
                <textarea readonly :value="waBroadcastText" rows="5" class="w-full p-3 text-xs font-mono bg-slate-50 border border-slate-200 rounded-xl text-slate-700 leading-relaxed outline-none"></textarea>
                <button type="button" @click="navigator.clipboard.writeText(waBroadcastText); alert('Format Broadcast WhatsApp berhasil disalin!')" class="w-full py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl transition shadow cursor-pointer">
                    📱 Salin Format Broadcast WA Panitia
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function adminEventsManager() {
    return {
        createModal: false,
        editModal: false,
        qrModal: false,
        activeQrUrl: '',
        activeQrTitle: '',
        activeInstitution: '',
        activeStartDate: '',
        assessmentType: 'single',
        createTargetCategory: '',
        createCustomSubcategories: '',

        provincesList: @json($provinces ?? []),
        regenciesMap: @json($regenciesMap ?? []),
        categoriesList: @json($categories ?? []),

        createProvinceId: '',
        createRegencyId: '',
        filteredCreateRegencies: [],

        editProvinceId: '',
        editRegencyId: '',
        filteredEditRegencies: [],

        autoLoadSubCategories(modalType) {
            const targetCat = modalType === 'create' ? this.createTargetCategory : this.editData.target_category;
            if (!targetCat) {
                alert('Pilih target kategori terlebih dahulu!');
                return;
            }

            const cat = this.categoriesList.find(c => c.name === targetCat);
            if (!cat || !cat.sub_categories || cat.sub_categories.length === 0) {
                alert('Tidak ada sub-kategori yang ditemukan untuk kategori ' + targetCat);
                return;
            }

            const subCatNames = cat.sub_categories.map(s => s.name);
            if (modalType === 'create') {
                this.createCustomSubcategories = subCatNames.join(', ');
                alert(`Berhasil memuat ${subCatNames.length} sub-kategori ke Opsi Kelompok!`);
            } else {
                let addedCount = 0;
                subCatNames.forEach(name => {
                    if (!this.editSubcategoriesList.includes(name)) {
                        this.editSubcategoriesList.push(name);
                        addedCount++;
                    }
                });
                alert(`Berhasil menambahkan ${addedCount} sub-kategori baru ke Opsi Kelompok!`);
            }
        },

        get waBroadcastText() {
            const instText = this.activeInstitution ? `\n🏛️ Instansi: *${this.activeInstitution}*` : '';
            const dateText = this.activeStartDate ? `\n📅 Jadwal: *${this.activeStartDate}*` : '';
            return `Assalamu'alaikum Wr. Wb.\n\nYth. Bapak/Ibu/Peserta Uji Ruhiologi,\n\nBerikut adalah Link & QR Code pendaftaran resmi untuk kegiatan:\n📌 *${this.activeQrTitle}*${instText}${dateText}\n\nSilakan melakukan registrasi & pengisian asesmen mandiri melalui link di bawah ini:\n🔗 ${this.activeQrUrl}\n\nTerima kasih.\n*Ruhiology Institute*`;
        },

        onCreateProvinceChange() {
            this.createRegencyId = '';
            if (!this.createProvinceId) {
                this.filteredCreateRegencies = [];
                return;
            }
            const pid = String(this.createProvinceId);
            this.filteredCreateRegencies = this.regenciesMap[pid] || this.regenciesMap[Number(pid)] || [];
        },

        onEditProvinceChange(resetRegency = true) {
            if (resetRegency) {
                this.editRegencyId = '';
            }
            if (!this.editProvinceId) {
                this.filteredEditRegencies = [];
                return;
            }
            const pid = String(this.editProvinceId);
            this.filteredEditRegencies = this.regenciesMap[pid] || this.regenciesMap[Number(pid)] || [];
        },

        editUrl: '',
        newOptionText: '',
        editSubcategoriesList: [],
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
            province_id: '',
            regency_id: '',
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

            this.editProvinceId = eventData.province_id || '';
            this.onEditProvinceChange(false);
            this.editRegencyId = eventData.regency_id || '';

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
                province_id: eventData.province_id || '',
                regency_id: eventData.regency_id || '',
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
