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

        <!-- Section 3: Sertifikat Digital, Tanda Tangan & Kartu Hasil -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2 flex items-center gap-2">
                <span>📜</span>
                <span>Sertifikat Digital, Tanda Tangan Founder & Kartu Hasil</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Upload Tanda Tangan Digital Founder (PNG Transparan Disarankan)</label>
                    <input type="file" name="founder_signature_file" accept="image/*" class="w-full p-2 rounded border border-slate-300 bg-slate-50">
                    <p class="text-[11px] text-slate-400 mt-1">Disarankan format PNG transparan untuk tampilan sertifikat & kartu hasil yang optimal.</p>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Gambar Tanda Tangan (Opsional Manual)</label>
                    <input type="text" name="founder_signature" value="{{ $settings['founder_signature'] ?? '' }}" placeholder="Direct URL atau biarkan kosong" class="w-full p-2.5 rounded border border-slate-300">
                    @if(!empty($settings['founder_signature']))
                        <div class="mt-2 p-2 border border-slate-200 rounded bg-slate-50 flex items-center gap-3">
                            <span class="text-[11px] text-slate-500 font-semibold">Preview Signature:</span>
                            <img src="{{ $settings['founder_signature'] }}" alt="Signature Preview" class="h-10 object-contain max-w-[150px] bg-white p-1 rounded border border-slate-200">
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Utama Sertifikat</label>
                    <input type="text" name="certificate_title" value="{{ $settings['certificate_title'] ?? 'SERTIFIKAT HASIL ASESMEN RQI' }}" required class="w-full p-2.5 rounded border border-slate-300 font-serif font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Subjudul Sertifikat</label>
                    <input type="text" name="certificate_subtitle" value="{{ $settings['certificate_subtitle'] ?? 'Ruhiology Quotient Assessment Certificate' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Teks Pengantar Sertifikat (Body Text)</label>
                <textarea name="certificate_body_text" rows="2" required class="w-full p-2.5 rounded border border-slate-300">{{ $settings['certificate_body_text'] ?? 'Diberikan kepada peserta di bawah ini atas partisipasi dan pencapaian evaluasi potensi diri dalam Asesmen Ruhiology Quotient (RQI).' }}</textarea>
            </div>
        </div>

        <!-- General Info -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-sm border-b border-slate-100 pb-2">Identitas Lembaga & Penggagas</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Institute</label>
                    <input type="text" name="institute_name" value="{{ $settings['institute_name'] ?? 'RUHIOLOGY INSTITUTE' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tagline</label>
                    <input type="text" name="tagline" value="{{ $settings['tagline'] ?? 'Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Penggagas Teori RQ</label>
                    <input type="text" name="founder_name" value="{{ $settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.' }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Gelar / Jabatan Penggagas</label>
                    <input type="text" name="founder_title" value="{{ $settings['founder_title'] ?? 'Founder Ruhiology Institute & Guru Besar UIN STS Jambi' }}" required class="w-full p-2.5 rounded border border-slate-300">
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
