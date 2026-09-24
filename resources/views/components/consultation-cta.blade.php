@props([
    'title' => 'Butuh Pendampingan Ruhiologi?',
    'description' => 'Konsultasi dengan tim profesional kami untuk mendapatkan solusi terbaik.',
    'buttonText' => 'Konsultasi Sekarang →',
    'url' => route('consultation.index')
])

<div class="relative p-8 sm:p-10 min-h-[260px] flex flex-col justify-between overflow-hidden bg-cover bg-center h-full" style="background-image: linear-gradient(to right, rgba(11, 42, 67, 0.92), rgba(11, 42, 67, 0.75)), url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=800&auto=format&fit=crop');">
    <div class="space-y-3 relative z-10">
        <h4 class="font-bold text-2xl sm:text-3xl font-serif text-white tracking-tight leading-snug">{{ $title }}</h4>
        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal max-w-xs">
            {{ $description }}
        </p>
    </div>
    <div class="pt-6 relative z-10">
        <a href="{{ $url }}" class="px-7 py-3 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] rounded-full text-xs font-bold uppercase tracking-wider shadow-lg transition-transform hover:scale-105 inline-flex items-center gap-2">
            <span>{{ $buttonText }}</span>
        </a>
    </div>
</div>
