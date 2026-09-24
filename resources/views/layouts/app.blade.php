<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruhiology Institute — Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.</title>
    <meta name="description" content="Ruhiology Institute adalah ekosistem pendidikan, assessment, training, konsultasi, dan pengembangan berbasis Kecerdasan Ruhiologi.">

    <!-- Open Graph SEO Tags -->
    <meta property="og:title" content="Ruhiology Institute — Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.">
    <meta property="og:description" content="Ruhiology Institute adalah ekosistem pendidikan, assessment, training, konsultasi, dan pengembangan berbasis Kecerdasan Ruhiologi.">
    <meta property="og:image" content="{{ asset('images/ruhiology-logo.png') }}">
    <meta property="og:type" content="website">

    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts: Plus Jakarta Sans (Sans) & Outfit (Display) & DM Serif Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

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
                        sans: ['"Plus Jakarta Sans"', '"Outfit"', 'Inter', 'sans-serif'],
                        serif: ['"Outfit"', '"Plus Jakarta Sans"', '"DM Serif Display"', 'sans-serif'],
                        display: ['"Outfit"', '"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    maxWidth: {
                        '7xl': '1280px',
                    }
                }
            }
        }
    </script>

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
    </style>
</head>
<body class="bg-white text-[#193247] font-sans antialiased min-h-screen flex flex-col selection:bg-[#0B2A43] selection:text-white">

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

    @stack('scripts')

</body>
</html>
