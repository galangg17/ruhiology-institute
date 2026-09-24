@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, qrModal: false, activeQrUrl: '', activeQrTitle: '', assessmentType: 'single' }">
    
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
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden relative group">
                <!-- Status Badge Header -->
                <div class="p-5 border-b border-slate-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[11px] font-extrabold text-[#C9A24D] bg-[#0B2A43] px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $event->event_code }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            @if($event->assessment_type === 'prepost')
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-300 rounded-full text-[10px] font-bold">Pre & Post</span>
                            @endif
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                {{ $event->status === 'active' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : '' }}
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

                    <p class="text-xs text-slate-500 flex items-center gap-1.5 font-medium">
                        <span>🏛️</span> <span>{{ $event->institution_name ?? 'Instansi Internal' }}</span>
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
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                    <a href="{{ route('admin.events.show', $event) }}" class="flex-1 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-center rounded-xl transition">
                        📊 Analytic Event
                    </a>

                    <button @click="activeQrUrl = '{{ $event->direct_access_url }}'; activeQrTitle = '{{ addslashes($event->title) }}'; qrModal = true" type="button" title="QR Code & Copy Link" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer">
                        📱 QR
                    </button>

                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Yakin menghapus event ini? Data peserta terkait tidak akan terhapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Event" class="p-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl transition cursor-pointer">
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

    <!-- CREATE EVENT MODAL (COMPACT 2-COLUMN DESIGN - NO EXCESSIVE SCROLLING) -->
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
                            <label class="block font-bold text-slate-800 text-xs mb-1">Preset Target Kategori Peserta</label>
                            <select name="target_category" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                                <option value="">🔘 Bebas / Fleksibel (Peserta memilih sendiri)</option>
                                <option value="Pelajar">🏫 Pelajar (Siswa SD / SMP / SMA / SMK)</option>
                                <option value="Mahasiswa/i">🎓 Mahasiswa / Mahasiswi</option>
                                <option value="Umum">👤 Personal / Mandiri (Umum)</option>
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
                                <input type="text" name="custom_subcategories" placeholder="Contoh: Kelas X-A, Kelas X-B, Kelas XI IPA 1" class="w-full p-2 rounded-xl border border-slate-300 bg-white text-xs">
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

    <!-- QR CODE & SHARE MODAL -->
    <div x-show="qrModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
        <div @click.away="qrModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 border border-slate-100 shadow-2xl relative">
            <button @click="qrModal = false" type="button" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700">✕</button>

            <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-wider block">QR CODE & LINK EVENT</span>
            <h3 class="font-serif font-bold text-lg text-[#0B2A43]" x-text="activeQrTitle"></h3>

            <!-- QR Image via API -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 inline-block mx-auto">
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(activeQrUrl)" alt="QR Code Event" class="w-48 h-48 mx-auto rounded-lg shadow-sm">
            </div>

            <div class="space-y-2">
                <span class="text-[11px] text-slate-400 block">Link Akses Langsung Peserta:</span>
                <input type="text" :value="activeQrUrl" readonly class="w-full p-2.5 text-xs text-center font-mono bg-slate-100 rounded-xl border border-slate-300 select-all font-bold text-[#0B2A43]">
                <button type="button" @click="navigator.clipboard.writeText(activeQrUrl); alert('Link berhasil disalin!')" class="w-full py-2.5 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-bold text-xs rounded-xl transition shadow cursor-pointer">
                    📋 Salin Link Pendaftaran
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
