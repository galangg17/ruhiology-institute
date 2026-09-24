@extends('layouts.app')

@section('content')
<div class="bg-navy-900 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Kabar Institusi</span>
        <h1 class="text-4xl font-bold font-serif">Berita & Agenda Ruhiology Institute</h1>
    </div>
</div>

<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($newsList as $n)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] text-amber-600 font-bold uppercase tracking-wider block mb-2">Berita Utama</span>
                        <h2 class="font-bold font-serif text-slate-900 text-base mb-2 leading-snug">
                            <a href="{{ route('news.show', $n->slug) }}">{{ $n->title }}</a>
                        </h2>
                        <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed">{{ $n->excerpt }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-400">
                        📅 {{ $n->published_at ? $n->published_at->format('d M Y') : '-' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
