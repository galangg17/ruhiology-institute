@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Quotes & Inspirasi</h2>
            <p class="text-xs text-slate-500">Mutiara hikmah dan petikan pemikiran Ruhiologi.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Tambah Quote Baru
        </button>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($quotes as $q)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <p class="text-xs text-slate-800 font-serif italic leading-relaxed">"{{ $q->quote }}"</p>
                <div class="border-t border-slate-100 pt-3 flex justify-between items-center text-xs">
                    <div>
                        <span class="font-bold text-amber-700 block">— {{ $q->author }}</span>
                        <span class="text-slate-400 text-[10px]">{{ $q->source ?? 'Ruhiology Institute' }}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $q->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                        {{ $q->status }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-base">Tambah Quote Ruhiologi</h3>
            <form action="{{ route('admin.quotes.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teks Kutipan (Quote)</label>
                    <textarea name="quote" rows="3" required placeholder="Tuliskan mutiara hikmah..." class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Tokoh / Tokoh Pengutip</label>
                    <input type="text" name="author" value="Prof. Dr. Iskandar Nazari" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sumber Referensi (Opsional)</label>
                    <input type="text" name="source" placeholder="Contoh: Buku Ruhiology Quotient (2024)" class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan Quote</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
