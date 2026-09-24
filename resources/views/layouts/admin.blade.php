<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - {{ \App\Models\Setting::get('institute_name', 'RUHIOLOGY INSTITUTE') }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                            200: '#BBF7D0',
                            600: '#10B981',
                            700: '#059669',
                            800: '#047857',
                            900: '#064E3B',
                            950: '#032E23',
                            night: '#081C15'
                        },
                        navy: { 800: '#1E293B', 900: '#0F172A', 950: '#0B132B' },
                        amber: { 500: '#F59E0B', 600: '#D97706', 700: '#B45309' }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Outfit"', 'sans-serif'],
                        serif: ['"Outfit"', '"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- SIDEBAR NAV -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-navy-950 text-slate-300 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col border-r border-slate-800/80 shadow-2xl shrink-0"
    >
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center justify-between px-6 bg-navy-950 border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology Institute Logo" class="h-11 w-auto object-contain drop-shadow-[0_2px_8px_rgba(0,0,0,0.5)]">
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                ✕
            </button>
        </div>

        <!-- Navigation Links (Streamlined, Minimal & Organized) -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 text-xs font-semibold">
            @php
                $pendingInstCount = \App\Models\PendingInstitution::where('status', 'pending')->count();
                $pendingOrderCount = \App\Models\Order::where('payment_status', 'pending')->count();
            @endphp

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#C9A24D] text-[#0B2A43] font-bold shadow-md' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard</span>
            </a>

            <!-- Group 1: Modul RQ Assessment -->
            <div class="pt-4 pb-1 px-3.5 text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest font-mono">Modul RQ Assessment</div>
            
            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.events.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>🎯</span> <span>Manajemen Event / Acara</span>
            </a>
            <a href="{{ route('admin.participants.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.participants.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>👥</span> <span>Data Peserta</span>
            </a>
            <a href="{{ route('admin.results.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.results.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>📈</span> <span>Hasil & Rekap Asesmen</span>
            </a>
            <a href="{{ route('admin.master_data.index') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.master_data.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <span>🗺️</span> <span>Master Data & Importer</span>
                </div>
                @if($pendingInstCount > 0)
                    <span class="bg-amber-500 text-slate-950 font-bold px-2 py-0.5 rounded-full text-[10px] shadow" title="{{ $pendingInstCount }} Usulan Kampus Baru">{{ $pendingInstCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.instruments.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.instruments.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>⚙️</span> <span>Instrumen & Scoring</span>
            </a>

            <!-- Group 2: Layanan & Program -->
            <div class="pt-4 pb-1 px-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Layanan & Institusi</div>

            <a href="{{ route('admin.institutions.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.institutions.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>🏛️</span> <span>Institusi Mitra</span>
            </a>
            <a href="{{ route('admin.training.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.training.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>🎯</span> <span>Training Programs</span>
            </a>
            <a href="{{ route('admin.consultations.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.consultations.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>💬</span> <span>Konsultasi</span>
            </a>

            <!-- Group 3: Buku Press & Konten Website -->
            <div class="pt-4 pb-1 px-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Buku Press & Konten</div>

            <a href="{{ route('admin.products.index') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.orders.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <span>📚</span> <span>Katalog Buku & Pesanan</span>
                </div>
                @if($pendingOrderCount > 0)
                    <span class="bg-rose-500 text-white font-bold px-2 py-0.5 rounded-full text-[10px] shadow" title="{{ $pendingOrderCount }} Pesanan Pending">{{ $pendingOrderCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.articles.*') || request()->routeIs('admin.quotes.*') || request()->routeIs('admin.media.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>📰</span> <span>Artikel, Quotes & Media</span>
            </a>

            <!-- Group 4: Sistem & Pengaturan -->
            <div class="pt-4 pb-1 px-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Pengaturan Sistem</div>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.audit_logs.*') || request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-[#C9A24D] font-bold border-l-4 border-[#C9A24D]' : 'hover:bg-slate-900 text-slate-300 hover:text-white' }}">
                <span>🔑</span> <span>User, Audit & Setting</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 bg-navy-950 border-t border-slate-800 text-[11px] text-slate-400">
            <div class="flex items-center justify-between">
                <span>Versi: 1.0.0</span>
                <a href="{{ route('home') }}" target="_blank" class="text-amber-400 hover:underline">Situs Publik ↗</a>
            </div>
        </div>
    </aside>

    <!-- MAIN BODY CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- ADMIN TOPBAR HEADER -->
        <header class="h-20 bg-white border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between shadow-sm sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-slate-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="hidden sm:block">
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-widest block">ADMINISTRATIVE SYSTEM</span>
                    <h1 class="text-base sm:text-lg font-bold font-serif text-slate-900 tracking-tight">{{ $title ?? 'Dashboard Overview' }}</h1>
                </div>
            </div>

            <!-- Global Quick Search Bar -->
            <div class="flex-1 max-w-md mx-4">
                <form action="{{ route('admin.results.index') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Cari nama peserta, kode submission, pesanan..." class="w-full pl-9 pr-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]/20 focus:border-[#0B2A43] focus:bg-white transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
            </div>

            <!-- User Menu & Quick Role Badge -->
            <div class="flex items-center gap-4" x-data="{ userMenu: false }">
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300/80">
                    {{ auth()->user()->role_display_name }}
                </span>

                <div class="relative">
                    <button @click="userMenu = !userMenu" class="flex items-center gap-3 text-xs font-bold text-slate-700 hover:text-slate-900 focus:outline-none bg-slate-100 p-1.5 rounded-xl border border-slate-200">
                        <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="hidden md:inline pr-1">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="userMenu" @click.away="userMenu = false" x-cloak class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl border border-slate-200 py-2 z-50 text-xs font-medium">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="font-bold text-slate-900">{{ auth()->user()->name }}</p>
                            <p class="text-slate-500 text-[10px] truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('home') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Kunjungi Situs Publik</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-bold">Keluar Sesi</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        @if(session('success'))
            <div class="bg-emerald-600 text-white text-xs font-semibold px-6 py-3 shadow-md flex justify-between items-center">
                <span>✅ {{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-600 text-white text-xs font-semibold px-6 py-3 shadow-md flex justify-between items-center">
                <span>⚠️ {{ session('error') }}</span>
            </div>
        @endif

        <!-- MAIN CONTAINER -->
        <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
