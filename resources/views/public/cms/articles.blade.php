@extends('layouts.app')

@section('content')
<div class="bg-forest-night text-white py-20 border-b border-forest-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20 mix-blend-overlay bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop');"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 relative z-10">
        <span class="inline-block px-4 py-1.5 rounded-full bg-forest-800 border border-emerald-500/30 text-emerald-300 text-xs font-semibold">
            ✦ Kajian & Artikel Publik
        </span>
        <h1 class="text-3xl sm:text-5xl font-bold font-sans tracking-tight text-white">Artikel & Pemikiran Ruhiologi</h1>
        <p class="text-slate-300 text-sm max-w-2xl mx-auto font-light leading-relaxed">
            Gagasan, analisis ilmiah, dan panduan praktis pengembangan kesadaran ruhiologi bagi individu dan institusi.
        </p>
    </div>
</div>

<div class="py-20 bg-[#FAFBFB]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($articles as $art)
                <article class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <span class="text-[10px] text-forest-800 font-bold uppercase tracking-wider block mb-2">{{ $art->category->name ?? 'Kajian Teori' }}</span>
                        <h2 class="font-bold font-sans text-slate-900 text-base mb-3 leading-snug hover:text-emerald-700">
                            <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                        </h2>
                        <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed">{{ $art->excerpt }}</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-[11px] text-slate-400">
                        <span>Penulis: {{ $art->author }}</span>
                        <a href="{{ route('articles.show', $art->slug) }}" class="text-forest-900 font-bold hover:underline">Baca Selengkapnya →</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
