@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, editData: {} }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#C9A24D] font-bold uppercase tracking-wider">
                <span>📦 BANK SOAL & INSTRUMEN ASESMEN</span>
            </div>
            <h2 class="text-2xl font-bold font-serif text-[#0B2A43] mt-1">Kelola Paket Soal</h2>
            <p class="text-xs text-slate-500 mt-0.5">Buat, ganti nama, edit, dan atur berbagai paket instrumen soal asesmen untuk disesuaikan dengan jenis peserta & event.</p>
        </div>
        <button @click="createModal = true" class="px-5 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 shrink-0">
            <span>+</span> <span>Buat Paket Soal Baru</span>
        </button>
    </div>

    <!-- Instruments / Question Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($instruments as $inst)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
                <div class="p-5 space-y-3">
                    <div class="flex justify-between items-start gap-2">
                        <span class="text-[10px] font-mono font-bold text-[#0B2A43] bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 shadow-xs">
                            {{ $inst->code }} • V{{ $inst->version }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $inst->status === 'active' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-300' }}">
                            {{ $inst->status }}
                        </span>
                    </div>

                    <h3 class="font-bold font-serif text-[#0B2A43] text-base line-clamp-1" title="{{ $inst->name }}">
                        {{ $inst->name }}
                    </h3>

                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 min-h-[36px]">
                        {{ $inst->description ?? 'Tidak ada deskripsi tambahan.' }}
                    </p>

                    <div class="flex items-center gap-3 text-xs text-slate-500 pt-3 border-t border-slate-100 font-medium">
                        <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                            <span>🧩</span> <strong>{{ $inst->dimensions_count }}</strong> <span class="text-slate-400">Dimensi</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                            <span>❓</span> <strong>{{ $inst->questions_count }}</strong> <span class="text-slate-400">Soal</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1">
                        <button @click="editData = {
                            id: {{ $inst->id }},
                            code: '{{ addslashes($inst->code) }}',
                            name: '{{ addslashes($inst->name) }}',
                            version: '{{ addslashes($inst->version) }}',
                            description: '{{ addslashes($inst->description ?? '') }}',
                            instructions: '{{ addslashes($inst->instructions ?? '') }}',
                            status: '{{ $inst->status }}'
                        }; editModal = true;" class="p-1.5 hover:bg-slate-200 rounded-lg text-slate-600 transition-all text-xs font-bold flex items-center gap-1" title="Edit / Ganti Nama Paket">
                            ✏️ <span>Edit</span>
                        </button>

                        <form action="{{ route('admin.instruments.destroy', $inst->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Paket Soal {{ addslashes($inst->name) }}?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 hover:bg-rose-100 rounded-lg text-rose-600 transition-all text-xs font-bold flex items-center gap-1" title="Hapus Paket Soal">
                                🗑️ <span>Hapus</span>
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('admin.instruments.show', $inst->id) }}" class="px-3.5 py-1.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1">
                        <span>Kelola Soal</span> <span>→</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-3">
                <div class="text-4xl">📦</div>
                <h3 class="font-bold text-slate-800 text-base">Belum Ada Paket Soal</h3>
                <p class="text-xs text-slate-500">Klik tombol "Buat Paket Soal Baru" di atas untuk menambah bank soal pertama Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Create Paket Soal Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base">Buat Paket Soal Baru</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.instruments.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Paket Soal *</label>
                        <input type="text" name="code" required placeholder="Contoh: RQI-30M" class="w-full p-2.5 rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-[#0B2A43] outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Versi Paket *</label>
                        <input type="text" name="version" value="1.0" required class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Paket Soal *</label>
                    <input type="text" name="name" required placeholder="Contoh: Paket Asesmen RQI-30M (Standar Pelajar)" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Paket</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan peruntukan paket soal ini..." class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Instruksi Pengerjaan untuk Peserta</label>
                    <textarea name="instructions" rows="2" placeholder="Instruksi awal yang muncul sebelum peserta menjawab..." class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Paket</label>
                    <select name="status" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                        <option value="active">Active (Dapat Digunakan Event)</option>
                        <option value="draft">Draft (Pengembangan)</option>
                        <option value="archived">Archived (Diarsipkan)</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl shadow">Simpan Paket Soal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Paket Soal Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base">Edit / Ganti Nama Paket Soal</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form :action="'/admin/instruments/' + editData.id" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Paket Soal *</label>
                        <input type="text" name="code" x-model="editData.code" required class="w-full p-2.5 rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-[#0B2A43] outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Versi Paket *</label>
                        <input type="text" name="version" x-model="editData.version" required class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Paket Soal *</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Paket</label>
                    <textarea name="description" x-model="editData.description" rows="2" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Instruksi Pengerjaan</label>
                    <textarea name="instructions" x-model="editData.instructions" rows="2" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Paket</label>
                    <select name="status" x-model="editData.status" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                        <option value="active">Active (Dapat Digunakan Event)</option>
                        <option value="draft">Draft (Pengembangan)</option>
                        <option value="archived">Archived (Diarsipkan)</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl shadow">Perbarui Paket Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
