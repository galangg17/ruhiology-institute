@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, qrModal: false, activeQrUrl: '', activeQrTitle: '' }">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 bg-[#0B2A43]/10 text-[#0B2A43] text-[10px] font-mono font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <span>🎯 EVENT & BATCH PROGRAM MANAGEMENT</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-[#0B2A43]">Manajemen Event & Kegiatan Uji Ruhiologi</h1>
            <p class="text-xs text-slate-500 mt-1">Buat dan kelola event khusus (workshop/uji instansi/batch) atau sesuaikan sesi mandiri publik.</p>
        </div>
        <div>
            <button @click="createModal = true" type="button" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-bold text-xs rounded-2xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
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
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                            {{ $event->status === 'active' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : '' }}
                            {{ $event->status === 'draft' ? 'bg-slate-100 text-slate-700 border border-slate-300' : '' }}
                            {{ $event->status === 'completed' ? 'bg-blue-100 text-blue-800 border border-blue-300' : '' }}
                            {{ $event->status === 'archived' ? 'bg-rose-100 text-rose-800 border border-rose-300' : '' }}">
                            {{ $event->status }}
                        </span>
                    </div>

                    <h3 class="font-serif font-bold text-lg text-[#0B2A43] group-hover:text-[#C9A24D] transition-colors leading-snug">
                        <a href="{{ route('admin.events.show', $event) }}">{{ $event->title }}</a>
                    </h3>

                    <p class="text-xs text-slate-500 flex items-center gap-1.5 font-medium">
                        <span>🏛️</span> <span>{{ $event->institution_name ?? 'Umum / Terbuka' }}</span>
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
                            <span>Tipe Akses:</span>
                            <strong class="text-slate-800">{{ $event->access_type === 'PUBLIC_SELF' ? 'Mandiri Publik' : 'Event Khusus' }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Jadwal:</span>
                            <strong class="text-slate-800">
                                {{ $event->start_date ? $event->start_date->format('d M Y') : 'Kapan Saja' }}
                            </strong>
                        </div>
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

    <!-- CREATE EVENT MODAL -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
        <div @click.away="createModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100 max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <div>
                    <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-wider">EVENT / BATCH SETUP</span>
                    <h3 class="text-xl font-serif font-bold text-[#0B2A43]">Buat Event Asesmen Baru</h3>
                </div>
                <button @click="createModal = false" type="button" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Event / Kegiatan *</label>
                    <input type="text" name="title" required placeholder="Contoh: Uji Ruhiologi Pemda Jambi 2026" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Event (Opsional)</label>
                        <input type="text" name="event_code" placeholder="Contoh: RQ-JAMBI-26" class="w-full p-3 rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-[#0B2A43] outline-none uppercase">
                        <span class="text-[10px] text-slate-400">Kosongkan untuk auto-generate</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Akses *</label>
                        <select name="access_type" required class="w-full p-3 rounded-xl border border-slate-300 font-bold bg-slate-50 outline-none">
                            <option value="EVENT_PROGRAM">🔵 Event / Kegiatan Khusus</option>
                            <option value="PUBLIC_SELF">🟢 Mandiri Umum</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Instansi / Lembaga Partner</label>
                    <input type="text" name="institution_name" placeholder="Contoh: Kanwil Kemenag Jambi / Universitas X" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full p-3 rounded-xl border border-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full p-3 rounded-xl border border-slate-300 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Target Kuota Peserta</label>
                        <input type="number" name="quota" placeholder="Contoh: 250" class="w-full p-3 rounded-xl border border-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Event *</label>
                        <select name="status" required class="w-full p-3 rounded-xl border border-slate-300 font-bold bg-slate-50 outline-none">
                            <option value="active">🟢 Active (Bisa diakses)</option>
                            <option value="draft">⚪ Draft</option>
                            <option value="completed">🔵 Completed</option>
                            <option value="archived">🔴 Archived</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan / Deskripsi Event</label>
                    <textarea name="description" rows="2" placeholder="Catatan singkat peruntukan kegiatan ini..." class="w-full p-3 rounded-xl border border-slate-300 outline-none"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl shadow-lg transition cursor-pointer">
                        🚀 Simpan Event & Buat Akses →
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
