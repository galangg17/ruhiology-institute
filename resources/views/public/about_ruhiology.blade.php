@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, rgba(11, 30, 18, 0.85) 0%, rgba(27, 67, 50, 0.9) 100%), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop'); background-size: cover; background-position: center; border-bottom: 2.5px solid var(--color-accent-gold);" class="text-white py-20 relative overflow-hidden">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 700px; height: 700px; background: radial-gradient(circle, rgba(212, 175, 55, 0.22) 0%, rgba(0,0,0,0) 70%); pointer-events: none;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 relative z-10">
        <span class="inline-block px-4 py-1.5 rounded-full bg-[#D4AF37]/20 border border-[#D4AF37] text-[#D4AF37] text-xs font-black uppercase tracking-wider">
            🏛️ Kerangka Teori & Filosofi
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-sans tracking-tight text-white">Tentang Konsep Kecerdasan Ruhiologi (RQ)</h1>
        <p class="text-[#EBF3EC] text-sm max-w-2xl mx-auto font-medium leading-relaxed">
            Gagasan psikologi pendidikan terintegrasi yang menempatkan ruh sebagai pusat navigasi seluruh potensi kemanusiaan.
        </p>
    </div>
</div>

<div class="py-20 bg-[#F7FAF7]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Section 1 -->
        <section class="space-y-4 bg-white p-8 rounded-3xl border-2 border-slate-200/80 shadow-md">
            <h2 class="text-2xl font-black font-sans text-[#1B4332]">1. Pengertian & Latar Belakang Ruhiologi</h2>
            <p class="text-slate-700 leading-relaxed text-sm">
                <strong>Ruhiologi (Ruhiology)</strong> dipopulerkan dan dirumuskan oleh <strong>Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.</strong> sebagai respons atas paradigma psikologi modern yang cenderung membatasi potensi manusia sebatas pada aspek rasional-kognitif (IQ) dan sosio-emosional (EQ).
            </p>
            <p class="text-slate-700 leading-relaxed text-sm">
                Ruhiologi menegaskan bahwa inti hakiki dari eksistensi manusia adalah <strong>ruh</strong>. Ruh bukan sekadar dorongan mistis, melainkan pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, intuisi kebenaran, serta komitmen etis dalam kehidupan nyata.
            </p>
        </section>

        <!-- Section 2 -->
        <section class="space-y-6">
            <h2 class="text-2xl font-black font-sans text-[#1B4332]">2. Empat Dimensi Utama Ruhiology Quotient (RQ)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-white border-2 border-slate-200/80 rounded-3xl space-y-2 shadow-sm hover:border-[#1B4332] transition">
                    <span class="text-[#D4AF37] text-2xl font-black">✦</span>
                    <h3 class="font-extrabold text-[#1B4332] text-base">Dimensi Spiritualitas Transendental</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Kesadaran mendalam akan hubungan vertikal dengan Sang Pencipta, zikir, dan ketenangan batiniah.</p>
                </div>
                <div class="p-6 bg-white border-2 border-slate-200/80 rounded-3xl space-y-2 shadow-sm hover:border-[#1B4332] transition">
                    <span class="text-[#D4AF37] text-2xl font-black">✦</span>
                    <h3 class="font-extrabold text-[#1B4332] text-base">Dimensi Purifikasi Mental (Tazkiyah)</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Kebersihan hati dari penyakit iri, keangkuhan intelektual, dan ketahanan terhadap dorongan amarah.</p>
                </div>
                <div class="p-6 bg-white border-2 border-slate-200/80 rounded-3xl space-y-2 shadow-sm hover:border-[#1B4332] transition">
                    <span class="text-[#D4AF37] text-2xl font-black">✦</span>
                    <h3 class="font-extrabold text-[#1B4332] text-base">Dimensi Orientasi Intensional</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Penyelarasan setiap niat bekerja, belajar, dan memimpin semata-mata demi keridhaan Ilahi.</p>
                </div>
                <div class="p-6 bg-white border-2 border-slate-200/80 rounded-3xl space-y-2 shadow-sm hover:border-[#1B4332] transition">
                    <span class="text-[#D4AF37] text-2xl font-black">✦</span>
                    <h3 class="font-extrabold text-[#1B4332] text-base">Dimensi Altruisme & Resiliensi</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Kekuatan sabar menghadapi ujian dan komitmen aksi melayani masyarakat secara tulus.</p>
                </div>
            </div>
        </section>

        <!-- Section 3 FAQ -->
        <section class="space-y-4 pt-6 bg-white p-8 rounded-3xl border-2 border-slate-200/80 shadow-md">
            <h2 class="text-2xl font-black font-sans text-[#1B4332]">Pertanyaan yang Sering Diajukan (FAQ)</h2>

            <div class="space-y-4 text-xs">
                <div class="p-5 bg-[#E8F5E9]/60 rounded-2xl border border-slate-200">
                    <h4 class="font-black text-[#1B4332] mb-1 text-sm">Apa perbedaan utama antara SQ (Spiritual Quotient) konvensional dengan RQ (Ruhiology Quotient)?</h4>
                    <p class="text-slate-600 leading-relaxed">SQ konvensional seringkali disimak secara abstrak atau humanis tanpa pijakan teologis yang jelas. RQ menempatkan wahyu, purifikasi ruh, dan metodologi ilmiah pendidikan Islam sebagai fondasi utama pengukuran.</p>
                </div>

                <div class="p-5 bg-[#E8F5E9]/60 rounded-2xl border border-slate-200">
                    <h4 class="font-black text-[#1B4332] mb-1 text-sm">Bagaimana instrumen RQ diuji secara ilmiah?</h4>
                    <p class="text-slate-600 leading-relaxed">Instrumen RQI dikembangkan melalui analisis faktor ilmiah, pengujian validitas instrumen psikometri, serta uji coba keterhandalan pada berbagai sampel pendidik dan mahasiswa.</p>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
