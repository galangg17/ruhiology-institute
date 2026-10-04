@extends('layouts.app')

@section('content')
<!-- HERO HEADER -->
<div class="bg-[#0B2A43] text-white py-16 sm:py-20 border-b border-[#C9A24D]/30 relative overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#C9A24D]/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 relative z-10">
        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#C9A24D]/20 text-[#C9A24D] border border-[#C9A24D]/40 text-xs font-mono font-bold uppercase tracking-wider">
            ✦ KAJIAN & ARTIKEL PUBLIK
        </span>
        <h1 class="text-3xl sm:text-5xl font-bold font-serif tracking-tight text-white">Artikel & Pemikiran Ruhiologi</h1>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto font-light leading-relaxed">
            Gagasan, analisis ilmiah, dan panduan praktis pengembangan kesadaran ruhiologi bagi individu dan institusi.
        </p>
    </div>
</div>

<!-- ARTICLES GRID -->
<div class="py-12 sm:py-20 bg-[#F8F6F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            @foreach($articles as $art)
                @php
                    $artImage = null;
                    if (!empty($art->featured_image)) {
                        if (\Illuminate\Support\Str::startsWith($art->featured_image, ['http://', 'https://'])) {
                            $artImage = $art->featured_image;
                        } elseif (\Illuminate\Support\Str::startsWith($art->featured_image, ['/images', 'images/', '/storage', 'storage/'])) {
                            $artImage = asset(ltrim($art->featured_image, '/'));
                        } else {
                            $artImage = asset('storage/' . $art->featured_image);
                        }
                    }
                @endphp
                <article class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition group">
                    @if($artImage)
                        <div class="h-48 overflow-hidden bg-slate-100 relative">
                            <img src="{{ $artImage }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-3 left-3 bg-[#0B2A43]/90 text-white font-mono font-bold text-[10px] px-2.5 py-1 rounded-lg border border-[#C9A24D]/40 uppercase tracking-wider">
                                {{ $art->category->name ?? 'Kajian Teori' }}
                            </span>
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-r from-[#0B2A43] to-[#123B59] p-6 flex flex-col justify-between relative overflow-hidden">
                            <span class="text-[#C9A24D] font-mono font-bold text-[10px] uppercase tracking-widest block">RUHIOLOGY INSTITUTE</span>
                            <span class="text-xs text-slate-300 font-serif italic line-clamp-2">"{{ $art->excerpt ?? $art->title }}"</span>
                        </div>
                    @endif

                    <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] text-[#C9A24D] font-mono font-bold uppercase tracking-wider block mb-1">
                                {{ $art->category->name ?? 'Kajian Ruhiologi' }}
                            </span>
                            <h2 class="font-bold font-serif text-slate-900 text-base mb-2 leading-snug group-hover:text-[#C9A24D] transition">
                                <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                            </h2>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ $art->excerpt }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-[11px] text-slate-400 font-mono">
                            <div class="flex items-center gap-2">
                                <span>✍️ {{ $art->author }}</span>
                                <span>·</span>
                                <span class="text-amber-800 font-bold">👁️ {{ number_format($art->views_count ?? 0, 0, ',', '.') }} dibaca</span>
                            </div>
                            <a href="{{ route('articles.show', $art->slug) }}" class="text-[#0B2A43] font-bold group-hover:text-[#C9A24D] flex items-center gap-1 transition">
                                <span>Baca</span> <span>→</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
