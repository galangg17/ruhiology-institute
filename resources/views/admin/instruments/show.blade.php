@extends('layouts.admin')

@section('content')
<div class="space-y-8" x-data="{ dimModal: false, qModal: false, editQModal: false, editQData: {} }">
    <!-- Breadcrumb & Header Card -->
    <div class="space-y-4">
        <a href="{{ route('admin.instruments.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#0B2A43] transition-all">
            <span>← Kembali ke Daftar Paket Soal</span>
        </a>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-[10px] font-mono font-bold text-[#0B2A43] bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 shadow-xs">
                    {{ $instrument->code }} • Versi {{ $instrument->version }}
                </span>
                <h2 class="text-2xl font-bold font-serif text-[#0B2A43] mt-2">{{ $instrument->name }}</h2>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">{{ $instrument->description ?? 'Tidak ada deskripsi tambahan.' }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button @click="dimModal = true" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                    <span>+</span> <span>Tambah Dimensi</span>
                </button>
                <button @click="qModal = true" class="px-4 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                    <span>+</span> <span>Tambah Butir Soal</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Dimensions & Questions Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="font-bold font-serif text-[#0B2A43] text-lg">Bank Soal & Dimensi Kompetensi</h3>
            <span class="text-xs text-slate-500 font-medium">Total: <strong>{{ $instrument->questions->count() }}</strong> Butir Soal dalam <strong>{{ $instrument->dimensions->count() }}</strong> Dimensi</span>
        </div>

        @forelse($instrument->dimensions as $dim)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                <div class="bg-[#0B2A43] text-white px-5 py-3.5 flex justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-400 font-mono text-xs font-bold bg-slate-900/60 px-2 py-0.5 rounded">[{{ $dim->code }}]</span>
                        <h4 class="font-bold text-sm text-white">{{ $dim->name }}</h4>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-300">Urutan: <strong>{{ $dim->order }}</strong></span>
                        <form action="{{ route('admin.instruments.dimensions.destroy', [$instrument->id, $dim->id]) }}" method="POST" onsubmit="return confirm('Hapus dimensi {{ addslashes($dim->name) }} beserta butir soal di dalamnya?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-bold px-2 py-0.5 hover:bg-slate-800 rounded transition-all">
                                🗑️ Hapus Dimensi
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200 text-[10px] tracking-wider">
                            <tr>
                                <th class="p-3.5 w-12 text-center">#</th>
                                <th class="p-3.5">Pernyataan Soal</th>
                                <th class="p-3.5 w-28">Tipe</th>
                                <th class="p-3.5 w-36">Scoring Direction</th>
                                <th class="p-3.5">Opsi Jawaban</th>
                                <th class="p-3.5 w-24 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($dim->questions as $q)
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="p-3.5 text-center font-bold text-slate-400 font-mono">{{ $q->order }}</td>
                                    <td class="p-3.5 font-semibold text-slate-900 leading-relaxed">{{ $q->question_text }}</td>
                                    <td class="p-3.5"><span class="px-2 py-0.5 bg-slate-100 border border-slate-200 font-bold rounded uppercase text-[10px] text-slate-700">{{ $q->type }}</span></td>
                                    <td class="p-3.5">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $q->scoring_direction === 'reverse' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300' }}">
                                            {{ $q->scoring_direction === 'reverse' ? '🔄 REVERSE (5→1)' : '➡️ NORMAL (1→5)' }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-slate-500 text-[11px]">
                                        {{ $q->options->pluck('option_text')->implode(', ') }}
                                    </td>
                                    <td class="p-3.5 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button @click="editQData = {
                                                id: {{ $q->id }},
                                                dimension_id: {{ $q->dimension_id }},
                                                question_text: '{{ addslashes($q->question_text) }}',
                                                type: '{{ $q->type }}',
                                                scoring_direction: '{{ $q->scoring_direction }}',
                                                order: {{ $q->order }}
                                            }; editQModal = true;" class="p-1 hover:bg-slate-200 rounded text-slate-600 transition-all text-xs font-bold" title="Edit Soal">
                                                ✏️
                                            </button>
                                            <form action="{{ route('admin.instruments.questions.destroy', [$instrument->id, $q->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 hover:bg-rose-100 rounded text-rose-600 transition-all text-xs font-bold" title="Hapus Soal">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-slate-400 italic">Belum ada butir pertanyaan untuk dimensi ini. Klik "+ Tambah Butir Soal" di atas untuk menambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500 text-xs">
                Belum ada Dimensi pada Paket Soal ini. Klik tombol "+ Tambah Dimensi" untuk memulai.
            </div>
        @endforelse
    </div>

    <!-- Scoring Engine Configuration Box -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="font-bold font-serif text-[#0B2A43] text-base border-b border-slate-100 pb-3 flex items-center gap-2">
            <span>⚙️</span> <span>Konfigurasi Reverse Scoring Engine</span>
        </h3>
        <form action="{{ route('admin.instruments.scoring.update', $instrument->id) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Skala Minimal (Scale Min)</label>
                    <input type="number" name="scale_min" value="{{ $instrument->scoringRules->scale_min ?? 1 }}" required class="w-full p-2.5 rounded-xl border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Skala Maksimal (Scale Max)</label>
                    <input type="number" name="scale_max" value="{{ $instrument->scoringRules->scale_max ?? 5 }}" required class="w-full p-2.5 rounded-xl border border-slate-300 font-mono">
                </div>
            </div>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                Aturan Reverse Scoring Otomatis: <code>Nilai = (Skala Max + Skala Min) - Jawaban Peserta</code> (Misal Likert 1–5: Pilihan 1 dihitung 5, Pilihan 5 dihitung 1).
            </p>
            <button type="submit" class="px-5 py-2.5 bg-[#0B2A43] text-white font-bold rounded-xl shadow hover:bg-[#123B59] transition-all">Simpan Aturan Scoring Engine</button>
        </form>
    </div>

    <!-- Dimension Modal -->
    <div x-show="dimModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base">Tambah Dimensi RQ Baru</h3>
                <button @click="dimModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.instruments.dimensions.store', $instrument->id) }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode Dimensi *</label>
                    <input type="text" name="code" required placeholder="Contoh: RQI-D6" class="w-full p-2.5 rounded-xl border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Dimensi *</label>
                    <input type="text" name="name" required placeholder="Nama Dimensi Kompetensi..." class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Urutan (Order) *</label>
                    <input type="number" name="order" value="{{ $instrument->dimensions->count() + 1 }}" required class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="dimModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow">Simpan Dimensi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Question Modal -->
    <div x-show="qModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base">Tambah Butir Pertanyaan Baru</h3>
                <button @click="qModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.instruments.questions.store', $instrument->id) }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Dimensi *</label>
                    <select name="dimension_id" required class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                        @foreach($instrument->dimensions as $d)
                            <option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teks Pernyataan Soal *</label>
                    <textarea name="question_text" rows="3" required placeholder="Tuliskan butir soal asesmen..." class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Pertanyaan</label>
                        <select name="type" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="likert">Likert Scale (1-5)</option>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="yes_no">Yes / No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Scoring Direction</label>
                        <select name="scoring_direction" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="normal">NORMAL (1→1, 5→5)</option>
                            <option value="reverse">REVERSE (1→5, 5→1)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Urutan Soal *</label>
                    <input type="number" name="order" value="{{ $instrument->questions->count() + 1 }}" required class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="qModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Question Modal -->
    <div x-show="editQModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base">Edit Butir Pertanyaan</h3>
                <button @click="editQModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form :action="'/admin/instruments/{{ $instrument->id }}/questions/' + editQData.id" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Dimensi *</label>
                    <select name="dimension_id" x-model="editQData.dimension_id" required class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                        @foreach($instrument->dimensions as $d)
                            <option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teks Pernyataan Soal *</label>
                    <textarea name="question_text" x-model="editQData.question_text" rows="3" required class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Pertanyaan</label>
                        <select name="type" x-model="editQData.type" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="likert">Likert Scale (1-5)</option>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="yes_no">Yes / No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Scoring Direction</label>
                        <select name="scoring_direction" x-model="editQData.scoring_direction" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="normal">NORMAL (1→1, 5→5)</option>
                            <option value="reverse">REVERSE (1→5, 5→1)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Urutan Soal *</label>
                    <input type="number" name="order" x-model="editQData.order" required class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editQModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow">Perbarui Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
