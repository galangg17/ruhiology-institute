@extends('layouts.app')

@section('content')
@php $isEn = app()->getLocale() === 'en'; @endphp
<div style="background: linear-gradient(135deg, rgba(11, 30, 18, 0.85) 0%, rgba(27, 67, 50, 0.9) 100%), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop'); background-size: cover; background-position: center; border-bottom: 2.5px solid var(--color-accent-gold);" class="text-white py-20 relative overflow-hidden">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 700px; height: 700px; background: radial-gradient(circle, rgba(212, 175, 55, 0.22) 0%, rgba(0,0,0,0) 70%); pointer-events: none;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 relative z-10">
        <span class="inline-block px-4 py-1.5 rounded-full bg-[#D4AF37]/20 border border-[#D4AF37] text-[#D4AF37] text-xs font-black uppercase tracking-wider">
            🏛️ {{ $isEn ? 'Theoretical Framework & Philosophy' : 'Kerangka Teori & Filosofi' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-sans tracking-tight text-white">
            {{ $isEn ? 'About the Concept of Ruhiology Quotient (RQ)' : 'Tentang Konsep Kecerdasan Ruhiologi (RQ)' }}
        </h1>
        <p class="text-[#EBF3EC] text-sm max-w-2xl mx-auto font-medium leading-relaxed">
            {{ $isEn ? 'An integrated educational psychology paradigm positioning the soul (ruh) as the navigational center of all human potential.' : 'Gagasan psikologi pendidikan terintegrasi yang menempatkan ruh sebagai pusat navigasi seluruh potensi kemanusiaan.' }}
        </p>
    </div>
</div>

<div class="py-20 bg-[#F7FAF7]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Section 1 -->
        <section class="space-y-4 bg-white p-8 rounded-3xl border-2 border-slate-200/80 shadow-md">
            <h2 class="text-2xl font-black font-sans text-[#1B4332]">{{ $isEn ? '1. Definition & Foundations of Ruhiology' : '1. Pengertian & Latar Belakang Ruhiologi' }}</h2>
            <p class="text-slate-700 leading-relaxed text-sm">
                <strong>Ruhiology</strong> {{ $isEn ? 'was conceptualized and developed by' : 'dipopulerkan dan dirumuskan oleh' }} <strong>Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.</strong> {{ $isEn ? 'as an intellectual response to conventional modern psychology paradigms that restrict human faculties solely to rational-cognitive (IQ) and socio-emotional (EQ) dimensions.' : 'sebagai respons atas paradigma psikologi modern yang cenderung membatasi potensi manusia sebatas pada aspek rasional-kognitif (IQ) dan sosio-emosional (EQ).' }}
            </p>
            <p class="text-slate-700 leading-relaxed text-sm">
                {{ $isEn ? 'Ruhiology affirms that the core essence of human existence is the soul (ruh). The ruh is not merely a mystical impulse, but the supreme intelligence center (Ruhiology Quotient) that governs ethical values, inner purity, moral intuition, and authentic resilience in daily life.' : 'Ruhiologi menegaskan bahwa inti hakiki dari eksistensi manusia adalah ruh. Ruh bukan sekadar dorongan mistis, melainkan pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, intuisi kebenaran, serta komitmen etis dalam kehidupan nyata.' }}
            </p>
        </section>

        <!-- Section 2 -->
        <section class="space-y-6">
            <h2 class="text-2xl font-black font-sans text-[#1B4332]">{{ $isEn ? '2. Core Dimensions of Ruhiology Quotient (RQ)' : '2. Empat Dimensi Utama Ruhiology Quotient (RQ)' }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-white border-2 border-slate-200/80 rounded-3xl space-y-2 shadow-sm hover:border-[#1B4332] transition">
                    <span class="text-[#D4AF37] text-2xl font-black">✦</span>
                    <h3 class="font-extrabold text-[#1B4332] text-base">{{ $isEn ? 'Transcendental Spirituality Dimension' : 'Dimensi Spiritualitas Transendental' }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $isEn ? 'Deep awareness of the vertical connection to the Creator, mindfulness, prayer, and inner peace.' : 'Kesadaran mendalam akan hubungan vertikal dengan Sang Pencipta, zikir, dan ketenangan batiniah.' }}</p>
                </div>
                <div class="p-6 bg-white border-2 border-slate-200/80 rounded-3xl space-y-2 shadow-sm hover:border-[#1B4332] transition">
                    <span class="text-[#D4AF37] text-2xl font-black">✦</span>
                    <h3 class="font-extrabold text-[#1B4332] text-base">{{ $isEn ? 'Mental Purification Dimension (Tazkiyah)' : 'Dimensi Purifikasi Mental (Tazkiyah)' }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $isEn ? 'Purity of the heart from envy, intellectual arrogance, and self-restraint against anger and harmful impulses.' : 'Kebersihan hati dari penyakit iri, keangkuhan intelektual, dan ketahanan terhadap dorongan amarah.' }}</p>
                </div>
                <div class="p-6 bg-white border-2 border-slate-200/80 rounded-3xl space-y-2 shadow-sm hover:border-[#1B4332] transition">
                    <span class="text-[#D4AF37] text-2xl font-black">✦</span>
                    <h3 class="font-extrabold text-[#1B4332] text-base">{{ $isEn ? 'Intentional Orientation Dimension' : 'Dimensi Orientasi Intensional' }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $isEn ? 'Aligning every purpose in study, professional labor, and leadership toward divine purpose.' : 'Penyelarasan setiap niat bekerja, belajar, dan memimpin semata-mata demi keridhaan Ilahi.' }}</p>
                </div>
                <div class="p-6 bg-white border-2 border-slate-200/80 rounded-3xl space-y-2 shadow-sm hover:border-[#1B4332] transition">
                    <span class="text-[#D4AF37] text-2xl font-black">✦</span>
                    <h3 class="font-extrabold text-[#1B4332] text-base">{{ $isEn ? 'Altruism & Resilience Dimension' : 'Dimensi Altruisme & Resiliensi' }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $isEn ? 'Patience in enduring life trials, emotional fortitude, and authentic service to humanity.' : 'Kekuatan sabar menghadapi ujian dan komitmen aksi melayani masyarakat secara tulus.' }}</p>
                </div>
            </div>
        </section>

        <!-- Section 3 FAQ -->
        <section class="space-y-4 pt-6 bg-white p-8 rounded-3xl border-2 border-slate-200/80 shadow-md">
            <h2 class="text-2xl font-black font-sans text-[#1B4332]">{{ $isEn ? 'Frequently Asked Questions (FAQ)' : 'Pertanyaan yang Sering Diajukan (FAQ)' }}</h2>

            <div class="space-y-4 text-xs">
                <div class="p-5 bg-[#E8F5E9]/60 rounded-2xl border border-slate-200">
                    <h4 class="font-black text-[#1B4332] mb-1 text-sm">{{ $isEn ? 'What is the key distinction between conventional SQ (Spiritual Quotient) and RQ (Ruhiology Quotient)?' : 'Apa perbedaan utama antara SQ (Spiritual Quotient) konvensional dengan RQ (Ruhiology Quotient)?' }}</h4>
                    <p class="text-slate-600 leading-relaxed">{{ $isEn ? 'Conventional SQ is often framed in abstract, secular, or anthropocentric terms. In contrast, RQ grounds spiritual intelligence in revelation, moral purification (tazkiyah), and rigorous psychometrics.' : 'SQ konvensional seringkali disimak secara abstrak atau humanis tanpa pijakan teologis yang jelas. RQ menempatkan wahyu, purifikasi ruh, dan metodologi ilmiah pendidikan Islam sebagai fondasi utama pengukuran.' }}</p>
                </div>

                <div class="p-5 bg-[#E8F5E9]/60 rounded-2xl border border-slate-200">
                    <h4 class="font-black text-[#1B4332] mb-1 text-sm">{{ $isEn ? 'How is the RQ instrument scientifically validated?' : 'Bagaimana instrumen RQ diuji secara ilmiah?' }}</h4>
                    <p class="text-slate-600 leading-relaxed">{{ $isEn ? 'The RQI scale was developed through empirical factor analysis, construct validity testing, and cross-sectional calibration across thousands of respondents.' : 'Instrumen RQI dikembangkan melalui analisis faktor ilmiah, pengujian validitas instrumen psikometri, serta uji coba keterhandalan pada berbagai sampel pendidik dan mahasiswa.' }}</p>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
