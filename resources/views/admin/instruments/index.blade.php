@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Instrumen RQ</h2>
            <p class="text-xs text-slate-500">Instrumen asesmen baku, dimensi, indikator, butir soal, dan aturan scoring engine.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Tambah Instrumen Baru
        </button>
    </div>

    <!-- Instruments Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($instruments as $inst)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-amber-700 uppercase bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            {{ $inst->code }} • V{{ $inst->version }}
                        </span>
                        <h3 class="font-bold font-serif text-slate-900 text-base mt-2">{{ $inst->name }}</h3>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $inst->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                        {{ $inst->status }}
                    </span>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed">{{ $inst->description }}</p>

                <div class="flex items-center gap-4 text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <div>🧩 <strong>{{ $inst->dimensions_count }}</strong> Dimensi</div>
                    <div>❓ <strong>{{ $inst->questions_count }}</strong> Pertanyaan</div>
                </div>

                <div class="pt-2 flex justify-end">
                    <a href="{{ route('admin.instruments.show', $inst->id) }}" class="px-4 py-2 bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs rounded-lg shadow">
                        Kelola Dimensi & Soal →
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Buat Instrumen Asesmen Baru</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.instruments.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Instrumen</label>
                        <input type="text" name="code" required placeholder="Contoh: RQI-V2" class="w-full p-2.5 rounded border border-slate-300 font-mono">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Versi</label>
                        <input type="text" name="version" value="1.0" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Instrumen</label>
                    <input type="text" name="name" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Akademik</label>
                    <textarea name="description" rows="2" class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Instruksi Pengerjaan</label>
                    <textarea name="instructions" rows="2" class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan Instrumen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
