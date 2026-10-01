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
        <div class="lg:col-span-6 xl:col-span-7 bg-gradient-to-br from-[#0B2A43] via-[#082033] to-[#04121E] text-white p-8 sm:p-12 flex flex-col justify-between relative overflow-hidden border-b lg:border-b-0 lg:border-r border-slate-700/40">
            
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
                        <span>🏛️</span> <span>Portal LITBANG & Management UI</span>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-white tracking-tight leading-snug">
                        Sistem Informasi Terpadu Asesmen & Riset Ruhiologi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                        Akses terpusat otentikasi aman untuk peserta, peneliti, praktisi, serta manajerial instansi mitra dalam mengelola data asesmen kecerdasan ruhiologi & pengembangan potensi.
                    </p>
                </div>
            </div>

            <!-- Middle Key Features List -->
            <div class="py-8 space-y-4 relative z-10">
                <div class="flex items-start gap-3 p-3 sm:p-3.5 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 hover:border-[#C9A24D]/40 transition">
                    <div class="w-8 h-8 rounded-xl bg-[#C9A24D]/20 border border-[#C9A24D]/40 flex items-center justify-center shrink-0 text-[#C9A24D] font-bold text-sm">
                        🔒
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-white">Keamanan Terenkripsi & Proteksi Akses</h4>
                        <p class="text-[11px] text-slate-300">Akses akun menggunakan otentikasi aman terenkripsi untuk perlindungan privasi instansi.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 sm:p-3.5 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 hover:border-[#C9A24D]/40 transition">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shrink-0 text-emerald-300 font-bold text-sm">
                        📊
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-white">Analytics Laporan & Skrining WHO-5</h4>
                        <p class="text-[11px] text-slate-300">Pantau akumulasi statistik peserta, indikator kesehatan mental, dan laporan instansi secara real-time.</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Quote Footer -->
            <div class="pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-400 relative z-10">
                <span class="font-serif italic text-amber-200/90">"Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh."</span>
                <span class="font-mono text-[10px] text-slate-400">v2.4 Protected</span>
            </div>

        </div>

        <!-- RIGHT COLUMN: EXECUTIVE LOGIN FORM -->
        <div class="lg:col-span-6 xl:col-span-5 p-8 sm:p-12 bg-white flex flex-col justify-between space-y-6">
            
            <div class="space-y-6">
                <!-- Header Title -->
                <div class="space-y-2 text-left">
                    <h2 class="text-xl sm:text-2xl font-bold font-serif text-[#0B2A43]">Masuk Portal Sistem</h2>
                    <p class="text-xs text-slate-500">Silakan masukkan kredensial terverifikasi untuk melanjutkan.</p>
                </div>

                <!-- Display Errors if Validation Fails -->
                @if ($errors->any())
                    <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 space-y-1">
                        <div class="font-bold flex items-center gap-1.5">
                            <span>⚠️</span> <span>Gagal Otentikasi</span>
                        </div>
                        <ul class="list-disc list-inside text-[11px] space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4" x-data="{ showPass: false }">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Email / NIP Instansi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   placeholder="nama@email.com" 
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/10 transition shadow-xs font-medium">
                        </div>
                    </div>

                    <!-- Password Field with Visibility Toggle -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Kata Sandi (Password)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input :type="showPass ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   placeholder="••••••••" 
                                   class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/10 transition shadow-xs font-medium">
                            <button type="button" 
                                    @click="showPass = !showPass" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPass" x-cloak class="w-4 h-4 text-[#0B2A43]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.97 8.97 0 013.682-.783c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-4.692-4.692a3 3 0 00-4.243-4.243"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-600 hover:text-slate-900 select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#0B2A43] focus:ring-[#0B2A43]">
                            <span>Ingat Sesi Saya</span>
                        </label>
                    </div>

                    <!-- Primary Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-6 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg hover:shadow-xl transition-all cursor-pointer flex items-center justify-center gap-2 group border border-[#C9A24D]/40">
                        <span>Masuk Ke Portal Management</span>
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </button>
                </form>

                <!-- Additional Links -->
                <div class="pt-4 border-t border-slate-100 text-center space-y-2 text-xs">
                    <p class="text-slate-500">
                        Belum memiliki akun peserta? 
                        <a href="{{ route('register') }}" class="font-bold text-[#0B2A43] hover:text-[#C9A24D] transition underline">
                            Daftar Akun Baru
                        </a>
                    </p>
                    <p>
                        <a href="{{ url('/') }}" class="text-[11px] text-slate-400 hover:text-slate-600 transition flex items-center justify-center gap-1">
                            <span>←</span> <span>Kembali ke Halaman Utama</span>
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer Security Notice -->
            <div class="pt-4 text-center border-t border-slate-100">
                <div class="inline-flex items-center gap-1.5 text-[10px] text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-200/60">
                    <span>🛡️</span> <span>Sistem Terproteksi — Jagalah kerahasiaan password Anda.</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
