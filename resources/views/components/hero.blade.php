<!-- HERO SECTION COMPONENT (FULLY EDITABLE VIA SYSTEM SETTINGS & MOBILE-FIRST OPTIMIZED) -->
<section id="hero" class="relative bg-[#0B2A43] text-white pt-8 pb-16 sm:pt-14 sm:pb-24 lg:pt-20 lg:pb-28 overflow-hidden border-b border-slate-800">
    
    <!-- Background Atmosphere Image Overlay -->
    <div class="absolute inset-0 z-0 opacity-20 mix-blend-overlay">
        <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=2000" alt="Pegunungan Ruhiology Institute" class="w-full h-full object-cover">
    </div>

    <!-- Ambient Radial Mesh Glow -->
    <div class="absolute top-0 right-0 w-[350px] sm:w-[600px] h-[350px] sm:h-[600px] bg-gradient-to-br from-[#C9A24D]/25 via-[#123B59]/20 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1280px] mx-auto px-4 sm:px-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
            
            <!-- Left Content Column (7 cols) -->
            <div class="lg:col-span-7 space-y-6 sm:space-y-8 text-center lg:text-left">
                
                <!-- Eyebrow Location Pill Badge -->
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 text-[#C9A24D] text-[11px] sm:text-xs font-mono font-bold px-3.5 sm:px-4.5 py-1.5 sm:py-2 rounded-full shadow-sm">
                    <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>{{ \App\Models\Setting::get('hero_eyebrow', '✨ Ruhiology Institute · Jambi, Indonesia') }}</span>
                </div>

                <!-- Main Headline -->
                <h1 class="font-serif font-bold text-3xl sm:text-5xl lg:text-6xl text-white leading-[1.15] tracking-tight">
                    {!! nl2br(e(\App\Models\Setting::get('hero_headline', "Mengenal Diri.\nMengembangkan Potensi.\nMenumbuhkan Ruh."))) !!}
                </h1>

                <!-- Sub-headline Narrative -->
                <p class="text-slate-200 text-xs sm:text-base leading-relaxed max-w-2xl mx-auto lg:mx-0 font-normal opacity-95">
                    {{ \App\Models\Setting::get('hero_narrative', 'Ruhiology Institute adalah ekosistem pendidikan, assessment, training, konsultasi, dan pengembangan yang berfokus pada Kecerdasan Ruhiologi (Ruhiology Quotient / RQ) untuk membentuk manusia berkarakter paripurna.') }}
                </p>

                <!-- Action Buttons (CTA) -->
                <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-3 sm:gap-4">
                    <a href="#tentang-ruhiologi" class="bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-bold text-xs sm:text-sm px-7 py-3.5 sm:py-4 rounded-2xl shadow-xl hover:shadow-[#C9A24D]/30 transition transform hover:-translate-y-0.5 flex items-center justify-center space-x-2.5 min-h-[44px]">
                        <span>Eksplorasi Ruhiologi</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    <a href="#assessment" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs sm:text-sm font-bold px-6 py-3.5 sm:py-4 rounded-2xl transition backdrop-blur-md flex items-center justify-center space-x-2 min-h-[44px]">
                        <span>RQ Assessment</span>
                        <svg class="w-4 h-4 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Quick Assessment Code Checker Bar (Wired to Global Pop-Up Modal) -->
                <div class="p-3.5 sm:p-4 bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl max-w-xl mx-auto lg:mx-0 space-y-2.5 text-left" x-data="{ heroCode: '' }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-200 gap-1">
                        <span class="font-bold flex items-center gap-1.5 text-[#C9A24D]">
                            <span>🔍</span> <span>Cek Skor Assessment Cepat</span>
                        </span>
                        <span class="text-[10px] text-slate-300">Masukkan kode misal: SUB-XXXXX / PAR-XXXXX</span>
                    </div>
                    <form @submit.prevent="$dispatch('open-quick-check', { code: heroCode })" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <input type="text" x-model="heroCode" required placeholder="Contoh: SUB-LIIGMH9LQL atau PAR-ABCD1234" class="flex-1 bg-slate-900/80 border border-white/20 text-white placeholder-slate-400 text-xs px-3.5 py-2.5 rounded-xl focus:outline-none focus:border-[#C9A24D] uppercase font-mono min-h-[42px]">
                        <button type="submit" class="bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-bold text-xs px-5 py-2.5 rounded-xl transition shrink-0 shadow cursor-pointer min-h-[42px]">
                            Cek Hasil →
                        </button>
                    </form>
                </div>

                <!-- Quick Feature Badges -->
                <div class="pt-2 flex flex-wrap lg:grid lg:grid-cols-3 justify-center lg:justify-start gap-3 sm:gap-4 text-xs text-slate-200 border-t border-white/10">
                    <div class="flex items-center space-x-2 bg-white/5 px-3 py-1.5 rounded-xl border border-white/10">
                        <span class="text-[#C9A24D] font-bold text-sm">📜</span>
                        <span class="font-medium text-slate-200 text-[11px] sm:text-xs">Paradigma Pendidikan</span>
                    </div>
                    <div class="flex items-center space-x-2 bg-white/5 px-3 py-1.5 rounded-xl border border-white/10">
                        <span class="text-emerald-400 font-bold text-sm">✨</span>
                        <span class="font-medium text-slate-200 text-[11px] sm:text-xs">Kecerdasan Ruhiologi (RQ)</span>
                    </div>
                    <div class="flex items-center space-x-2 bg-white/5 px-3 py-1.5 rounded-xl border border-white/10">
                        <span class="text-amber-400 font-bold text-sm">📖</span>
                        <span class="font-medium text-slate-200 text-[11px] sm:text-xs">Berbasis Nilai Islam</span>
                    </div>
                </div>

            </div>

            <!-- Right Visual Hero Card (5 cols) - Fully Editable Image & Text -->
            <div class="lg:col-span-5 relative">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20 bg-slate-900 aspect-[4/3] sm:aspect-[4/3] lg:aspect-[3/4] group max-w-md mx-auto lg:max-w-none">
                    <img src="{{ \App\Models\Setting::get('hero_card_image', asset('images/settings/hero_card_1790081685.jpg')) }}" alt="Riset & Ekosistem Ruhiologi" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent"></div>
                    
                    <!-- Top Badge Tag -->
                    <div class="absolute top-4 left-4 bg-[#0B2A43]/95 backdrop-blur-md text-[#C9A24D] text-[10px] sm:text-[11px] font-bold px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full shadow border border-[#C9A24D]/30">
                        {{ \App\Models\Setting::get('hero_card_badge_top', '🌾 Ekosistem & Riset Ruhiologi') }}
                    </div>

                    <!-- Bottom Caption Overlay -->
                    <div class="absolute bottom-4 left-4 right-4 sm:bottom-5 sm:left-5 sm:right-5 text-white space-y-1.5 sm:space-y-2">
                        <div class="inline-flex items-center space-x-1.5 bg-[#C9A24D] text-[#0B2A43] text-[9px] sm:text-[10px] font-bold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-lg">
                            <span>{{ \App\Models\Setting::get('hero_card_badge_bottom', 'Pusat Inteligensi Ruh') }}</span>
                        </div>
                        <h3 class="font-serif text-lg sm:text-2xl font-bold leading-snug">
                            {{ \App\Models\Setting::get('hero_card_title', 'Menumbuhkan Potensi Manusia Paripurna') }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-200 font-normal opacity-90 leading-relaxed">
                            {{ \App\Models\Setting::get('hero_card_subtitle', 'Pengembangan karakter berbasis nilai wahyu dan metodologi psikometri ilmiah.') }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
