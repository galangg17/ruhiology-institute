<!-- HEADER / NAVBAR COMPONENT (PREMIUM INSTITUTIONAL DESIGN WITH GLASSMORPHISM & ACCENTS) -->
<header x-data="{ mobileOpen: false }" class="bg-white/95 backdrop-blur-md border-b border-slate-200/90 sticky top-0 z-50 shadow-sm h-[82px] flex items-center font-sans">
    
    <!-- Top Decorative Gradient Accent Bar -->
    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#0B2A43] via-[#C9A24D] to-[#0B2A43]"></div>

    <div class="max-w-[1280px] mx-auto px-6 sm:px-10 w-full">
        <div class="flex justify-between items-center h-full">
            
            <!-- Left Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                <div class="p-1 bg-slate-50 rounded-xl border border-slate-200/70 shadow-sm group-hover:border-[#C9A24D]/50 transition-colors">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology Institute Logo" class="h-9 w-auto object-contain transition-transform group-hover:scale-105">
                </div>
                <div class="flex flex-col">
                    <span class="font-black tracking-widest text-[#0B2A43] text-sm sm:text-base leading-none font-serif uppercase group-hover:text-[#C9A24D] transition-colors">
                        RUHIOLOGY
                    </span>
                    <span class="text-[9px] font-extrabold text-[#C9A24D] tracking-[0.25em] uppercase mt-0.5">
                        INSTITUTE
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Menu (Pill Bar Container with Icons & Gold Accents) -->
            <nav class="hidden lg:flex items-center bg-slate-100/70 p-1.5 rounded-full border border-slate-200/70 text-xs font-semibold text-slate-600 shadow-inner gap-1">
                
                <a href="{{ url('/') }}"
                   class="px-4 py-2 rounded-full transition-all flex items-center gap-1.5 {{ request()->is('/') ? 'bg-[#0B2A43] text-white font-bold shadow-md' : 'hover:text-[#0B2A43] hover:bg-white/80' }}">
                    <svg class="w-3.5 h-3.5 {{ request()->is('/') ? 'text-[#C9A24D]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>

                <a href="{{ url('/#tentang-ruhiologi') }}"
                   class="px-4 py-2 rounded-full transition-all flex items-center gap-1.5 hover:text-[#0B2A43] hover:bg-white/80">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Tentang Kami</span>
                </a>

                <a href="{{ url('/#assessment') }}"
                   class="px-4 py-2 rounded-full transition-all flex items-center gap-1.5 hover:text-[#0B2A43] hover:bg-white/80">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>RQ Assessment</span>
                </a>

                <a href="{{ url('/#training') }}"
                   class="px-4 py-2 rounded-full transition-all flex items-center gap-1.5 hover:text-[#0B2A43] hover:bg-white/80">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    <span>Training Center</span>
                </a>

                <a href="{{ route('catalog.index') }}"
                   class="px-4 py-2 rounded-full transition-all flex items-center gap-1.5 {{ request()->routeIs('catalog.*') ? 'bg-[#0B2A43] text-white font-bold shadow-md' : 'hover:text-[#0B2A43] hover:bg-white/80' }}">
                    <svg class="w-3.5 h-3.5 {{ request()->routeIs('catalog.*') ? 'text-[#C9A24D]' : 'text-[#C9A24D]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Katalog Buku</span>
                </a>

                <a href="{{ url('/#artikel') }}"
                   class="px-4 py-2 rounded-full transition-all flex items-center gap-1.5 hover:text-[#0B2A43] hover:bg-white/80">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>Artikel</span>
                </a>

            </nav>

            <!-- Right Action CTA Button (Proportional Rounded-XL Style with Proper Padding) -->
            <div class="hidden sm:flex items-center gap-2.5 shrink-0">
                <button @click="$dispatch('open-quick-check', { code: '' })" type="button" class="py-2.5 px-4.5 bg-amber-500/10 hover:bg-amber-500/20 text-[#0B2A43] font-bold text-xs rounded-xl border border-[#C9A24D]/40 transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                    <span>🔍</span> <span>Cek Skor / Mulai</span>
                </button>


                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="py-2.5 px-6 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-sm hover:shadow-md border border-[#C9A24D]/30 transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer group">
                        <svg class="w-4 h-4 text-[#C9A24D] group-hover:rotate-12 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>{{ auth()->user()->isAdmin() ? 'Dashboard Admin' : 'Dashboard Saya' }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="py-2.5 px-6 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-sm hover:shadow-md border border-[#C9A24D]/30 transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer group">
                        <svg class="w-4 h-4 text-[#C9A24D] group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>Masuk / Dashboard</span>
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="lg:hidden flex items-center">
                <button @click="mobileOpen = !mobileOpen" type="button" aria-label="Toggle Navigation" class="text-[#0B2A43] p-2.5 focus:outline-none rounded-xl hover:bg-slate-100 transition border border-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileOpen" x-cloak class="lg:hidden bg-white/95 backdrop-blur-md border-b border-slate-200 px-6 pt-4 pb-6 space-y-3 text-xs font-semibold shadow-2xl">
        <a href="{{ url('/') }}" @click="mobileOpen = false" class="flex items-center gap-2 text-slate-800 py-2 border-b border-slate-100 hover:text-[#0B2A43]">
            <span>🏠</span>
            <span>Beranda</span>
        </a>
        <a href="{{ url('/#tentang-ruhiologi') }}" @click="mobileOpen = false" class="flex items-center gap-2 text-slate-800 py-2 border-b border-slate-100 hover:text-[#0B2A43]">
            <span>🏛️</span>
            <span>Tentang Kami</span>
        </a>
        <a href="{{ url('/#assessment') }}" @click="mobileOpen = false" class="flex items-center gap-2 text-slate-800 py-2 border-b border-slate-100 hover:text-[#0B2A43]">
            <span>📄</span>
            <span>RQ Assessment</span>
        </a>
        <a href="{{ url('/#training') }}" @click="mobileOpen = false" class="flex items-center gap-2 text-slate-800 py-2 border-b border-slate-100 hover:text-[#0B2A43]">
            <span>💡</span>
            <span>Training Center</span>
        </a>
        <a href="{{ route('catalog.index') }}" @click="mobileOpen = false" class="flex items-center gap-2 text-slate-800 py-2 border-b border-slate-100 hover:text-[#0B2A43]">
            <span>📚</span>
            <span>Katalog Buku</span>
        </a>
        <a href="{{ url('/#artikel') }}" @click="mobileOpen = false" class="flex items-center gap-2 text-slate-800 py-2 border-b border-slate-100 hover:text-[#0B2A43]">
            <span>📰</span>
            <span>Artikel</span>
        </a>
        <div class="pt-3">
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="text-center block w-full py-3 text-xs font-bold text-white bg-[#0B2A43] rounded-xl shadow-md border border-[#C9A24D]/30">
                    {{ auth()->user()->isAdmin() ? 'Dashboard Admin' : 'Dashboard Saya' }}
                </a>
            @else
                <a href="{{ route('login') }}" class="text-center block w-full py-3 text-xs font-bold text-white bg-[#0B2A43] rounded-xl shadow-md border border-[#C9A24D]/30">Masuk / Dashboard</a>
            @endauth
        </div>
    </div>
</header>
