@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, rgba(11, 30, 18, 0.85) 0%, rgba(27, 67, 50, 0.9) 100%), url('https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=1600&auto=format&fit=crop'); background-size: cover; background-position: center; border-bottom: 2.5px solid var(--color-accent-gold);" class="text-white py-20 relative overflow-hidden">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 700px; height: 700px; background: radial-gradient(circle, rgba(212, 175, 55, 0.22) 0%, rgba(0,0,0,0) 70%); pointer-events: none;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 relative z-10">
        <span class="inline-block px-4 py-1.5 rounded-full bg-[#D4AF37]/20 border border-[#D4AF37] text-[#D4AF37] text-xs font-black uppercase tracking-wider">
            💡 Ruhiology Training Center
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-sans tracking-tight text-white">Katalog Pelatihan & Sertifikasi RQ</h1>
        <p class="text-[#EBF3EC] text-sm max-w-2xl mx-auto font-medium leading-relaxed">
            Program pelatihan profesional, workshop, dan sertifikasi fasilitator kecerdasan ruhiologi untuk individu, pendidik, dan lembaga eksekutif.
        </p>
    </div>
</div>

<div class="py-20 bg-[#F7FAF7]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($trainings as $t)
                <div class="bg-white border-2 border-slate-200/80 rounded-3xl overflow-hidden shadow-md flex flex-col justify-between hover:border-[#1B4332] transition p-6 space-y-4">
                    <div class="space-y-3">
                        <span class="inline-block px-3 py-1 bg-[#FFF9E6] text-[#92400E] border border-[#FDE68A] text-[10px] font-black rounded-full uppercase tracking-wider">
                            {{ $t->category }}
                        </span>
                        <h3 class="font-extrabold font-sans text-[#1B4332] text-lg leading-snug">{{ $t->title }}</h3>
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed font-medium">{{ $t->description }}</p>
                        <div class="space-y-1.5 text-xs text-slate-500 border-t border-slate-100 pt-3 font-semibold">
                            <div>👨‍🏫 <strong>Trainer:</strong> {{ $t->trainer }}</div>
                            <div>⏳ <strong>Durasi:</strong> {{ $t->duration }}</div>
                            <div>📍 <strong>Lokasi:</strong> {{ $t->location }}</div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase block font-bold">Investasi</span>
                            <span class="text-base font-black text-[#1B4332]">Rp {{ number_format($t->price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('training.show', $t->slug) }}" style="background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 100%); color: #D4AF37;" class="px-5 py-2.5 font-extrabold rounded-full text-xs shadow-md hover:scale-105 transition">
                            Detail & Daftar ↗
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
