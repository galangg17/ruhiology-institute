@extends('layouts.app')

@section('content')
<!-- HERO HEADER -->
<div class="bg-[#0B2A43] text-white py-16 sm:py-20 border-b border-[#C9A24D]/30 relative overflow-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#C9A24D]/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 relative z-10">
        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#C9A24D]/20 text-[#C9A24D] border border-[#C9A24D]/40 text-xs font-mono font-bold uppercase tracking-wider">
            <svg class="w-3.5 h-3.5 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span>KABAR INSTITUSI</span>
        </span>
        <h1 class="text-3xl sm:text-5xl font-bold font-serif tracking-tight text-white">Berita & Agenda Ruhiology Institute</h1>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto font-light leading-relaxed">
            Informasi kegiatan terbaru, liputan asesmen, rilis hasil riset, dan agenda resmi dari Ruhiology Institute.
        </p>
    </div>
</div>

<!-- NEWS LIST GRID -->
<div class="py-12 sm:py-20 bg-[#FAF9F6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            @foreach($newsList as $n)
                @php
                    $nImage = null;
                    if (!empty($n->featured_image)) {
                        if (\Illuminate\Support\Str::startsWith($n->featured_image, ['http://', 'https://'])) {
                            $nImage = $n->featured_image;
                        } elseif (\Illuminate\Support\Str::startsWith($n->featured_image, ['/images', 'images/', '/storage', 'storage/'])) {
                            $nImage = asset(ltrim($n->featured_image, '/'));
                        } else {
                            $nImage = asset('storage/' . $n->featured_image);
                        }
                    }
                @endphp
                <article class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition group">
                    @if($nImage)
                        <div class="h-48 overflow-hidden bg-slate-100 relative">
                            <img src="{{ $nImage }}" alt="{{ $n->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-3 left-3 bg-[#0B2A43]/90 text-white font-mono font-bold text-[10px] px-2.5 py-1 rounded-lg border border-[#C9A24D]/40 uppercase tracking-wider">
                                Berita Utama
                            </span>
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-r from-[#0B2A43] to-[#123B59] p-6 flex flex-col justify-between relative overflow-hidden">
                            <span class="text-[#C9A24D] font-mono font-bold text-[10px] uppercase tracking-widest block">RUHIOLOGY NEWS</span>
                            <span class="text-xs text-slate-300 font-serif italic line-clamp-2">"{{ $n->excerpt ?? $n->title }}"</span>
                        </div>
                    @endif

                    <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] text-[#C9A24D] font-mono font-bold uppercase tracking-wider mb-1 inline-flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $n->published_at ? $n->published_at->format('d M Y · H:i') . ' WIB' : 'Draft' }}</span>
                            </span>
                            <h2 class="font-bold font-serif text-slate-900 text-base mb-2 leading-snug group-hover:text-[#C9A24D] transition">
                                <a href="{{ route('news.show', $n->slug) }}">{{ $n->title }}</a>
                            </h2>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ $n->excerpt }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-[11px] text-slate-400 font-mono">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-slate-600">
                                    <svg class="w-3 h-3 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>{{ $n->author }}</span>
                                </span>
                                <span>·</span>
                                <span class="text-amber-800 font-bold inline-flex items-center gap-1">
                                    <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>{{ number_format($n->views_count ?? 0, 0, ',', '.') }} dibaca</span>
                                </span>
                            </div>
                            <a href="{{ route('news.show', $n->slug) }}" class="text-[#0B2A43] font-bold group-hover:text-[#C9A24D] flex items-center gap-1 transition">
                                <span>Baca</span> <span>→</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $newsList->links() }}
        </div>
    </div>
</div>
@endsection
