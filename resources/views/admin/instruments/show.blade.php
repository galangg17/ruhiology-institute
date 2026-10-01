@extends('layouts.admin')

@section('content')
<div class="space-y-8" x-data="{ dimModal: false, qModal: false, batchModal: false, importModal: false, editQModal: false, editQData: {} }">
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
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.instruments.template.download') }}" class="px-3.5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5" title="Download Template Excel 20 Soal (Terstruktur)">
                    <span>📥</span> <span>Template Excel</span>
                </a>
                <button @click="importModal = true" class="px-3.5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                    <span>📊</span> <span>Import Excel</span>
                </button>
                <button @click="batchModal = true" class="px-4 py-2.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold text-xs rounded-xl shadow transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>⚡</span> <span>Form Batch 20 Soal</span>
                </button>
                <button @click="dimModal = true" class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                    <span>+ Dimensi</span>
                </button>
                <form action="{{ route('admin.instruments.destroy', $instrument->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh paket soal \'{{ addslashes($instrument->name) }}\' beserta semua dimensi & butir soal di dalamnya? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5 cursor-pointer">
                        <span>🗑️ Hapus Paket</span>
                    </button>
                </form>
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
                                    <td colspan="6" class="p-4 text-center text-slate-400 italic">Belum ada butir pertanyaan untuk dimensi ini. Klik "⚡ Form Batch 20 Soal" atau "+ Tambah Butir Soal" untuk mengisi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500 text-xs">
                Belum ada Dimensi pada Paket Soal ini. Klik tombol "+ Tambah Dimensi" atau "⚡ Form Batch 20 Soal" untuk memulai.
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

    <!-- BATCH BUILDER WEB UI MODAL (20 QUESTIONS PRE-STRUCTURED MATRIX) -->
    <div x-show="batchModal" x-cloak class="fixed inset-0 bg-slate-900/70 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div @click.away="batchModal = false" class="bg-white rounded-3xl max-w-5xl w-full shadow-2xl relative border border-slate-100 flex flex-col max-h-[92vh] overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0B2A43] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#C9A24D] text-[#0B2A43] font-bold flex items-center justify-center text-base shadow">
                        ⚡
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">STRUCTURED BATCH QUESTION BUILDER</span>
                        <h3 class="text-base font-serif font-bold text-white">Form Batch 20 Soal (15 RQI + 5 WHO-5)</h3>
                    </div>
                </div>
                <button @click="batchModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">✕</button>
            </div>

            <form action="{{ route('admin.instruments.questions.store_batch', $instrument->id) }}" method="POST" class="p-6 text-xs overflow-y-auto flex-1 space-y-6">
                @csrf

                <div class="bg-amber-50 p-4 rounded-2xl border border-amber-200 text-amber-900 leading-relaxed text-xs">
                    💡 <strong>Panduan Pengisian Form Batch:</strong><br>
                    Ruang di bawah ini telah dikelompokkan menjadi **6 Dimensi Terstruktur**:
                    Dimensi 1 s/d 5 (masing-masing 3 soal) + Dimensi ke-6 WHO-5 (5 soal). Ketik atau tempelkan teks pertanyaan pada kotak yang tersedia.
                </div>

                @php
                    $dimConfigs = [
                        ['code' => 'DIM-1', 'name' => 'Pengenalan & Kesadaran Diri Hakiki', 'count' => 3, 'color' => 'border-blue-200 bg-blue-50/40'],
                        ['code' => 'DIM-2', 'name' => 'Pengenalan Ketuhanan (God Spot)', 'count' => 3, 'color' => 'border-emerald-200 bg-emerald-50/40'],
                        ['code' => 'DIM-3', 'name' => 'Ketaatan Ibadah', 'count' => 3, 'color' => 'border-purple-200 bg-purple-50/40'],
                        ['code' => 'DIM-4', 'name' => 'Perubahan Perilaku & Akhlak Karimah', 'count' => 3, 'color' => 'border-amber-200 bg-amber-50/40'],
                        ['code' => 'DIM-5', 'name' => 'Kesadaran Puncak Ketuhanan (God Light & Muraqabah)', 'count' => 3, 'color' => 'border-rose-200 bg-rose-50/40'],
                        ['code' => 'DIM-WHO5', 'name' => 'Indeks Kesejahteraan Mental (WHO-5)', 'count' => 5, 'color' => 'border-teal-200 bg-teal-50/40'],
                    ];
                    $globalIndex = 0;
                @endphp

                <div class="space-y-6">
                    @foreach($dimConfigs as $dIdx => $cfg)
                        <div class="p-5 rounded-2xl border {{ $cfg['color'] }} space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                <h4 class="font-bold font-serif text-[#0B2A43] text-sm flex items-center gap-2">
                                    <span class="font-mono bg-[#0B2A43] text-[#C9A24D] text-xs px-2.5 py-0.5 rounded-md">[{{ $cfg['code'] }}]</span>
                                    <span>{{ $cfg['name'] }}</span>
                                </h4>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider bg-white px-2.5 py-1 rounded-full border border-slate-200">
                                    {{ $cfg['count'] }} Pertanyaan
                                </span>
                            </div>

                            <div class="space-y-3">
                                @for($i = 1; $i <= $cfg['count']; $i++)
                                    @php $qNum = ++$globalIndex; @endphp
                                    <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-mono font-bold text-[#0B2A43] text-xs">Pertanyaan #{{ $qNum }} (Soal {{ $i }} dari {{ $cfg['count'] }})</span>
                                            <div class="flex items-center gap-2">
                                                <label class="text-[10px] text-slate-500 font-bold">Scoring:</label>
                                                <select name="questions[{{ $qNum }}][scoring_direction]" class="p-1 rounded-lg border border-slate-300 text-[11px] font-medium bg-slate-50">
                                                    <option value="normal">➡️ Normal (1→5)</option>
                                                    <option value="reverse">🔄 Reverse (5→1)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="questions[{{ $qNum }}][dimension_code]" value="{{ $cfg['code'] }}">
                                        <input type="hidden" name="questions[{{ $qNum }}][dimension_name]" value="{{ $cfg['name'] }}">
                                        <textarea name="questions[{{ $qNum }}][question_text]" rows="2" placeholder="Tuliskan pernyataan soal #{{ $qNum }} di sini..." class="w-full p-2.5 rounded-xl border border-slate-300 font-medium text-xs focus:ring-2 focus:ring-[#0B2A43] outline-none"></textarea>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" @click="batchModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer text-xs flex items-center gap-2">
                        <span>💾 Simpan Semua 20 Soal Sekaligus →</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CSV IMPORT MODAL -->
    <div x-show="importModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base">Import File Excel / CSV</h3>
                <button @click="importModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.instruments.import_specific', $instrument->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih File CSV / Excel (.csv)</label>
                    <input type="file" name="file" accept=".csv,.txt" required class="w-full p-2.5 rounded-xl border border-slate-300 bg-slate-50">
                </div>
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-[11px] space-y-1">
                    <strong class="block font-bold">📌 Catatan Format:</strong>
                    <p>Gunakan file CSV terstruktur (Kode Dimensi, Nama Dimensi, Teks Pertanyaan, Skoring). Unduh file template bawaan jika belum memilikinya.</p>
                </div>
                <div class="pt-2 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="importModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 text-white font-bold rounded-xl shadow hover:bg-emerald-800 transition">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Single Dimension Modal -->
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
                    <input type="text" name="code" placeholder="Contoh: DIM-6" required class="w-full p-2.5 rounded-xl border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Dimensi *</label>
                    <input type="text" name="name" placeholder="Contoh: Kesejahteraan Mental" required class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Dimensi</label>
                    <textarea name="description" rows="2" class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Urutan *</label>
                    <input type="number" name="order" value="{{ $instrument->dimensions->count() + 1 }}" required class="w-full p-2.5 rounded-xl border border-slate-300">
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="dimModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow">Simpan Dimensi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Single Question Modal -->
    <div x-show="qModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-[#0B2A43] text-base">Tambah Pertanyaan Baru</h3>
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
                    <textarea name="question_text" rows="3" placeholder="Tuliskan butir soal di sini..." required class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
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

    <!-- Edit Single Question Modal -->
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
