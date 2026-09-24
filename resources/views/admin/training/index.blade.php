@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, batchModal: false, selectedTrainingId: null }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Training Program Center</h2>
            <p class="text-xs text-slate-500">Kelola program pelatihan, workshop, sertifikasi, dan angkatan (batch).</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Tambah Program Training
        </button>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($trainings as $t)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            {{ $t->category }}
                        </span>
                        <h3 class="font-bold font-serif text-slate-900 text-base mt-2">{{ $t->title }}</h3>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $t->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                        {{ $t->status }}
                    </span>
                </div>

                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $t->description }}</p>

                <div class="text-xs text-slate-500 space-y-1">
                    <div>👨‍🏫 Trainer: {{ $t->trainer }}</div>
                    <div>💰 Harga: <strong>Rp {{ number_format($t->price, 0, ',', '.') }}</strong></div>
                </div>

                <!-- Batches list -->
                <div class="border-t border-slate-100 pt-3 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-xs text-slate-800">Daftar Angkatan (Batches)</span>
                        <button @click="selectedTrainingId = {{ $t->id }}; batchModal = true" class="text-[11px] font-bold text-amber-700 hover:underline">
                            + Tambah Batch
                        </button>
                    </div>
                    @foreach($t->batches as $b)
                        <div class="p-2.5 bg-slate-50 rounded border border-slate-200 text-xs flex justify-between items-center">
                            <div>
                                <span class="font-bold text-slate-900 block">{{ $b->batch_name }}</span>
                                <span class="text-[10px] text-slate-500">{{ $b->start_date->format('d M Y') }} s/d {{ $b->end_date->format('d M Y') }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $b->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $b->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Training Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-base">Buat Training Program Baru</h3>
            <form action="{{ route('admin.training.store') }}" method="POST" class="space-y-3 text-xs">
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
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
