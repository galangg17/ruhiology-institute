@props([
    'title',
    'subtitle' => null,
    'linkText' => null,
    'linkUrl' => null,
])

<div class="flex justify-between items-end mb-10 flex-wrap gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1.5">
            <span class="w-6 h-[2px] bg-[#C9A24D]"></span>
            <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest">RUHIOLOGY PLATFORM</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-bold font-serif text-[#0B2A43] tracking-tight">
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="text-sm text-slate-600 mt-2 max-w-xl font-normal leading-relaxed">
                {{ $subtitle }}
            </p>
        @endif
    </div>
    @if($linkText && $linkUrl)
        <a href="{{ $linkUrl }}" class="text-xs sm:text-sm font-bold text-[#0B2A43] hover:text-[#C9A24D] transition-colors inline-flex items-center gap-1.5 group py-1.5 px-3.5 rounded-full hover:bg-slate-50 border border-transparent hover:border-slate-200">
            <span>{{ $linkText }}</span>
            <span class="group-hover:translate-x-1 transition-transform inline-block text-[#C9A24D]">→</span>
        </a>
    @endif
</div>
