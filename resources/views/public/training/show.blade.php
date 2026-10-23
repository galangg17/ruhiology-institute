@extends('layouts.app')

@section('title', $training->title . ' — Ruhiology Training Center')

@section('content')
<!-- Header Banner -->
<div class="bg-navy text-white py-12 md:py-16 border-b-4 border-gold relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#C9A24D_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 space-y-4">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
            <a href="{{ route('training.index') }}" class="hover:text-gold transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Katalog Pelatihan
            </a>
            <span>/</span>
            <span class="text-slate-300">{{ $training->category }}</span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gold/20 text-gold border border-gold/30 uppercase tracking-wider">
                {{ $training->category }}
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-white border border-white/20">
                {{ $training->is_online ? '🌐 Format Online (Zoom)' : '🏛️ Tatap Muka (' . $training->location . ')' }}
            </span>
        </div>

        <h1 class="text-2xl sm:text-4xl md:text-5xl font-serif font-bold text-white tracking-tight leading-snug">
            {{ $training->title }}
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-xs md:text-sm text-slate-300 pt-2">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>Trainer: <strong class="text-white">{{ $training->trainer }}</strong></span>
            </div>
            <span class="text-slate-500">&bull;</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Durasi: <strong class="text-white">{{ $training->duration }}</strong></span>
            </div>
            <span class="text-slate-500">&bull;</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Kuota: <strong class="text-white">{{ $training->quota }} Peserta / Batch</strong></span>
            </div>
        </div>
    </div>
</div>

<!-- Main Details & Registration Section -->
<div class="py-12 md:py-16 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs md:text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Detailed Content -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Cover Image -->
                @if($training->image)
                    <div class="bg-white p-2 rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <img src="{{ $training->image }}" alt="{{ $training->title }}" class="w-full h-auto max-h-[440px] object-cover rounded-2xl">
                    </div>
                @endif

                <!-- Program Description Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <h2 class="text-xl md:text-2xl font-serif font-bold text-navy flex items-center gap-2">
                        <span class="w-2.5 h-6 rounded-full bg-gold inline-block"></span>
                        Deskripsi & Pengantar Program
                    </h2>
                    <div class="text-xs md:text-sm text-slate-700 leading-relaxed space-y-3 whitespace-pre-line">
                        {{ $training->description }}
                    </div>
                </div>

                <!-- Target Audience -->
                @if(!empty($training->target_audience))
                    <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                        <h3 class="text-base font-bold font-serif text-navy flex items-center gap-2">
                            <span>🎯</span>
                            Sasaran & Target Peserta
                        </h3>
                        <p class="text-xs md:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $training->target_audience }}
                        </p>
                    </div>
                @endif

                <!-- Syllabus / Curriculum Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-lg md:text-xl font-serif font-bold text-navy flex items-center gap-2">
                        <span class="w-2.5 h-6 rounded-full bg-primary-600 inline-block"></span>
                        Silabus & Materi Pelatihan
                    </h3>
                    
                    @php
                        $syllabusList = $training->parsed_syllabus;
                    @endphp

                    @if(count($syllabusList) > 0)
                        <div class="space-y-3">
                            @foreach($syllabusList as $idx => $item)
                                <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                                    <div class="w-6 h-6 rounded-full bg-navy text-gold text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                        {{ $idx + 1 }}
                                    </div>
                                    <div class="text-xs md:text-sm text-slate-800 font-medium">
                                        {{ $item }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="space-y-2 text-xs md:text-sm text-slate-600">
                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-gold font-bold">✓</span>
                                <span>Fondasi Teoretis & Konsep Utama Kecerdasan Ruhiologi (Ruhiology Intelligence).</span>
                            </div>
                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-gold font-bold">✓</span>
                                <span>Metodologi Asesmen, Skoring Dimensi, dan Interpretasi Profil Diri.</span>
                            </div>
                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-gold font-bold">✓</span>
                                <span>Praktik Studi Kasus Konseling, Fasilitasi Kelompok, dan Coaching Karakter.</span>
                            </div>
                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-gold font-bold">✓</span>
                                <span>Evaluasi Akhir, Standar Etika Profesi, dan Prosedur Penerbitan Lisensi/Sertifikat.</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Facilities & Benefits Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-lg md:text-xl font-serif font-bold text-navy flex items-center gap-2">
                        <span class="w-2.5 h-6 rounded-full bg-emerald-600 inline-block"></span>
                        Fasilitas & Benefit Peserta
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($training->parsed_facilities as $fac)
                            <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-emerald-50/40 border border-emerald-100 text-xs md:text-sm text-slate-700">
                                <span class="text-emerald-600 font-bold text-base shrink-0">✓</span>
                                <span>{{ $fac }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Trainer Profile Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-lg font-serif font-bold text-navy flex items-center gap-2">
                        <span>👨‍🏫</span>
                        Fasilitator & Trainer
                    </h3>
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-navy text-gold flex items-center justify-center font-bold text-xl shrink-0 border border-gold/30">
                            {{ substr($training->trainer, 0, 1) }}
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-base">{{ $training->trainer }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ $training->trainer_bio ?? 'Pakar Kecerdasan Ruhiologi dan Trainer Utama di Ruhiology Institute dengan pengalaman memfasilitasi ribuan peserta dari berbagai perguruan tinggi, lembaga pendidikan, dan instansi nasional.' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Sticky Registration Form -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-xl overflow-hidden sticky top-24">
                    
                    <!-- Pricing Header -->
                    <div class="bg-navy p-6 text-white border-b-2 border-gold">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-gold block mb-1">Investasi Pelatihan</span>
                        <div class="flex items-baseline gap-2">
                            @if($training->price > 0)
                                <span class="text-3xl font-extrabold text-white">Rp {{ number_format($training->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-300">/ orang</span>
                            @else
                                <span class="text-3xl font-extrabold text-emerald-400">GRATIS</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 md:p-7 space-y-5">
                        <h3 class="font-serif font-bold text-slate-800 text-base">Pendaftaran Peserta</h3>

                        @if($openBatches->isEmpty())
                            <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-center space-y-2">
                                <span class="text-2xl block">⏳</span>
                                <h4 class="font-bold text-amber-800 text-sm">Pendaftaran Belum Dibuka</h4>
                                <p class="text-xs text-amber-700 leading-relaxed">
                                    Saat ini belum ada jadwal angkatan baru yang dibuka untuk program ini. Silakan hubungi admin Ruhiology untuk informasi batch berikutnya.
                                </p>
                                @if($training->contact_person)
                                    <div class="pt-2">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $training->contact_person) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition">
                                            Tanya Jadwal via WhatsApp
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('training.register', $training->slug) }}" method="POST" class="space-y-4">
                                @csrf

                                <!-- Batch Selector -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Pilih Jadwal / Angkatan <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="space-y-2">
                                        @foreach($openBatches as $batch)
                                            <label class="flex items-start gap-3 p-3 rounded-2xl border border-slate-200 hover:border-navy cursor-pointer transition has-[:checked]:border-navy has-[:checked]:bg-primary-50/40">
                                                <input type="radio" name="batch_id" value="{{ $batch->id }}" 
                                                       {{ old('batch_id') == $batch->id || $loop->first ? 'checked' : '' }}
                                                       class="mt-1 w-4 h-4 text-navy focus:ring-navy border-slate-300">
                                                <div class="flex-1 text-xs">
                                                    <div class="font-bold text-slate-800">{{ $batch->batch_name }}</div>
                                                    <div class="text-slate-500 mt-0.5">
                                                        📅 {{ $batch->start_date ? $batch->start_date->format('d M Y') : '-' }} s/d {{ $batch->end_date ? $batch->end_date->format('d M Y') : '-' }}
                                                    </div>
                                                    <div class="text-[11px] text-emerald-600 font-semibold mt-1">
                                                        Sisa kuota: {{ $batch->remaining_quota }} dari {{ $batch->quota }} kursi
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('batch_id')
                                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Participant Name -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nama Lengkap & Gelar (untuk Sertifikat) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="participant_name" value="{{ old('participant_name', auth()->user()?->name) }}" required
                                           placeholder="Contoh: Dr. Fulan, M.Pd"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-navy">
                                    @error('participant_name')
                                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Alamat Email Aktif <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                                           placeholder="nama@email.com"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-navy">
                                    @error('email')
                                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- WhatsApp / Phone -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nomor WhatsApp / HP Aktif <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required
                                           placeholder="081234567890"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-navy">
                                    @error('phone')
                                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Institution -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Asal Institusi / Universitas / Lembaga
                                    </label>
                                    <input type="text" name="institution" value="{{ old('institution') }}"
                                           placeholder="Contoh: Universitas Indonesia / Mandiri"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-navy">
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-2">
                                    <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-navy hover:bg-navy-dark text-white font-bold text-xs md:text-sm shadow-lg shadow-navy/20 transition flex items-center justify-center gap-2">
                                        <span>Daftar Sekarang & Buat Tagihan</span>
                                        <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                </div>
                            </form>
                        @endif

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-center gap-2 text-[11px] text-slate-400">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>Pendaftaran Resmi Ruhiology Institute Terenkripsi</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
