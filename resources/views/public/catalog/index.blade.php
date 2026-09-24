@extends('layouts.app')

@section('content')
<!-- Banner Header (Centered Aesthetic with Ruhiology Colors) -->
<div class="bg-[#0B2A43] text-white py-16 sm:py-20 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3 relative z-10">
        <span class="text-xs font-bold text-[#C9A24D] uppercase tracking-widest block font-mono">
            📚 Katalog Buku & Literatur RQ
        </span>
        <h1 class="font-serif text-3xl sm:text-5xl font-bold leading-tight tracking-tight">
            Buku & Monograf Ruhiology Institute
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed font-normal">
            Jelajahi karya monograf ilmiah, modul asesmen mandiri, dan buku panduan coaching karangan Prof. Dr. Iskandar Nazari dan Tim Peneliti Ruhiologi.
        </p>
    </div>
</div>

<div class="bg-[#F8F6F0] min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Search, Filter & Sort Controls (Centered Flex Layout) -->
        <form action="{{ route('catalog.index') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-10 space-y-4 md:space-y-0 md:flex md:items-center md:justify-between gap-4">
            
            <!-- Search Input -->
            <div class="relative flex-grow max-w-md mx-auto md:mx-0">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    name="q"
                    placeholder="Cari judul buku, penulis, atau kata kunci..."
                    value="{{ request('q') }}"
                    class="w-full text-xs pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0B2A43]"
                />
            </div>

            <!-- Controls -->
            <div class="flex flex-wrap items-center justify-center md:justify-end gap-3 text-xs">
                <div class="flex items-center space-x-1.5 text-slate-600 font-semibold">
                    <svg class="w-4 h-4 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Kategori:</span>
                </div>
                <select
                    name="category"
                    onchange="this.form.submit()"
                    class="bg-slate-50 text-slate-800 text-xs px-3 py-2 rounded-xl border border-slate-300 focus:outline-none font-medium cursor-pointer"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <div class="flex items-center space-x-1.5 text-slate-600 font-semibold ml-2">
                    <svg class="w-4 h-4 text-[#0B2A43]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    <span>Urutkan:</span>
                </div>
                <select
                    name="sort"
                    onchange="this.form.submit()"
                    class="bg-slate-50 text-slate-800 text-xs px-3 py-2 rounded-xl border border-slate-300 focus:outline-none font-medium cursor-pointer"
                >
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Nama A-Z</option>
                </select>
            </div>

        </form>

        <!-- Product Grid matching TK-PPEG -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $book)
                    <x-book-card 
                        :id="$book->id"
                        :title="$book->title"
                        :author="$book->author ?? 'Prof. Dr. Iskandar Nazari'"
                        :price="$book->price"
                        :badge="$book->category->name ?? 'Literasi Ruhiologi'"
                        :format="$book->format ?? 'Softcover · 2025'"
                        :image="$book->image"
                        :description="$book->description"
                        :url="route('catalog.show', $book->slug)"
                    />
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State matching TK-PPEG -->
            <div class="py-20 text-center bg-white rounded-3xl border border-slate-200 space-y-3 p-8">
                <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="font-serif text-lg font-bold text-slate-800">Belum ada buku yang sesuai.</h3>
                <p class="text-xs text-slate-500">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block bg-[#0B2A43] text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-[#123B59] transition-colors">
                    Reset Filter
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
