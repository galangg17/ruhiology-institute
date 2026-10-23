@extends('layouts.app')

@section('title', 'Katalog Pelatihan & Sertifikasi — Ruhiology Training Center')

@section('content')
<!-- Hero Section -->
<div class="relative bg-navy text-white py-16 md:py-24 overflow-hidden border-b-4 border-gold">
    <!-- Subtle Background Elements -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#C9A24D_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-gold/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-primary-600/20 blur-3xl pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 text-center space-y-4">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gold/20 border border-gold/40 text-gold text-xs font-bold uppercase tracking-widest">
            <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
            Ruhiology Training & Certification Center
        </span>

        <h1 class="text-3xl sm:text-5xl font-serif font-bold text-white tracking-tight leading-tight">
            Tingkatkan Kapasitas & Kecerdasan Ruhiologi Anda
        </h1>

        <p class="text-slate-300 text-xs sm:text-base max-w-2xl mx-auto font-light leading-relaxed">
            Program pelatihan profesional, workshop eksekutif, dan sertifikasi fasilitator resmi Ruhiology Institute untuk pendidik, profesional, konselor, dan pemimpin lembaga.
        </p>

        <!-- Search & Filter Bar -->
        <div class="pt-6 max-w-2xl mx-auto">
            <form action="{{ route('training.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 bg-white/10 p-2 rounded-2xl backdrop-blur-md border border-white/20 shadow-xl">
                <div class="relative w-full flex-1">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama topik, trainer, atau sertifikasi..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white text-slate-800 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-gold border-0 placeholder-slate-400">
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gold hover:bg-gold-dark text-navy font-bold text-xs sm:text-sm transition shadow-md">
                        Cari
                    </button>
                    @if(request('q') || request('category') || request('mode'))
                        <a href="{{ route('training.index') }}" class="px-3 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-medium transition text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="py-12 md:py-16 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Filter Controls (Pills) -->
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <!-- Mode Filters -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('training.index', array_merge(request()->query(), ['mode' => null])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ !request('mode') ? 'bg-navy text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    Semua Format
                </a>
                <a href="{{ route('training.index', array_merge(request()->query(), ['mode' => 'online'])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('mode') === 'online' ? 'bg-navy text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    🌐 Online (Zoom)
                </a>
                <a href="{{ route('training.index', array_merge(request()->query(), ['mode' => 'offline'])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('mode') === 'offline' ? 'bg-navy text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    🏛️ Tatap Muka (Offline)
                </a>
            </div>

            <!-- Category Chips -->
            @if(count($categories) > 0)
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs text-slate-400 font-medium mr-1">Kategori:</span>
                    <a href="{{ route('training.index', array_merge(request()->query(), ['category' => null])) }}" 
                       class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('category') ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('training.index', array_merge(request()->query(), ['category' => $cat])) }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('category') === $cat ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Training Programs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($trainings as $t)
                @php
                    $openBatchesCount = $t->batches->filter(fn($b) => $b->isOpen())->count();
                    $earliestBatch = $t->batches->filter(fn($b) => $b->isOpen())->first();
                @endphp
                <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <!-- Cover Image & Badges -->
                        <div class="relative h-52 bg-slate-900 overflow-hidden">
                            @if($t->image)
                                <img src="{{ $t->image }}" alt="{{ $t->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-navy via-navy-dark to-slate-900 flex flex-col items-center justify-center text-center p-6 space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl text-gold">
                                        🎓
                                    </div>
                                    <span class="text-xs font-mono font-bold tracking-widest text-gold uppercase">RUHIOLOGY TRAINING</span>
                                </div>
                            @endif

                            <!-- Category Badge -->
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/95 text-navy shadow-sm backdrop-blur-md">
                                {{ $t->category }}
                            </span>

                            <!-- Format Badge -->
                            <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-[10px] font-bold bg-navy/90 text-white shadow-sm backdrop-blur-md flex items-center gap-1">
                                @if($t->is_online)
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Online
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Offline
                                @endif
                            </span>

                            @if($openBatchesCount > 0)
                                <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-600 text-white shadow-md flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    {{ $openBatchesCount }} Angkatan Terbuka
                                </span>
                            @else
                                <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-800/90 text-slate-200 shadow-md">
                                    Segera Dibuka
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-4">
                            <div>
                                <h3 class="font-serif font-bold text-navy text-lg leading-snug group-hover:text-primary-600 transition-colors">
                                    <a href="{{ route('training.show', $t->slug) }}">{{ $t->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-2 leading-relaxed">
                                    {{ $t->description }}
                                </p>
                            </div>

                            <!-- Meta Details -->
                            <div class="space-y-2 text-xs text-slate-600 pt-3 border-t border-slate-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="truncate"><strong>Trainer:</strong> {{ $t->trainer }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span><strong>Durasi:</strong> {{ $t->duration }}</span>
                                </div>
                                @if($earliestBatch)
                                    <div class="flex items-center gap-2 text-primary-700 font-medium">
                                        <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span><strong>Jadwal:</strong> {{ $earliestBatch->start_date ? $earliestBatch->start_date->format('d M Y') : '-' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-6 pt-0">
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Investasi</span>
                                <span class="text-base font-extrabold text-navy">
                                    @if($t->price > 0)
                                        Rp {{ number_format($t->price, 0, ',', '.') }}
                                    @else
                                        <span class="text-emerald-600">GRATIS</span>
                                    @endif
                                </span>
                            </div>
                            <a href="{{ route('training.show', $t->slug) }}" 
                               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-navy hover:bg-navy-dark text-white font-bold text-xs transition shadow-sm group-hover:bg-gold group-hover:text-navy">
                                <span>Detail Program</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="font-serif font-bold text-slate-700 text-lg">Tidak Ada Program yang Sesuai</h3>
                    <p class="text-xs text-slate-400 mt-1">Coba ubah filter format, kategori, atau kata kunci pencarian Anda.</p>
                    <div class="mt-4">
                        <a href="{{ route('training.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-navy text-white text-xs font-semibold">
                            Tampilkan Semua Program
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Value Proposition / Features Banner -->
        <div class="mt-16 bg-white rounded-3xl border border-slate-200 p-8 md:p-12 shadow-sm">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs uppercase font-bold text-gold tracking-widest block mb-1">Keunggulan Pelatihan</span>
                <h2 class="text-2xl md:text-3xl font-serif font-bold text-navy">Mengapa Mengikuti Pelatihan di Ruhiology?</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="p-4 space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-gold flex items-center justify-center mx-auto text-xl border border-amber-200">
                        📜
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Sertifikat Resmi Ber-SK</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Diterbitkan langsung oleh Ruhiology Institute dengan nomor registrasi unik terverifikasi.</p>
                </div>

                <div class="p-4 space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mx-auto text-xl border border-primary-200">
                        📚
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Modul & Toolkit Aplikatif</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Dilengkapi dengan instrumen asesmen, rubrik, dan panduan fasilitasi praktis.</p>
                </div>

                <div class="p-4 space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto text-xl border border-emerald-200">
                        🤝
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Komunitas Praktisi</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Akses eksklusif ke jaringan alumni fasilitator dan sesi bimbingan lanjutan secara berkala.</p>
                </div>

                <div class="p-4 space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto text-xl border border-purple-200">
                        🎥
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Akses Rekaman Seumur Hidup</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Rekaman sesi webinar, materi slide, dan update studi kasus dapat diakses kapan saja.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
