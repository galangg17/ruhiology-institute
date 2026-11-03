<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruhiology Institute — Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.</title>
    <meta name="description" content="Ruhiology Institute adalah ekosistem pendidikan, assessment, training, konsultasi, dan pengembangan berbasis Kecerdasan Ruhiologi.">

    <!-- Open Graph & Social SEO Meta Tags -->
    @hasSection('og_meta')
        @yield('og_meta')
    @else
        <meta property="og:title" content="Ruhiology Institute — Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.">
        <meta property="og:description" content="Ruhiology Institute adalah ekosistem pendidikan, assessment, training, konsultasi, dan pengembangan berbasis Kecerdasan Ruhiologi.">
        <meta property="og:image" content="{{ asset('images/ruhiology-logo.png') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ request()->fullUrl() }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Ruhiology Institute — Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.">
        <meta name="twitter:description" content="Ruhiology Institute adalah ekosistem pendidikan, assessment, training, konsultasi, dan pengembangan berbasis Kecerdasan Ruhiologi.">
        <meta name="twitter:image" content="{{ asset('images/ruhiology-logo.png') }}">
    @endif
    @stack('meta')

    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts: Poppins (Headings & UI) + Inter & Plus Jakarta Sans (Body & Tabular Data) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Chart.js for Assessment Engine -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            DEFAULT: '#0B2A43', // Midnight Navy
                            dark: '#123B59',    // Dark Navy
                            deep: '#08141E',    // Deep Footer Navy
                        },
                        ivory: {
                            DEFAULT: '#F8F6F0', // Warm Ivory
                            warm: '#F7F5F0',
                        },
                        softblue: '#EDF3F6',     // Soft Blue-Gray
                        gold: {
                            DEFAULT: '#C9A24D', // Muted Gold Accent
                            dark: '#B48A16',
                        },
                        textdark: '#193247',
                        textmuted: '#687582',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Inter"', '"Poppins"', 'sans-serif'],
                        serif: ['"Poppins"', '"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Poppins"', '"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"Poppins"', '"Inter"', 'sans-serif'],
                    },
                    maxWidth: {
                        '7xl': '1280px',
                    }
                }
            }
        }
    </script>
    <style>
        h1, h2, h3, h4, h5, h6, button, .btn {
            font-family: 'Poppins', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.01em;
        }
        table, td, th, .font-mono {
            font-variant-numeric: tabular-nums;
        }
    </style>

    <style>
        [x-cloak] { display: none !important; }

        body {
            background-color: #FFFFFF;
            color: #193247;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .nav-item {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #334155;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .nav-item:hover {
            color: #0B2A43;
        }

        .nav-item.active {
            color: #0B2A43;
            font-weight: 700;
            border-bottom: 2px solid #0B2A43;
            padding-bottom: 0.2rem;
        }

        /* Luxury Gold & Glassmorphism Utilities */
        @keyframes pulseGlowGold {
            0%, 100% { box-shadow: 0 0 15px rgba(201, 162, 77, 0.2), 0 0 30px rgba(201, 162, 77, 0.1); }
            50% { box-shadow: 0 0 25px rgba(201, 162, 77, 0.4), 0 0 45px rgba(201, 162, 77, 0.2); }
        }
        @keyframes floatSubtle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        .glow-gold { animation: pulseGlowGold 4s infinite ease-in-out; }
        .animate-float { animation: floatSubtle 6s infinite ease-in-out; }

        .glass-dark {
            background: rgba(11, 42, 67, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(201, 162, 77, 0.25);
        }
        .glass-gold {
            background: linear-gradient(135deg, rgba(201, 162, 77, 0.12) 0%, rgba(11, 42, 67, 0.3) 100%);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(201, 162, 77, 0.35);
        }
    </style>
</head>
<body class="bg-white text-[#193247] font-sans antialiased min-h-screen flex flex-col selection:bg-[#0B2A43] selection:text-white overflow-x-hidden">

    <!-- HEADER / NAVBAR COMPONENT -->
    <x-navbar />

    <!-- SYSTEM ALERTS -->
    @if(session('success'))
        <div class="max-w-[1280px] mx-auto px-6 sm:px-10 mt-4 w-full">
            <div class="bg-[#0B2A43] text-white text-xs font-semibold px-5 py-3 rounded-lg shadow flex items-center justify-between">
                <span>✨ {{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-[1280px] mx-auto px-6 sm:px-10 mt-4 w-full">
            <div class="bg-rose-900 text-white text-xs font-semibold px-5 py-3 rounded-lg shadow flex items-center justify-between">
                <span>⚠️ {{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- MAIN CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER COMPONENT -->
    <x-footer />

    <!-- GLOBAL WHATSAPP DIRECT ORDER MODAL -->
    <x-wa-order-modal />

    <!-- GLOBAL DIRECT INVOICE ORDER MODAL POPUP -->
    <x-order-modal />

    <!-- GLOBAL QUICK CHECK SCORE & POSTTEST MODAL -->
    <x-quick-check-modal />

    <!-- FLOATING BACK TO TOP WITH CIRCULAR PROGRESS RING -->
    <div x-data="{
            show: false,
            progress: 0,
            updateProgress() {
                const scrollTotal = document.documentElement.scrollHeight - window.innerHeight;
                this.progress = scrollTotal > 0 ? Math.min(100, Math.round((window.scrollY / scrollTotal) * 100)) : 0;
                this.show = window.scrollY > 300;
            },
            scrollToTop() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }"
        @scroll.window.passive="updateProgress()"
        x-cloak
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-90"
        class="fixed bottom-6 right-6 z-40 print:hidden"
    >
        <button @click="scrollToTop()" type="button" aria-label="Kembali ke atas" class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#0B2A43] text-[#C9A24D] shadow-2xl flex items-center justify-center hover:scale-110 hover:shadow-[#C9A24D]/30 transition-all border border-[#C9A24D]/40 cursor-pointer relative group">
            <!-- Circular Progress Ring SVG -->
            <svg class="w-11 h-11 sm:w-12 sm:h-12 -rotate-90 absolute inset-0 pointer-events-none" viewBox="0 0 48 48">
                <circle cx="24" cy="24" r="20" stroke="rgba(255,255,255,0.15)" stroke-width="2.5" fill="none"/>
                <circle cx="24" cy="24" r="20" stroke="#C9A24D" stroke-width="2.5" fill="none"
                        stroke-dasharray="125.6"
                        :stroke-dashoffset="125.6 - (125.6 * progress / 100)"
                        stroke-linecap="round"
                        class="transition-all duration-150"/>
            </svg>
            <svg class="w-4 h-4 text-[#C9A24D] group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    </div>

    @stack('scripts')

</body>
</html>
