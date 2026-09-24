@props([
    'id' => null,
    'title',
    'author' => 'Prof. Dr. Iskandar Nazari',
    'price' => 'Rp 120.000',
    'badge' => 'Literasi Ruhiologi',
    'format' => 'Softcover · 2025',
    'image' => null,
    'description' => null,
    'url' => '#'
])

@php
    $formattedPrice = is_numeric($price) ? 'Rp ' . number_format($price, 0, ',', '.') : $price;
    $defaultImage = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=400&auto=format&fit=crop';
    $bookImage = $image ?? $defaultImage;
    $shortDesc = $description ?? 'Karya ilmiah monograf dan panduan komprehensif pengembangan potensi diri berbasis Ruhiologi.';
    $productId = $id ?? '1';
@endphp

<div x-data="{}" class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
    <div>
        <!-- Book Cover Visual Container -->
        <div class="relative aspect-square bg-slate-100 overflow-hidden cursor-pointer"
             onclick="window.dispatchEvent(new CustomEvent('open-order-modal', { detail: { id: '{{ $productId }}', title: '{{ addslashes($title) }}', price: '{{ addslashes($formattedPrice) }}' } }))"
             @click="$dispatch('open-order-modal', { id: '{{ $productId }}', title: '{{ addslashes($title) }}', price: '{{ addslashes($formattedPrice) }}' })">
            
            <img src="{{ $bookImage }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

            <!-- Category Top-Left Badge -->
            <div class="absolute top-2.5 left-2.5 bg-[#0B2A43] text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                {{ $badge }}
            </div>

            <!-- Format Bottom-Right Badge -->
            @if($format)
                <div class="absolute bottom-2.5 right-2.5 bg-slate-900/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded-md backdrop-blur-sm">
                    {{ $format }}
                </div>
            @endif
        </div>

        <!-- Book Metadata Content -->
        <div class="p-4 space-y-2">
            <span class="text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest block">
                {{ $badge }}
            </span>
            <h3 class="font-serif font-bold text-base sm:text-lg text-[#0B2A43] group-hover:text-[#C9A24D] transition-colors line-clamp-1 cursor-pointer tracking-tight"
                onclick="window.dispatchEvent(new CustomEvent('open-order-modal', { detail: { id: '{{ $productId }}', title: '{{ addslashes($title) }}', price: '{{ addslashes($formattedPrice) }}' } }))"
                @click="$dispatch('open-order-modal', { id: '{{ $productId }}', title: '{{ addslashes($title) }}', price: '{{ addslashes($formattedPrice) }}' })">
                {{ $title }}
            </h3>
            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-normal">
                ✍️ {{ $author }} &mdash; {{ $shortDesc }}
            </p>
            <div class="pt-2 font-bold text-[#0B2A43] text-sm sm:text-base">
                {{ $formattedPrice }}
            </div>
        </div>
    </div>

    <!-- Actions (Pesan Sekarang Pop-up + Detail Link) -->
    <div class="p-4 pt-0 space-y-2">
        <button type="button"
                onclick="window.dispatchEvent(new CustomEvent('open-order-modal', { detail: { id: '{{ $productId }}', title: '{{ addslashes($title) }}', price: '{{ addslashes($formattedPrice) }}' } }))"
                @click="$dispatch('open-order-modal', { id: '{{ $productId }}', title: '{{ addslashes($title) }}', price: '{{ addslashes($formattedPrice) }}' })"
                aria-label="Pesan {{ $title }}"
                class="w-full bg-[#0B2A43] hover:bg-[#123B59] text-white text-xs font-semibold py-2.5 rounded-xl transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Pesan Sekarang</span>
        </button>
        <a href="{{ $url }}" class="w-full block text-center text-xs font-medium text-slate-600 hover:text-slate-900 py-1 transition-colors">
            Lihat Detail Buku
        </a>
    </div>
</div>
