@props([
    'title',
    'description',
    'icon' => null
])

<div class="space-y-3">
    <div class="w-10 h-10 rounded-full bg-[#FDFBF7] border border-[#E5D7C5] flex items-center justify-center text-slate-700 font-bold text-base shadow-sm">
        @if($icon)
            {{ $icon }}
        @else
            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        @endif
    </div>
    <h3 class="font-bold text-sm text-[#0F2942]">{{ $title }}</h3>
    <p class="text-xs text-slate-500 leading-relaxed font-medium">
        {{ $description }}
    </p>
</div>
