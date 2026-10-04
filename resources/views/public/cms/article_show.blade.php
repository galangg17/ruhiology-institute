@extends('layouts.app')

@php
    $imageUrl = null;
    if (!empty($article->featured_image)) {
        if (\Illuminate\Support\Str::startsWith($article->featured_image, ['http://', 'https://'])) {
            $imageUrl = $article->featured_image;
        } elseif (\Illuminate\Support\Str::startsWith($article->featured_image, ['/images', 'images/', '/storage', 'storage/'])) {
            $imageUrl = asset(ltrim($article->featured_image, '/'));
        } else {
            $imageUrl = asset('storage/' . $article->featured_image);
        }
    }
    
    // Estimate read time based on word count
    $wordCount = str_word_count(strip_tags($article->content ?? ''));
    $readTime = max(1, ceil($wordCount / 200));

    $shareDescription = !empty($article->excerpt) 
        ? $article->excerpt 
        : \Illuminate\Support\Str::limit(strip_tags($article->content ?? ''), 160);
    $shareImage = $imageUrl ?: asset('images/ruhiology-logo.png');
@endphp

@section('og_meta')
    <title>{{ $article->title }} — Ruhiology Institute</title>
    <meta name="description" content="{{ $shareDescription }}">
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $shareDescription }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="article:published_time" content="{{ $article->published_at ? $article->published_at->toIso8601String() : '' }}">
    <meta property="article:author" content="{{ $article->author ?? 'Tim Redaksi Ruhiology Institute' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $article->title }}">
    <meta name="twitter:description" content="{{ $shareDescription }}">
    <meta name="twitter:image" content="{{ $shareImage }}">
@endsection

@section('content')

<div x-data="{ showImageModal: false, copiedToast: false }">

    <!-- HERO HEADER (MIDNIGHT NAVY BRANDING) -->
    <div class="bg-[#0B2A43] text-white pt-10 pb-16 sm:pb-24 border-b border-[#C9A24D]/30 relative overflow-hidden">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#C9A24D]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#C9A24D]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">
            
            <!-- BREADCRUMBS -->
            <nav class="flex items-center gap-2 text-xs font-mono text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-[#C9A24D] transition flex items-center gap-1">
                    <span>🏠</span> <span>Beranda</span>
                </a>
                <span>/</span>
                @if(($article->type ?? '') === 'news')
                    <a href="{{ route('news.index') }}" class="hover:text-[#C9A24D] transition">Berita Institute</a>
                @else
                    <a href="{{ route('articles.index') }}" class="hover:text-[#C9A24D] transition">Kajian & Artikel</a>
                @endif
                <span>/</span>
                <span class="text-[#C9A24D] font-medium truncate max-w-[200px] sm:max-w-xs">{{ $article->title }}</span>
            </nav>

            <!-- CATEGORY / TYPE BADGE -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#C9A24D]/20 text-[#C9A24D] border border-[#C9A24D]/40 text-xs font-mono font-bold uppercase tracking-wider">
                    @if(($article->type ?? '') === 'news')
                        <span>📰</span> <span>Berita & Kabar Institute</span>
                    @else
                        <span>📚</span> <span>{{ $article->category->name ?? 'Kajian Ruhiologi' }}</span>
                    @endif
                </span>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-800/80 text-slate-300 border border-slate-700 text-xs font-mono">
                    <span>⏱️</span> <span>{{ $readTime }} Min Baca</span>
                </span>
            </div>

            <!-- TITLE -->
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-bold font-serif leading-tight text-white tracking-tight">
                {{ $article->title }}
            </h1>

            <!-- META INFORMATION BAR -->
            <div class="pt-4 border-t border-slate-700/80 flex flex-wrap items-center justify-between gap-4 text-xs font-sans text-slate-300">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#C9A24D] text-[#0B2A43] font-bold flex items-center justify-center font-serif text-sm shadow-md">
                        {{ strtoupper(substr($article->author ?? 'R', 0, 1)) }}
                    </div>
                    <div>
                        <strong class="text-white block font-bold text-sm">{{ $article->author ?? 'Tim Redaksi Ruhiology Institute' }}</strong>
                        <span class="text-slate-400 text-[11px]">Penulis & Peneliti Ruhiologi</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-slate-300 font-mono text-[11px] sm:text-xs">
                    <span class="flex items-center gap-1.5 bg-slate-800/90 px-3 py-1.5 rounded-xl border border-slate-700">
                        <span>📅</span>
                        <span>{{ $article->published_at ? $article->published_at->format('d M Y · H:i') . ' WIB' : 'Draft' }}</span>
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- MAIN BODY CONTENT AREA WITH WARM CREAM BACKGROUND -->
    <div class="bg-[#F8F6F0] py-10 sm:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- FEATURED HERO IMAGE CONTAINER (IF IMAGE UPLOADED) -->
            @if($imageUrl)
                <div class="bg-white rounded-3xl p-3 border border-slate-200 shadow-xl overflow-hidden group relative">
                    <div class="relative overflow-hidden rounded-2xl cursor-pointer bg-slate-900" @click="showImageModal = true">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $article->title }}" 
                             class="w-full h-auto max-h-[520px] object-cover group-hover:scale-105 transition duration-500">
                        
                        <!-- Hover Overlay badge -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2 text-white font-bold text-xs">
                            <span class="bg-[#0B2A43]/90 px-4 py-2 rounded-xl border border-[#C9A24D]/40 shadow-lg flex items-center gap-2">
                                <span>🔍</span> <span>Klik Untuk Memperbesar Gambar</span>
                            </span>
                        </div>
                    </div>
                    <div class="mt-2.5 px-2 flex justify-between items-center text-[11px] text-slate-500 font-sans italic">
                        <span>📸 Gambar Utama Artikel / Berita</span>
                        <span class="text-slate-400 font-mono">Ruhiology Institute Official</span>
                    </div>
                </div>
            @else
                <!-- FALLBACK ELEGANT BANNER IF NO CUSTOM IMAGE UPLOADED -->
                <div class="bg-gradient-to-r from-[#0B2A43] via-[#123B59] to-[#0B2A43] p-8 sm:p-12 rounded-3xl border border-[#C9A24D]/30 shadow-xl text-center space-y-4 text-white relative overflow-hidden">
                    <div class="w-16 h-16 mx-auto bg-[#C9A24D]/20 border border-[#C9A24D]/50 rounded-2xl flex items-center justify-center text-2xl shadow-inner text-[#C9A24D]">
                        📖
                    </div>
                    <div class="space-y-1">
                        <span class="text-[11px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">RUHIOLOGY INSTITUTE PUBLICATION</span>
                        <h3 class="font-serif text-xl sm:text-2xl font-bold text-white max-w-xl mx-auto">{{ $article->title }}</h3>
                    </div>
                </div>
            @endif

            <!-- ARTICLE CONTENT CONTAINER CARD -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-md space-y-8 font-sans">
                
                <!-- EXCERPT CALLOUT BOX (IF AVAILABLE) -->
                @if($article->excerpt)
                    <div class="p-5 bg-amber-50/80 rounded-2xl border-l-4 border-[#C9A24D] text-slate-800 space-y-1 shadow-2xs">
                        <span class="text-[10px] font-mono font-bold text-amber-900 uppercase tracking-widest block">RINGKASAN EKSEKUTIF</span>
                        <p class="font-serif text-sm sm:text-base leading-relaxed italic text-slate-700">
                            "{{ $article->excerpt }}"
                        </p>
                    </div>
                @endif

                <!-- RICH ARTICLE BODY CONTENT -->
                <article class="prose prose-slate max-w-none text-slate-800 text-sm sm:text-base leading-relaxed space-y-4 font-normal">
                    {!! nl2br($article->content) !!}
                </article>

                <!-- SHARE & INTERACTION FOOTER BAR -->
                <div class="pt-6 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 font-mono">BAGIKAN ARTIKEL:</span>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . request()->fullUrl()) }}" 
                           target="_blank" 
                           class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                            <span>💬</span> <span>WhatsApp</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(request()->fullUrl()) }}" 
                           target="_blank" 
                           class="px-3.5 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-300 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                            <span>🐦</span> <span>Twitter / X</span>
                        </a>
                        <button @click="navigator.clipboard.writeText(window.location.href); copiedToast = true; setTimeout(() => copiedToast = false, 3000)" 
                                class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                            <span>🔗</span> <span x-text="copiedToast ? 'Tersalin! ✅' : 'Salin Link'"></span>
                        </button>
                    </div>

                    <div>
                        @if(($article->type ?? '') === 'news')
                            <a href="{{ route('news.index') }}" class="text-xs font-bold text-[#0B2A43] hover:text-[#C9A24D] transition flex items-center gap-1">
                                <span>← Kembali ke Semua Berita</span>
                            </a>
                        @else
                            <a href="{{ route('articles.index') }}" class="text-xs font-bold text-[#0B2A43] hover:text-[#C9A24D] transition flex items-center gap-1">
                                <span>← Kembali ke Semua Artikel</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- EDITORIAL AUTHOR BIOGRAPHY CARD -->
                <div class="p-6 rounded-2xl bg-gradient-to-r from-slate-50 via-[#F8F6F0] to-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#0B2A43] text-[#C9A24D] font-serif font-bold text-xl flex items-center justify-center shrink-0 border-2 border-[#C9A24D]/40 shadow-sm">
                        🏛️
                    </div>
                    <div class="space-y-1 text-center sm:text-left">
                        <strong class="text-sm font-bold text-[#0B2A43] block">Ruhiology Institute Media & Publications</strong>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Dipublikasikan oleh Pusat Riset & Media Ruhiology Institute. Seluruh isi tulisan ditujukan untuk pengembangan sains psikologi ruhiologi, kesadaran spiritual, serta peningkatan mutu pendidikan dan karakter bangsa.
                        </p>
                    </div>
                </div>

            </div>

            <!-- RELATED ARTICLES & NEWS SECTION -->
            @if(isset($recentArticles) && $recentArticles->count() > 0)
                <div class="pt-8 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-300/80 pb-3">
                        <h3 class="font-serif font-bold text-xl text-[#0B2A43] flex items-center gap-2">
                            <span>📰</span> <span>Rekomendasi Berita & Kajian Terkait</span>
                        </h3>
                        @if(($article->type ?? '') === 'news')
                            <a href="{{ route('news.index') }}" class="text-xs font-bold text-[#C9A24D] hover:underline">Lihat Semua →</a>
                        @else
                            <a href="{{ route('articles.index') }}" class="text-xs font-bold text-[#C9A24D] hover:underline">Lihat Semua →</a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($recentArticles as $rec)
                            @php
                                $recImage = null;
                                if (!empty($rec->featured_image)) {
                                    if (\Illuminate\Support\Str::startsWith($rec->featured_image, ['http://', 'https://'])) {
                                        $recImage = $rec->featured_image;
                                    } elseif (\Illuminate\Support\Str::startsWith($rec->featured_image, ['/images', 'images/', '/storage', 'storage/'])) {
                                        $recImage = asset(ltrim($rec->featured_image, '/'));
                                    } else {
                                        $recImage = asset('storage/' . $rec->featured_image);
                                    }
                                }
                            @endphp
                            <a href="{{ $rec->type === 'news' ? route('news.show', $rec->slug) : route('articles.show', $rec->slug) }}" 
                               class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                                @if($recImage)
                                    <div class="h-36 overflow-hidden bg-slate-100 relative">
                                        <img src="{{ $recImage }}" alt="{{ $rec->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        <span class="absolute top-2 left-2 bg-[#0B2A43]/90 text-white font-mono font-bold text-[9px] px-2 py-0.5 rounded uppercase">
                                            {{ $rec->type === 'news' ? 'Berita' : ($rec->category->name ?? 'Kajian') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="h-36 bg-[#0B2A43] p-4 flex items-center justify-center text-center relative overflow-hidden">
                                        <span class="text-[#C9A24D] font-mono font-bold text-[10px] uppercase tracking-widest">Ruhiology Institute</span>
                                    </div>
                                @endif

                                <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h4 class="font-bold font-serif text-slate-900 text-sm leading-snug group-hover:text-[#C9A24D] transition line-clamp-2">
                                            {{ $rec->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                            {{ $rec->excerpt }}
                                        </p>
                                    </div>
                                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-[10px] text-slate-400 font-mono">
                                        <span>📅 {{ $rec->published_at ? $rec->published_at->format('d M Y') : '-' }}</span>
                                        <span class="text-[#0B2A43] font-bold group-hover:translate-x-1 transition">Baca →</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- FULLSCREEN HIGH-RES IMAGE MODAL -->
    @if($imageUrl)
        <div x-show="showImageModal" 
             x-cloak
             class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4 sm:p-8"
             @keydown.escape.window="showImageModal = false">
            <div class="relative max-w-5xl w-full max-h-full flex flex-col items-center">
                <button @click="showImageModal = false" 
                        class="absolute -top-12 right-0 text-white hover:text-[#C9A24D] font-bold text-sm bg-slate-800/80 px-4 py-2 rounded-xl border border-slate-700 transition">
                    ✕ Tutup Pratinjau
                </button>
                <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="max-h-[85vh] w-auto object-contain rounded-2xl shadow-2xl border border-slate-700">
                <p class="text-slate-300 text-xs mt-3 text-center font-sans font-medium">
                    {{ $article->title }} — Ruhiology Institute Media
                </p>
            </div>
        </div>
    @endif

</div>
@endsection
