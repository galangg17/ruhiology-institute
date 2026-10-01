@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-74px)] bg-[#07131E] py-8 sm:py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center relative overflow-hidden">
    
    <!-- Background Ambient Glow & Grid -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-[#0B2A43] via-[#07131E] to-[#040B12] opacity-90"></div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#C9A24D]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-[#0B2A43]/40 rounded-full blur-3xl pointer-events-none"></div>

    <!-- MAIN PORTAL CONTAINER (DUAL-PANE EXECUTIVE LITBANG MANAGEMENT PORTAL DESIGN) -->
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-700/30 relative z-10 grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
        
        <!-- LEFT COLUMN: INSTITUTIONAL LITBANG BRANDING & HIGHLIGHTS -->
        <div class="lg:col-span-6 xl:col-span-6 bg-gradient-to-br from-[#0B2A43] via-[#082033] to-[#04121E] text-white p-8 sm:p-12 flex flex-col justify-between relative overflow-hidden border-b lg:border-b-0 lg:border-r border-slate-700/40">
            
            <!-- Top Gold Accent Bar -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#C9A24D] via-amber-300 to-[#C9A24D]"></div>
            
            <!-- Background Decorative Watermark SVG -->
            <div class="absolute -bottom-16 -right-16 opacity-5 pointer-events-none">
                <svg class="w-96 h-96 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>

            <!-- Top Header & Institutional Badge -->
            <div class="space-y-6 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-md shrink-0">
                        <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology Institute Logo" class="h-10 sm:h-11 w-auto object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-serif font-black text-sm sm:text-base tracking-widest text-white uppercase leading-tight">RUHIOLOGY</span>
                        <span class="text-[9px] font-extrabold text-[#C9A24D] tracking-[0.25em] uppercase">INSTITUTE</span>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-[#C9A24D]/30 text-[#C9A24D] text-[10px] sm:text-xs font-mono font-bold uppercase tracking-wider">
                        <span>✨</span> <span>Pendaftaran Peserta Terintegrasi</span>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-white tracking-tight leading-snug">
                        Registrasi Akun Peserta & Praktisi Ruhiologi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                        Daftarkan akun pribadi atau instansi Anda untuk mengakses instrumen asesmen, modul pelatihan, laporan evaluasi, dan konsultasi profesional.
                    </p>
                </div>
            </div>

            <!-- Middle Benefits List -->
            <div class="py-6 space-y-3 relative z-10 text-xs text-slate-200">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-[#C9A24D]/20 border border-[#C9A24D]/40 text-[#C9A24D] font-bold flex items-center justify-center shrink-0">✓</span>
                    <span>Akses instan ke instrumen RQI & Skrining WHO-5</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-[#C9A24D]/20 border border-[#C9A24D]/40 text-[#C9A24D] font-bold flex items-center justify-center shrink-0">✓</span>
                    <span>Penyimpanan arsip sertifikat & riwayat asesmen aman</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-[#C9A24D]/20 border border-[#C9A24D]/40 text-[#C9A24D] font-bold flex items-center justify-center shrink-0">✓</span>
                    <span>Terhubung langsung dengan event instansi & sekolah mitra</span>
                </div>
            </div>

            <!-- Bottom Quote Footer -->
            <div class="pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-400 relative z-10">
                <span class="font-serif italic text-amber-200/90">"Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh."</span>
                <span class="font-mono text-[10px] text-slate-400">Portal v2.4</span>
            </div>

        </div>

        <!-- RIGHT COLUMN: REGISTRATION FORM -->
        <div class="lg:col-span-6 xl:col-span-6 p-8 sm:p-12 bg-white flex flex-col justify-between space-y-6">
            
            <div class="space-y-5">
                <!-- Header Title -->
                <div class="space-y-1 text-left">
                    <h2 class="text-xl sm:text-2xl font-bold font-serif text-[#0B2A43]">Daftar Akun Baru</h2>
                    <p class="text-xs text-slate-500">Isi data lengkap Anda di bawah ini untuk membuat akun peserta.</p>
                </div>

                <!-- Display Errors if Validation Fails -->
                @if ($errors->any())
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 space-y-1">
                        <div class="font-bold flex items-center gap-1.5">
                            <span>⚠️</span> <span>Mohon Periksa Data Input Anda</span>
                        </div>
                        <ul class="list-disc list-inside text-[11px] space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Register Form -->
                <form action="{{ route('register') }}" method="POST" class="space-y-3.5">
                    @csrf

                    <!-- Full Name -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap Anda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/10 transition shadow-xs font-medium">
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Alamat Email Aktif</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/10 transition shadow-xs font-medium">
                    </div>

                    <!-- Phone Field -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/10 transition shadow-xs font-medium">
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                            <input type="password" name="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/10 transition shadow-xs font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/10 transition shadow-xs font-medium">
                        </div>
                    </div>

                    <!-- Primary Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-6 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg hover:shadow-xl transition-all cursor-pointer flex items-center justify-center gap-2 group border border-[#C9A24D]/40 mt-2">
                        <span>Daftarkan Akun Peserta Baru</span>
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </button>
                </form>

                <!-- Additional Links -->
                <div class="pt-3 border-t border-slate-100 text-center space-y-1.5 text-xs">
                    <p class="text-slate-500">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="font-bold text-[#0B2A43] hover:text-[#C9A24D] transition underline">
                            Masuk Portal Saja
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer Security Notice -->
            <div class="pt-3 text-center border-t border-slate-100">
                <div class="inline-flex items-center gap-1.5 text-[10px] text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-200/60">
                    <span>🛡️</span> <span>Data akun Anda terlindungi dengan enkripsi portal resmi.</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
