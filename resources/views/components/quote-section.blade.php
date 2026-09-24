@props([
    'quote' => 'Pendidikan sejati bukan hanya tentang apa yang kita ketahui, tetapi tentang siapa yang kita menjadi.',
    'author' => 'Prof. Dr. Iskandar Nazari'
])

<div class="p-8 sm:p-10 space-y-4">
    <div class="text-5xl text-[#C9A24D] font-serif leading-none">“</div>
    <p class="text-base sm:text-lg font-serif italic leading-relaxed text-slate-100 font-normal">
        "{{ $quote }}"
    </p>
    <div class="pt-2 text-xs font-bold text-[#C9A24D] uppercase tracking-widest font-mono">
        — {{ $author }}
    </div>
</div>
