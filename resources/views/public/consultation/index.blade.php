@extends('layouts.app')

@section('content')
<!-- HERO SECTION (UNIFIED LUXURY DESIGN SYSTEM WITH DEEP NAVY & GOLD) -->
<div class="relative bg-gradient-to-b from-[#0B2A43] via-[#0F3554] to-[#081F33] text-white py-16 sm:py-20 overflow-hidden border-b border-[#C9A24D]/30">
    <!-- Ambient Radial Glows -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[650px] bg-gradient-to-tr from-[#C9A24D]/15 to-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -top-24 right-10 w-96 h-96 bg-[#C9A24D]/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-4">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#C9A24D]/15 border border-[#C9A24D]/40 text-[#C9A24D] text-xs font-black uppercase tracking-widest shadow-xs">
            <span>📍</span> <span>Pendampingan Ahli & Audiensi Institusi</span>
        </div>
        
        <h1 class="text-3xl sm:text-5xl font-black font-serif tracking-tight text-white leading-tight">
            Layanan Konsultasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#E5C158] via-[#C9A24D] to-[#997528]">Ruhiologi</span>
        </h1>
        
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto font-medium leading-relaxed">
            Ajukan jadwal konsultasi personal, audiensi institusi, atau perancangan program pengembangan karakter berkeadaban bersama Tim Pakar Ruhiology Institute.
        </p>

        <!-- Quick Stats Badge -->
        <div class="pt-4 flex flex-wrap justify-center items-center gap-4 text-xs font-semibold text-slate-300">
            <div class="flex items-center gap-1.5 bg-white/5 border border-white/10 px-3.5 py-1.5 rounded-xl">
                <span class="text-[#C9A24D]">✨</span> <span>Pendampingan Personal & Korporasi</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/5 border border-white/10 px-3.5 py-1.5 rounded-xl">
                <span class="text-emerald-400">🌐</span> <span>Sesi Online (Zoom) & Offline</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/5 border border-white/10 px-3.5 py-1.5 rounded-xl">
                <span class="text-amber-400">🛡️</span> <span>Kerahasiaan Data Terjamin</span>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT SECTION (LAYOUT UNIFIED WITH OTHER PAGES) -->
<div class="py-12 sm:py-16 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            
            <!-- LEFT COLUMN: LAYANAN UNGGULAN & ALUR PROSEDUR (5 COLS) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Card 1: Pillar Layanan -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                    <h2 class="text-base font-black text-[#0B2A43] font-serif border-b border-slate-100 pb-3 flex items-center gap-2">
                        <span class="text-amber-500">🌟</span> <span>Pilihan Layanan Konsultasi</span>
                    </h2>

                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1 hover:border-[#C9A24D]/50 transition-colors">
                            <div class="flex items-center gap-2 font-bold text-xs text-[#0B2A43]">
                                <span class="p-1 rounded-lg bg-emerald-500/10 text-emerald-600 text-xs">👤</span>
                                <span>Konsultasi Personal RQ</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed pl-7">
                                Pendampingan jiwa individual untuk mengurai kecemasan, penguatan God Spot, dan pembentukan ketenangan mental.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1 hover:border-[#C9A24D]/50 transition-colors">
                            <div class="flex items-center gap-2 font-bold text-xs text-[#0B2A43]">
                                <span class="p-1 rounded-lg bg-amber-500/10 text-amber-600 text-xs">🏛️</span>
                                <span>Training & Asesmen Institusi</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed pl-7">
                                Audiensi dan penyusunan program pemetaan kecerdasan ruhiologi untuk kampus, sekolah, dan organisasi.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1 hover:border-[#C9A24D]/50 transition-colors">
                            <div class="flex items-center gap-2 font-bold text-xs text-[#0B2A43]">
                                <span class="p-1 rounded-lg bg-indigo-500/10 text-indigo-600 text-xs">👑</span>
                                <span>Executive Leadership Guidance</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed pl-7">
                                Sesi privat kepemimpinan berlandaskan integritas puncak (*God Light & Muraqabah*) bagi pimpinan lembaga.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Alur Prosedur -->
                <div class="bg-gradient-to-br from-[#0B2A43] to-[#0F3554] p-6 rounded-3xl text-white space-y-4 shadow-md border border-[#C9A24D]/30">
                    <h3 class="text-sm font-black text-[#C9A24D] font-serif uppercase tracking-wider flex items-center gap-2">
                        <span>🗺️</span> <span>Alur & Prosedur Layanan</span>
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#C9A24D] text-[#0B2A43] font-black flex items-center justify-center shrink-0 text-[11px]">1</span>
                            <div>
                                <h4 class="font-bold text-white">Isi Form Permintaan</h4>
                                <p class="text-[11px] text-slate-300">Lengkapi data diri dan tentukan rencana jadwal konsultasi yang diinginkan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#C9A24D] text-[#0B2A43] font-black flex items-center justify-center shrink-0 text-[11px]">2</span>
                            <div>
                                <h4 class="font-bold text-white">Konfirmasi Tim Pakar</h4>
                                <p class="text-[11px] text-slate-300">Tim Ruhiology Institute akan menghubungi via WhatsApp/Email untuk jadwal final.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#C9A24D] text-[#0B2A43] font-black flex items-center justify-center shrink-0 text-[11px]">3</span>
                            <div>
                                <h4 class="font-bold text-white">Pelaksanaan Sesi</h4>
                                <p class="text-[11px] text-slate-300">Sesi pendampingan dilakukan secara hikmat via Zoom Meeting atau Tatap Muka.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Helpdesk Fast Response -->
                <div class="p-5 rounded-2xl bg-amber-500/10 border border-[#C9A24D]/40 text-slate-800 flex items-center gap-3">
                    <span class="text-2xl">💬</span>
                    <div class="text-xs">
                        <h4 class="font-black text-[#0B2A43]">Butuh Respon Cepat?</h4>
                        <p class="text-[11px] text-slate-600 mt-0.5">Hubungi Admin Secretariat Ruhiology via WhatsApp di <a href="https://wa.me/6281274110001" target="_blank" class="font-bold text-[#0B2A43] underline hover:text-[#C9A24D]">0812-7411-0001</a></p>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: FORM REQUEST (7 COLS) -->
            <div class="lg:col-span-7">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md">
                    
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h2 class="text-xl font-black text-[#0B2A43] font-serif flex items-center gap-2">
                            <span class="text-[#C9A24D]">📝</span> <span>Form Permintaan Jadwal Konsultasi</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Silakan isi formulir di bawah ini dengan lengkap untuk mengajukan sesi konsultasi.</p>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                            <span>✅</span> <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('consultation.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                        @csrf

                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required placeholder="Masukkan nama lengkap Anda" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] text-slate-800">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5">Email Aktif <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="contoh@domain.com" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] text-slate-800">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required placeholder="08xxxxxxxxxx" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] text-slate-800">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Institusi / Perusahaan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="institution" value="{{ old('institution') }}" placeholder="Contoh: Kanwil Kemenag / UIN / Korporasi / Pribadi" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] text-slate-800">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5">Jenis Konsultasi <span class="text-red-500">*</span></label>
                                <select name="consultation_type" required class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] text-slate-800 bg-white">
                                    <option value="Personal RQ Consultation">Konsultasi Personal RQ</option>
                                    <option value="Institution RQ Assessment & Training">Asesmen & Training Institusi</option>
                                    <option value="Executive Leadership Guidance">Executive Leadership Guidance</option>
                                    <option value="Academic Research & Collaboration">Kerjasama Riset Akademik</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1.5">Rencana Tanggal Konsultasi <span class="text-red-500">*</span></label>
                                <input type="date" name="preferred_date" value="{{ old('preferred_date', now()->addDays(3)->format('Y-m-d')) }}" required class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] text-slate-800">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Pilihan Waktu Jam <span class="text-red-500">*</span></label>
                            <select name="preferred_time" required class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] text-slate-800 bg-white">
                                <option value="09:00 - 10:30 WIB">Sesi Pagi (09:00 - 10:30 WIB)</option>
                                <option value="11:00 - 12:30 WIB">Sesi Siang (11:00 - 12:30 WIB)</option>
                                <option value="14:00 - 15:30 WIB">Sesi Sore (14:00 - 15:30 WIB)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Pesan & Kebutuhan Konsultasi <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="4" required placeholder="Jelaskan kebutuhan konsultasi atau latar belakang institusi Anda secara ringkas..." class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] text-slate-800"></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 font-black rounded-xl text-xs uppercase tracking-wider text-white bg-[#0B2A43] hover:bg-[#123B59] border border-[#C9A24D]/40 shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer group">
                            <span>📍</span> <span>Kirim Permintaan Konsultasi</span> <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
