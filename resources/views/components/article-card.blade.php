@props([
    'title',
    'category' => 'Artikel',
    'date' => '12 Mar 2025',
    'image' => null,
    'url' => '#'
])

<div class="space-y-3 group flex flex-col justify-between h-full">
    <div>
        <div class="h-44 rounded-2xl overflow-hidden bg-slate-100 shadow-sm border border-slate-200 aspect-video">
            <img src="{{ $image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=400&auto=format&fit=crop' }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        </div>
        <div class="pt-3 space-y-1.5">
            <span class="inline-block text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest bg-[#0B2A43]/5 px-2.5 py-0.5 rounded-full border border-[#0B2A43]/10">
                {{ $category }}
            </span>
            <h3 class="font-serif font-bold text-[#0B2A43] text-sm sm:text-base leading-snug group-hover:text-[#C9A24D] transition-colors line-clamp-2">
                {{ $title }}
            </h3>
            <p class="text-[11px] text-slate-400 font-medium pt-0.5">{{ $date }}</p>
        </div>
    </div>
    <div class="pt-2">
        <a href="{{ $url }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#0B2A43] hover:text-[#C9A24D] transition-colors group/link">
            <span>Baca Selengkapnya</span>
            <span class="group-hover/link:translate-x-1 transition-transform inline-block">→</span>
        </a>
    </div>
</div>
