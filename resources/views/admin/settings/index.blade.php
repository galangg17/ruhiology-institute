@extends('layouts.admin')

@section('content')
<div x-data="{
    settingsTab: (function() {
        const hash = (window.location.hash || '').replace('#', '');
        if (hash === 'founder' || hash === 'kontak-rekening' || hash === 'founder_general') return 'founder_general';
        if (hash === 'hero-card' || hash === 'dokumentasi-filosofi' || hash === 'hero_philosophy') return 'hero_philosophy';
        if (hash === 'landasan-teori' || hash === 'enam-pilar' || hash === 'theory_dimensions') return 'theory_dimensions';
        if (hash === 'kenali-profil' || hash === 'alur-proses' || hash === 'assessment_flow') return 'assessment_flow';
        if (hash === 'sertifikat-preview' || hash === 'certificate_preview') return 'certificate_preview';
        return 'hero_philosophy';
    })(),
    founderName: '{{ addslashes($settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.') }}',
    founderTitle: '{{ addslashes($settings['founder_title'] ?? 'Founder Ruhiology Institute & Guru Besar UIN STS Jambi') }}',
    setTab(tab) {
        this.settingsTab = tab;
        window.location.hash = tab;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    getTabName() {
        const names = {
            'hero_philosophy': 'Hero & Filosofi',
            'theory_dimensions': 'Teori & 6 Dimensi',
            'assessment_flow': 'Showcase Asesmen & Alur',
            'certificate_preview': 'Template Sertifikat & Preview',
            'founder_general': 'Profil Founder, Kontak & Bank'
        };
        return names[this.settingsTab] || 'Pengaturan';
    }
}" x-init="
    window.addEventListener('hashchange', () => {
        const hash = (window.location.hash || '').replace('#', '');
        if (hash === 'founder' || hash === 'kontak-rekening' || hash === 'founder_general') settingsTab = 'founder_general';
        else if (hash === 'hero-card' || hash === 'dokumentasi-filosofi' || hash === 'hero_philosophy') settingsTab = 'hero_philosophy';
        else if (hash === 'landasan-teori' || hash === 'enam-pilar' || hash === 'theory_dimensions') settingsTab = 'theory_dimensions';
        else if (hash === 'kenali-profil' || hash === 'alur-proses' || hash === 'assessment_flow') settingsTab = 'assessment_flow';
        else if (hash === 'sertifikat-preview' || hash === 'certificate_preview') settingsTab = 'certificate_preview';
    });
" class="space-y-6 pb-28">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs uppercase font-mono font-bold tracking-wider text-slate-400">Pengaturan CMS</span>
                <span class="text-slate-300">•</span>
                <span class="text-[11px] font-bold text-[#D4AF37] bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60" x-text="getTabName()"></span>
            </div>
            <h2 class="text-xl font-bold font-serif text-slate-900 mt-1">Pengaturan Sistem & Konten Landing Page</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola narasi filosofi, kerangka 6 dimensi, template sertifikat digital, dan identitas institusi.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[11px] font-medium text-slate-400 hidden md:inline">5 Kategori Fokus</span>
        </div>
    </div>

    <!-- TABBED NAVIGATION BAR (Sticky) -->
    <div class="bg-white/95 backdrop-blur-md p-1.5 rounded-2xl border border-slate-200 shadow-xs sticky top-3 z-30">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-1.5 text-xs font-sans">
            <!-- TAB 1 -->
            <button type="button" @click="setTab('hero_philosophy')"
                :class="settingsTab === 'hero_philosophy' ? 'bg-[#0B2A43] text-white shadow-sm ring-1 ring-[#0B2A43]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700'"
                class="p-2.5 rounded-xl transition text-left flex flex-col justify-center cursor-pointer group">
                <div class="flex items-center gap-1.5 font-bold text-xs">
                    <span>🌟</span>
                    <span class="truncate">Hero & Filosofi</span>
                </div>
                <span :class="settingsTab === 'hero_philosophy' ? 'text-amber-300' : 'text-slate-400'" class="text-[10px] truncate mt-0.5 font-medium">Hero Card & Riset</span>
            </button>

            <!-- TAB 2 -->
            <button type="button" @click="setTab('theory_dimensions')"
                :class="settingsTab === 'theory_dimensions' ? 'bg-[#0B2A43] text-white shadow-sm ring-1 ring-[#0B2A43]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700'"
                class="p-2.5 rounded-xl transition text-left flex flex-col justify-center cursor-pointer group">
                <div class="flex items-center gap-1.5 font-bold text-xs">
                    <span>🔬</span>
                    <span class="truncate">Teori & 6 Dimensi</span>
                </div>
                <span :class="settingsTab === 'theory_dimensions' ? 'text-amber-300' : 'text-slate-400'" class="text-[10px] truncate mt-0.5 font-medium">Landasan & WHO-5</span>
            </button>

            <!-- TAB 3 -->
            <button type="button" @click="setTab('assessment_flow')"
                :class="settingsTab === 'assessment_flow' ? 'bg-[#0B2A43] text-white shadow-sm ring-1 ring-[#0B2A43]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700'"
                class="p-2.5 rounded-xl transition text-left flex flex-col justify-center cursor-pointer group">
                <div class="flex items-center gap-1.5 font-bold text-xs">
                    <span>📊</span>
                    <span class="truncate">Showcase & Alur</span>
                </div>
                <span :class="settingsTab === 'assessment_flow' ? 'text-amber-300' : 'text-slate-400'" class="text-[10px] truncate mt-0.5 font-medium">Profil RQ & 4 Langkah</span>
            </button>

            <!-- TAB 4 -->
            <button type="button" @click="setTab('certificate_preview')"
                :class="settingsTab === 'certificate_preview' ? 'bg-[#0B2A43] text-white shadow-sm ring-1 ring-[#0B2A43]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700'"
                class="p-2.5 rounded-xl transition text-left flex flex-col justify-center cursor-pointer group">
                <div class="flex items-center gap-1.5 font-bold text-xs">
                    <span>📜</span>
                    <span class="truncate">Sertifikat & Preview</span>
                </div>
                <span :class="settingsTab === 'certificate_preview' ? 'text-amber-300' : 'text-slate-400'" class="text-[10px] truncate mt-0.5 font-medium">A4 PDF & Story 9:16</span>
            </button>

            <!-- TAB 5 -->
            <button type="button" @click="setTab('founder_general')"
                :class="settingsTab === 'founder_general' ? 'bg-[#0B2A43] text-white shadow-sm ring-1 ring-[#0B2A43]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700'"
                class="p-2.5 rounded-xl transition text-left flex flex-col justify-center col-span-2 sm:col-span-1 cursor-pointer group">
                <div class="flex items-center gap-1.5 font-bold text-xs">
                    <span>👤</span>
                    <span class="truncate">Founder & Kontak</span>
                </div>
                <span :class="settingsTab === 'founder_general' ? 'text-amber-300' : 'text-slate-400'" class="text-[10px] truncate mt-0.5 font-medium">Prof. Iskandar & Bank</span>
            </button>
        </div>
    </div>

    <!-- FORM UTAMA -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" novalidate class="space-y-6 text-xs font-sans">
        @csrf

        <!-- ========================================== -->
        <!-- TAB 1: HERO & FILOSOFI                     -->
        <!-- ========================================== -->
        <div x-show="settingsTab === 'hero_philosophy'" class="space-y-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-blue-50/70 border border-blue-200/70 p-4 rounded-2xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🌟</span>
                    <div>
                        <h4 class="font-bold text-sm text-[#0B2A43]">Bagian 1: Hero Beranda & Narasi Filosofi</h4>
                        <p class="text-[11px] text-slate-600">Mengatur visual kartu kanan pada hero section serta dokumentasi filosofi kesadaran ruh.</p>
                    </div>
                </div>
                <span class="text-[11px] font-mono font-bold bg-white px-2.5 py-1 rounded-lg border border-blue-200 text-[#0B2A43]">Tab 1 dari 5</span>
            </div>

        <!-- Hero Card & Visual Banner Settings -->
        <div id="hero-card" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 scroll-mt-16">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2 flex items-center gap-2">
                <span>🖼️</span>
                <span>Kartu Visual Hero Beranda (Kartu Kanan Hero)</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Upload Gambar Hero Card Baru</label>
                    <input type="file" name="hero_card_image_file" accept="image/*" class="w-full p-2 rounded border border-slate-300 bg-slate-50">
                    <p class="text-[11px] text-slate-400 mt-1">Pilih file gambar baru jika ingin mengganti gambar pemandangan/riset hero card.</p>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Gambar Hero Card</label>
                    <input type="text" name="hero_card_image" value="{{ $settings['hero_card_image'] ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=1200' }}" class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Atas (Top Tag)</label>
                    <input type="text" name="hero_card_badge_top" value="{{ $settings['hero_card_badge_top'] ?? '🌾 Ekosistem & Riset Ruhiologi' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Bawah (Bottom Pill)</label>
                    <input type="text" name="hero_card_badge_bottom" value="{{ $settings['hero_card_badge_bottom'] ?? 'Pusat Inteligensi Ruh' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Judul Kartu Hero (Title)</label>
                <input type="text" name="hero_card_title" value="{{ $settings['hero_card_title'] ?? 'Menumbuhkan Potensi Manusia Paripurna' }}" required class="w-full p-2.5 rounded border border-slate-300 font-serif font-bold text-sm">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi / Subjudul Kartu Hero</label>
                <textarea name="hero_card_subtitle" rows="2" required class="w-full p-2.5 rounded border border-slate-300">{{ $settings['hero_card_subtitle'] ?? 'Pengembangan karakter berbasis nilai wahyu dan metodologi psikometri ilmiah.' }}</textarea>
            </div>
        </div>


        <!-- Section 2: Storytelling & Filosofi Ruhiologi Settings -->
        <div id="dokumentasi-filosofi" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 scroll-mt-16">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2 flex items-center gap-2">
                <span>📚</span>
                <span>Section 2: Dokumentasi & Filosofi Ruhiologi</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Upload Gambar Dokumentasi Section 2</label>
                    <input type="file" name="about_image_file" accept="image/*" class="w-full p-2 rounded border border-slate-300 bg-slate-50">
                    <p class="text-[11px] text-slate-400 mt-1">Pilih file gambar baru jika ingin mengganti gambar buku & kopi section 2.</p>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Gambar Dokumentasi Section 2</label>
                    <input type="text" name="about_image" value="{{ $settings['about_image'] ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=1200' }}" class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tag Gambar Dokumentasi</label>
                    <input type="text" name="about_image_tag" value="{{ $settings['about_image_tag'] ?? 'DOKUMENTASI RISET & KARYA RUHIOLOGI' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Kategori Section 2</label>
                    <input type="text" name="about_badge" value="{{ $settings['about_badge'] ?? 'Filosofi & Kesadaran Ruh' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Keterangan Foto Dokumentasi (Caption)</label>
                <input type="text" name="about_image_caption" value="{{ $settings['about_image_caption'] ?? 'Prof. Dr. Iskandar Nazari merumuskan konsep Ruhiology Quotient (RQ) sebagai navigasi potensi manusia.' }}" required class="w-full p-2.5 rounded border border-slate-300">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Judul Utama Filosofi (Title)</label>
                <input type="text" name="about_title" value="{{ $settings['about_title'] ?? 'Bukan Sekadar Ilmu: Menempatkan Ruh Sebagai Pusat Peradaban' }}" required class="w-full p-2.5 rounded border border-slate-300 font-serif font-bold text-sm">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Paragraf Narasi Filosofi</label>
                <textarea name="about_description" rows="3" required class="w-full p-2.5 rounded border border-slate-300">{{ $settings['about_description'] ?? 'Ruhiologi dipopulerkan dan dirumuskan sebagai respons atas paradigma psikologi modern yang cenderung membatasi potensi manusia sebatas pada aspek rasional-kognitif (IQ) dan sosio-emosional (EQ).' }}</textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Kutipan Khusus (Quote Box)</label>
                <textarea name="about_quote" rows="3" required class="w-full p-2.5 rounded border border-slate-300 font-serif italic">{{ $settings['about_quote'] ?? 'Ruh bukan sekadar dorongan mistis, melainkan pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, intuisi kebenaran, serta komitmen etis dalam kehidupan nyata.' }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Fitur Poin 1</label>
                    <input type="text" name="about_feature_1" value="{{ $settings['about_feature_1'] ?? 'Integrasi Wahyu & Sains' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Fitur Poin 2</label>
                    <input type="text" name="about_feature_2" value="{{ $settings['about_feature_2'] ?? 'Purifikasi Batin (Tazkiyah)' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Fitur Poin 3</label>
                    <input type="text" name="about_feature_3" value="{{ $settings['about_feature_3'] ?? 'Resiliensi Spiritual' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>
        </div>


            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-white font-extrabold rounded-xl shadow-md transition cursor-pointer flex items-center gap-2">
                    <span>💾</span> Simpan Perubahan Hero & Filosofi
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: TEORI & 6 PILAR DIMENSI             -->
        <!-- ========================================== -->
        <div x-show="settingsTab === 'theory_dimensions'" class="space-y-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-emerald-50/70 border border-emerald-200/70 p-4 rounded-2xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🔬</span>
                    <div>
                        <h4 class="font-bold text-sm text-emerald-950">Bagian 2: Landasan Riset & Enam Pilar Dimensi (RQI-15 + WHO-5)</h4>
                        <p class="text-[11px] text-emerald-800">Mengatur metodologi riset psikometri dan 6 pilar dimensi lengkap yang dirumuskan Prof. Dr. Iskandar Nazari.</p>
                    </div>
                </div>
                <span class="text-[11px] font-mono font-bold bg-white px-2.5 py-1 rounded-lg border border-emerald-200 text-emerald-900">Tab 2 dari 5</span>
            </div>

        <!-- Section: Landasan Teori & Metodologi -->
        <div id="landasan-teori" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 scroll-mt-16">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <span>🔬</span>
                    <span>Section: Landasan Teori & Metodologi Riset</span>
                </span>
                <span class="text-[10px] font-mono bg-blue-50 text-blue-800 px-2.5 py-0.5 rounded-full font-bold">Landing Page Section</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Atas Section</label>
                    <input type="text" name="theory_badge" value="{{ $settings['theory_badge'] ?? '✦ LANDASAN TEORI & METODOLOGI' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Utama Riset & Teori</label>
                    <input type="text" name="theory_title" value="{{ $settings['theory_title'] ?? 'Riset Psikometri & Integrasi Nilai-Nilai Ruhiologi' }}" required class="w-full p-2.5 rounded border border-slate-300 font-serif font-bold text-sm">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Narasi Teori</label>
                <textarea name="theory_description" rows="2" required class="w-full p-2.5 rounded border border-slate-300 leading-relaxed">{{ $settings['theory_description'] ?? 'Pengembangan instrumen kecerdasan ruhiologi (Ruhiology Quotient) berakar pada riset panjang Prof. Dr. Iskandar Nazari yang memadukan kajian tekstual-klasik dan uji validitas psikometri ilmiah modern.' }}</textarea>
            </div>

            <!-- 4 Metrik Kunci -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                <span class="font-bold text-slate-800 text-xs block">📊 4 Indikator / Metrik Kunci Riset</span>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[11px] font-semibold text-slate-600">Metrik 1 (Nilai & Label)</label>
                        <input type="text" name="theory_metric1_val" value="{{ $settings['theory_metric1_val'] ?? '> 0.88' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-mono font-bold bg-white">
                        <input type="text" name="theory_metric1_lbl" value="{{ $settings['theory_metric1_lbl'] ?? 'Reliabilitas Alpha' }}" class="w-full p-1.5 rounded border border-slate-300 text-[11px] bg-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-semibold text-slate-600">Metrik 2 (Nilai & Label)</label>
                        <input type="text" name="theory_metric2_val" value="{{ $settings['theory_metric2_val'] ?? 'RQI-15' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-mono font-bold bg-white">
                        <input type="text" name="theory_metric2_lbl" value="{{ $settings['theory_metric2_lbl'] ?? 'Scale Terstruktur' }}" class="w-full p-1.5 rounded border border-slate-300 text-[11px] bg-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-semibold text-slate-600">Metrik 3 (Nilai & Label)</label>
                        <input type="text" name="theory_metric3_val" value="{{ $settings['theory_metric3_val'] ?? 'WHO-5' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-mono font-bold bg-white">
                        <input type="text" name="theory_metric3_lbl" value="{{ $settings['theory_metric3_lbl'] ?? 'Well-being Index' }}" class="w-full p-1.5 rounded border border-slate-300 text-[11px] bg-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-semibold text-slate-600">Metrik 4 (Nilai & Label)</label>
                        <input type="text" name="theory_metric4_val" value="{{ $settings['theory_metric4_val'] ?? '100%' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-mono font-bold bg-white">
                        <input type="text" name="theory_metric4_lbl" value="{{ $settings['theory_metric4_lbl'] ?? 'Peer-Reviewed' }}" class="w-full p-1.5 rounded border border-slate-300 text-[11px] bg-white">
                    </div>
                </div>
            </div>

            <!-- 4 Bukti Akademis -->
            <div class="p-4 bg-amber-50/60 rounded-xl border border-amber-200/80 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-amber-900 text-xs block">📚 4 Bukti Akademis & Karya Utama (Kartu Kanan)</span>
                    <input type="text" name="theory_card_title" value="{{ $settings['theory_card_title'] ?? '4 Bukti Akademis & Karya Utama' }}" placeholder="Judul Kotak Bukti..." class="p-1 px-2 rounded border border-amber-300 bg-white text-xs font-bold w-64">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Bukti #1</label>
                        <input type="text" name="theory_item1_title" value="{{ $settings['theory_item1_title'] ?? 'Buku Monograf Ruhiologi (2025)' }}" placeholder="Judul..." class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold">
                        <input type="text" name="theory_item1_desc" value="{{ $settings['theory_item1_desc'] ?? 'Karya monumental Prof. Dr. Iskandar Nazari penerbit Ruhiology Press.' }}" placeholder="Keterangan..." class="w-full p-1.5 rounded border border-slate-300 text-[11px]">
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Bukti #2</label>
                        <input type="text" name="theory_item2_title" value="{{ $settings['theory_item2_title'] ?? 'Uji Validitas pada 14.000+ Responden' }}" placeholder="Judul..." class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold">
                        <input type="text" name="theory_item2_desc" value="{{ $settings['theory_item2_desc'] ?? 'Meliputi mahasiswa, pelajar, ASN, pendidik, dan praktisi di 38 provinsi.' }}" placeholder="Keterangan..." class="w-full p-1.5 rounded border border-slate-300 text-[11px]">
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Bukti #3</label>
                        <input type="text" name="theory_item3_title" value="{{ $settings['theory_item3_title'] ?? 'Mitra Konsorsium PT & Sekolah' }}" placeholder="Judul..." class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold">
                        <input type="text" name="theory_item3_desc" value="{{ $settings['theory_item3_desc'] ?? 'Kerja sama riset pengukuran dengan perguruan tinggi & sekolah se-Indonesia.' }}" placeholder="Keterangan..." class="w-full p-1.5 rounded border border-slate-300 text-[11px]">
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Bukti #4</label>
                        <input type="text" name="theory_item4_title" value="{{ $settings['theory_item4_title'] ?? 'Rekomendasi Refleksi & Intervensi' }}" placeholder="Judul..." class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold">
                        <input type="text" name="theory_item4_desc" value="{{ $settings['theory_item4_desc'] ?? 'Setiap skor dilengkapi panduan penataan niat dan purifikasi batin.' }}" placeholder="Keterangan..." class="w-full p-1.5 rounded border border-slate-300 text-[11px]">
                    </div>
                </div>
            </div>
        </div>


        <!-- Section: 6 Pilar Dimensi Ruhiologi & WHO-5 -->
        <div id="enam-pilar" class="bg-white p-6 rounded-2xl border-2 border-emerald-500/30 shadow-md space-y-4 scroll-mt-16">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <span>🏛️</span>
                    <span>Section: Enam Pilar Dimensi Kecerdasan Ruhiologi & WHO-5</span>
                </span>
                <span class="text-[10px] font-mono bg-emerald-100 text-emerald-900 px-2.5 py-0.5 rounded-full font-bold">6 Dimensi Lengkap</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Atas Section</label>
                    <input type="text" name="dimensi_badge" value="{{ $settings['dimensi_badge'] ?? '✦ Dimensi & Kerangka Teori' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Utama Section</label>
                    <input type="text" name="dimensi_title" value="{{ $settings['dimensi_title'] ?? 'Enam Pilar Dimensi Kecerdasan Ruhiologi & Kesejahteraan Jiwa (RQI-15 + WHO-5)' }}" required class="w-full p-2.5 rounded border border-slate-300 font-serif font-bold text-sm">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Subjudul / Deskripsi Teori</label>
                <textarea name="dimensi_subtitle" rows="2" required class="w-full p-2.5 rounded border border-slate-300 leading-relaxed">{{ $settings['dimensi_subtitle'] ?? 'Enam pilar utama yang dirumuskan oleh Prof. Dr. Iskandar Nazari bersama indeks standar WHO sebagai pusat inteligensi dan keseimbangan potensi kemanusiaan paripurna.' }}</textarea>
            </div>

            <!-- 6 Pilar Cards Settings Grid (3x2) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 pt-2">
                
                <!-- Pilar 1 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0B2A43]">Pilar 1</span>
                        <input type="text" name="pillar1_icon" value="{{ $settings['pillar1_icon'] ?? '✦' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Nama Pilar</label>
                        <input type="text" name="pillar1_title" value="{{ $settings['pillar1_title'] ?? '1. Kesadaran Ketuhanan (God Spot)' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi Filosofis</label>
                        <textarea name="pillar1_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['pillar1_desc'] ?? 'Kesadaran mendalam akan kehadiran Sang Pencipta, keikhlasan niat, dan orientasi hidup transendental.' }}</textarea>
                    </div>
                </div>

                <!-- Pilar 2 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0B2A43]">Pilar 2</span>
                        <input type="text" name="pillar2_icon" value="{{ $settings['pillar2_icon'] ?? '🕌' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Nama Pilar</label>
                        <input type="text" name="pillar2_title" value="{{ $settings['pillar2_title'] ?? '2. Ketaatan Spiritual & Ibadah' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi Filosofis</label>
                        <textarea name="pillar2_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['pillar2_desc'] ?? 'Kedisiplinan ibadah ritual, penghayatan doa/zikir, dan konsistensi menjaga hubungan vertikal.' }}</textarea>
                    </div>
                </div>

                <!-- Pilar 3 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0B2A43]">Pilar 3</span>
                        <input type="text" name="pillar3_icon" value="{{ $settings['pillar3_icon'] ?? '✨' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Nama Pilar</label>
                        <input type="text" name="pillar3_title" value="{{ $settings['pillar3_title'] ?? '3. Purifikasi Batin (Tazkiyah)' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi Filosofis</label>
                        <textarea name="pillar3_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['pillar3_desc'] ?? 'Kebersihan hati dari penyakit iri, dengki, keangkuhan intelektual, serta kejujuran nurani.' }}</textarea>
                    </div>
                </div>

                <!-- Pilar 4 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0B2A43]">Pilar 4</span>
                        <input type="text" name="pillar4_icon" value="{{ $settings['pillar4_icon'] ?? '🤝' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Nama Pilar</label>
                        <input type="text" name="pillar4_title" value="{{ $settings['pillar4_title'] ?? '4. Akhlak & Kepedulian Sosial' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi Filosofis</label>
                        <textarea name="pillar4_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['pillar4_desc'] ?? 'Adab pergaulan, empati tulus, dan keberfungsian sosial yang membawa manfaat nyata bagi sesama.' }}</textarea>
                    </div>
                </div>

                <!-- Pilar 5 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0B2A43]">Pilar 5</span>
                        <input type="text" name="pillar5_icon" value="{{ $settings['pillar5_icon'] ?? '🛡️' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Nama Pilar</label>
                        <input type="text" name="pillar5_title" value="{{ $settings['pillar5_title'] ?? '5. Ketangguhan & Resiliensi Jiwa' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi Filosofis</label>
                        <textarea name="pillar5_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['pillar5_desc'] ?? 'Ketenangan batin, kesabaran menghadapi tekanan/ujian hidup, dan optimisme bangkit dari kesulitan.' }}</textarea>
                    </div>
                </div>

                <!-- Pilar 6 (WHO-5 Mental Well-Being) -->
                <div class="p-3.5 bg-emerald-50 rounded-xl border-2 border-emerald-300 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-900">Pilar 6 (WHO-5)</span>
                        <input type="text" name="pillar6_icon" value="{{ $settings['pillar6_icon'] ?? '💚' }}" class="w-10 text-center p-1 rounded border border-emerald-400 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-emerald-800 font-bold uppercase">Nama Pilar</label>
                        <input type="text" name="pillar6_title" value="{{ $settings['pillar6_title'] ?? '6. Kesejahteraan Mental (WHO-5)' }}" class="w-full p-1.5 rounded border border-emerald-400 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-emerald-800 font-bold uppercase">Deskripsi Filosofis</label>
                        <textarea name="pillar6_desc" rows="3" class="w-full p-1.5 rounded border border-emerald-400 text-[11px] leading-relaxed bg-white">{{ $settings['pillar6_desc'] ?? 'Vitalitas psikologis, ketenangan suasana hati, energi positif, dan kesejahteraan emosional berstandar WHO.' }}</textarea>
                    </div>
                </div>

            </div>
        </div>


            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-white font-extrabold rounded-xl shadow-md transition cursor-pointer flex items-center gap-2">
                    <span>💾</span> Simpan Perubahan Teori & 6 Dimensi
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: SHOWCASE ASESMEN & ALUR             -->
        <!-- ========================================== -->
        <div x-show="settingsTab === 'assessment_flow'" class="space-y-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-amber-50/70 border border-amber-200/70 p-4 rounded-2xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📊</span>
                    <div>
                        <h4 class="font-bold text-sm text-amber-950">Bagian 3: Showcase Profil Asesmen & 4 Langkah Alur</h4>
                        <p class="text-[11px] text-amber-800">Mengatur kartu promosi asesmen RQ dan langkah-langkah alur registrasi hingga penerbitan hasil.</p>
                    </div>
                </div>
                <span class="text-[11px] font-mono font-bold bg-white px-2.5 py-1 rounded-lg border border-amber-200 text-amber-900">Tab 3 dari 5</span>
            </div>

        <!-- Section: Kenali Profil Ruhiologi Anda -->
        <div id="kenali-profil" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 scroll-mt-16">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <span>✨</span>
                    <span>Section: Kenali Profil Ruhiologi Anda (Card Asesmen)</span>
                </span>
                <span class="text-[10px] font-mono bg-emerald-50 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold">Assessment Showcase</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Upload Gambar Banner Profil Asesmen</label>
                    <input type="file" name="rq_assessment_image_file" accept="image/*" class="w-full p-2 rounded border border-slate-300 bg-slate-50">
                    <p class="text-[11px] text-slate-400 mt-1">Pilih file jika ingin mengganti gambar visual kartu profil di landing page.</p>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Gambar Banner Profil Asesmen</label>
                    <input type="text" name="rq_assessment_image" value="{{ $settings['rq_assessment_image'] ?? asset('images/god-spot-light.jpg') }}" class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Utama</label>
                    <input type="text" name="profile_title" value="{{ $settings['profile_title'] ?? 'Kenali Profil Ruhiologi Anda' }}" required class="w-full p-2.5 rounded border border-slate-300 font-serif font-bold text-sm">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul List 6 Dimensi Bawah</label>
                    <input type="text" name="profile_pills_title" value="{{ $settings['profile_pills_title'] ?? '6 Dimensi Pengukuran: Pilar RQI-15 + Indeks WHO-5' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Narasi Profil Asesmen</label>
                <textarea name="profile_description" rows="2" required class="w-full p-2.5 rounded border border-slate-300 leading-relaxed">{{ $settings['profile_description'] ?? 'RQ Assessment dirancang sebagai instrumen ilmiah terstruktur untuk memetakan 6 Pilar Dimensi Ruhiologi (RQI-15) dan Indeks Kesejahteraan Mental (WHO-5).' }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Poin Keunggulan 1</label>
                    <input type="text" name="profile_bullet1" value="{{ $settings['profile_bullet1'] ?? 'Instrumen Terstruktur (20 Soal)' }}" required class="w-full p-2 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Poin Keunggulan 2</label>
                    <input type="text" name="profile_bullet2" value="{{ $settings['profile_bullet2'] ?? 'Laporan Dual-Pilar & Radar' }}" required class="w-full p-2 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Poin Keunggulan 3</label>
                    <input type="text" name="profile_bullet3" value="{{ $settings['profile_bullet3'] ?? 'Sertifikat Resmi & Kartu Story' }}" required class="w-full p-2 rounded border border-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teks Tombol 1 (Mulai)</label>
                    <input type="text" name="profile_btn1_text" value="{{ $settings['profile_btn1_text'] ?? 'Mulai Assessment' }}" required class="w-full p-2 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teks Tombol 2 (Cek Hasil)</label>
                    <input type="text" name="profile_btn2_text" value="{{ $settings['profile_btn2_text'] ?? 'Cek Hasil / Sertifikat Saya' }}" required class="w-full p-2 rounded border border-slate-300">
                </div>
            </div>
        </div>


        <!-- Section: Alur & Proses Evaluasi (4 Langkah) -->
        <div id="alur-proses" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 scroll-mt-16">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <span>🪜</span>
                    <span>Section: Alur & Proses Evaluasi (4 Langkah Mengikuti Assessment)</span>
                </span>
                <span class="text-[10px] font-mono bg-amber-50 text-amber-800 px-2.5 py-0.5 rounded-full font-bold">Process Steps</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Section</label>
                    <input type="text" name="flow_badge" value="{{ $settings['flow_badge'] ?? '✦ ALUR & PROSES EVALUASI' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Section</label>
                    <input type="text" name="flow_title" value="{{ $settings['flow_title'] ?? '4 Langkah Mudah Mengikuti Assessment RQ' }}" required class="w-full p-2.5 rounded border border-slate-300 font-serif font-bold text-sm">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat Alur</label>
                <textarea name="flow_description" rows="2" required class="w-full p-2.5 rounded border border-slate-300">{{ $settings['flow_description'] ?? 'Proses praktis dan terstruktur dari registrasi hingga lembar rekomendasi potensi.' }}</textarea>
            </div>

            <!-- 4 Langkah Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                <!-- Step 1 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-xs text-[#0B2A43]">Langkah 01</span>
                        <input type="text" name="flow_step1_icon" value="{{ $settings['flow_step1_icon'] ?? '📝' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Judul</label>
                        <input type="text" name="flow_step1_title" value="{{ $settings['flow_step1_title'] ?? 'Intake Data & Kode' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi</label>
                        <textarea name="flow_step1_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['flow_step1_desc'] ?? 'Pilih jalur Asesmen Mandiri atau masukkan Kode Akses Event dari kampus/sekolah/instansi Anda.' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Label Tombol</label>
                        <input type="text" name="flow_step1_tag" value="{{ $settings['flow_step1_tag'] ?? 'Akses Instan' }}" class="w-full p-1 rounded border border-slate-300 text-[10px] font-bold bg-white">
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-xs text-[#0B2A43]">Langkah 02</span>
                        <input type="text" name="flow_step2_icon" value="{{ $settings['flow_step2_icon'] ?? '⏱️' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Judul</label>
                        <input type="text" name="flow_step2_title" value="{{ $settings['flow_step2_title'] ?? 'Pengerjaan 10-15 Menit' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi</label>
                        <textarea name="flow_step2_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['flow_step2_desc'] ?? 'Isikan 20 pernyataan terstruktur (15 butir RQI & 5 butir WHO-5) dengan fitur auto-save otomatis.' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Label Tombol</label>
                        <input type="text" name="flow_step2_tag" value="{{ $settings['flow_step2_tag'] ?? '20 Pertanyaan' }}" class="w-full p-1 rounded border border-slate-300 text-[10px] font-bold bg-white">
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-xs text-[#0B2A43]">Langkah 03</span>
                        <input type="text" name="flow_step3_icon" value="{{ $settings['flow_step3_icon'] ?? '📊' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Judul</label>
                        <input type="text" name="flow_step3_title" value="{{ $settings['flow_step3_title'] ?? 'Kalkulasi Dual-Pilar' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi</label>
                        <textarea name="flow_step3_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['flow_step3_desc'] ?? 'Sistem secara instan menghitung skor RQI-15 (15–75 Poin), Indeks WHO-5 (0–100%), dan Grafik Radar 6 Dimensi.' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Label Tombol</label>
                        <input type="text" name="flow_step3_tag" value="{{ $settings['flow_step3_tag'] ?? 'Kalkulasi Instan' }}" class="w-full p-1 rounded border border-slate-300 text-[10px] font-bold bg-white">
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-xs text-[#0B2A43]">Langkah 04</span>
                        <input type="text" name="flow_step4_icon" value="{{ $settings['flow_step4_icon'] ?? '📄' }}" class="w-10 text-center p-1 rounded border border-slate-300 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Judul</label>
                        <input type="text" name="flow_step4_title" value="{{ $settings['flow_step4_title'] ?? 'Laporan & Sertifikat' }}" class="w-full p-1.5 rounded border border-slate-300 text-xs font-bold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Deskripsi</label>
                        <textarea name="flow_step4_desc" rows="3" class="w-full p-1.5 rounded border border-slate-300 text-[11px] leading-relaxed bg-white">{{ $settings['flow_step4_desc'] ?? 'Unduh Sertifikat Resmi A4, Laporan PDF Eksekutif, serta Kartu Shareable 4:5 untuk Instagram/WhatsApp.' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 font-bold uppercase">Label Tombol</label>
                        <input type="text" name="flow_step4_tag" value="{{ $settings['flow_step4_tag'] ?? 'Unduh Sertifikat & Kartu' }}" class="w-full p-1 rounded border border-slate-300 text-[10px] font-bold bg-white">
                    </div>
                </div>
            </div>
        </div>


            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-white font-extrabold rounded-xl shadow-md transition cursor-pointer flex items-center gap-2">
                    <span>💾</span> Simpan Perubahan Showcase Asesmen & Alur
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: TEMPLATE SERTIFIKAT & PREVIEW       -->
        <!-- ========================================== -->
        <div x-show="settingsTab === 'certificate_preview'" class="space-y-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-slate-100 border border-slate-200 p-4 rounded-2xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📜</span>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Bagian 4: Template Sertifikat Digital & Pratinjau Langsung</h4>
                        <p class="text-[11px] text-slate-600">Sesuaikan tata letak sertifikat PDF A4 resmi, tanda tangan founder, background, dan kartu story 9:16.</p>
                    </div>
                </div>
                <span class="text-[11px] font-mono font-bold bg-white px-2.5 py-1 rounded-lg border border-slate-300 text-slate-800">Tab 4 dari 5</span>
            </div>

        <!-- Section 3: Sertifikat Digital, Tanda Tangan Founder & Kartu Hasil dengan Live Preview -->
        <div id="sertifikat-preview" x-data="{
            activeTab: 'certificate',
            certTitle: '{{ addslashes($settings['certificate_title'] ?? 'SERTIFIKAT HASIL ASESMEN RQI') }}',
            certSubtitle: '{{ addslashes($settings['certificate_subtitle'] ?? 'Ruhiology Quotient Assessment Certificate') }}',
            certBody: '{{ addslashes($settings['certificate_body_text'] ?? 'Diberikan kepada peserta di bawah ini atas partisipasi dan pencapaian evaluasi potensi diri dalam Asesmen Ruhiology Quotient (RQI).') }}',
            founderName: '{{ addslashes($settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.') }}',
            founderTitle: '{{ addslashes($settings['founder_title'] ?? 'Founder Ruhiology Institute & Guru Besar UIN STS Jambi') }}',
            signatureUrl: '{{ $settings['founder_signature'] ?? '' }}',
            certLogoUrl: '{{ $settings['certificate_logo'] ?? asset('images/ruhiology-logo.png') }}',
            certBgUrl: '{{ $settings['certificate_bg'] ?? '' }}',
            storyBgUrl: '{{ $settings['story_bg'] ?? '' }}',

            previewFile(event, type) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (type === 'signature') this.signatureUrl = e.target.result;
                        if (type === 'certLogo') this.certLogoUrl = e.target.result;
                        if (type === 'certBg') this.certBgUrl = e.target.result;
                        if (type === 'storyBg') this.storyBgUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex flex-wrap justify-between items-center border-b border-slate-100 pb-3 gap-3">
                <div>
                    <h3 class="font-bold font-serif text-slate-900 text-sm flex items-center gap-2">
                        <span>📜</span>
                        <span>Pengaturan & Pratinjau Live Sertifikat Digital & Kartu Hasil</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Kelola logo, gambar latar, tanda tangan founder, serta lihat hasil pratinjau langsung di sebelah kanan.</p>
                </div>
                
                <!-- Tab Switcher for Live Preview -->
                <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
                    <button type="button" @click="activeTab = 'certificate'" :class="activeTab === 'certificate' ? 'bg-[#0B2A43] text-white shadow' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg font-bold text-xs transition cursor-pointer">
                        📜 Live Preview Sertifikat (A4)
                    </button>
                    <button type="button" @click="activeTab = 'story'" :class="activeTab === 'story' ? 'bg-[#0B2A43] text-white shadow' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg font-bold text-xs transition cursor-pointer">
                        📱 Live Preview Kartu Hasil (9:16)
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Side (Form Inputs: 7 cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- Logo & Founder Signature Upload -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                        <span class="font-bold text-slate-800 text-xs block">🏛️ Logo Institusi & ✍️ Tanda Tangan Founder</span>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-600 mb-1">Upload Logo Sertifikat (PNG Transparan)</label>
                                <input type="file" name="certificate_logo_file" accept="image/*" @change="previewFile($event, 'certLogo')" class="w-full p-2 text-xs rounded border border-slate-300 bg-white">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 mb-1">Upload File Tanda Tangan (PNG Transparan)</label>
                                <input type="file" name="founder_signature_file" accept="image/*" @change="previewFile($event, 'signature')" class="w-full p-2 text-xs rounded border border-slate-300 bg-white">
                            </div>
                        </div>
                    </div>

                    <!-- Custom Backgrounds Upload -->
                    <div class="p-4 bg-amber-50/60 rounded-xl border border-amber-200/80 space-y-3">
                        <span class="font-bold text-amber-900 text-xs block">🎨 Gambar Latar / Custom Background</span>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Certificate Background -->
                            <div class="space-y-1">
                                <label class="block font-semibold text-slate-700 text-xs">Latar Sertifikat Digital (A4)</label>
                                <input type="file" name="certificate_bg_file" accept="image/*" @change="previewFile($event, 'certBg')" class="w-full p-2 text-xs rounded border border-slate-300 bg-white">
                                <input type="text" name="certificate_bg" x-model="certBgUrl" placeholder="URL Background Sertifikat..." class="w-full p-2 text-[11px] rounded border border-slate-300 mt-1">
                                <template x-if="certBgUrl">
                                    <button type="button" @click="certBgUrl = ''" class="text-[10px] text-rose-600 font-bold hover:underline mt-1 block">✕ Hapus Latar Sertifikat (Gunakan Default)</button>
                                </template>
                            </div>

                            <!-- 9:16 Story Background -->
                            <div class="space-y-1">
                                <label class="block font-semibold text-slate-700 text-xs">Latar Kartu Hasil (Story 9:16)</label>
                                <input type="file" name="story_bg_file" accept="image/*" @change="previewFile($event, 'storyBg')" class="w-full p-2 text-xs rounded border border-slate-300 bg-white">
                                <input type="text" name="story_bg" x-model="storyBgUrl" placeholder="URL Background Story 9:16..." class="w-full p-2 text-[11px] rounded border border-slate-300 mt-1">
                                <template x-if="storyBgUrl">
                                    <button type="button" @click="storyBgUrl = ''" class="text-[10px] text-rose-600 font-bold hover:underline mt-1 block">✕ Hapus Latar Story (Gunakan Default)</button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Text Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Judul Utama Sertifikat</label>
                            <input type="text" name="certificate_title" x-model="certTitle" required class="w-full p-2.5 rounded border border-slate-300 font-serif font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Subjudul Sertifikat</label>
                            <input type="text" name="certificate_subtitle" x-model="certSubtitle" required class="w-full p-2.5 rounded border border-slate-300">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Teks Pengantar Sertifikat (Body Text)</label>
                        <textarea name="certificate_body_text" x-model="certBody" rows="2" required class="w-full p-2.5 rounded border border-slate-300"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Founder / Penandatangan</label>
                            <input type="text" name="founder_name" x-model="founderName" required class="w-full p-2.5 rounded border border-slate-300">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Gelar / Jabatan Founder</label>
                            <input type="text" name="founder_title" x-model="founderTitle" required class="w-full p-2.5 rounded border border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- Right Side (Live Interactive Preview Box: 5 cols) -->
                <div class="lg:col-span-5 bg-slate-900 p-4 rounded-2xl border border-slate-800 text-white space-y-3 sticky top-24">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Pratinjau Tampilan Live</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono" x-text="activeTab === 'certificate' ? 'A4 Landscape' : 'Story 9:16'"></span>
                    </div>

                    <!-- PREVIEW TAB 1: SERTIFIKAT A4 LANDSCAPE -->
                    <div x-show="activeTab === 'certificate'" class="relative aspect-[1.414/1] w-full bg-[#F8F6F0] text-[#0F2537] p-3 rounded-xl border-4 border-[#0F2537] shadow-xl overflow-hidden flex flex-col justify-between text-center select-none"
                         :style="certBgUrl ? `background-image: url('${certBgUrl}'); background-size: cover; background-position: center;` : ''">
                        
                        <!-- Inner Gold Frame -->
                        <div class="absolute inset-1 border border-[#C5A059] rounded-lg pointer-events-none"></div>

                        <!-- 1. Header with Logo -->
                        <div class="space-y-0.5 pt-0.5 flex flex-col items-center">
                            <img :src="certLogoUrl" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0F2537&color=C5A059';" class="h-5 w-5 object-contain mx-auto" alt="Logo">
                            <span class="block text-[6px] font-bold tracking-widest uppercase text-[#0F2537]" x-text="'RUHIOLOGY INSTITUTE'"></span>
                            <h4 class="font-serif font-black text-[9px] uppercase text-[#0F2537] leading-tight" x-text="certTitle"></h4>
                            <p class="text-[5px] italic text-[#8C703E] font-serif" x-text="certSubtitle"></p>
                        </div>

                        <!-- 2. Body Centered -->
                        <div class="space-y-0.5 my-0.5">
                            <span class="text-[5px] uppercase font-serif tracking-widest text-slate-500 font-bold block">Diberikan Kepada :</span>
                            <h5 class="font-serif font-bold text-[10px] text-[#0F2537] border-b border-[#C5A059] inline-block px-3" x-text="'NAMA PESERTA CONTOH'"></h5>
                            <p class="text-[5px] text-slate-600 line-clamp-1 leading-tight px-2" x-text="certBody"></p>
                            
                            <!-- Dual Main Score Badges (RQI + WHO-5) -->
                            <div class="grid grid-cols-2 gap-1 max-w-[95%] mx-auto py-0.5">
                                <div class="bg-amber-50/90 p-1 rounded border border-amber-300 text-center">
                                    <span class="block text-[4.5px] font-mono font-bold text-amber-900 uppercase">Skor RQI: 85.0</span>
                                    <span class="text-[4.5px] font-bold text-[#0F2537] truncate block">Level 5: Paripurna</span>
                                </div>
                                <div class="bg-emerald-50/90 p-1 rounded border border-emerald-300 text-center">
                                    <span class="block text-[4.5px] font-mono font-bold text-emerald-900 uppercase">Indeks WHO-5: 88%</span>
                                    <span class="text-[4.5px] font-bold text-emerald-800 truncate block">Kesejahteraan Baik</span>
                                </div>
                            </div>

                            <!-- 6-Dimension Mini Grid Preview (5 RQI + WHO-5) -->
                            <div class="grid grid-cols-6 gap-0.5 max-w-[95%] mx-auto">
                                <div class="bg-white/90 p-0.5 rounded text-[4.5px] border border-[#E5E0D5] text-center font-mono">D1 Diri: 88%</div>
                                <div class="bg-white/90 p-0.5 rounded text-[4.5px] border border-[#E5E0D5] text-center font-mono">D2 Batin: 84%</div>
                                <div class="bg-white/90 p-0.5 rounded text-[4.5px] border border-[#E5E0D5] text-center font-mono">D3 Resil: 80%</div>
                                <div class="bg-white/90 p-0.5 rounded text-[4.5px] border border-[#E5E0D5] text-center font-mono">D4 Ilahi: 85%</div>
                                <div class="bg-white/90 p-0.5 rounded text-[4.5px] border border-[#E5E0D5] text-center font-mono">D5 Akhlak: 88%</div>
                                <div class="bg-emerald-50/90 p-0.5 rounded text-[4.5px] border border-emerald-300 text-emerald-800 text-center font-mono font-bold">WHO-5: 88%</div>
                            </div>
                        </div>

                        <!-- 3. Footer & QR Verification -->
                        <div class="space-y-0.5">
                            <p class="text-[4.5px] font-serif italic text-slate-500 line-clamp-1">"Ruh adalah pusat inteligensi tertinggi yang mengendalikan orientasi nilai." — Prof. Dr. Iskandar Nazari</p>
                            
                            <div class="flex justify-between items-end pt-0.5 border-t border-amber-200 text-[5px]">
                                <div class="text-left">
                                    <span class="font-mono text-slate-400 block text-[4.5px]">NO: CERT/RQI/2026/SAMPLE</span>
                                    <span class="text-emerald-700 font-bold text-[4.5px] flex items-center gap-0.5">
                                        <svg class="w-2 h-2 text-emerald-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Terverifikasi Resmi
                                    </span>
                                </div>
                                <div class="text-center space-y-0.5 min-w-[70px]">
                                    <!-- QR Code Menggantikan TTD -->
                                    <div class="h-6 flex items-center justify-center">
                                        <div class="p-0.5 bg-white border border-amber-300 rounded inline-block">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=https%3A%2F%2Fruhiology.id%2Fsample" class="h-5 w-5 object-contain mx-auto" alt="QR Verifikasi">
                                        </div>
                                    </div>
                                    <span class="font-bold block text-[5px] text-[#0F2537] leading-tight" x-text="founderName"></span>
                                    <span class="text-[#8C703E] text-[4.5px] block truncate" x-text="founderTitle"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PREVIEW TAB 2: KARTU HASIL STORY 9:16 (EDITORIAL OXFORD NAVY & GOLD) -->
                    <div x-show="activeTab === 'story'" class="relative aspect-[9/16] max-w-[240px] mx-auto w-full bg-gradient-to-b from-[#0A1A28] via-[#0F2537] to-[#0A1A28] text-white p-3.5 rounded-3xl border-2 border-[#C5A059]/50 shadow-2xl overflow-hidden flex flex-col justify-between text-center select-none"
                         :style="storyBgUrl ? `background-image: url('${storyBgUrl}'); background-size: cover; background-position: center;` : ''">
                        
                        <!-- Ambient Radial Glow -->
                        <div class="absolute -top-10 -left-10 w-32 h-32 bg-[#C5A059]/15 rounded-full blur-2xl pointer-events-none"></div>

                        <!-- Top Header -->
                        <div class="space-y-0.5 pt-1 relative z-10">
                            <img :src="certLogoUrl" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0F2537&color=C5A059';" class="h-6 w-6 object-contain mx-auto" alt="Logo">
                            <span class="text-[7.5px] font-bold font-serif-gold text-[#C5A059] uppercase tracking-widest block">RUHIOLOGY INSTITUTE</span>
                            <span class="text-[5.5px] font-mono text-slate-300 uppercase tracking-wider block">LAPORAN HASIL RQI & WHO-5</span>
                        </div>

                        <!-- Participant Glass Box -->
                        <div class="my-1 py-1.5 px-2 bg-white/10 rounded-xl border border-white/20 backdrop-blur-md space-y-0.5 relative z-10">
                            <span class="text-[5.5px] text-[#C5A059] font-mono font-bold uppercase block">PESERTA ASESMEN</span>
                            <div class="text-[10px] font-bold font-serif text-white truncate" x-text="'NAMA PESERTA CONTOH'"></div>
                        </div>

                        <!-- Central RQI & WHO-5 Score Badges -->
                        <div class="space-y-1 my-1 relative z-10">
                            <div class="p-2 bg-gradient-to-b from-white/15 to-white/5 rounded-2xl border border-[#C5A059]/40 backdrop-blur-md shadow-inner space-y-0.5">
                                <span class="text-[5.5px] font-mono font-bold text-[#C5A059] uppercase block">SKOR RQI-15</span>
                                <div class="text-3xl font-black font-serif-gold text-white tracking-tight">85.0</div>
                                <span class="text-[5.5px] bg-[#C5A059] text-[#0F2537] font-bold px-2 py-0.5 rounded-full inline-block font-mono">Level 5: Paripurna</span>
                            </div>

                            <div class="p-1 bg-emerald-500/20 rounded-xl border border-emerald-400/40 text-center">
                                <span class="text-[5.5px] font-mono font-bold text-emerald-300 block">WHO-5 MENTAL WELL-BEING: 88% (BAIK)</span>
                            </div>
                        </div>

                        <!-- 6 Dimension Grid (3x2 Balanced Layout) -->
                        <div class="grid grid-cols-3 gap-1 text-[4.5px] font-mono relative z-10">
                            <div class="bg-white/10 p-1 rounded border border-white/15 text-slate-200">D1 Diri: 88%</div>
                            <div class="bg-white/10 p-1 rounded border border-white/15 text-slate-200">D2 Batin: 84%</div>
                            <div class="bg-white/10 p-1 rounded border border-white/15 text-slate-200">D3 Resil: 80%</div>
                            <div class="bg-white/10 p-1 rounded border border-white/15 text-slate-200">D4 Ilahi: 85%</div>
                            <div class="bg-white/10 p-1 rounded border border-white/15 text-slate-200">D5 Akhlak: 88%</div>
                            <div class="bg-emerald-500/30 p-1 rounded border border-emerald-400/30 text-emerald-200 font-bold">WHO-5: 88%</div>
                        </div>

                        <!-- Footer QR Code Verification -->
                        <div class="space-y-1 pb-0.5 border-t border-white/20 pt-1.5 relative z-10">
                            <div class="flex items-center justify-center gap-1.5">
                                <div class="p-0.5 bg-white border border-amber-300 rounded inline-block">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=40x40&data=https%3A%2F%2Fruhiology.id%2Fsample" class="h-5 w-5 object-contain" alt="QR Verifikasi">
                                </div>
                                <div class="text-left leading-tight">
                                    <span class="font-bold block text-[5.5px] text-white" x-text="founderName"></span>
                                    <span class="text-slate-300 text-[4.5px] block truncate" x-text="founderTitle"></span>
                                    <span class="text-[#C5A059] text-[4.5px] font-mono font-bold flex items-center gap-0.5">
                                        <svg class="w-1.5 h-1.5 text-[#C5A059] inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Terverifikasi QR Digital
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-white font-extrabold rounded-xl shadow-md transition cursor-pointer flex items-center gap-2">
                    <span>💾</span> Simpan Template Sertifikat & Preview
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 5: FOUNDER, KONTAK & REKENING BANK     -->
        <!-- ========================================== -->
        <div x-show="settingsTab === 'founder_general'" class="space-y-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-amber-50/50 border border-[#D4AF37]/30 p-4 rounded-2xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">👤</span>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Bagian 5: Profil Founder, Alamat Lembaga & Rekening Bank</h4>
                        <p class="text-[11px] text-slate-600">Mengelola biografi Prof. Dr. Iskandar Nazari, kanal komunikasi resmi, dan nomor rekening pembayaran.</p>
                    </div>
                </div>
                <span class="text-[11px] font-mono font-bold bg-white px-2.5 py-1 rounded-lg border border-[#D4AF37]/40 text-amber-900">Tab 5 dari 5</span>
            </div>

        <!-- Dedicated Founder Profile (Prof. Dr. Iskandar Nazari) Settings -->
        <div id="founder" class="bg-white p-6 rounded-2xl border-2 border-[#C9A24D]/30 shadow-md space-y-4 scroll-mt-6">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <span>👨‍🏫</span>
                    <span>Profil Founder & Inisiator Teori Ruhiologi (Prof. Dr. Iskandar Nazari)</span>
                </span>
                <span class="text-[10px] font-mono bg-amber-50 text-amber-900 px-2.5 py-0.5 rounded-full font-bold">Dynamic Admin CMS</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar Founder *</label>
                    <input type="text" name="founder_name" value="{{ $settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.' }}" required class="w-full p-2.5 rounded border border-slate-300 font-bold text-slate-900">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jabatan & Institusi Founder *</label>
                    <input type="text" name="founder_title" value="{{ $settings['founder_title'] ?? 'Founder Ruhiology Institute & Guru Besar UIN Sulthan Thaha Saifuddin Jambi' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Upload Foto Resmi Founder (Prof. Dr. Iskandar Nazari)</label>
                    <input type="file" name="founder_photo_file" accept="image/*" class="w-full p-2 rounded border border-slate-300 bg-slate-50">
                    <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG. Rekomendasi rasio portrait 3:4 atau 1:1.</p>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Foto Founder (Opsional)</label>
                    <input type="text" name="founder_photo" value="{{ $settings['founder_photo'] ?? asset('images/settings/founder_photo.jpg') }}" class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Kata Hikmah / Kutipan Inspirasi Utama Founder</label>
                <textarea name="founder_quote" rows="2" class="w-full p-2.5 rounded border border-slate-300 font-serif italic text-slate-800">{{ $settings['founder_quote'] ?? 'Ruh bukan sekadar dorongan mistis, melainkan pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, intuisi kebenaran, serta komitmen etis dalam kehidupan nyata.' }}</textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Biografi Singkat & Perjalanan Ilmiah Founder</label>
                <textarea name="founder_bio" rows="3" class="w-full p-2.5 rounded border border-slate-300 text-slate-700">{{ $settings['founder_bio'] ?? 'Prof. Dr. Iskandar Nazari merumuskan konsep Ruhiology Quotient (RQ) sebagai navigasi potensi manusia. Penyelidikan ilmiah beliau memadukan prinsip kecerdasan wahyu dengan penyusunan metodologi psikometri ilmiah.' }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Riwayat Pendidikan (1 baris per jenjang)</label>
                    <textarea name="founder_education" rows="3" class="w-full p-2.5 rounded border border-slate-300 font-mono text-[11px]">{{ $settings['founder_education'] ?? "S1 UIN Sulthan Thaha Saifuddin Jambi\nS2 & S3 Psikologi Pendidikan & Studi Islam\nPh.D. & Guru Besar UIN Sulthan Thaha Saifuddin Jambi" }}</textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Publikasi & Karya Buku Utama (1 baris per karya)</label>
                    <textarea name="founder_publications" rows="3" class="w-full p-2.5 rounded border border-slate-300 font-mono text-[11px]">{{ $settings['founder_publications'] ?? "Buku: Inventori Kecerdasan Ruhiologi (RQI-15 & RQI-30M)\nBuku: Psikologi Pendidikan Berbasis Ruhiologi\nJurnal: Ruhiology Quotient as a Predictor of Mental Well-being (WHO-5)" }}</textarea>
                </div>
            </div>
        </div>


        <!-- Contact & Location -->
        <div id="kontak-rekening" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 scroll-mt-16">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2">Kontak & Alamat Sekretariat</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Official</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'info@ruhiologyinstitute.com' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Telepon Official</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+62 812-7411-0000' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp Center</label>
                    <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] ?? '6281274110000' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat Sekretariat</label>
                <textarea name="address" rows="2" required class="w-full p-2.5 rounded border border-slate-300">{{ $settings['address'] ?? 'Kampus UIN STS Jambi & Gedung Pusat Ruhiology Institute, Kota Jambi' }}</textarea>
            </div>
        </div>


        <!-- Payment Instructions for Books & Training -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2">Rekening Pembayaran Resmi</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Bank</label>
                    <input type="text" name="payment_bank_name" value="{{ $settings['payment_bank_name'] ?? 'Bank Mandiri' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Rekening</label>
                    <input type="text" name="payment_account_number" value="{{ $settings['payment_account_number'] ?? '110-00-1988273-1' }}" required class="w-full p-2.5 rounded border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Atas Nama Rekening</label>
                    <input type="text" name="payment_account_holder" value="{{ $settings['payment_account_holder'] ?? 'RUHIOLOGY INSTITUTE INDONESIA' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>
        </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-white font-extrabold rounded-xl shadow-md transition cursor-pointer flex items-center gap-2">
                    <span>💾</span> Simpan Profil Founder, Kontak & Bank
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STICKY BOTTOM ACTION BAR                   -->
        <!-- ========================================== -->
        <div class="fixed bottom-4 left-4 right-4 md:left-68 lg:left-72 z-30 pointer-events-none">
            <div class="max-w-7xl mx-auto bg-slate-900/95 backdrop-blur-md text-white px-5 py-3.5 rounded-2xl shadow-2xl border border-slate-700/80 flex flex-wrap items-center justify-between gap-3 pointer-events-auto">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    <div>
                        <p class="text-xs font-bold text-white flex items-center gap-1.5">
                            <span class="text-slate-400">Sedang mengedit:</span>
                            <span class="text-[#D4AF37] font-semibold" x-text="getTabName()"></span>
                        </p>
                        <p class="text-[10px] text-slate-400 hidden sm:block">Seluruh perubahan pada tab aktif maupun tab lainnya tersimpan saat Anda menekan tombol simpan.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#D4AF37] to-amber-500 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-black rounded-xl text-xs shadow-lg transition flex items-center gap-2 cursor-pointer active:scale-95">
                        <span>💾 Simpan Seluruh Pengaturan</span>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
