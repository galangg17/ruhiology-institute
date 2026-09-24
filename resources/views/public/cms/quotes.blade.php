@extends('layouts.app')

@section('content')
<div class="bg-navy-900 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Inspirasi & Mutiara Hikmah</span>
        <h1 class="text-4xl font-bold font-serif">Kutipan Ruhiologi</h1>
    </div>
</div>

<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quotes as $q)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <p class="text-xs text-slate-800 font-serif italic leading-relaxed mb-4">"{{ $q->quote }}"</p>
                    <div class="border-t border-slate-100 pt-3">
                        <span class="font-bold text-amber-700 text-xs block">— {{ $q->author }}</span>
                        @if($q->source)
                            <span class="text-[10px] text-slate-400 block font-light">{{ $q->source }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
