@extends('layouts.admin')

@section('title', 'Tambah Program Pelatihan Baru — Ruhiology')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">
                <a href="{{ route('admin.training.index') }}" class="hover:text-primary-600 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Daftar Program
                </a>
                <span>/</span>
                <span class="text-slate-600">Tambah Baru</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-slate-800">Tambah Program Pelatihan & Sertifikasi</h1>
        </div>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8">
        <form action="{{ route('admin.training.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs md:text-sm">
            @csrf

            <!-- Program Title & Category -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Judul Pelatihan / Sertifikasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Certified Ruhiology Facilitator (CRF) Level 1"
                           class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('title')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category', 'Sertifikasi Nasional') }}" required
                           placeholder="Sertifikasi / Workshop / Executive"
                           class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <!-- Trainer Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Trainer / Instruktur Utama <span class="text-rose-500">*</span></label>
                    <input type="text" name="trainer" value="{{ old('trainer', 'Prof. Dr. Iskandar Nazari & Tim') }}" required
                           class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">WhatsApp PIC / Admin Pelatihan</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="081234567890"
                           class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Bio / Kredensial Singkat Trainer</label>
                <textarea name="trainer_bio" rows="2" placeholder="Latar belakang, kepakaran, atau pengalaman trainer..."
                          class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">{{ old('trainer_bio') }}</textarea>
            </div>

            <!-- Image Upload Banner -->
            <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200 space-y-2">
                <label class="block font-bold text-amber-900">Upload Banner / Cover Program (PNG, JPG, WEBP maks 5MB)</label>
                <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp"
                       class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-amber-800 border border-amber-300 rounded-xl p-1.5 bg-white">
                <div class="text-[11px] text-slate-500 italic">Atau isi link URL gambar eksternal jika sudah ada:</div>
                <input type="text" name="image" value="{{ old('image') }}" placeholder="https://domain.com/banner.jpg"
                       class="w-full text-xs px-3 py-2 rounded-xl border border-slate-300 bg-white">
            </div>

            <!-- Description -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Lengkap Program <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="4" required placeholder="Jelaskan tujuan program, profil kompetensi, dan manfaat bagi peserta..."
                          class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">{{ old('description') }}</textarea>
            </div>

            <!-- Syllabus -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Silabus & Materi (Pisahkan per baris materi)</label>
                <textarea name="syllabus" rows="4" placeholder="Modul 1: Konsep Dasar Kecerdasan Ruhiologi&#10;Modul 2: Konstruk Dimensi & Skoring Asesmen&#10;Modul 3: Praktik Supervisi & Konseling&#10;Modul 4: Uji Kompetensi & Sertifikasi"
                          class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">{{ old('syllabus') }}</textarea>
            </div>

            <!-- Facilities -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Fasilitas & Benefit Peserta (Pisahkan per baris fasilitas)</label>
                <textarea name="facilities" rows="3" placeholder="E-Sertifikat Resmi Ber-SK Ruhiology Institute&#10;Modul & Handbook Lengkap&#10;Akses Rekaman Sesi Webinar&#10;Komunitas Fasilitator"
                          class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">{{ old('facilities') }}</textarea>
            </div>

            <!-- Target Audience -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Sasaran / Target Peserta</label>
                <input type="text" name="target_audience" value="{{ old('target_audience') }}" placeholder="Guru, Dosen, Konselor BK, Praktisi SDM, dan Umum"
                       class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <!-- Pricing & Duration & Quota -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Durasi Pelatihan <span class="text-rose-500">*</span></label>
                    <input type="text" name="duration" value="{{ old('duration', '2 Hari (16 JP)') }}" required
                           class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Biaya Investasi (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', 500000) }}" required min="0"
                           class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kuota Peserta per Angkatan <span class="text-rose-500">*</span></label>
                    <input type="number" name="quota" value="{{ old('quota', 30) }}" required min="1"
                           class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <!-- Format & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Lokasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location', 'Online (Zoom Meeting)') }}" required
                           class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Format Pelaksanaan <span class="text-rose-500">*</span></label>
                    <select name="is_online" class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="1" {{ old('is_online', '1') == '1' ? 'selected' : '' }}>Online (Zoom)</option>
                        <option value="0" {{ old('is_online') == '0' ? 'selected' : '' }}>Offline (Tatap Muka)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Publikasi <span class="text-rose-500">*</span></label>
                    <select name="status" class="w-full text-xs md:text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Tampil di Web)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-200 flex justify-end gap-3">
                <a href="{{ route('admin.training.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-navy hover:bg-navy-dark text-white font-bold transition shadow-md">
                    Simpan & Publikasikan Program
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
