@extends('layouts.app')

@section('content')
<div class="py-12 bg-slate-50 font-sans" x-data="{}">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Navigation Back Bar -->
        <div class="mb-6 flex items-center justify-between">
            <button type="button" onclick="window.history.back()" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#0B2A43] transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>← Batal & Kembali ke Halaman Sebelumnya</span>
            </button>
            <span class="text-xs font-medium text-slate-400">Katalog Ruhiology Institute</span>
        </div>

        <!-- Book Detail Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-md grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            
            <!-- Book Cover Showcase -->
            <div class="md:col-span-5 flex justify-center">
                <div class="w-full bg-gradient-to-b from-slate-50 to-slate-100/70 p-3 sm:p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-center">
                    @if($product->cover_image || $product->image)
                        <img src="{{ $product->cover_image ?? $product->image }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-auto max-h-[480px] object-contain rounded-xl shadow-md transition duration-300 hover:scale-[1.02]">
                    @else
                        <div class="w-full aspect-[3/4] bg-[#0B2A43] rounded-xl flex flex-col items-center justify-center text-white/80 p-6 text-center space-y-2 shadow-inner">
                            <span class="text-4xl block">📖</span>
                            <span class="font-serif font-bold text-sm block">{{ $product->title }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Book Specs & Description -->
            <div class="md:col-span-7 flex flex-col justify-between space-y-5">
                <div class="space-y-3">
                    <span class="inline-block px-3 py-1 rounded-full bg-[#0B2A43]/10 text-[#0B2A43] text-[11px] font-bold uppercase tracking-wider">
                        {{ $product->category->name ?? 'Literasi Ruhiologi' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold font-serif text-slate-900 leading-snug">
                        {{ $product->title }}
                    </h1>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs text-slate-600 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60 font-medium">
                        <div>Penulis: <strong class="text-slate-900 block font-semibold">{{ $product->author ?? 'Prof. Dr. Iskandar Nazari' }}</strong></div>
                        <div>Penerbit: <strong class="text-slate-900 block font-semibold">{{ $product->publisher ?? 'Ruhiology Press' }} ({{ $product->year ?? '2025' }})</strong></div>
                        <div>Format: <strong class="text-slate-900 block font-semibold">{{ $product->format ?? 'Softcover' }}</strong></div>
                        <div>ISBN: <strong class="text-slate-900 block font-semibold">{{ $product->isbn ?? '-' }}</strong></div>
                        <div>Halaman: <strong class="text-slate-900 block font-semibold">{{ $product->pages ?? '-' }} hlm.</strong></div>
                        <div>Stok: <strong class="text-emerald-700 block font-semibold">{{ $product->stock ?? 50 }} eksemplar</strong></div>
                    </div>

                    <div class="text-2xl font-black text-[#0B2A43] pt-1">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    <div class="prose prose-sm text-slate-700 leading-relaxed text-xs border-t border-slate-100 pt-3">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Action Buttons: Beli (Pop-up Modal) & Batal -->
                <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100">
                    <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-order-modal', { detail: { id: '{{ $product->id }}', title: '{{ addslashes($product->title) }}', price: '{{ number_format($product->price, 0, ',', '.') }}', stock: {{ $product->stock ?? 50 }} } }))"
                            @click="$dispatch('open-order-modal', { id: '{{ $product->id }}', title: '{{ addslashes($product->title) }}', price: '{{ number_format($product->price, 0, ',', '.') }}', stock: {{ $product->stock ?? 50 }} })"
                            class="px-6 py-3.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-extrabold text-xs rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Beli / Pesan Sekarang</span>
                    </button>

                    <button type="button"
                            onclick="window.history.back()"
                            class="px-5 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Batal (Kembali)</span>
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
