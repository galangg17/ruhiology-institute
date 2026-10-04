@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, batchModal: false, selectedTrainingId: null, activeTraining: {} }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Training Program Center</h2>
            <p class="text-xs text-slate-500">Kelola program pelatihan, workshop, sertifikasi, dan angkatan (batch).</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow transition">
            + Tambah Program Training
        </button>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($trainings as $t)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between group">
                <!-- Training Cover Banner Image -->
                <div class="relative h-44 bg-slate-100 overflow-hidden border-b border-slate-100">
                    @if($t->image)
                        <img src="{{ $t->image }}" alt="{{ $t->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-[#0B2A43] to-[#123B59] flex items-center justify-center text-white/80 p-4 text-center">
                            <span class="font-serif font-bold text-sm">🎓 {{ $t->title }}</span>
                        </div>
                    @endif
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-[#0B2A43]/90 text-[#C9A24D] border border-[#C9A24D]/30 text-[10px] font-bold uppercase tracking-wider backdrop-blur-xs">
                        {{ $t->category }}
                    </span>
                    <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm {{ $t->status === 'published' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                        {{ $t->status }}
                    </span>
                </div>

                <div class="p-5 space-y-4 flex-1 flex flex-col justify-between">
                    <div class="space-y-2">
                        <h3 class="font-bold font-serif text-slate-900 text-base leading-snug">{{ $t->title }}</h3>
                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $t->description }}</p>
                        
                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-100 font-medium">
                            <div>👨‍🏫 Trainer: <strong class="text-slate-800 block truncate">{{ $t->trainer }}</strong></div>
                            <div>⏳ Durasi: <strong class="text-slate-800 block">{{ $t->duration }}</strong></div>
                            <div>💰 Biaya: <strong class="text-amber-700 block">Rp {{ number_format($t->price, 0, ',', '.') }}</strong></div>
                            <div>📍 Lokasi: <strong class="text-slate-800 block truncate">{{ $t->location }}</strong></div>
                        </div>
                    </div>

                    <!-- Batches list -->
                    <div class="border-t border-slate-100 pt-3 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-xs text-slate-800">Daftar Angkatan (Batches)</span>
                            <button @click="selectedTrainingId = {{ $t->id }}; batchModal = true" class="text-[11px] font-bold text-amber-700 hover:underline">
                                + Tambah Batch
                            </button>
                        </div>
                        @if($t->batches->count() > 0)
                            @foreach($t->batches as $b)
                                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs flex justify-between items-center">
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ $b->batch_name }}</span>
                                        <span class="text-[10px] text-slate-500">{{ $b->start_date->format('d M Y') }} s/d {{ $b->end_date->format('d M Y') }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $b->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $b->status }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-[11px] text-slate-400 italic">Belum ada batch/angkatan dibuka.</div>
                        @endif
                    </div>

                    <!-- Action buttons -->
                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center gap-2">
                        <button @click="activeTraining = {{ json_encode($t) }}; editModal = true" 
                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition flex items-center gap-1">
                            <span>✏️ Edit Program</span>
                        </button>
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
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <h3 class="font-bold font-serif text-slate-900 text-base">Buat Training Program Baru</h3>
            <form action="{{ route('admin.training.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Training</label>
                    <input type="text" name="title" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <input type="text" name="category" value="Sertifikasi Nasional" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Trainer Utama</label>
                        <input type="text" name="trainer" value="Prof. Dr. Iskandar Nazari & Tim" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>

                <!-- Upload Gambar Banner Training -->
                <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200 space-y-2">
                    <label class="block font-bold text-amber-900">Upload Banner / Gambar Program (PNG, JPG, WEBP max 5MB)</label>
                    <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="w-full p-2 rounded border border-slate-300 bg-white text-xs">
                    <div class="text-[10px] text-slate-500 italic">Atau pasang URL Gambar eksternal (opsional):</div>
                    <input type="text" name="image" placeholder="https://domain.com/path-to-banner.jpg" class="w-full p-2 rounded border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="3" required class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Durasi</label>
                        <input type="text" name="duration" value="2 Hari" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" value="500000" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kuota</label>
                        <input type="number" name="quota" value="30" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lokasi</label>
                        <input type="text" name="location" value="Online (Zoom)" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mode Pelaksanaan</label>
                        <select name="is_online" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="1">Online (Zoom)</option>
                            <option value="0">Offline (Tatap Muka)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded shadow transition">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Training Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <h3 class="font-bold font-serif text-slate-900 text-base">Edit Program Training</h3>
            <form :action="'{{ url('/admin/training') }}/' + activeTraining.id" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Training</label>
                    <input type="text" name="title" x-model="activeTraining.title" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <input type="text" name="category" x-model="activeTraining.category" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Trainer Utama</label>
                        <input type="text" name="trainer" x-model="activeTraining.trainer" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
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
                    <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="w-full p-2 rounded border border-slate-300 bg-white text-xs">
                    <div class="text-[10px] text-slate-500 italic">Atau sesuaikan URL Gambar:</div>
                    <input type="text" name="image" x-model="activeTraining.image" class="w-full p-2 rounded border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="3" x-model="activeTraining.description" required class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Durasi</label>
                        <input type="text" name="duration" x-model="activeTraining.duration" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" x-model="activeTraining.price" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kuota</label>
                        <input type="number" name="quota" x-model="activeTraining.quota" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lokasi</label>
                        <input type="text" name="location" x-model="activeTraining.location" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mode Pelaksanaan</label>
                        <select name="is_online" x-model="activeTraining.is_online" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="1">Online (Zoom)</option>
                            <option value="0">Offline (Tatap Muka)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" x-model="activeTraining.status" class="w-full p-2.5 rounded border border-slate-300">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border rounded text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded shadow transition">Perbarui Program</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Batch Modal -->
    <div x-show="batchModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-base">Tambah Angkatan (Batch Baru)</h3>
            <form :action="'{{ url('/admin/training') }}/' + selectedTrainingId + '/batches'" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Batch / Angkatan</label>
                    <input type="text" name="batch_name" placeholder="Angkatan 05 - Oktober 2026" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Pelaksanaan (Mulai)</label>
                        <input type="date" name="start_date" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Pelaksanaan (Selesai)</label>
                        <input type="date" name="end_date" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Buka Pendaftaran</label>
                        <input type="date" name="registration_open" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tutup Pendaftaran</label>
                        <input type="date" name="registration_close" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kuota Batch Ini</label>
                        <input type="number" name="quota" value="30" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Batch</label>
                        <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="open">Open (Buka Pendaftaran)</option>
                            <option value="closed">Closed</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="batchModal = false" class="px-4 py-2 border rounded text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded shadow transition">Simpan Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
