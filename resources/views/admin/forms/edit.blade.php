@extends('layouts.admin')

@section('title', 'Edit Formulir Kuisioner')

@section('content')
<div x-data="formBuilder()" class="space-y-6 max-w-5xl mx-auto pb-16">

    <!-- TOP HEADER -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.forms.index') }}" class="text-slate-400 hover:text-slate-700 text-xs font-bold transition flex items-center gap-1">
                    ← Kembali ke Daftar Form
                </a>
            </div>
            <h1 class="text-2xl font-bold font-serif text-[#0B2A43] mt-1">Edit Formulir Kuisioner</h1>
            <p class="text-xs text-slate-500">Perbarui pengaturan formulir, pertanyaan, atau pilihan jawaban.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ $form->public_url }}" target="_blank" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition flex items-center gap-1.5">
                <span>👁️ Lihat Halaman Publik</span>
            </a>
            <button type="button" @click="submitForm('published')" class="px-5 py-2.5 bg-gradient-to-r from-[#0B2A43] to-[#123B59] hover:from-[#123B59] hover:to-[#1a4a6e] text-[#D4AF37] font-bold rounded-xl text-xs shadow-md transition flex items-center gap-2 cursor-pointer border border-[#D4AF37]/30 hover:scale-[1.02]">
                <span>💾 Simpan Perubahan</span>
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs space-y-1">
            <strong class="font-bold flex items-center gap-1">⚠️ Terjadi kesalahan validasi:</strong>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="mainFormBuilder" action="{{ route('admin.forms.update', $form->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs font-sans">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" x-model="status">

        <!-- 1. FORM METADATA CARD (TITLE, SLUG, DESCRIPTION) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#0B2A43] via-[#D4AF37] to-[#0B2A43]"></div>

            <div class="space-y-4 pt-1">
                <div>
                    <label class="block font-bold text-slate-800 text-sm mb-1">Judul Formulir / Kuisioner *</label>
                    <input type="text" name="title" x-model="title" required placeholder="Judul formulir..." class="w-full p-3.5 text-base sm:text-lg font-bold rounded-2xl border-2 border-slate-200 focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/10 outline-none transition bg-slate-50/50 focus:bg-white text-slate-900">
                </div>

                <!-- Custom URL Slug Preview -->
                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 font-mono bg-slate-100/70 p-3 rounded-xl border border-slate-200/60">
                    <span class="text-slate-400">🔗 Tautan Publik:</span>
                    <span class="text-[#0B2A43] font-bold">{{ url('/f') }}/</span>
                    <input type="text" name="slug" x-model="slug" placeholder="slug-formulir" class="p-1 px-2 font-mono font-bold text-xs bg-white border border-slate-300 rounded-lg outline-none focus:ring-1 focus:ring-[#0B2A43] text-[#B48A16]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi & Petunjuk Pengisian Formulir</label>
                    <textarea name="description" x-model="description" rows="3" placeholder="Tuliskan petunjuk pengisian..." class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none text-xs leading-relaxed"></textarea>
                </div>
            </div>

            <!-- Accordion / Settings Grid -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Batas Waktu Pengisian (Deadline)</label>
                    <input type="datetime-local" name="closes_at" value="{{ $form->closes_at ? $form->closes_at->format('Y-m-d\TH:i') : '' }}" class="w-full p-2.5 rounded-xl border border-slate-300 outline-none text-xs bg-white">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Kosongkan jika tanpa batas waktu.</span>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kunci Akses / Passcode (Opsional)</label>
                    <input type="text" name="access_code" value="{{ $form->access_code }}" placeholder="Contoh: GURU2026" class="w-full p-2.5 rounded-xl border border-slate-300 outline-none text-xs font-mono uppercase bg-white">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Jika diisi, peserta wajib memasukkan kunci.</span>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Ganti Banner Header (Opsional)</label>
                    <input type="file" name="header_file" accept="image/*" class="w-full text-xs p-1.5 rounded-xl border border-slate-300 bg-white">
                    @if($form->header_image)
                        <span class="text-[10px] text-emerald-600 font-semibold mt-0.5 block">✓ Banner aktif terpasang</span>
                    @endif
                </div>
            </div>

            <div class="pt-2">
                <label class="block font-bold text-slate-700 mb-1">Pesan Sukses Setelah Pengiriman</label>
                <input type="text" name="success_message" value="{{ $form->success_message }}" class="w-full p-2.5 rounded-xl border border-slate-300 outline-none text-xs bg-white">
            </div>
        </div>

        <!-- 2. CERTIFICATE SETTINGS CARD (EVENT & DIKLAT JP) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6 relative overflow-hidden">
            <!-- Top Toggle Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-xl flex-shrink-0">
                        🎓
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-serif font-bold text-[#0B2A43] flex items-center gap-2">
                            <span>Penerbitan Sertifikat Resmi Event & JP</span>
                            <span class="text-[10px] font-sans font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-800 uppercase tracking-wider">Diklat & Pelatihan</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Hasilkan sertifikat resmi 2 halaman (Piagam + Matriks Materi & JP) untuk peserta formulir ini.
                        </p>
                    </div>
                </div>

                <!-- Main Toggle Switch -->
                <label class="relative inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox" name="has_certificate" value="1" x-model="hasCertificate" class="sr-only peer">
                    <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0B2A43]"></div>
                    <span class="ml-3 text-xs font-bold text-slate-700" x-text="hasCertificate ? 'Sertifikat Aktif' : 'Nonaktif'"></span>
                </label>
            </div>

            <!-- Certificate Configuration Body (shown if hasCertificate is true) -->
            <div x-show="hasCertificate" x-transition.duration.200ms class="space-y-6 pt-1" style="display: none;">
                
                <!-- Notice & Status Release Switch -->
                <div class="p-4 rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50/50 border border-amber-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                            <span>Status Kesiapan Sertifikat Saat Ini:</span>
                        </span>
                        <p class="text-[11px] text-amber-800 leading-relaxed">
                            Pilih <strong class="underline">Terkunci</strong> jika acara masih berlangsung agar peserta tidak bisa klaim lebih awal. Buka setelah acara tuntas.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 p-2 px-3 rounded-xl border cursor-pointer text-xs font-semibold"
                               :class="certificateStatus === 'locked' ? 'bg-amber-500 text-white border-amber-600' : 'bg-white text-slate-700 border-slate-200'">
                            <input type="radio" name="certificate_status" value="locked" x-model="certificateStatus" class="sr-only">
                            <span>🔒 Terkunci (Acara Berlangsung)</span>
                        </label>
                        <label class="inline-flex items-center gap-2 p-2 px-3 rounded-xl border cursor-pointer text-xs font-semibold"
                               :class="certificateStatus === 'released' ? 'bg-emerald-600 text-white border-emerald-700' : 'bg-white text-slate-700 border-slate-200'">
                            <input type="radio" name="certificate_status" value="released" x-model="certificateStatus" class="sr-only">
                            <span>🚀 Terbuka (Siap Klaim)</span>
                        </label>
                    </div>
                </div>

                <!-- Event Details Grid -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">1. Data Pokok Agenda / Pelatihan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Nama Agenda / Kegiatan Pelatihan *</label>
                            <input type="text" name="certificate_event_name" value="{{ $form->certificate_event_name }}" placeholder="Contoh: Ruhiology Assessment & Peningkatan Kompetensi Guru SMAN TT" class="w-full p-2.5 rounded-xl border border-slate-300 outline-none text-xs bg-white focus:border-[#0B2A43] focus:ring-1 focus:ring-[#0B2A43]">
                            <span class="text-[10px] text-slate-400 mt-0.5 block">Nama ini akan tercantum tebal di badan piagam sertifikat peserta.</span>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Waktu / Tanggal Pelaksanaan</label>
                            <input type="text" name="certificate_event_date" value="{{ $form->certificate_event_date }}" placeholder="Contoh: 24 - 26 Oktober 2026" class="w-full p-2.5 rounded-xl border border-slate-300 outline-none text-xs bg-white focus:border-[#0B2A43]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tempat / Kota Kegiatan</label>
                            <input type="text" name="certificate_event_location" value="{{ $form->certificate_event_location ?? 'Tanjung Jabung Timur, Jambi' }}" placeholder="Contoh: Tanjung Jabung Timur, Jambi" class="w-full p-2.5 rounded-xl border border-slate-300 outline-none text-xs bg-white focus:border-[#0B2A43]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Total Bobot Jam Pelajaran (JP)</label>
                            <input type="text" name="certificate_total_jp" value="{{ $form->certificate_total_jp ?? '32 JP' }}" placeholder="Contoh: 32 JP" class="w-full p-2.5 rounded-xl border border-slate-300 outline-none text-xs bg-white focus:border-[#0B2A43]">
                            <span class="text-[10px] text-slate-400 mt-0.5 block">Tercantum di piagam depan & total di tabel lembar belakang.</span>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Format / Pola Nomor Sertifikat</label>
                            <input type="text" name="certificate_number_pattern" value="{{ $form->certificate_number_pattern ?? '{no}/SERT-RQ/SMAN-TT/{m}/{Y}' }}" placeholder="{no}/SERT-RQ/SMAN-TT/{m}/{Y}" class="w-full p-2.5 rounded-xl border border-slate-300 outline-none text-xs font-mono bg-white focus:border-[#0B2A43]">
                            <span class="text-[10px] text-slate-400 mt-0.5 block">Gunakan tag: <code>{no}</code> (nomor urut), <code>{m}</code> (bulan romawi), <code>{Y}</code> (tahun).</span>
                        </div>
                    </div>
                </div>

                <!-- Signers & Logo Grid -->
                <div class="space-y-4 pt-3 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">2. Penandatangan & Identitas Mitra</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- Signer 1: Ruhiology -->
                        <div class="p-4 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-3">
                            <span class="text-xs font-bold text-slate-800 block">Tanda Tangan 1: Direktur Ruhiology Institute</span>
                            <div>
                                <label class="block text-[11px] text-slate-600 mb-1">Nama Lengkap & Gelar</label>
                                <input type="text" name="certificate_signer1_name" value="{{ $form->certificate_signer1_name ?? 'Prof. Dr. Iskandar Nazari, M.Pd' }}" class="w-full p-2 rounded-lg border border-slate-300 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-600 mb-1">Jabatan Resmi</label>
                                <input type="text" name="certificate_signer1_role" value="{{ $form->certificate_signer1_role ?? 'Direktur Ruhiology Institute' }}" class="w-full p-2 rounded-lg border border-slate-300 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-600 mb-1">File Tanda Tangan Digital (.png transparan)</label>
                                <input type="file" name="signer1_sig_file" accept="image/*" class="w-full text-xs p-1 rounded-lg border border-slate-300 bg-white">
                                @if($form->certificate_signer1_signature)
                                    <div class="mt-1 flex items-center gap-2">
                                        <img src="{{ $form->certificate_signer1_signature }}" class="h-8 object-contain bg-white p-0.5 border rounded">
                                        <span class="text-[10px] text-emerald-600 font-semibold">✓ Tanda tangan aktif</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Signer 2: Partner / School -->
                        <div class="p-4 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-3">
                            <span class="text-xs font-bold text-slate-800 block">Tanda Tangan 2: Pimpinan Mitra / Sekolah</span>
                            <div>
                                <label class="block text-[11px] text-slate-600 mb-1">Nama Lengkap & Gelar</label>
                                <input type="text" name="certificate_signer2_name" value="{{ $form->certificate_signer2_name }}" placeholder="Contoh: Drs. H. Ahmad Sobri, M.Si" class="w-full p-2 rounded-lg border border-slate-300 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-600 mb-1">Jabatan Resmi</label>
                                <input type="text" name="certificate_signer2_role" value="{{ $form->certificate_signer2_role }}" placeholder="Contoh: Kepala Sekolah SMAN TT" class="w-full p-2 rounded-lg border border-slate-300 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-600 mb-1">File Tanda Tangan Digital (.png transparan)</label>
                                <input type="file" name="signer2_sig_file" accept="image/*" class="w-full text-xs p-1 rounded-lg border border-slate-300 bg-white">
                                @if($form->certificate_signer2_signature)
                                    <div class="mt-1 flex items-center gap-2">
                                        <img src="{{ $form->certificate_signer2_signature }}" class="h-8 object-contain bg-white p-0.5 border rounded">
                                        <span class="text-[10px] text-emerald-600 font-semibold">✓ Tanda tangan aktif</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Partner Logo -->
                        <div class="md:col-span-2 p-4 bg-slate-50/70 rounded-2xl border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-slate-800 block">Logo Mitra / Sekolah (Opsional)</span>
                                <p class="text-[11px] text-slate-500">
                                    Akan ditampilkan berdampingan dengan lambang resmi Ruhiology Institute di kop sertifikat.
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($form->certificate_partner_logo)
                                    <img src="{{ $form->certificate_partner_logo }}" class="h-10 w-10 object-contain p-1 bg-white border border-slate-200 rounded-lg">
                                @endif
                                <input type="file" name="partner_logo_file" accept="image/*" class="text-xs p-1.5 rounded-xl border border-slate-300 bg-white">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Dynamic Curriculum Matrix (Halaman 2 / Lembar Belakang) -->
                <div class="space-y-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">3. Matriks Materi Pelatihan & Alokasi JP (Halaman 2)</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Isi pokok materi/modul pembelajaran yang diajarkan beserta alokasi jam pelajarannya.
                            </p>
                        </div>
                        <button type="button" @click="addCurriculum()" class="px-3.5 py-1.5 bg-[#0B2A43] hover:bg-[#123B59] text-[#D4AF37] font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                            <span>➕ Tambah Materi</span>
                        </button>
                    </div>

                    <input type="hidden" name="certificate_curriculum" :value="JSON.stringify(curriculum)">

                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600 font-bold border-b border-slate-200">
                                    <th class="p-3 w-12 text-center">No</th>
                                    <th class="p-3">Pokok Materi / Modul Pembelajaran</th>
                                    <th class="p-3 w-32">Alokasi JP</th>
                                    <th class="p-3 w-16 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                <template x-for="(item, idx) in curriculum" :key="idx">
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="p-3 text-center font-bold text-slate-400" x-text="idx + 1"></td>
                                        <td class="p-2.5">
                                            <input type="text" x-model="item.title" placeholder="Contoh: Psikologi Karakter & Asesmen Holistik Siswa" class="w-full p-2 rounded-lg border border-slate-300 text-xs bg-white outline-none focus:ring-1 focus:ring-[#0B2A43]">
                                        </td>
                                        <td class="p-2.5">
                                            <input type="text" x-model="item.jp" placeholder="Contoh: 10 JP" class="w-full p-2 rounded-lg border border-slate-300 text-xs font-mono text-center bg-white outline-none focus:ring-1 focus:ring-[#0B2A43]">
                                        </td>
                                        <td class="p-2.5 text-center">
                                            <button type="button" @click="removeCurriculum(idx)" :disabled="curriculum.length <= 1" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 disabled:opacity-30 cursor-pointer transition">
                                                ✕
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- 3. DYNAMIC QUESTIONS BUILDER SECTION -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-serif font-bold text-base text-[#0B2A43]">Daftar Pertanyaan / Kolom Formulir</h3>
                    <p class="text-xs text-slate-500">Susun pertanyaan kuisioner, tipe jawaban, dan opsi pilihan yang dibutuhkan.</p>
                </div>
                <button type="button" @click="addField()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <span>➕ Tambah Pertanyaan</span>
                </button>
            </div>

            <!-- Questions List -->
            <template x-for="(field, index) in fields" :key="field.id">
                <div class="bg-white rounded-2xl p-5 border-2 transition-all space-y-4 shadow-xs" :class="field.is_required ? 'border-slate-200/90' : 'border-slate-200/60'">
                    
                    <!-- Question Top Bar -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#0B2A43] text-white flex items-center justify-center font-bold text-xs" x-text="index + 1"></span>
                            <span class="font-bold text-slate-700 text-xs" x-text="'Pertanyaan #' + (index + 1)"></span>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center gap-1">
                            <button type="button" @click="moveUp(index)" :disabled="index === 0" class="p-1.5 text-slate-400 hover:text-slate-700 disabled:opacity-30 rounded hover:bg-slate-100 cursor-pointer" title="Pindah ke Atas">
                                ⬆️
                            </button>
                            <button type="button" @click="moveDown(index)" :disabled="index === fields.length - 1" class="p-1.5 text-slate-400 hover:text-slate-700 disabled:opacity-30 rounded hover:bg-slate-100 cursor-pointer" title="Pindah ke Bawah">
                                ⬇️
                            </button>
                            <button type="button" @click="duplicateField(index)" class="p-1.5 text-slate-400 hover:text-blue-600 rounded hover:bg-blue-50 cursor-pointer" title="Duplikasi Pertanyaan">
                                📋
                            </button>
                            <button type="button" @click="removeField(index)" :disabled="fields.length === 1" class="p-1.5 text-slate-400 hover:text-rose-600 disabled:opacity-30 rounded hover:bg-rose-50 cursor-pointer" title="Hapus Pertanyaan">
                                🗑️
                            </button>
                        </div>
                    </div>

                    <!-- Question Input Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-start">
                        <!-- Label / Pertanyaan -->
                        <div class="sm:col-span-8 space-y-2">
                            <input type="text" :name="'fields[' + index + '][label]'" x-model="field.label" required placeholder="Tuliskan pertanyaan / label kolom di sini..." class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] font-bold text-slate-800 outline-none text-xs">
                            
                            <!-- Placeholder / Hint -->
                            <input type="text" :name="'fields[' + index + '][placeholder]'" x-model="field.placeholder" placeholder="Teks petunjuk singkat (placeholder) opsional..." class="w-full p-2 rounded-lg border border-slate-200 text-[11px] text-slate-600 outline-none">
                        </div>

                        <!-- Tipe Jawaban Selector -->
                        <div class="sm:col-span-4">
                            <label class="block font-bold text-slate-700 text-[10px] uppercase mb-1">Tipe Kolom / Jawaban</label>
                            <select :name="'fields[' + index + '][field_type]'" x-model="field.field_type" class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 text-xs focus:bg-white outline-none">
                                <option value="text">✏️ Teks Singkat</option>
                                <option value="textarea">📄 Paragraf / Esai</option>
                                <option value="radio">🔘 Pilihan Ganda (Radio)</option>
                                <option value="checkbox">☑️ Kotak Centang (Banyak Pilihan)</option>
                                <option value="dropdown">🔽 Pilihan Dropdown</option>
                                <option value="number">🔢 Angka / Bilangan</option>
                                <option value="date">📅 Tanggal</option>
                                <option value="file">📎 Upload File / Dokumen</option>
                            </select>
                        </div>
                    </div>

                    <!-- OPTIONS BUILDER (FOR RADIO, CHECKBOX, DROPDOWN) -->
                    <template x-if="['radio', 'checkbox', 'dropdown'].includes(field.field_type)">
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                            <label class="block font-bold text-slate-700 text-xs flex items-center justify-between">
                                <span>Daftar Pilihan Jawaban:</span>
                                <button type="button" @click="addOption(field)" class="text-blue-600 hover:text-blue-800 text-[11px] font-bold cursor-pointer">
                                    + Tambah Opsi
                                </button>
                            </label>

                            <div class="space-y-2">
                                <template x-for="(opt, optIdx) in field.options" :key="optIdx">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400 text-xs font-mono" x-text="(optIdx + 1) + '.'"></span>
                                        <input type="text" :name="'fields[' + index + '][options][]'" x-model="field.options[optIdx]" placeholder="Ketik pilihan jawaban..." class="flex-1 p-2 rounded-lg border border-slate-300 bg-white text-xs outline-none focus:ring-1 focus:ring-[#0B2A43]">
                                        <button type="button" @click="removeOption(field, optIdx)" :disabled="field.options.length <= 1" class="text-slate-400 hover:text-rose-600 disabled:opacity-30 p-1 cursor-pointer">
                                            ✕
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Bottom Question Toggles -->
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" :name="'fields[' + index + '][is_required]'" value="1" x-model="field.is_required" class="w-4 h-4 text-[#0B2A43] rounded border-slate-300 focus:ring-[#0B2A43]">
                            <span class="font-bold text-slate-700 text-xs">Wajib Diisi (Required) *</span>
                        </label>
                        <span class="text-[11px] text-slate-400" x-text="field.is_required ? '⚠️ Peserta wajib mengisi kolom ini' : 'Opsional (bisa dilewati)'"></span>
                    </div>

                </div>
            </template>

            <!-- Bottom Add Question Button -->
            <div class="p-6 border-2 border-dashed border-slate-300 rounded-3xl text-center bg-slate-50/50 hover:bg-slate-50 transition cursor-pointer" @click="addField()">
                <button type="button" class="text-[#0B2A43] font-bold text-xs flex items-center justify-center gap-2 mx-auto pointer-events-none">
                    <span class="w-7 h-7 rounded-full bg-[#0B2A43] text-white flex items-center justify-center text-sm shadow">➕</span>
                    <span>Tambah Pertanyaan Baru</span>
                </button>
            </div>
        </div>

        <!-- BOTTOM STICKY ACTION BAR -->
        <div class="sticky bottom-4 bg-white/95 backdrop-blur-md p-4 rounded-2xl border border-slate-200/80 shadow-xl flex items-center justify-between z-20">
            <div class="flex items-center gap-2 text-xs text-slate-600">
                <span>Total:</span>
                <strong class="font-bold text-[#0B2A43]" x-text="fields.length + ' Pertanyaan'"></strong>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" @click="submitForm('draft')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition cursor-pointer">
                    Simpan Sebagai Draft
                </button>
                <button type="button" @click="submitForm('published')" class="px-6 py-2.5 bg-gradient-to-r from-[#0B2A43] to-[#123B59] hover:from-[#123B59] hover:to-[#1a4a6e] text-[#D4AF37] font-extrabold rounded-xl text-xs shadow-md transition cursor-pointer border border-[#D4AF37]/30 hover:scale-[1.02]">
                    Simpan Perubahan Formulir →
                </button>
            </div>
        </div>

    </form>
</div>

<script>
function formBuilder() {
    return {
        title: @json($form->title),
        slug: @json($form->slug),
        description: @json($form->description ?? ''),
        status: @json($form->status),
        fields: @json($fieldsData),
        hasCertificate: @json((bool) ($form->has_certificate ?? false)),
        certificateStatus: @json($form->certificate_status ?? 'locked'),
        curriculum: @json(!empty($curriculumData) ? $curriculumData : [
            ['title' => 'Pondasi Ruhiology & Integritas Kepemimpinan Pendidik', 'jp' => '6 JP'],
            ['title' => 'Psikologi Karakter & Asesmen Holistik Siswa', 'jp' => '10 JP'],
            ['title' => 'Strategi Pedagogik Berbasis Asesmen & Empati', 'jp' => '10 JP'],
            ['title' => 'Implementasi Kurikulum & Evaluasi Tindak Lanjut', 'jp' => '6 JP']
        ]),

        addCurriculum() {
            this.curriculum.push({
                title: '',
                jp: '4 JP'
            });
        },

        removeCurriculum(idx) {
            if (this.curriculum.length > 1) {
                this.curriculum.splice(idx, 1);
            }
        },

        addField() {
            this.fields.push({
                id: Date.now() + Math.random(),
                label: '',
                field_type: 'text',
                placeholder: '',
                is_required: false,
                options: ['Opsi 1', 'Opsi 2']
            });
        },

        removeField(index) {
            if (this.fields.length > 1) {
                this.fields.splice(index, 1);
            }
        },

        duplicateField(index) {
            const item = JSON.parse(JSON.stringify(this.fields[index]));
            item.id = Date.now() + Math.random();
            item.label = item.label + ' (Salinan)';
            this.fields.splice(index + 1, 0, item);
        },

        moveUp(index) {
            if (index > 0) {
                const temp = this.fields[index];
                this.fields[index] = this.fields[index - 1];
                this.fields[index - 1] = temp;
            }
        },

        moveDown(index) {
            if (index < this.fields.length - 1) {
                const temp = this.fields[index];
                this.fields[index] = this.fields[index + 1];
                this.fields[index + 1] = temp;
            }
        },

        addOption(field) {
            if (!field.options) field.options = [];
            field.options.push('Opsi ' + (field.options.length + 1));
        },

        removeOption(field, optIdx) {
            if (field.options.length > 1) {
                field.options.splice(optIdx, 1);
            }
        },

        submitForm(targetStatus) {
            this.status = targetStatus;

            // Bersihkan field kosong yang tidak sengaja ditambahkan sebelum dikirim
            const validFields = this.fields.filter(f => f.label && f.label.trim().length > 0);
            if (validFields.length === 0) {
                alert('Mohon isi minimal satu pertanyaan dengan judul/label yang jelas.');
                return;
            }
            this.fields = validFields;

            this.$nextTick(() => {
                document.getElementById('mainFormBuilder').submit();
            });
        }
    };
}
</script>
@endsection
