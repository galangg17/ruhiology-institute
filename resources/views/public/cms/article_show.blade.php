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
                <a href="{{ route('home') }}" class="hover:text-[#C9A24D] transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
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
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span>Berita & Kabar Institute</span>
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>{{ $article->category->name ?? 'Kajian Ruhiologi' }}</span>
                    @endif
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 text-slate-300 border border-slate-700 text-xs font-mono">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $readTime }} Min Baca</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9A24D]/10 text-[#C9A24D] border border-[#C9A24D]/30 text-xs font-mono font-bold">
                    <svg class="w-3.5 h-3.5 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>{{ number_format($article->views_count ?? 0, 0, ',', '.') }} Pembaca</span>
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

                <div class="flex items-center gap-3 text-slate-300 font-mono text-[11px] sm:text-xs">
                    <span class="flex items-center gap-1.5 bg-slate-800/90 px-3 py-1.5 rounded-xl border border-slate-700">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ $article->published_at ? $article->published_at->format('d M Y · H:i') . ' WIB' : 'Draft' }}</span>
                    </span>
                    <span class="flex items-center gap-1.5 bg-slate-800/90 px-3 py-1.5 rounded-xl border border-slate-700 text-[#C9A24D] font-bold">
                        <svg class="w-3.5 h-3.5 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>{{ number_format($article->views_count ?? 0, 0, ',', '.') }} Pembaca</span>
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
                                <svg class="w-4 h-4 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                <span>Klik Untuk Memperbesar Gambar</span>
                            </span>
                        </div>
                    </div>
                    <div class="mt-2.5 px-2 flex justify-between items-center text-[11px] text-slate-500 font-sans italic">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Gambar Utama Artikel / Berita</span>
                        </span>
                        <span class="text-slate-400 font-mono">Ruhiology Institute Official</span>
                    </div>
                </div>
            @else
                <!-- FALLBACK ELEGANT BANNER IF NO CUSTOM IMAGE UPLOADED -->
                <div class="bg-gradient-to-r from-[#0B2A43] via-[#123B59] to-[#0B2A43] p-8 sm:p-12 rounded-3xl border border-[#C9A24D]/30 shadow-xl text-center space-y-4 text-white relative overflow-hidden">
                    <div class="w-16 h-16 mx-auto bg-[#C9A24D]/20 border border-[#C9A24D]/50 rounded-2xl flex items-center justify-center text-2xl shadow-inner text-[#C9A24D]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
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
                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.99.54 1.77.829 2.796.831 3.185 0 5.77-2.587 5.77-5.768.001-3.182-2.584-5.816-5.77-5.816z"/></svg>
                            <span>WhatsApp</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(request()->fullUrl()) }}" 
                           target="_blank" 
                           class="px-3.5 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-300 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-sky-600" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span>Twitter / X</span>
                        </a>
                        <button @click="navigator.clipboard.writeText(window.location.href); copiedToast = true; setTimeout(() => copiedToast = false, 3000)" 
                                class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span x-text="copiedToast ? 'Tersalin! ✓' : 'Salin Link'"></span>
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
                <div class="p-6 rounded-2xl bg-gradient-to-r from-slate-50 via-[#FAF9F6] to-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#0B2A43] text-[#C9A24D] font-serif font-bold text-xl flex items-center justify-center shrink-0 border-2 border-[#C9A24D]/40 shadow-sm">
                        <svg class="w-7 h-7 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
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
                            <svg class="w-5 h-5 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            <span>Rekomendasi Berita & Kajian Terkait</span>
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
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>{{ $rec->published_at ? $rec->published_at->format('d M Y') : '-' }}</span>
                                        </span>
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
