@extends('layouts.app')

@section('content')
<div>

    <!-- HERO SECTION COMPONENT -->
    <x-hero />

    <!-- BANNER METRIK DATA REAL & ANIMASI COUNTER -->
    <div class="bg-[#F8F6F0] border-b border-slate-200/80 py-5 sm:py-7">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 text-xs sm:text-sm">
                
                <!-- Metric 1: Cakupan Provinsi -->
                <div 
                    x-data="{ current: 0, target: {{ $stats['total_provinces'] ?? 38 }}, init() { let step = Math.max(1, Math.ceil(this.target / 30)); let timer = setInterval(() => { this.current += step; if (this.current >= this.target) { this.current = this.target; clearInterval(timer); } }, 40); } }"
                    class="flex items-center gap-2.5 sm:gap-3 bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-sm transition"
                >
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-base sm:text-lg shrink-0 border border-amber-200">
                        📍
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg sm:text-2xl font-black font-mono text-[#0B2A43]" x-text="current"></span>
                            <span class="text-[11px] sm:text-xs font-bold text-[#0B2A43]">Provinsi</span>
                        </div>
                        <span class="text-[10px] sm:text-[11px] text-slate-500 font-normal block leading-tight">Cakupan Indonesia</span>
                    </div>
                </div>

                <!-- Metric 2: Cakupan Institusi -->
                <div 
                    x-data="{ current: 0, target: {{ $stats['total_institutions'] ?? 10 }}, init() { let step = Math.max(1, Math.ceil(this.target / 30)); let timer = setInterval(() => { this.current += step; if (this.current >= this.target) { this.current = this.target; clearInterval(timer); } }, 40); } }"
                    class="flex items-center gap-2.5 sm:gap-3 bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-sm transition"
                >
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-base sm:text-lg shrink-0 border border-emerald-200">
                        🏛️
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg sm:text-2xl font-black font-mono text-[#0B2A43]" x-text="current"></span>
                            <span class="text-[11px] sm:text-xs font-bold text-[#0B2A43]">Institusi</span>
                        </div>
                        <span class="text-[10px] sm:text-[11px] text-slate-500 font-normal block leading-tight">Kampus & Sekolah</span>
                    </div>
                </div>

                <!-- Metric 3: Cakupan Peserta -->
                <div 
                    x-data="{ current: 0, target: {{ $stats['total_participants'] ?? 50 }}, init() { let step = Math.max(1, Math.ceil(this.target / 30)); let timer = setInterval(() => { this.current += step; if (this.current >= this.target) { this.current = this.target; clearInterval(timer); } }, 40); } }"
                    class="flex items-center gap-2.5 sm:gap-3 bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-sm transition"
                >
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center font-bold text-base sm:text-lg shrink-0 border border-sky-200">
                        👥
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg sm:text-2xl font-black font-mono text-[#0B2A43]" x-text="current"></span>
                            <span class="text-[11px] sm:text-xs font-bold text-[#0B2A43]">Peserta</span>
                        </div>
                        <span class="text-[10px] sm:text-[11px] text-slate-500 font-normal block leading-tight">Pengguna Asesmen</span>
                    </div>
                </div>

                <!-- Metric 4: Sesi Assessment -->
                <div 
                    x-data="{ current: 0, target: {{ $stats['total_submissions'] ?? 100 }}, init() { let step = Math.max(1, Math.ceil(this.target / 30)); let timer = setInterval(() => { this.current += step; if (this.current >= this.target) { this.current = this.target; clearInterval(timer); } }, 40); } }"
                    class="flex items-center gap-2.5 sm:gap-3 bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-sm transition"
                >
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-base sm:text-lg shrink-0 border border-purple-200">
                        📝
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg sm:text-2xl font-black font-mono text-[#0B2A43]" x-text="current"></span>
                            <span class="text-[11px] sm:text-xs font-bold text-[#0B2A43]">Sesi Evaluasi</span>
                        </div>
                        <span class="text-[10px] sm:text-[11px] text-slate-500 font-normal block leading-tight">Pretest & Posttest</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SECTION 1.5: MINI-QUIZ TEASER (CEK RQ 1-MENIT) -->
    <section class="py-12 bg-gradient-to-b from-[#F8F6F0] to-white border-b border-slate-200/80">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="bg-[#0B2A43] text-white rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden border border-[#C9A24D]/30"
                 x-data="{
                    q1: 0, q2: 0, q3: 0,
                    get total() { return this.q1 + this.q2 + this.q3; },
                    get isDone() { return this.q1 > 0 && this.q2 > 0 && this.q3 > 0; },
                    get category() {
                        let pct = Math.round((this.total / 15) * 100);
                        if (pct >= 85) return 'Enlightened Soul (Level 5)';
                        if (pct >= 70) return 'Mindful Youth (Level 4)';
                        if (pct >= 55) return 'Developing Soul (Level 3)';
                        if (pct >= 35) return 'Awakening Pilgrim (Level 2)';
                        return 'Spiritual Lowbat (Level 1)';
                    }
                 }">
                
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-[#C9A24D]/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                    
                    <!-- Left Teaser Intro -->
                    <div class="lg:col-span-5 space-y-4 text-center lg:text-left">
                        <span class="inline-flex items-center space-x-2 bg-[#C9A24D]/20 text-[#C9A24D] text-xs font-mono font-bold px-3.5 py-1.5 rounded-full border border-[#C9A24D]/30 uppercase tracking-widest">
                            <span>⚡ CEK RQ 1-MENIT</span>
                        </span>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white leading-tight">
                            Uji Cepat Potensi Ruhiologi Anda (Mini Quiz)
                        </h2>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                            Jawab 3 pertanyaan reflektif berikut untuk mendapatkan estimasi tingkat kesadaran batin Anda secara instan sebelum mengambil asesmen lengkap terstruktur (20 soal).
                        </p>

                        <!-- Live Mini Score Box -->
                        <template x-if="isDone">
                            <div class="p-4 bg-white/10 rounded-2xl border border-[#C9A24D]/40 backdrop-blur-md space-y-2 animate-fade-in">
                                <div class="text-xs text-slate-300 font-mono">Estimasi Skor Mini-RQ:</div>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-3xl font-black font-mono text-[#C9A24D]" x-text="Math.round((total / 15) * 100) + '%'"></span>
                                    <span class="text-xs font-bold text-emerald-300" x-text="category"></span>
                                </div>
                                <button type="button" @click="$dispatch('open-assessment-intake')" class="w-full mt-2 py-3 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-bold text-xs rounded-xl shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2 cursor-pointer">
                                    <span>Ambil Asesmen Terstruktur Lengkap (20 Soal)</span>
                                    <span>→</span>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Right 3 Questions -->
                    <div class="lg:col-span-7 bg-white/5 p-5 sm:p-7 rounded-2xl border border-white/10 space-y-5">
                        
                        <!-- Question 1 -->
                        <div class="space-y-2">
                            <label class="text-xs sm:text-sm font-bold text-slate-200 block">
                                1. Penataan Niat & Orientasi Ilahi:
                                <span class="text-slate-300 font-normal block text-[11px] mt-0.5">Seberapa sering Anda menata niat demi keridhaan Tuhan sebelum belajar atau bekerja?</span>
                            </label>
                            <div class="grid grid-cols-5 gap-1.5 sm:gap-2">
                                <template x-for="i in 5">
                                    <button type="button" @click="q1 = i" :class="q1 === i ? 'bg-[#C9A24D] text-[#0B2A43] font-black shadow-md scale-105' : 'bg-white/10 text-white hover:bg-white/20'" class="py-2 rounded-xl text-xs font-bold transition">
                                        <span x-text="i"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="space-y-2">
                            <label class="text-xs sm:text-sm font-bold text-slate-200 block">
                                2. Ketenangan & Purifikasi Batin:
                                <span class="text-slate-300 font-normal block text-[11px] mt-0.5">Seberapa tenang dan mampu Anda mengendalikan emosi amarah ketika sedang tertekan?</span>
                            </label>
                            <div class="grid grid-cols-5 gap-1.5 sm:gap-2">
                                <template x-for="i in 5">
                                    <button type="button" @click="q2 = i" :class="q2 === i ? 'bg-[#C9A24D] text-[#0B2A43] font-black shadow-md scale-105' : 'bg-white/10 text-white hover:bg-white/20'" class="py-2 rounded-xl text-xs font-bold transition">
                                        <span x-text="i"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="space-y-2">
                            <label class="text-xs sm:text-sm font-bold text-slate-200 block">
                                3. Keberfungsian Sosial & Altruisme:
                                <span class="text-slate-300 font-normal block text-[11px] mt-0.5">Seberapa dorongan dalam diri Anda untuk membantu sesama secara tulus tanpa mengharapkan balasan?</span>
                            </label>
                            <div class="grid grid-cols-5 gap-1.5 sm:gap-2">
                                <template x-for="i in 5">
                                    <button type="button" @click="q3 = i" :class="q3 === i ? 'bg-[#C9A24D] text-[#0B2A43] font-black shadow-md scale-105' : 'bg-white/10 text-white hover:bg-white/20'" class="py-2 rounded-xl text-xs font-bold transition">
                                        <span x-text="i"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: STORYTELLING & FILOSOFI RUHIOLOGI -->
    <section id="tentang-ruhiologi" class="py-14 sm:py-20 lg:py-24 bg-[#F8F6F0] border-b border-slate-200/80 scroll-mt-20">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
                
                <!-- Left Photo Documentation (5 cols) -->
                <div class="lg:col-span-5 relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-xl border-4 border-white aspect-[4/3] sm:aspect-[4/3] lg:aspect-[4/5] group bg-slate-200 max-w-md mx-auto lg:max-w-none">
                        <img src="{{ \App\Models\Setting::get('about_image', asset('images/settings/about_1790081685.jpg')) }}" alt="Dokumentasi Riset Ruhiologi" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-90"></div>
                        <div class="absolute bottom-4 left-4 right-4 sm:bottom-5 sm:left-5 sm:right-5 text-white space-y-1">
                            <span class="text-[9px] sm:text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">
                                {{ \App\Models\Setting::get('about_image_tag', 'DOKUMENTASI RISET & KARYA RUHIOLOGI') }}
                            </span>
                            <p class="font-serif italic text-[11px] sm:text-xs text-slate-200">
                                {{ \App\Models\Setting::get('about_image_caption', 'Prof. Dr. Iskandar Nazari merumuskan konsep Ruhiology Quotient (RQ) sebagai navigasi potensi manusia.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Storytelling Narrative (7 cols) -->
                <div class="lg:col-span-7 space-y-5 sm:space-y-6 text-center lg:text-left">
                    
                    <div class="inline-flex items-center space-x-2 bg-[#0B2A43]/10 text-[#0B2A43] text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest border border-[#0B2A43]/15">
                        <span class="text-[#C9A24D]">✦</span>
                        <span>{{ \App\Models\Setting::get('about_badge', 'Filosofi & Kesadaran Ruh') }}</span>
                    </div>

                    <h2 class="font-serif text-2xl sm:text-4xl lg:text-5xl font-bold text-[#0B2A43] leading-tight tracking-tight">
                        "{{ \App\Models\Setting::get('about_title', 'Bukan Sekadar Ilmu: Menempatkan Ruh Sebagai Pusat Peradaban') }}"
                    </h2>

                    <div class="space-y-3 sm:space-y-4 text-slate-700 text-xs sm:text-base leading-relaxed font-normal text-left">
                        <p>
                            {{ \App\Models\Setting::get('about_description', 'Ruhiologi dipopulerkan dan dirumuskan sebagai respons atas paradigma psikologi modern yang cenderung membatasi potensi manusia sebatas pada aspek rasional-kognitif (IQ) dan sosio-emosional (EQ).') }}
                        </p>
                        <div class="bg-white p-4 sm:p-6 rounded-2xl border-l-4 border-[#C9A24D] shadow-sm font-serif italic text-[#0B2A43] text-sm sm:text-lg leading-relaxed">
                            "{{ \App\Models\Setting::get('about_quote', 'Ruh bukan sekadar dorongan mistis, melainkan pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, intuisi kebenaran, serta komitmen etis dalam kehidupan nyata.') }}"
                        </div>
                    </div>

                    <div class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-6 text-xs sm:text-sm font-bold text-[#0B2A43]">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ \App\Models\Setting::get('about_feature_1', 'Integrasi Wahyu & Sains') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ \App\Models\Setting::get('about_feature_2', 'Purifikasi Batin (Tazkiyah)') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ \App\Models\Setting::get('about_feature_3', 'Resiliensi Spiritual') }}</span>
                        </div>
                    </div>

                    <!-- 3 Inteligensi Comparison Grid -->
                    <div class="mt-6 pt-6 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-left">
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-1">
                            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-wider block">IQ · Kognitif</span>
                            <strong class="text-xs text-[#0B2A43] block font-bold">Kecerdasan Rasional</strong>
                            <p class="text-[11px] text-slate-500 leading-snug">Nalar logika, intelek teknis, & analisis empiris.</p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-1">
                            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-wider block">EQ · Afektif</span>
                            <strong class="text-xs text-[#0B2A43] block font-bold">Kecerdasan Emosional</strong>
                            <p class="text-[11px] text-slate-500 leading-snug">Pengelolaan emosi harian & empati sosial.</p>
                        </div>
                        <div class="bg-[#0B2A43] text-white p-4 rounded-2xl border border-[#C9A24D]/40 shadow-md space-y-1 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-[#C9A24D]/20 rounded-full blur-xl pointer-events-none"></div>
                            <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-wider block">RQ · Ruhiologi</span>
                            <strong class="text-xs text-white block font-bold">Pusat Inteligensi Ruh</strong>
                            <p class="text-[11px] text-slate-200 leading-snug">Kesadaran Ilahi, kompas etika, & purifikasi batin.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- SPOTLIGHT RISET & LANDASAN TEORI PROF. ISKANDAR NAZARI -->
    <section class="py-14 sm:py-20 bg-[#0B2A43] text-white border-b border-slate-800">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-5 text-center lg:text-left">
                    <div class="inline-flex items-center space-x-2 bg-[#C9A24D]/20 text-[#C9A24D] text-xs font-mono font-bold px-3.5 py-1.5 rounded-full border border-[#C9A24D]/30 uppercase tracking-widest">
                        <span>✦ LANDASAN TEORI & METODOLOGI</span>
                    </div>

                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-white leading-tight tracking-tight">
                        Riset Psikometri & Integrasi Nilai-Nilai Ruhiologi
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                        Pengembangan instrumen kecerdasan ruhiologi (*Ruhiology Quotient*) berakar pada riset panjang Prof. Dr. Iskandar Nazari yang memadukan kajian tekstual-klasik dan uji validitas psikometri ilmiah modern.
                    </p>

                    <!-- Key Metrics Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                        <div class="bg-white/10 p-3 sm:p-3.5 rounded-2xl border border-white/15 backdrop-blur-md text-left">
                            <span class="text-lg sm:text-2xl font-black font-mono text-[#C9A24D] block">&gt; 0.88</span>
                            <span class="text-[10px] sm:text-[11px] text-slate-300 block font-medium mt-0.5">Reliabilitas Alpha</span>
                        </div>
                        <div class="bg-white/10 p-3 sm:p-3.5 rounded-2xl border border-white/15 backdrop-blur-md text-left">
                            <span class="text-lg sm:text-2xl font-black font-mono text-[#C9A24D] block">RQI-15</span>
                            <span class="text-[10px] sm:text-[11px] text-slate-300 block font-medium mt-0.5">Scale Terstruktur</span>
                        </div>
                        <div class="bg-white/10 p-3 sm:p-3.5 rounded-2xl border border-white/15 backdrop-blur-md text-left">
                            <span class="text-lg sm:text-2xl font-black font-mono text-[#C9A24D] block">WHO-5</span>
                            <span class="text-[10px] sm:text-[11px] text-slate-300 block font-medium mt-0.5">Well-being Index</span>
                        </div>
                        <div class="bg-white/10 p-3 sm:p-3.5 rounded-2xl border border-white/15 backdrop-blur-md text-left">
                            <span class="text-lg sm:text-2xl font-black font-mono text-[#C9A24D] block">100%</span>
                            <span class="text-[10px] sm:text-[11px] text-slate-300 block font-medium mt-0.5">Peer-Reviewed</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Theoretical Foundation Card (5 cols) -->
                <div class="lg:col-span-5 bg-white/10 p-5 sm:p-7 rounded-3xl border border-white/20 backdrop-blur-md space-y-4">
                    <h3 class="font-serif font-bold text-base sm:text-lg text-[#C9A24D] flex items-center gap-2">
                        <span>📚</span> <span>4 Bukti Akademis & Karya Utama</span>
                    </h3>

                    <ul class="space-y-3 text-xs text-slate-200">
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold shrink-0 mt-0.5 text-[10px]">✓</span>
                            <div>
                                <strong class="text-white block font-bold">Buku Monograf Ruhiologi (2025)</strong>
                                <span class="text-slate-300 text-[11px]">Karya monumental Prof. Dr. Iskandar Nazari penerbit Ruhiology Press.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold shrink-0 mt-0.5 text-[10px]">✓</span>
                            <div>
                                <strong class="text-white block font-bold">Uji Validitas pada 1.250+ Responden</strong>
                                <span class="text-slate-300 text-[11px]">Meliputi mahasiswa, pendidik, dan praktisi di berbagai institusi.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold shrink-0 mt-0.5 text-[10px]">✓</span>
                            <div>
                                <strong class="text-white block font-bold">Mitra Konsorsium PT & Sekolah</strong>
                                <span class="text-slate-300 text-[11px]">Kerja sama riset pengukuran dengan perguruan tinggi mitra.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold shrink-0 mt-0.5 text-[10px]">✓</span>
                            <div>
                                <strong class="text-white block font-bold">Rekomendasi Refleksi & Intervensi</strong>
                                <span class="text-slate-300 text-[11px]">Setiap skor dilengkapi panduan penataan niat dan purifikasi batin.</span>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 3: RQ ASSESSMENT -->
    <section id="assessment" class="py-12 sm:py-18 bg-white scroll-mt-20">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="bg-[#EDF3F6] rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch min-h-[340px]">
                    
                    <!-- Left Photo -->
                    <div class="lg:col-span-4 relative h-64 sm:h-80 lg:h-auto overflow-hidden bg-[#070b19]">
                        <img src="{{ \App\Models\Setting::get('rq_assessment_image', asset('images/god-spot-light.jpg')) }}" alt="Kenali Profil Ruhiologi - God Spot & God Light" class="w-full h-full object-cover object-top sm:object-center">
                    </div>

                    <!-- Right Content Area -->
                    <div class="lg:col-span-8 p-6 sm:p-12 flex flex-col justify-between">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-center w-full">
                            
                            <!-- Center Content -->
                            <div class="lg:col-span-7 space-y-3 sm:space-y-4 text-center lg:text-left">
                                <h2 class="text-2xl sm:text-4xl font-bold font-serif text-[#0B2A43] tracking-tight">
                                    Kenali Profil Ruhiologi Anda
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                    RQ Assessment dirancang sebagai instrumen untuk mengeksplorasi dan memetakan dimensi Ruhiologi berdasarkan instrumen yang digunakan oleh Ruhiology Institute.
                                </p>
                                <div class="pt-2">
                                    <a href="{{ route('assessment.index') }}" class="px-7 py-3.5 bg-[#0B2A43] hover:bg-[#123B59] text-white rounded-full text-xs font-bold uppercase tracking-wider shadow-md transition-transform hover:scale-105 inline-flex items-center gap-2 min-h-[44px]">
                                        <span>Mulai Assessment</span> <span>→</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Right Bullet List -->
                            <div class="lg:col-span-5 space-y-3 sm:space-y-4 border-t lg:border-t-0 lg:border-l border-slate-300/80 pt-5 lg:pt-0 lg:pl-8">
                                <div class="flex items-center gap-3 text-xs sm:text-sm">
                                    <div class="w-8 h-8 rounded-full bg-white border border-slate-300 flex items-center justify-center shrink-0 shadow-sm text-[#0B2A43]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <strong class="text-[#0B2A43] block font-bold">Instrumen Terstruktur</strong>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-xs sm:text-sm">
                                    <div class="w-8 h-8 rounded-full bg-white border border-slate-300 flex items-center justify-center shrink-0 shadow-sm text-[#0B2A43]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <div>
                                        <strong class="text-[#0B2A43] block font-bold">Hasil Assessment</strong>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-xs sm:text-sm">
                                    <div class="w-8 h-8 rounded-full bg-white border border-slate-300 flex items-center justify-center shrink-0 shadow-sm text-[#0B2A43]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <strong class="text-[#0B2A43] block font-bold">Profil Ruhiologi</strong>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- 4 Dimensi Pengukuran RQ Grid -->
                        <div class="mt-6 sm:mt-8 pt-5 sm:pt-6 border-t border-slate-300/80">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-bold text-[#0B2A43] uppercase tracking-wider font-mono flex items-center gap-1.5">
                                    <span>✨</span> <span>5 Tahap Pengukuran RQI</span>
                                </span>
                                <span class="text-[10px] sm:text-[11px] font-mono font-bold text-emerald-800 bg-emerald-100/80 px-2.5 py-0.5 rounded-full border border-emerald-300">
                                    📊 1.250+ Peserta Terukur
                                </span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3">
                                <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200 text-center space-y-0.5 shadow-2xs">
                                    <span class="text-sm sm:text-base">🌌</span>
                                    <div class="text-[10px] sm:text-[11px] font-bold text-[#0B2A43]">Pengenalan Diri Hakiki</div>
                                </div>
                                <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200 text-center space-y-0.5 shadow-2xs">
                                    <span class="text-sm sm:text-base">🌿</span>
                                    <div class="text-[10px] sm:text-[11px] font-bold text-[#0B2A43]">Purifikasi & Regulasi Diri</div>
                                </div>
                                <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200 text-center space-y-0.5 shadow-2xs">
                                    <span class="text-sm sm:text-base">🎯</span>
                                    <div class="text-[10px] sm:text-[11px] font-bold text-[#0B2A43]">Pembentukan Karakter</div>
                                </div>
                                <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200 text-center space-y-0.5 shadow-2xs">
                                    <span class="text-sm sm:text-base">🛡️</span>
                                    <div class="text-[10px] sm:text-[11px] font-bold text-[#0B2A43]">Keberfungsian Sosial</div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3.5: ALUR 4 LANGKAH MUDAH ASSESSMENT RQ -->
    <section class="py-14 sm:py-20 bg-[#F8F6F0] border-y border-slate-200/80">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14 space-y-2">
                <span class="text-xs font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">✦ ALUR & PROSES EVALUASI</span>
                <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[#0B2A43] tracking-tight">
                    4 Langkah Mudah Mengikuti Assessment RQ
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 font-normal">Proses praktis dan terstruktur dari registrasi hingga lembar rekomendasi potensi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 relative">
                
                <!-- Step 1 -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-sm relative z-10 flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-[#0B2A43] text-white font-mono font-bold text-base sm:text-lg flex items-center justify-center shadow-sm">01</span>
                            <span class="text-xl sm:text-2xl">📝</span>
                        </div>
                        <h3 class="font-serif font-bold text-base text-[#0B2A43]">Intake Data & Kode</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Masukkan data profil atau Kode Unik Peserta dari kampus/sekolah Anda untuk memulai sesi asesmen.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-[11px] font-bold text-[#C9A24D]">
                        <span>Akses Instan ➔</span>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-sm relative z-10 flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-[#0B2A43] text-white font-mono font-bold text-base sm:text-lg flex items-center justify-center shadow-sm">02</span>
                            <span class="text-xl sm:text-2xl">⏱️</span>
                        </div>
                        <h3 class="font-serif font-bold text-base text-[#0B2A43]">Pengerjaan 15-20 Menit</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Isikan 20 pernyataan terstruktur (RQI-15 & WHO-5 Index) dengan tenang sesuai kondisi batin Anda.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-[11px] font-bold text-[#C9A24D]">
                        <span>20 Pertanyaan ➔</span>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-sm relative z-10 flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-[#0B2A43] text-white font-mono font-bold text-base sm:text-lg flex items-center justify-center shadow-sm">03</span>
                            <span class="text-xl sm:text-2xl">📊</span>
                        </div>
                        <h3 class="font-serif font-bold text-base text-[#0B2A43]">Kalkulasi Real-time</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Sistem secara instan menghitung skor total RQI-15 dan menyajikan hasil 5 Dimensi Ruhiologi secara visual.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-[11px] font-bold text-[#C9A24D]">
                        <span>Kalkulasi Instan ➔</span>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-sm relative z-10 flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-600 text-white font-mono font-bold text-base sm:text-lg flex items-center justify-center shadow-sm">04</span>
                            <span class="text-xl sm:text-2xl">📄</span>
                        </div>
                        <h3 class="font-serif font-bold text-base text-[#0B2A43]">Laporan & Rekomendasi</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Dapatkan interpretasi tingkat kedalaman RQ Gen Z beserta saran refleksi & intervensi pengembangan potensi batin.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-[11px] font-bold text-emerald-600">
                        <span>Unduh Hasil & Refleksi ➔</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 4: 4 PILAR DIMENSI RUHIOLOGI -->
    <section class="py-14 sm:py-20 bg-[#0B2A43] text-white">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12">
                <span class="text-xs font-bold text-[#C9A24D] uppercase tracking-widest block mb-2 font-mono">✦ Dimensi & Kerangka Teori</span>
                <h2 class="font-serif text-2xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight">
                    Empat Dimensi Utama Kecerdasan Ruhiologi (RQ)
                </h2>
                <p class="text-slate-200 text-xs sm:text-sm mt-3 font-normal leading-relaxed">
                    Empat pilar utama yang dirumuskan oleh Prof. Dr. Iskandar Nazari sebagai pusat inteligensi dan potensi kemanusiaan.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                
                <!-- Pilar 1 -->
                <div class="bg-white/10 p-6 sm:p-7 rounded-3xl border border-white/15 backdrop-blur-md flex flex-col justify-between hover:bg-white/15 transition duration-300">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-[#C9A24D]/20 text-[#C9A24D] flex items-center justify-center font-bold text-lg sm:text-xl mb-4 sm:mb-5 border border-[#C9A24D]/30">
                            ✦
                        </div>
                        <h3 class="font-serif font-bold text-base sm:text-xl text-white mb-2 tracking-tight">1. Spiritualitas Transendental</h3>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                            Kesadaran mendalam akan hubungan vertikal dengan Sang Pencipta, ketenangan batiniah, dan zikir.
                        </p>
                    </div>
                </div>

                <!-- Pilar 2 -->
                <div class="bg-white/10 p-6 sm:p-7 rounded-3xl border border-white/15 backdrop-blur-md flex flex-col justify-between hover:bg-white/15 transition duration-300">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold text-lg sm:text-xl mb-4 sm:mb-5 border border-emerald-400/30">
                            ✨
                        </div>
                        <h3 class="font-serif font-bold text-base sm:text-xl text-white mb-2 tracking-tight">2. Purifikasi Mental (Tazkiyah)</h3>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                            Kebersihan hati dari penyakit iri, keangkuhan intelektual, dan ketahanan terhadap amarah.
                        </p>
                    </div>
                </div>

                <!-- Pilar 3 -->
                <div class="bg-white/10 p-6 sm:p-7 rounded-3xl border border-white/15 backdrop-blur-md flex flex-col justify-between hover:bg-white/15 transition duration-300">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-lg sm:text-xl mb-4 sm:mb-5 border border-amber-400/30">
                            🎯
                        </div>
                        <h3 class="font-serif font-bold text-base sm:text-xl text-white mb-2 tracking-tight">3. Orientasi Intensional</h3>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                            Penyelarasan setiap niat bekerja, belajar, dan memimpin semata-mata demi keridhaan Ilahi.
                        </p>
                    </div>
                </div>

                <!-- Pilar 4 -->
                <div class="bg-white/10 p-6 sm:p-7 rounded-3xl border border-white/15 backdrop-blur-md flex flex-col justify-between hover:bg-white/15 transition duration-300">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-blue-500/20 text-blue-300 flex items-center justify-center font-bold text-lg sm:text-xl mb-4 sm:mb-5 border border-blue-400/30">
                            🌱
                        </div>
                        <h3 class="font-serif font-bold text-base sm:text-xl text-white mb-2 tracking-tight">4. Altruisme & Resiliensi</h3>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                            Kekuatan sabar menghadapi ujian dan komitmen aksi melayani masyarakat secara tulus.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Interactive Radar Chart Visualizer (5 Dimensi Balance) -->
            <div class="mt-12 pt-10 border-t border-white/10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-5 bg-white/10 p-6 rounded-3xl border border-white/20 backdrop-blur-md">
                    <h4 class="font-serif font-bold text-base text-[#C9A24D] mb-1 flex items-center gap-2">
                        <span>🕸️</span> <span>Visualisasi Balance 5 Dimensi RQ</span>
                    </h4>
                    <p class="text-xs text-slate-200 mb-4">Grafik keseimbangan potensi ruhiologi dari hasil asesmen terstruktur.</p>
                    <div class="relative w-full aspect-square max-w-xs mx-auto">
                        <canvas id="landingRadarChart"></canvas>
                    </div>
                </div>
                <div class="lg:col-span-7 space-y-4">
                    <div class="inline-flex items-center space-x-2 bg-[#C9A24D]/20 text-[#C9A24D] text-xs font-mono font-bold px-3.5 py-1.5 rounded-full border border-[#C9A24D]/30 uppercase tracking-widest">
                        <span>✦ PEMETAAN INTEGRIF</span>
                    </div>
                    <h3 class="font-serif font-bold text-xl sm:text-3xl text-white leading-snug">
                        Keseimbangan Integratif Antara Batin, Pikiran, & Aksi Real
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                        Kecerdasan Ruhiologi (RQ) mengukur integrasi harmonis antara kebersihan niat, regulasi emosi di bawah tekanan, serta keberfungsian sosial yang nyata dalam masyarakat.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/10">
                            <strong class="text-xs text-[#C9A24D] block font-bold">1. Spiritualitas Transendental</strong>
                            <span class="text-[11px] text-slate-300">Kesadaran Ilahi & ketenangan batin.</span>
                        </div>
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/10">
                            <strong class="text-xs text-[#C9A24D] block font-bold">2. Purifikasi Mental (Tazkiyah)</strong>
                            <span class="text-[11px] text-slate-300">Regulasi amarah & pembersihan batin.</span>
                        </div>
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/10">
                            <strong class="text-xs text-[#C9A24D] block font-bold">3. Orientasi Intensional</strong>
                            <span class="text-[11px] text-slate-300">Penyelarasan niat ikhlas dalam bekerja.</span>
                        </div>
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/10">
                            <strong class="text-xs text-[#C9A24D] block font-bold">4. Altruisme & Resiliensi</strong>
                            <span class="text-[11px] text-slate-300">Kesabaran & aksi melayani sesama.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 5: TRAINING CENTER (WITH INTERACTIVE CATEGORY TABS) -->
    <section id="training" class="py-14 sm:py-20 bg-white border-b border-slate-100 scroll-mt-20" x-data="{ activeTab: 'all' }">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            
            <x-section-heading 
                title="Training Center"
                subtitle="Program pembelajaran, pelatihan, dan pengembangan potensi berbasis Ruhiologi."
                linkText="Lihat Semua Program"
                linkUrl="{{ route('training.index') }}"
            />

            <!-- Interactive Category Filter Tabs -->
            <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 mb-8 sm:mb-10">
                <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-[#0B2A43] text-white font-bold shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-full text-xs transition cursor-pointer">
                    Semua Program
                </button>
                <button @click="activeTab = 'eksekutif'" :class="activeTab === 'eksekutif' ? 'bg-[#0B2A43] text-white font-bold shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-full text-xs transition cursor-pointer">
                    🏆 Sertifikasi Eksekutif
                </button>
                <button @click="activeTab = 'akademik'" :class="activeTab === 'akademik' ? 'bg-[#0B2A43] text-white font-bold shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-full text-xs transition cursor-pointer">
                    🎓 Workshop Akademik
                </button>
                <button @click="activeTab = 'spiritual'" :class="activeTab === 'spiritual' ? 'bg-[#0B2A43] text-white font-bold shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-full text-xs transition cursor-pointer">
                    🌿 Retreat Spiritual
                </button>
            </div>

            <!-- 3 Training Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                @if(isset($trainings) && count($trainings) > 0)
                    @foreach($trainings as $training)
                        <div x-show="activeTab === 'all' || activeTab === '{{ strtolower($training->category ?? 'akademik') }}'" x-transition>
                            <x-training-card 
                                :title="$training->title"
                                :description="$training->description"
                                :type="$training->category ?? 'Online'"
                                :date="$training->batches->first()?->start_date ? \Carbon\Carbon::parse($training->batches->first()->start_date)->format('d-m-Y') : '12-14 Apr 2025'"
                                :participants="($training->batches->first()?->quota_limit ?? 50) . ' peserta'"
                                :url="route('training.show', $training->slug)"
                            />
                        </div>
                    @endforeach
                @else
                    <div x-show="activeTab === 'all' || activeTab === 'akademik'" x-transition>
                        <x-training-card 
                            title="Training Ruhiologi Dasar"
                            description="Memahami konsep dasar Ruhiologi dan penerapannya dalam kehidupan sehari-hari."
                            type="Online"
                            date="12-14 Apr 2025"
                            participants="50 peserta"
                            image="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=600&auto=format&fit=crop"
                            url="{{ route('training.index') }}"
                        />
                    </div>
                    <div x-show="activeTab === 'all' || activeTab === 'eksekutif'" x-transition>
                        <x-training-card 
                            title="Pengembangan Potensi Diri"
                            description="Membangun kesadaran diri dan mengoptimalkan potensi melalui pendekatan Ruhiologi."
                            type="Offline"
                            date="20-22 Mei 2025"
                            participants="30 peserta"
                            image="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=600&auto=format&fit=crop"
                            url="{{ route('training.index') }}"
                        />
                    </div>
                    <div x-show="activeTab === 'all' || activeTab === 'spiritual'" x-transition>
                        <x-training-card 
                            title="Leadership Berbasis Ruhiologi"
                            description="Kepemimpinan yang berakar pada nilai dan kesadaran ruh."
                            type="Online"
                            date="10-12 Jun 2025"
                            participants="40 peserta"
                            image="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=600&auto=format&fit=crop"
                            url="{{ route('training.index') }}"
                        />
                    </div>
                @endif
            </div>

        </div>
    </section>

    <!-- SECTION 5.5: KATALOG BUKU -->
    <section id="katalog" class="py-14 sm:py-20 bg-white border-b border-slate-100 scroll-mt-20">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="text-xs font-bold text-[#C9A24D] uppercase tracking-widest block mb-2 font-mono">📚 Katalog Utama Literasi</span>
                <h2 class="font-serif text-2xl sm:text-4xl lg:text-5xl font-bold text-[#0B2A43] tracking-tight">
                    Inovasi Literasi & Karya Utama Ruhiologi
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-3 font-normal leading-relaxed">
                    Karya-karya inspiratif Prof. Dr. Iskandar Nazari untuk memperkaya pemahaman dan pengembangan potensi diri Anda.
                </p>
            </div>

            <!-- 4 Book Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                @if(isset($books) && count($books) > 0)
                    @foreach($books as $book)
                        <x-book-card 
                            :id="$book->id"
                            :title="$book->title"
                            :author="$book->author ?? 'Prof. Dr. Iskandar Nazari'"
                            :price="$book->price"
                            :badge="$loop->first ? 'Karya Utama' : 'Literasi Ruhiologi'"
                            :url="route('catalog.show', $book->slug)"
                        />
                    @endforeach
                @else
                    <x-book-card 
                        id="1"
                        title="Ruhiologi Konsep dan Aplikasi"
                        author="Prof. Dr. Iskandar Nazari"
                        price="Rp 120.000"
                        badge="Karya Utama"
                        format="Softcover · 2025"
                        image="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=400&auto=format&fit=crop"
                        url="{{ route('catalog.index') }}"
                    />
                    <x-book-card 
                        id="2"
                        title="Kecerdasan Ruhiologi Dalam Pendidikan"
                        author="Prof. Dr. Iskandar Nazari"
                        price="Rp 95.000"
                        badge="Riset Psikometri"
                        format="Softcover · 2025"
                        image="https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=400&auto=format&fit=crop"
                        url="{{ route('catalog.index') }}"
                    />
                    <x-book-card 
                        id="3"
                        title="Membangun Manusia Berkualitas"
                        author="Prof. Dr. Iskandar Nazari"
                        price="Rp 85.000"
                        badge="Pengembangan Diri"
                        format="Softcover · 2024"
                        image="https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=400&auto=format&fit=crop"
                        url="{{ route('catalog.index') }}"
                    />
                    <x-book-card 
                        id="4"
                        title="Ruh dan Pendidikan Paripurna"
                        author="Prof. Dr. Iskandar Nazari"
                        price="Rp 110.000"
                        badge="Filosofi Utama"
                        format="Hardcover · 2025"
                        image="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=400&auto=format&fit=crop"
                        url="{{ route('catalog.index') }}"
                    />
                @endif
            </div>

        </div>
    </section>

    <!-- SECTION 6: ARTIKEL & BERITA -->
    <section id="artikel" class="py-14 sm:py-20 bg-white border-b border-slate-100 scroll-mt-20">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            
            <x-section-heading 
                title="Artikel & Berita"
                subtitle="Dapatkan informasi terbaru seputar Ruhiologi, kegiatan institute, dan perkembangan gagasan."
                linkText="Lihat Semua"
                linkUrl="{{ route('articles.index') }}"
            />

            <!-- 4 Article Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @if(isset($articles) && count($articles) > 0)
                    @foreach($articles as $article)
                        <x-article-card 
                            :title="$article->title"
                            :category="$article->type === 'news' ? 'Berita' : 'Artikel'"
                            :date="$article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : '12 Mar 2025'"
                            :url="route('articles.show', $article->slug)"
                        />
                    @endforeach
                @else
                    <x-article-card 
                        title="Peran Ruh dalam Pendidikan Modern"
                        category="Artikel"
                        date="12 Mar 2025"
                        image="https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=400&auto=format&fit=crop"
                        url="{{ route('articles.index') }}"
                    />
                    <x-article-card 
                        title="Ruhiology Institute Gelar Training Batch II di Jambi"
                        category="Berita"
                        date="5 Mar 2025"
                        image="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=400&auto=format&fit=crop"
                        url="{{ route('articles.index') }}"
                    />
                    <x-article-card 
                        title="Membangun Karakter dengan Kesadaran Ruh"
                        category="Artikel"
                        date="28 Feb 2025"
                        image="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=400&auto=format&fit=crop"
                        url="{{ route('articles.index') }}"
                    />
                    <x-article-card 
                        title="Kolaborasi dengan UIN STS Jambi untuk Pengembangan RQ"
                        category="Berita"
                        date="20 Feb 2025"
                        image="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=400&auto=format&fit=crop"
                        url="{{ route('articles.index') }}"
                    />
                @endif
            </div>

        </div>
    </section>

    <!-- SECTION 6.2: TESTIMONI & DUKUNGAN TOKOH/PESERTA -->
    <section class="py-14 sm:py-20 bg-white border-b border-slate-100">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14 space-y-2">
                <span class="text-xs font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">✦ TESTIMONI & REVIEWS</span>
                <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[#0B2A43] tracking-tight">
                    Pengalaman Peserta & Peneliti Ruhiologi
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 font-normal">Apa kata para akademisi, praktisi, dan peserta yang telah mencoba asesmen serta pelatihan Ruhiology Institute.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Testimonial 1 -->
                <div class="bg-[#F8F6F0] p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4 hover:shadow-md transition">
                    <div class="space-y-3">
                        <div class="flex text-amber-400 text-sm">★★★★★</div>
                        <p class="text-xs text-slate-700 italic leading-relaxed font-serif">
                            "Asesmen RQI-15 sangat presisi. Sebagai pendidik, saya bisa melihat pemetaan karakter batin mahasiswa secara objektif dan terbantu dengan panduan perbaikannya."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-200/80">
                        <div class="w-10 h-10 rounded-full bg-[#0B2A43] text-[#C9A24D] font-bold text-xs flex items-center justify-center font-mono">
                            AF
                        </div>
                        <div>
                            <strong class="text-xs text-[#0B2A43] block font-bold">Dr. Ahmad Farhan, M.Pd.</strong>
                            <span class="text-[10px] text-slate-500 block">Dosen & Peneliti Psikologi Pendidikan</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-[#F8F6F0] p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4 hover:shadow-md transition">
                    <div class="space-y-3">
                        <div class="flex text-amber-400 text-sm">★★★★★</div>
                        <p class="text-xs text-slate-700 italic leading-relaxed font-serif">
                            "Hasil interpretasi gaya Gen Z sangat adem dan kena di hati! Tidak menghakimi, justru memberi rekomendasi amalan dan penataan niat yang sangat pas."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-200/80">
                        <div class="w-10 h-10 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center font-mono">
                            SR
                        </div>
                        <div>
                            <strong class="text-xs text-[#0B2A43] block font-bold">Siti Rahmawati, S.Psi.</strong>
                            <span class="text-[10px] text-slate-500 block">Praktisi HR & Behavioral Specialist</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-[#F8F6F0] p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4 hover:shadow-md transition">
                    <div class="space-y-3">
                        <div class="flex text-amber-400 text-sm">★★★★★</div>
                        <p class="text-xs text-slate-700 italic leading-relaxed font-serif">
                            "Sangat merekomendasikan asesmen ini untuk civitas akademika. Pendekatan integratif wahyu dan psikometri membuat kita lebih sadar akan potensi ruh."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-200/80">
                        <div class="w-10 h-10 rounded-full bg-[#C9A24D] text-[#0B2A43] font-bold text-xs flex items-center justify-center font-mono">
                            FA
                        </div>
                        <div>
                            <strong class="text-xs text-[#0B2A43] block font-bold">Fikri Al-Ghazali, M.Ag.</strong>
                            <span class="text-[10px] text-slate-500 block">Mahasiswa Pascasarjana & Peserta Asesmen</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 6.5: FAQ INTERAKTIF -->
    <section id="faq" class="py-14 sm:py-20 bg-[#F8F6F0] border-b border-slate-200/80 scroll-mt-20">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12 space-y-2">
                <span class="text-xs font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">✦ PERTANYAAN UMUM (FAQ)</span>
                <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[#0B2A43] tracking-tight">
                    Hal yang Sering Ditanyakan seputar Ruhiologi
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 font-normal">Jawaban lengkap seputar asesmen kecerdasan ruhiologi, sertifikasi, dan layanan institute.</p>
            </div>

            <div class="max-w-3xl mx-auto space-y-3" x-data="{ activeFaq: null }">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full px-5 py-4 text-left font-bold text-xs sm:text-sm text-[#0B2A43] flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer">
                        <span>1. Apa perbedaan utama Ruhiology Quotient (RQ) dengan IQ dan EQ?</span>
                        <span class="text-lg text-[#C9A24D] font-mono transition-transform duration-200 shrink-0" :class="activeFaq === 1 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse x-cloak class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        IQ berfokus pada kecerdasan rasional-logis dan EQ mengelola emosi-sosial. Sementara RQ (Ruhiology Quotient) mengukur inteligensi ruh sebagai pusat pengendali kompas nilai, purifikasi mental (Tazkiyah), integritas niat, dan kedalaman spiritual manusia.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full px-5 py-4 text-left font-bold text-xs sm:text-sm text-[#0B2A43] flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer">
                        <span>2. Berapa lama waktu pengerjaan RQ Assessment dan kapan hasilnya keluar?</span>
                        <span class="text-lg text-[#C9A24D] font-mono transition-transform duration-200 shrink-0" :class="activeFaq === 2 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse x-cloak class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Pengerjaan tes mandiri membutuhkan waktu sekitar 15–20 menit. Hasil skor beserta rekap 5 dimensi RQ dan interpretasi lengkap akan langsung muncul seketika (*real-time*) setelah Anda menekan tombol submit.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full px-5 py-4 text-left font-bold text-xs sm:text-sm text-[#0B2A43] flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer">
                        <span>3. Apakah hasil RQ Assessment bisa digunakan untuk instansi kampus/sekolah?</span>
                        <span class="text-lg text-[#C9A24D] font-mono transition-transform duration-200 shrink-0" :class="activeFaq === 3 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse x-cloak class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Sangat bisa! Ruhiology Institute menyediakan fitur Kode Peserta dan Kode Periode Khusus untuk kampus, perguruan tinggi, sekolah, dan organisasi yang ingin mengukur kecerdasan ruhiologi secara masal (Pretest & Posttest).
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button @click="activeFaq = (activeFaq === 4 ? null : 4)" class="w-full px-5 py-4 text-left font-bold text-xs sm:text-sm text-[#0B2A43] flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer">
                        <span>4. Bagaimana cara memesan buku terbitan Ruhiology Institute Press?</span>
                        <span class="text-lg text-[#C9A24D] font-mono transition-transform duration-200 shrink-0" :class="activeFaq === 4 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse x-cloak class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Anda dapat langsung memilih buku pada bagian Katalog Buku di situs ini, kemudian mengisi form pemesanan untuk mendapatkan Invoice resmi otomatis atau memesan langsung via WhatsApp tim kami.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 7: DARK NAVY BAR (QUOTE + TESTIMONIAL + CONSULTATION CTA) -->
    <section id="inspirasi" class="py-12 sm:py-18 bg-white scroll-mt-20">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">
            <div class="bg-[#0B2A43] rounded-3xl overflow-hidden text-white border border-slate-800 shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-slate-700/60">
                    
                    <!-- Col 1: Founder Quote (3.5 cols) -->
                    <div class="lg:col-span-4">
                        <x-quote-section 
                            quote="Pendidikan sejati bukan hanya tentang apa yang kita ketahui, tetapi tentang siapa yang kita menjadi."
                            author="Prof. Dr. Iskandar Nazari"
                        />
                    </div>

                    <!-- Col 2: Participant Testimonial (4.5 cols) -->
                    <div class="lg:col-span-4">
                        <x-testimonial-card 
                            content="Setelah mengikuti training Ruhiologi, saya lebih memahami diri dan tujuan hidup saya. Materinya sangat relevan dan aplikatif."
                            name="Siti Nurhaliza"
                            role="Peserta Training Ruhiologi"
                        />
                    </div>

                    <!-- Col 3: Consultation CTA (4 cols) -->
                    <div class="lg:col-span-4">
                        <x-consultation-cta 
                            title="Butuh Pendampingan Ruhiologi?"
                            description="Konsultasi dengan tim profesional kami untuk mendapatkan solusi terbaik."
                            buttonText="Konsultasi Sekarang →"
                            :url="route('consultation.index')"
                        />
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- FLOATING QUICK CONTACT WIDGET -->
    <div x-data="{ showTopBtn: false }" @scroll.window="showTopBtn = (window.pageYOffset > 300)" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 items-end">
        <a href="https://wa.me/6281234567890?text=Halo%20Ruhiology%20Institute,%20saya%20ingin%20bertanya%20seputar%20Assessment%20dan%20Pelatihan" target="_blank" class="px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-xs shadow-2xl flex items-center gap-2 transition transform hover:scale-105 border-2 border-white">
            <span class="text-base">💬</span>
            <span class="hidden sm:inline">WhatsApp Konsultasi</span>
        </a>

        <button x-show="showTopBtn" x-transition @click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="w-10 h-10 bg-[#0B2A43] hover:bg-[#123B59] text-white rounded-full font-bold text-xs shadow-lg flex items-center justify-center transition border-2 border-white cursor-pointer" title="Ke Atas Halaman">
            ↑
        </button>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('landingRadarChart');
    if (ctx && typeof Chart !== 'undefined') {
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: [
                    'Transendental',
                    'Tazkiyah Mental',
                    'Niat Intensional',
                    'Altruisme',
                    'Fungsi Sosial'
                ],
                datasets: [{
                    label: 'Benchmark RQI (%)',
                    data: [88, 82, 90, 85, 84],
                    backgroundColor: 'rgba(201, 162, 77, 0.25)',
                    borderColor: '#C9A24D',
                    borderWidth: 2,
                    pointBackgroundColor: '#C9A24D',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#C9A24D'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255, 255, 255, 0.15)' },
                        grid: { color: 'rgba(255, 255, 255, 0.15)' },
                        pointLabels: {
                            color: '#e2e8f0',
                            font: { size: 9, family: 'Plus Jakarta Sans', weight: 'bold' }
                        },
                        ticks: { display: false, beginAtZero: true, max: 100 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
