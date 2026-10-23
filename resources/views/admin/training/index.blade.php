@extends('layouts.admin')

@section('title', 'Training Program Center — Admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, batchModal: false, selectedTrainingId: null, activeTraining: {} }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Training Program Center</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola program pelatihan, silabus kurikulum, jadwal angkatan (batch), dan kuota pendaftar.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.training.registrations') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-sm transition">
                Data & Verifikasi Peserta
            </a>
            <button @click="createModal = true" class="px-4 py-2.5 bg-navy hover:bg-navy-dark text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Program Training
            </button>
        </div>
    </div>

    <!-- Grid of Training Programs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($trainings as $t)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between group hover:border-slate-300 transition">
                <!-- Training Cover Banner Image -->
                <div class="relative h-48 bg-slate-900 overflow-hidden border-b border-slate-100">
                    @if($t->image)
                        <img src="{{ $t->image }}" alt="{{ $t->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-navy via-navy-dark to-slate-900 flex items-center justify-center text-white/90 p-4 text-center">
                            <span class="font-serif font-bold text-sm text-gold">🎓 {{ $t->title }}</span>
                        </div>
                    @endif
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-navy/90 text-gold border border-gold/30 text-[10px] font-bold uppercase tracking-wider backdrop-blur-xs">
                        {{ $t->category }}
                    </span>
                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm {{ $t->status === 'published' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                        {{ $t->status }}
                    </span>
                </div>

                <div class="p-5 space-y-4 flex-1 flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="font-bold font-serif text-slate-900 text-base leading-snug">{{ $t->title }}</h3>
                        </div>
                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $t->description }}</p>
                        
                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-100 font-medium">
                            <div>👨‍🏫 Trainer: <strong class="text-slate-800 block truncate">{{ $t->trainer }}</strong></div>
                            <div>⏳ Durasi: <strong class="text-slate-800 block">{{ $t->duration }}</strong></div>
                            <div>💰 Biaya: <strong class="text-navy block font-bold">Rp {{ number_format($t->price, 0, ',', '.') }}</strong></div>
                            <div>📍 Lokasi: <strong class="text-slate-800 block truncate">{{ $t->location }}</strong></div>
                        </div>
                    </div>

                    <!-- Batches list -->
                    <div class="border-t border-slate-100 pt-3 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-xs text-slate-800">Daftar Angkatan (Batches)</span>
                            <button @click="selectedTrainingId = {{ $t->id }}; batchModal = true" class="text-[11px] font-bold text-primary-700 hover:underline">
                                + Tambah Batch
                            </button>
                        </div>
                        @if($t->batches->count() > 0)
                            <div class="space-y-1.5">
                                @foreach($t->batches as $b)
                                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs flex justify-between items-center">
                                        <div>
                                            <span class="font-bold text-slate-900 block">{{ $b->batch_name }}</span>
                                            <span class="text-[10px] text-slate-500">{{ $b->start_date ? $b->start_date->format('d M Y') : '-' }} s/d {{ $b->end_date ? $b->end_date->format('d M Y') : '-' }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $b->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $b->status }}
                                            </span>
                                            <div class="text-[10px] text-slate-400 mt-0.5">
                                                Kuota: {{ $b->quota }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-[11px] text-slate-400 italic">Belum ada batch/angkatan dibuka.</div>
                        @endif
                    </div>

                    <!-- Action buttons -->
                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center gap-2">
                        <div class="flex items-center gap-2">
                            <button @click="activeTraining = {{ json_encode($t) }}; editModal = true" 
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition flex items-center gap-1">
                                <span>✏️ Edit Program</span>
                            </button>
                            <a href="{{ route('training.show', $t->slug) }}" target="_blank" 
                               class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs rounded-lg transition">
                                👁️ Preview Publik
                            </a>
                        </div>
                        <form action="{{ route('admin.training.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program pelatihan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-lg transition flex items-center gap-1">
                                <span>🗑️ Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-4">
        {{ $trainings->links() }}
    </div>

    <!-- Create Training Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Buat Training Program Baru</h3>
                <button type="button" @click="createModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('admin.training.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Training</label>
                    <input type="text" name="title" required placeholder="Contoh: Certified Ruhiology Facilitator (CRF) Level 1" class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <input type="text" name="category" value="Sertifikasi Nasional" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Trainer Utama</label>
                        <input type="text" name="trainer" value="Prof. Dr. Iskandar Nazari & Tim" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Bio / Kredensial Trainer</label>
                    <textarea name="trainer_bio" rows="2" placeholder="Profil ringkas, kepakaran, atau gelar fasilitator..." class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <!-- Upload Gambar Banner Training -->
                <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200 space-y-2">
                    <label class="block font-bold text-amber-900">Upload Banner / Gambar Program (PNG, JPG, WEBP max 5MB)</label>
                    <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="w-full p-2 rounded-xl border border-slate-300 bg-white text-xs">
                    <div class="text-[10px] text-slate-500 italic">Atau gunakan URL Gambar eksternal (opsional):</div>
                    <input type="text" name="image" placeholder="https://domain.com/path-to-banner.jpg" class="w-full p-2 rounded-xl border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Lengkap Program</label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan latar belakang, visi, dan kompetensi yang dipelajari..." class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Silabus & Materi (Pisahkan per baris baru)</label>
                    <textarea name="syllabus" rows="3" placeholder="Modul 1: Konsep Dasar Ruhiologi&#10;Modul 2: Metodologi Asesmen & Diagnosa&#10;Modul 3: Praktik Konseling & Supervisi" class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Fasilitas Peserta (Pisahkan per baris baru)</label>
                    <textarea name="facilities" rows="2" placeholder="E-Sertifikat Resmi Ber-SK Ruhiology&#10;Modul Pelatihan Lengkap&#10;Akses Rekaman Video & Diskusi" class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sasaran / Target Peserta (Opsional)</label>
                    <input type="text" name="target_audience" placeholder="Guru, Dosen, Konselor BK, Praktisi SDM, dan Umum" class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Durasi</label>
                        <input type="text" name="duration" value="2 Hari" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Biaya Investasi (Rp)</label>
                        <input type="number" name="price" value="500000" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kuota per Batch</label>
                        <input type="number" name="quota" value="30" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lokasi</label>
                        <input type="text" name="location" value="Online (Zoom)" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mode Pelaksanaan</label>
                        <select name="is_online" class="w-full p-2.5 rounded-xl border border-slate-300">
                            <option value="1">Online (Zoom)</option>
                            <option value="0">Offline (Tatap Muka)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">WhatsApp PIC Admin</label>
                        <input type="text" name="contact_person" placeholder="081234567890" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" class="w-full p-2.5 rounded-xl border border-slate-300">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div class="pt-3 border-t flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded-xl text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-navy hover:bg-navy-dark text-white font-bold rounded-xl shadow transition">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Training Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Edit Program Training</h3>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form :action="'{{ url('/admin/training') }}/' + activeTraining.id" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Training</label>
                    <input type="text" name="title" x-model="activeTraining.title" required class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <input type="text" name="category" x-model="activeTraining.category" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Trainer Utama</label>
                        <input type="text" name="trainer" x-model="activeTraining.trainer" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Bio / Kredensial Trainer</label>
                    <textarea name="trainer_bio" rows="2" x-model="activeTraining.trainer_bio" placeholder="Profil ringkas trainer..." class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <!-- Upload Gambar Banner Training Edit -->
                <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200 space-y-2">
                    <label class="block font-bold text-amber-900">Ganti Banner / Gambar Program (Opsional)</label>
                    <template x-if="activeTraining.image">
                        <div class="flex items-center gap-3">
                            <img :src="activeTraining.image" class="w-16 h-10 object-cover rounded border border-slate-300">
                            <span class="text-[10px] text-slate-500">Banner saat ini</span>
                        </div>
                    </template>
                    <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="w-full p-2 rounded-xl border border-slate-300 bg-white text-xs">
                    <div class="text-[10px] text-slate-500 italic">Atau sesuaikan URL Gambar:</div>
                    <input type="text" name="image" x-model="activeTraining.image" class="w-full p-2 rounded-xl border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="3" x-model="activeTraining.description" required class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Silabus & Materi (Pisahkan per baris baru)</label>
                    <textarea name="syllabus" rows="3" x-model="activeTraining.syllabus" placeholder="Pisahkan per baris materi pelatihan..." class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Fasilitas Peserta (Pisahkan per baris baru)</label>
                    <textarea name="facilities" rows="2" x-model="activeTraining.facilities" placeholder="Pisahkan per baris fasilitas..." class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sasaran / Target Peserta (Opsional)</label>
                    <input type="text" name="target_audience" x-model="activeTraining.target_audience" class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Durasi</label>
                        <input type="text" name="duration" x-model="activeTraining.duration" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Biaya Investasi (Rp)</label>
                        <input type="number" name="price" x-model="activeTraining.price" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kuota</label>
                        <input type="number" name="quota" x-model="activeTraining.quota" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lokasi</label>
                        <input type="text" name="location" x-model="activeTraining.location" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mode Pelaksanaan</label>
                        <select name="is_online" x-model="activeTraining.is_online" class="w-full p-2.5 rounded-xl border border-slate-300">
                            <option value="1">Online (Zoom)</option>
                            <option value="0">Offline (Tatap Muka)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">WhatsApp PIC Admin</label>
                        <input type="text" name="contact_person" x-model="activeTraining.contact_person" placeholder="081234567890" class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" x-model="activeTraining.status" class="w-full p-2.5 rounded-xl border border-slate-300">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div class="pt-3 border-t flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border rounded-xl text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-navy hover:bg-navy-dark text-white font-bold rounded-xl shadow transition">Perbarui Program</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Batch Modal -->
    <div x-show="batchModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Tambah Angkatan (Batch Baru)</h3>
                <button type="button" @click="batchModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form :action="'{{ url('/admin/training') }}/' + selectedTrainingId + '/batches'" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Batch / Angkatan</label>
                    <input type="text" name="batch_name" placeholder="Angkatan 05 - November 2026" required class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tgl Pelaksanaan (Mulai)</label>
                        <input type="date" name="start_date" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tgl Pelaksanaan (Selesai)</label>
                        <input type="date" name="end_date" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Buka Pendaftaran</label>
                        <input type="date" name="registration_open" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tutup Pendaftaran</label>
                        <input type="date" name="registration_close" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kuota Batch Ini</label>
                        <input type="number" name="quota" value="30" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Batch</label>
                        <select name="status" class="w-full p-2.5 rounded-xl border border-slate-300">
                            <option value="open">Open (Buka Pendaftaran)</option>
                            <option value="closed">Closed</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 border-t flex justify-end gap-2">
                    <button type="button" @click="batchModal = false" class="px-4 py-2 border rounded-xl text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-navy hover:bg-navy-dark text-white font-bold rounded-xl shadow transition">Simpan Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
