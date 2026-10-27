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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                        sans: ['"Plus Jakarta Sans"', '"Inter"', '"Poppins"', 'sans-serif'],
                        serif: ['"Poppins"', '"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Poppins"', '"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"Poppins"', '"Inter"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        h1, h2, h3, h4, h5, h6, button {
            font-family: 'Poppins', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.01em;
        }
        table, td, th, .font-mono {
            font-variant-numeric: tabular-nums;
        }
        @media print {
            body {
                background: #ffffff !important;
                display: block !important;
                min-height: auto !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            aside, header, .no-print {
                display: none !important;
            }
            main {
                padding: 0 !important;
                overflow: visible !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex print:block print:bg-white" x-data="{ sidebarOpen: false }">

    <!-- SIDEBAR NAV -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="print:hidden fixed inset-y-0 left-0 z-40 w-64 bg-navy-950 text-slate-300 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col border-r border-slate-800/80 shadow-2xl shrink-0"
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

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard</span>
            </a>

            <!-- Group 1: Modul RQ Assessment -->
            <div class="pt-4 pb-1 px-3.5 text-[10px] font-extrabold text-[#D4AF37] uppercase tracking-widest font-mono">Modul RQ Assessment</div>
            
            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.events.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Manajemen Event / Acara</span>
            </a>
            <a href="{{ route('admin.participants.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.participants.*') || request()->routeIs('admin.results.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Data Peserta & Hasil Evaluasi</span>
            </a>
            <a href="{{ route('admin.master_data.index') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.master_data.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Master Data & Importer</span>
                </div>
                @if($pendingInstCount > 0)
                    <span class="bg-[#8C703E] text-white font-bold px-2 py-0.5 rounded-full text-[10px] shadow" title="{{ $pendingInstCount }} Usulan Kampus Baru">{{ $pendingInstCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.instruments.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.instruments.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Paket Soal & Bank Soal</span>
            </a>
            <a href="{{ route('admin.forms.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.forms.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>Formulir & Kuisioner</span>
                <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Baru</span>
            </a>

            <!-- Group 2: Layanan & Program -->
            <div class="pt-4 pb-1 px-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Layanan & Institusi</div>

            <a href="{{ route('admin.institutions.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.institutions.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Institusi Mitra</span>
            </a>
            <a href="{{ route('admin.training.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.training.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Training Programs</span>
            </a>
            <a href="{{ route('admin.consultations.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.consultations.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>Konsultasi</span>
            </a>

            <!-- Group 3: Buku Press & Konten Website -->
            <div class="pt-4 pb-1 px-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Buku Press & Konten</div>

            <a href="{{ route('admin.products.index') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.orders.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Katalog Buku & Pesanan</span>
                </div>
                @if($pendingOrderCount > 0)
                    <span class="bg-rose-500 text-white font-bold px-2 py-0.5 rounded-full text-[10px] shadow" title="{{ $pendingOrderCount }} Pesanan Pending">{{ $pendingOrderCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.articles.*') || request()->routeIs('admin.quotes.*') || request()->routeIs('admin.media.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <span>Artikel, Quotes & Media</span>
            </a>

            <!-- Group 4: Sistem & Pengaturan -->
            <div class="pt-4 pb-1 px-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest font-mono">Pengaturan Sistem</div>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Pengaturan Landing & CMS</span>
            </a>
            <a href="{{ route('admin.settings.index') }}#founder_general" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all text-[#D4AF37]/90 hover:bg-white/5 hover:text-white">
                <svg class="w-4 h-4 shrink-0 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Profil Founder (Prof. Iskandar)</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.audit_logs.*') ? 'bg-[#123B59] text-white font-bold border-l-4 border-[#D4AF37] shadow-sm' : 'hover:bg-white/5 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Manajemen User & Audit Log</span>
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
    <div class="flex-1 flex flex-col min-w-0 print:block">
        
        <!-- ADMIN TOPBAR HEADER -->
        <header class="print:hidden h-20 bg-white border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between shadow-sm sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-slate-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="hidden sm:block">
                    <span class="text-[10px] font-mono font-bold text-[#8C703E] uppercase tracking-widest block">ADMINISTRATIVE SYSTEM</span>
                    <h1 class="text-base sm:text-lg font-bold font-serif text-[#0F2537] tracking-tight">{{ $title ?? 'Dashboard Overview' }}</h1>
                </div>
            </div>

            <!-- Global Quick Search Bar -->
            <div class="flex-1 max-w-md mx-4">
                <form action="{{ route('admin.results.index') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Cari nama peserta, kode submission, pesanan..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-[#0F2537] focus:border-[#0F2537] focus:bg-white transition outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
            </div>

            <!-- User Menu & Quick Role Badge -->
            <div class="flex items-center gap-4" x-data="{ userMenu: false }">
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#F4F1EA] text-[#8C703E] border border-[#DFD8C8]">
                    {{ auth()->user()->role_display_name }}
                </span>

                <div class="relative">
                    <button @click="userMenu = !userMenu" class="flex items-center gap-3 text-xs font-bold text-slate-700 hover:text-slate-900 focus:outline-none bg-slate-50 p-1.5 rounded-xl border border-slate-200 cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-[#0F2537] text-[#D4AF37] flex items-center justify-center font-bold text-xs shadow-sm">
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
                            <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-bold cursor-pointer">Keluar Sesi</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        @if(session('success'))
            <div class="print:hidden bg-emerald-700 text-white text-xs font-semibold px-6 py-3 shadow-md flex justify-between items-center">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </span>
            </div>
        @endif
        @if(session('error'))
            <div class="print:hidden bg-rose-700 text-white text-xs font-semibold px-6 py-3 shadow-md flex justify-between items-center">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ session('error') }}
                </span>
            </div>
        @endif

        <!-- MAIN CONTAINER -->
        <main class="flex-1 p-4 sm:p-8 overflow-y-auto print:p-0 print:overflow-visible">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
