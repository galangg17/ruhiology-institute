@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, rgba(11, 30, 18, 0.85) 0%, rgba(27, 67, 50, 0.9) 100%), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop'); background-size: cover; background-position: center; border-bottom: 2.5px solid var(--color-accent-gold);" class="text-white py-20 relative overflow-hidden">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 700px; height: 700px; background: radial-gradient(circle, rgba(212, 175, 55, 0.22) 0%, rgba(0,0,0,0) 70%); pointer-events: none;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 relative z-10">
        <span class="inline-block px-4 py-1.5 rounded-full bg-[#D4AF37]/20 border border-[#D4AF37] text-[#D4AF37] text-xs font-black uppercase tracking-wider">
            📍 Pendampingan Ahli & Audiensi
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-sans tracking-tight text-white">Layanan Konsultasi Ruhiologi</h1>
        <p class="text-[#EBF3EC] text-sm max-w-2xl mx-auto font-medium leading-relaxed">
            Ajukan jadwal konsultasi personal, audiensi institusi, atau perancangan program pengembangan karakter bersama Tim Pakar Ruhiology Institute.
        </p>
    </div>
</div>

<div class="py-20 bg-[#F7FAF7]">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-3xl border-2 border-slate-200/80 shadow-md">
            <h2 class="text-xl font-black font-sans text-[#1B4332] mb-6 pb-3 border-b border-slate-100 flex items-center gap-2">
                <span class="text-[#D4AF37]">📝</span> Form Permintaan Jadwal Konsultasi
            </h2>

            <form action="{{ route('consultation.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                @csrf

                <div>
                    <label class="block font-black text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required class="w-full p-3 rounded-2xl border-2 border-slate-300 focus:ring-2 focus:ring-[#1B4332]">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-black text-slate-700 mb-1">Email Aktif</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required class="w-full p-3 rounded-2xl border-2 border-slate-300 focus:ring-2 focus:ring-[#1B4332]">
                    </div>
                    <div>
                        <label class="block font-black text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required class="w-full p-3 rounded-2xl border-2 border-slate-300 focus:ring-2 focus:ring-[#1B4332]">
                    </div>
                </div>

                <div>
                    <label class="block font-black text-slate-700 mb-1">Institusi / Perusahaan (Opsional)</label>
                    <input type="text" name="institution" value="{{ old('institution') }}" placeholder="Contoh: Kanwil Kemenag / UIN / Korporasi" class="w-full p-3 rounded-2xl border-2 border-slate-300 focus:ring-2 focus:ring-[#1B4332]">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-black text-slate-700 mb-1">Jenis Konsultasi</label>
                        <select name="consultation_type" required class="w-full p-3 rounded-2xl border-2 border-slate-300 focus:ring-2 focus:ring-[#1B4332]">
                            <option value="Personal RQ Consultation">Konsultasi Personal RQ</option>
                            <option value="Institution RQ Assessment & Training">Asesmen & Training Institusi</option>
                            <option value="Executive Leadership Guidance">Executive Leadership Guidance</option>
                            <option value="Academic Research & Collaboration">Kerjasama Riset Akademik</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-black text-slate-700 mb-1">Rencana Tanggal Konsultasi</label>
                        <input type="date" name="preferred_date" value="{{ old('preferred_date', now()->addDays(3)->format('Y-m-d')) }}" required class="w-full p-3 rounded-2xl border-2 border-slate-300 focus:ring-2 focus:ring-[#1B4332]">
                    </div>
                </div>

                <div>
                    <label class="block font-black text-slate-700 mb-1">Pilihan Waktu Jam</label>
                    <select name="preferred_time" required class="w-full p-3 rounded-2xl border-2 border-slate-300 focus:ring-2 focus:ring-[#1B4332]">
                        <option value="09:00 - 10:30 WIB">Sesi Pagi (09:00 - 10:30 WIB)</option>
                        <option value="11:00 - 12:30 WIB">Sesi Siang (11:00 - 12:30 WIB)</option>
                        <option value="14:00 - 15:30 WIB">Sesi Sore (14:00 - 15:30 WIB)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-black text-slate-700 mb-1">Pesan & Kebutuhan Konsultasi</label>
                    <textarea name="message" rows="4" required placeholder="Jelaskan kebutuhan konsultasi atau latar belakang institusi Anda..." class="w-full p-3 rounded-2xl border-2 border-slate-300 focus:ring-2 focus:ring-[#1B4332]"></textarea>
                </div>

                <button type="submit" style="background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 100%); color: #D4AF37; border: 1.5px solid #D4AF37;" class="w-full py-4 font-black rounded-full text-xs uppercase tracking-wider shadow-lg hover:scale-[1.02] transition">
                    📍 Kirim Permintaan Konsultasi →
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
