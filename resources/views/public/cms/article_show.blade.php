@extends('layouts.app')

@section('content')
<div class="bg-navy-900 text-white py-16 border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">{{ $article->category->name ?? 'Kajian Ruhiologi' }}</span>
        <h1 class="text-3xl sm:text-4xl font-bold font-serif leading-snug">{{ $article->title }}</h1>
        <div class="text-xs text-slate-300 flex items-center gap-4 pt-2 border-t border-slate-800">
            <span>✍️ {{ $article->author }}</span>
            <span>📅 {{ $article->published_at ? $article->published_at->format('d M Y') : 'Draft' }}</span>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-slate max-w-none text-slate-800 leading-relaxed text-sm">
            {!! $article->content !!}
        </article>
    </div>
</div>
@endsection
