@props([
    'title',
    'subtitle' => null,
    'linkText' => null,
    'linkUrl' => null,
])

<div class="flex justify-between items-end mb-10 flex-wrap gap-4">
    <div>
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
        <a href="{{ $linkUrl }}" class="text-xs sm:text-sm font-bold text-[#0B2A43] hover:text-[#C9A24D] transition-colors inline-flex items-center gap-1.5 group">
            <span>{{ $linkText }}</span>
            <span class="group-hover:translate-x-1 transition-transform inline-block">→</span>
        </a>
    @endif
</div>
