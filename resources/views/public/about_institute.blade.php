@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, rgba(11, 30, 18, 0.85) 0%, rgba(27, 67, 50, 0.9) 100%), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop'); background-size: cover; background-position: center; border-bottom: 2.5px solid var(--color-accent-gold);" class="text-white py-20 relative overflow-hidden">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 700px; height: 700px; background: radial-gradient(circle, rgba(212, 175, 55, 0.22) 0%, rgba(0,0,0,0) 70%); pointer-events: none;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 relative z-10">
        <span class="inline-block px-4 py-1.5 rounded-full bg-[#D4AF37]/20 border border-[#D4AF37] text-[#D4AF37] text-xs font-black uppercase tracking-wider">
            🎓 Profil Pendiri & Penggagas
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-sans tracking-tight text-white">Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.</h1>
        <p class="text-[#EBF3EC] text-sm max-w-2xl mx-auto font-medium leading-relaxed">
            Guru Besar Psikologi Pendidikan Universitas Islam Negeri (UIN) Sulthan Thaha Saifuddin Jambi.
        </p>
    </div>
</div>

<div class="py-20 bg-[#F7FAF7]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Profile Card -->
        <div class="bg-white border-2 border-slate-200/80 p-8 rounded-3xl flex flex-col md:flex-row gap-8 items-center shadow-md">
            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#B48A16] text-[#04140B] font-serif font-black text-4xl flex items-center justify-center shrink-0 shadow-lg border-4 border-white">
                IN
            </div>
            <div class="space-y-3 text-center md:text-left">
                <h2 class="text-2xl font-black font-sans text-[#1B4332]">Prof. Dr. Iskandar Nazari</h2>
                <p class="text-xs font-extrabold text-[#B48A16]">Guru Besar Psikologi Pendidikan • Riset & Rekayasa Ruhiologi</p>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                    Seorang akademisi, peneliti, dan pakar psikologi pendidikan yang mendedikasikan karirnya dalam menjembatani keilmuan psikologi modern dengan konsep spiritual keislaman yang menempatkan ruh sebagai pusat peradaban.
                </p>
            </div>
        </div>

        <!-- Academic Journey -->
        <section class="space-y-4 bg-white p-8 rounded-3xl border-2 border-slate-200/80 shadow-md">
            <h3 class="text-xl font-black font-sans text-[#1B4332] border-b border-slate-200 pb-3">Perjalanan Akademik & Gelar</h3>
            <ul class="space-y-4 text-xs text-slate-700 font-medium">
                <li class="flex items-start gap-3">
                    <span class="text-[#D4AF37] font-black shrink-0">✦</span>
                    <div><strong>Guru Besar Psikologi Pendidikan</strong> — Universitas Islam Negeri Sulthan Thaha Saifuddin Jambi.</div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-[#D4AF37] font-black shrink-0">✦</span>
                    <div><strong>Doktor (Ph.D.)</strong> — Riset dan Rekayasa Psikologi Pendidikan Islam.</div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-[#D4AF37] font-black shrink-0">✦</span>
                    <div><strong>Magister (M.Pd. / M.S.I. / M.H.)</strong> — Magister Pendidikan, Magister Studi Islam, dan Magister Hukum.</div>
                </li>
            </ul>
        </section>

        <!-- Main Thought -->
        <section class="space-y-4 bg-white p-8 rounded-3xl border-2 border-slate-200/80 shadow-md">
            <h3 class="text-xl font-black font-sans text-[#1B4332] border-b border-slate-200 pb-3">Pemikiran & Karya Landmark</h3>
            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                Pemikiran beliau terangkum dalam karya monumental <em>Ruhiology Quotient (RQ): Menempatkan Ruh Sebagai Pusat Potensi Kemanusiaan</em>. Buku ini menjadi acuan nasional dalam pengembangan kurikulum pendidikan karakter, asesmen spiritualitas pendidik, dan metode pelatihan eksekutif.
            </p>
        </section>

    </div>
</div>
@endsection
