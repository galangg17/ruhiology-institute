@extends('layouts.app')

@section('title', 'Klaim Sertifikat Resmi: ' . $form->title)

@section('content')
<div class="min-h-screen bg-slate-100/70 py-10 md:py-16 px-4 sm:px-6">
    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Top Header Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/90 text-center relative overflow-hidden border-t-8 border-t-navy">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold/20 text-[#8C703E] text-xs font-bold uppercase tracking-wider mb-3 border border-gold/30">
                <span>🎓</span> Portal Sertifikat Resmi
            </span>

            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-navy leading-snug">
                Klaim & Unduh Sertifikat
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-lg mx-auto font-medium">
                {{ $form->certificate_event_name ?: $form->title }}
            </p>

            @if($form->certificate_total_jp)
                <div class="mt-3 inline-block px-3 py-1 bg-primary-50 rounded-lg text-primary-800 text-xs font-bold border border-primary-200">
                    Alokasi Bobot: {{ $form->calculated_total_jp }}
                </div>
            @endif

            <!-- Lock Status Notice -->
            @if(!$form->isCertificateReleased())
                <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs text-left flex items-start gap-3">
                    <span class="text-2xl shrink-0 mt-0.5">🔒</span>
                    <div class="space-y-1">
                        <strong class="font-bold text-amber-950 block">Akses Unduh Sertifikat Masih Terkunci</strong>
                        <p class="text-amber-800 leading-relaxed text-[11px] sm:text-xs">
                            Rangkaian sesi kegiatan masih berlangsung. Tautan unduh sertifikat resmi ber-JP akan diaktifkan secara serentak oleh panitia setelah acara resmi ditutup.
                        </p>
                    </div>
                </div>
            @else
                <div class="mt-6 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs text-left flex items-center gap-3">
                    <span class="text-xl shrink-0">✅</span>
                    <p class="text-[11px] sm:text-xs font-medium">
                        Akses sertifikat kegiatan telah <strong>DIBUKA</strong>. Silakan cari nama atau nomor WhatsApp Anda untuk mengunduh piagam resmi.
                    </p>
                </div>
            @endif
        </div>

        @if(session('locked_warning'))
            <div class="p-4 bg-amber-50 border border-amber-300 text-amber-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                <span>⚠️</span>
                <span>{{ session('locked_warning') }}</span>
            </div>
        @endif

        @if(session('name_success'))
            <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                <span>✅</span>
                <span>{{ session('name_success') }}</span>
            </div>
        @endif

        <!-- Search Box Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/90 space-y-4">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Cari Data Sertifikat Anda
            </h2>

            <form method="GET" action="{{ route('public.form.claim_certificate', $form->slug) }}" class="space-y-3">
                <div class="relative">
                    <input type="text" name="q" value="{{ $searchQuery }}" required autofocus
                           placeholder="Ketik Nomor WhatsApp atau Nama Lengkap saat mendaftar..."
                           class="w-full text-xs sm:text-sm px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-navy focus:outline-none transition">
                </div>
                <button type="submit" class="w-full py-3 px-6 rounded-xl bg-navy hover:bg-navy-dark text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Cari Sertifikat Saya</span>
                </button>
            </form>
        </div>

        <!-- Search Results Section -->
        @if($searchQuery !== null)
            <div class="space-y-4">
                <div class="flex items-center justify-between text-xs text-slate-500 px-2">
                    <span>Hasil pencarian untuk: <strong class="text-slate-800 font-mono">"{{ $searchQuery }}"</strong></span>
                    <span>Ditemukan: <strong>{{ count($results) }}</strong> data</span>
                </div>

                @forelse($results as $res)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-4" x-data="{ editName: false, currentName: '{{ addslashes($res->participant_name) }}' }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Kode Registrasi Responden</span>
                                <span class="font-mono font-bold text-navy text-sm">{{ $res->response_code }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 block">Waktu Pengisian</span>
                                <span class="text-xs text-slate-600">{{ $res->submitted_at ? $res->submitted_at->format('d M Y, H:i') : '-' }} WIB</span>
                            </div>
                        </div>

                        <!-- Participant Name & Title Preview -->
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Nama Lengkap & Gelar (Tertulis di Sertifikat):</span>
                            
                            <div x-show="!editName" class="flex items-center justify-between gap-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                <div>
                                    <h3 class="font-serif font-bold text-slate-800 text-base" x-text="currentName"></h3>
                                    @if($res->participant_institution)
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $res->participant_institution }}</p>
                                    @endif
                                </div>
                                <button type="button" @click="editName = true" class="text-xs text-primary-600 hover:text-primary-800 font-bold underline shrink-0 cursor-pointer">
                                    ✏️ Perbaiki Nama
                                </button>
                            </div>

                            <!-- Edit Name Form (if typo) -->
                            <div x-show="editName" class="bg-amber-50/70 p-4 rounded-xl border border-amber-200 space-y-3" style="display: none;">
                                <form method="POST" action="{{ route('public.form.update_certificate_name', [$form->slug, $res->response_code]) }}" class="space-y-2">
                                    @csrf
                                    <label class="block text-xs font-bold text-slate-800">Perbaiki Nama & Gelar:</label>
                                    <input type="text" name="participant_name" x-model="currentName" required class="w-full text-xs p-2 rounded-lg border border-slate-300 bg-white">
                                    <div class="flex items-center gap-2 justify-end pt-1">
                                        <button type="button" @click="editName = false" class="px-3 py-1.5 rounded-lg border border-slate-300 text-slate-600 text-xs font-medium cursor-pointer">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-navy text-white text-xs font-bold cursor-pointer">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Download or Lock Button -->
                        <div class="pt-2">
                            @if($form->isCertificateReleased())
                                <a href="{{ route('public.form.view_certificate', [$form->slug, $res->response_code]) }}" target="_blank"
                                   class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-200 transition flex items-center justify-center gap-2 cursor-pointer">
                                    <span>📜 Buka & Unduh Sertifikat Resmi (PDF)</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @else
                                <div class="w-full py-3 px-4 rounded-2xl bg-slate-200 text-slate-500 font-bold text-xs sm:text-sm text-center flex items-center justify-center gap-2 cursor-not-allowed">
                                    <span>🔒 Sertifikat Belum Dapat Diunduh (Acara Berlangsung)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200 text-center space-y-3">
                        <span class="text-3xl block">🔍</span>
                        <h3 class="font-serif font-bold text-slate-800 text-base">Data Tidak Ditemukan</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                            Pastikan Anda memasukkan nomor WhatsApp atau nama yang sama persis seperti yang Anda daftarkan pada formulir kegiatan ini.
                        </p>
                    </div>
                @endforelse
            </div>
        @endif

        <!-- Bottom Navigation -->
        <div class="text-center pt-4">
            <a href="{{ route('public.form.show', $form->slug) }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-navy transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Halaman Formulir
            </a>
        </div>

    </div>
</div>
@endsection
