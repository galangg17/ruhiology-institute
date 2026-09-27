@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Pengaturan Sistem & Konten Landing Page</h2>
            <p class="text-xs text-slate-500">Kelola gambar hero, teks narasi filosofi, identitas lembaga, kontak, dan rekening pembayaran.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs font-sans">
        @csrf

        <!-- Hero Card & Visual Banner Settings -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
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
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
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

        <!-- Section 3: Sertifikat Digital, Tanda Tangan Founder & Kartu Hasil dengan Live Preview -->
        <div x-data="{
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
                    <div x-show="activeTab === 'certificate'" class="relative aspect-[1.414/1] w-full bg-[#F8F6F0] text-[#0B2A43] p-3 rounded-xl border-4 border-[#0B2A43] shadow-xl overflow-hidden flex flex-col justify-between text-center select-none"
                         :style="certBgUrl ? `background-image: url('${certBgUrl}'); background-size: cover; background-position: center;` : ''">
                        
                        <!-- Inner Gold Frame -->
                        <div class="absolute inset-1 border border-[#C9A24D] rounded-lg pointer-events-none"></div>

                        <!-- 1. Header with Logo -->
                        <div class="space-y-0.5 pt-0.5 flex flex-col items-center">
                            <img :src="certLogoUrl" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="h-5 w-5 object-contain mx-auto" alt="Logo">
                            <span class="block text-[6px] font-bold tracking-widest uppercase text-[#0B2A43]" x-text="'RUHIOLOGY INSTITUTE'"></span>
                            <h4 class="font-serif font-black text-[9px] uppercase text-[#0B2A43] leading-tight" x-text="certTitle"></h4>
                            <p class="text-[5px] italic text-amber-800 font-serif" x-text="certSubtitle"></p>
                        </div>

                        <!-- 2. Body Centered -->
                        <div class="space-y-0.5 my-0.5">
                            <span class="text-[5px] uppercase font-serif tracking-widest text-slate-500 font-bold block">Diberikan Kepada :</span>
                            <h5 class="font-serif font-bold text-[10px] text-[#0B2A43] border-b border-amber-400 inline-block px-3" x-text="'NAMA PESERTA CONTOH'"></h5>
                            <p class="text-[5px] text-slate-600 line-clamp-1 leading-tight px-2" x-text="certBody"></p>
                            
                            <!-- Dual Main Score Badges (RQI + WHO-5) -->
                            <div class="grid grid-cols-2 gap-1 max-w-[90%] mx-auto py-0.5">
                                <div class="bg-amber-50/90 p-0.5 rounded border border-amber-300 text-center">
                                    <span class="block text-[4.5px] font-mono font-bold text-amber-900 uppercase">Skor RQI: 85.0</span>
                                    <span class="text-[4.5px] font-bold text-[#0B2A43] truncate block">✦ Level 5: Paripurna</span>
                                </div>
                                <div class="bg-emerald-50/90 p-0.5 rounded border border-emerald-300 text-center">
                                    <span class="block text-[4.5px] font-mono font-bold text-emerald-900 uppercase">Indeks WHO-5: 88%</span>
                                    <span class="text-[4.5px] font-bold text-emerald-800 truncate block">🌱 Kesejahteraan Baik</span>
                                </div>
                            </div>

                            <!-- 5-Dimension Mini Grid Preview -->
                            <div class="grid grid-cols-5 gap-0.5 max-w-[90%] mx-auto">
                                <div class="bg-white/80 p-0.5 rounded text-[4.5px] border border-amber-200 text-center font-mono">Nafl: 88%</div>
                                <div class="bg-white/80 p-0.5 rounded text-[4.5px] border border-amber-200 text-center font-mono">Nurani: 84%</div>
                                <div class="bg-white/80 p-0.5 rounded text-[4.5px] border border-amber-200 text-center font-mono">Resil: 80%</div>
                                <div class="bg-white/80 p-0.5 rounded text-[4.5px] border border-amber-200 text-center font-mono">Wahyu: 85%</div>
                                <div class="bg-white/80 p-0.5 rounded text-[4.5px] border border-amber-200 text-center font-mono">Etis: 88%</div>
                            </div>
                        </div>

                        <!-- 3. Footer & QR Verification (Opsi A) -->
                        <div class="space-y-0.5">
                            <p class="text-[4.5px] font-serif italic text-slate-500 line-clamp-1">"Ruh adalah pusat inteligensi tertinggi yang mengendalikan orientasi nilai." — Prof. Iskandar Nazari</p>
                            
                            <div class="flex justify-between items-end pt-0.5 border-t border-amber-200 text-[5px]">
                                <div class="text-left">
                                    <span class="font-mono text-slate-400 block text-[4.5px]">NO: CERT/RQI/2026/SAMPLE</span>
                                    <span class="text-emerald-700 font-bold text-[4.5px]">✓ Terverifikasi Resmi</span>
                                </div>
                                <div class="text-center space-y-0.5 min-w-[70px]">
                                    <!-- QR Code Menggantikan TTD (Opsi A) -->
                                    <div class="h-6 flex items-center justify-center">
                                        <div class="p-0.5 bg-white border border-amber-300 rounded inline-block">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=https%3A%2F%2Fruhiology.id%2Fsample" class="h-5 w-5 object-contain mx-auto" alt="QR Verifikasi">
                                        </div>
                                    </div>
                                    <span class="font-bold block text-[5px] text-[#0B2A43] leading-tight" x-text="founderName"></span>
                                    <span class="text-amber-800 text-[4.5px] block truncate" x-text="founderTitle"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PREVIEW TAB 2: KARTU HASIL STORY 9:16 (ULTRA MODERN GLASSMORPHIC) -->
                    <div x-show="activeTab === 'story'" class="relative aspect-[9/16] max-w-[240px] mx-auto w-full bg-gradient-to-b from-[#071E30] via-[#0B2A43] to-[#071E30] text-white p-3.5 rounded-3xl border-2 border-[#C9A24D]/50 shadow-2xl overflow-hidden flex flex-col justify-between text-center select-none"
                         :style="storyBgUrl ? `background-image: url('${storyBgUrl}'); background-size: cover; background-position: center;` : ''">
                        
                        <!-- Ambient Radial Glow -->
                        <div class="absolute -top-10 -left-10 w-32 h-32 bg-[#C9A24D]/20 rounded-full blur-2xl pointer-events-none"></div>

                        <!-- Top Header -->
                        <div class="space-y-0.5 pt-1 relative z-10">
                            <img :src="certLogoUrl" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="h-6 w-6 object-contain mx-auto" alt="Logo">
                            <span class="text-[7.5px] font-bold font-serif-gold text-[#C9A24D] uppercase tracking-widest block">RUHIOLOGY INSTITUTE</span>
                            <span class="text-[5.5px] font-mono text-slate-300 uppercase tracking-wider block">LAPORAN HASIL RQI & WHO-5</span>
                        </div>

                        <!-- Participant Glass Box -->
                        <div class="my-1 py-1.5 px-2 bg-white/10 rounded-xl border border-white/20 backdrop-blur-md space-y-0.5 relative z-10">
                            <span class="text-[5.5px] text-[#C9A24D] font-mono font-bold uppercase block">PESERTA ASESMEN</span>
                            <div class="text-[10px] font-bold font-serif text-white truncate" x-text="'NAMA PESERTA CONTOH'"></div>
                        </div>

                        <!-- Central RQI & WHO-5 Score Badges -->
                        <div class="space-y-1 my-1 relative z-10">
                            <div class="p-2 bg-gradient-to-b from-white/15 to-white/5 rounded-2xl border border-[#C9A24D]/40 backdrop-blur-md shadow-inner space-y-0.5">
                                <span class="text-[5.5px] font-mono font-bold text-[#C9A24D] uppercase block">SKOR RQI-15</span>
                                <div class="text-3xl font-black font-serif-gold text-white tracking-tight">85.0</div>
                                <span class="text-[5.5px] bg-[#C9A24D] text-[#0B2A43] font-bold px-2 py-0.5 rounded-full inline-block font-mono">✦ Level 5: Paripurna</span>
                            </div>

                            <div class="p-1 bg-emerald-500/20 rounded-xl border border-emerald-400/40 text-center">
                                <span class="text-[5.5px] font-mono font-bold text-emerald-300 block">🌱 WHO-5 MENTAL WELL-BEING: 88% (BAIK)</span>
                            </div>
                        </div>

                        <!-- 5 Dimension Mini Grid -->
                        <div class="grid grid-cols-5 gap-0.5 text-[4.5px] font-mono relative z-10">
                            <div class="bg-white/10 p-0.5 rounded border border-white/15 text-slate-200">Nafl 88%</div>
                            <div class="bg-white/10 p-0.5 rounded border border-white/15 text-slate-200">Nurani 84%</div>
                            <div class="bg-white/10 p-0.5 rounded border border-white/15 text-slate-200">Resil 80%</div>
                            <div class="bg-white/10 p-0.5 rounded border border-white/15 text-slate-200">Wahyu 85%</div>
                            <div class="bg-white/10 p-0.5 rounded border border-white/15 text-slate-200">Etis 88%</div>
                        </div>

                        <!-- Footer QR Code Verification (Opsi A) -->
                        <div class="space-y-1 pb-0.5 border-t border-white/20 pt-1.5 relative z-10">
                            <div class="flex items-center justify-center gap-1.5">
                                <div class="p-0.5 bg-white border border-amber-300 rounded inline-block">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=40x40&data=https%3A%2F%2Fruhiology.id%2Fsample" class="h-5 w-5 object-contain" alt="QR Verifikasi">
                                </div>
                                <div class="text-left leading-tight">
                                    <span class="font-bold block text-[5.5px] text-white" x-text="founderName"></span>
                                    <span class="text-slate-300 text-[4.5px] block truncate" x-text="founderTitle"></span>
                                    <span class="text-[#C9A24D] text-[4.5px] font-mono font-bold block">🔑 Terverifikasi QR Digital</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
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

        <button type="submit" class="px-7 py-3.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-extrabold rounded-xl shadow-md transition cursor-pointer">
            Simpan Seluruh Pengaturan Sistem & Tampilan →
        </button>
    </form>
</div>
@endsection
