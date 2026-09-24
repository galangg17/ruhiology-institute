@props([
    'title',
    'description',
    'type' => 'Online',
    'date' => '12-14 Apr 2025',
    'participants' => '50 peserta',
    'image' => null,
    'url' => '#'
])

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 group flex flex-col justify-between">
    <div>
        <div class="relative h-48 bg-slate-100 overflow-hidden aspect-video">
            <img src="{{ $image ?? 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=600&auto=format&fit=crop' }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            <span class="absolute top-3 left-3 {{ strtolower($type) === 'online' ? 'bg-[#E5D7C5] text-[#78350F]' : 'bg-[#0B2A43] text-white' }} text-[10px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-md shadow">
                {{ $type }}
            </span>
        </div>
        <div class="p-6 space-y-2.5">
            <a href="{{ $url }}" class="font-serif font-bold text-[#0B2A43] text-lg sm:text-xl leading-snug group-hover:text-[#C9A24D] transition-colors block">
                {{ $title }}
            </a>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-2 font-normal">
                {{ $description }}
            </p>
        </div>
    </div>
    <div class="p-6 pt-0">
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ $date }}</span>
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>{{ $participants }}</span>
            </span>
        </div>
    </div>
</div>
