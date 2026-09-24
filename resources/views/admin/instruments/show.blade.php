@extends('layouts.admin')

@section('content')
<div class="space-y-8" x-data="{ dimModal: false, qModal: false }">
    <!-- Header Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-mono font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                {{ $instrument->code }} • Versi {{ $instrument->version }}
            </span>
            <h2 class="text-2xl font-bold font-serif text-slate-900 mt-2">{{ $instrument->name }}</h2>
            <p class="text-xs text-slate-500 mt-1">{{ $instrument->description }}</p>
        </div>
        <div class="flex gap-2">
            <button @click="dimModal = true" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-lg shadow">
                + Tambah Dimensi
            </button>
            <button @click="qModal = true" class="px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
                + Tambah Pertanyaan
            </button>
        </div>
    </div>

    <!-- Dimensions & Questions Section -->
    <div class="space-y-6">
        <h3 class="font-bold font-serif text-slate-900 text-lg">Dimensi & Butir Pertanyaan</h3>

        @foreach($instrument->dimensions as $dim)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-3">
                <div class="bg-slate-900 text-white p-4 flex justify-between items-center">
                    <div>
                        <span class="text-amber-400 font-mono text-xs font-bold font-mono">[{{ $dim->code }}]</span>
                        <h4 class="font-bold text-sm inline-block ml-2">{{ $dim->name }}</h4>
                    </div>
                    <span class="text-xs text-slate-400">Urutan: {{ $dim->order }}</span>
                </div>

                <div class="p-4 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3 w-12">#</th>
                                <th class="p-3">Pernyataan Soal</th>
                                <th class="p-3">Tipe</th>
                                <th class="p-3">Scoring Direction</th>
                                <th class="p-3">Opsi Jawaban</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($dim->questions as $q)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3 font-bold text-slate-400">{{ $q->order }}</td>
                                    <td class="p-3 font-medium text-slate-900">{{ $q->question_text }}</td>
                                    <td class="p-3"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded uppercase text-[10px]">{{ $q->type }}</span></td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $q->scoring_direction === 'reverse' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $q->scoring_direction === 'reverse' ? '🔄 REVERSE SCORING' : '➡️ NORMAL' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-slate-500">
                                        {{ $q->options->pluck('option_text')->implode(', ') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-3 text-slate-400">Belum ada butir pertanyaan untuk dimensi ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Scoring Engine Configuration Box -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="font-bold font-serif text-slate-900 text-base border-b border-slate-100 pb-3">⚙️ Konfigurasi Reverse Scoring & Interpretation Rules</h3>
        <form action="{{ route('admin.instruments.scoring.update', $instrument->id) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Skala Minimal (Scale Min)</label>
                    <input type="number" name="scale_min" value="{{ $instrument->scoringRules->scale_min ?? 1 }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Skala Maksimal (Scale Max)</label>
                    <input type="number" name="scale_max" value="{{ $instrument->scoringRules->scale_max ?? 5 }}" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
            </div>
            <p class="text-[11px] text-slate-500">
                Formula Reverse Scoring: <code>Score = (Scale_Max + Scale_Min) - Raw_Value</code> (misal Likert 1–5: 1 menjadi 5, 2 menjadi 4, 3 tetap 3, 4 menjadi 2, 5 menjadi 1).
            </p>
            <button type="submit" class="px-5 py-2 bg-navy-900 text-white font-bold rounded shadow">Simpan Aturan Scoring Engine</button>
        </form>
    </div>

    <!-- Dimension Modal -->
    <div x-show="dimModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-base">Tambah Dimensi RQ</h3>
            <form action="{{ route('admin.instruments.dimensions.store', $instrument->id) }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode Dimensi</label>
                    <input type="text" name="code" required placeholder="Contoh: DIM-5" class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Dimensi</label>
                    <input type="text" name="name" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Urutan (Order)</label>
                    <input type="number" name="order" value="1" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="dimModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white font-bold rounded shadow">Simpan Dimensi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Question Modal -->
    <div x-show="qModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-base">Tambah Butir Pertanyaan</h3>
            <form action="{{ route('admin.instruments.questions.store', $instrument->id) }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Dimensi</label>
                    <select name="dimension_id" required class="w-full p-2.5 rounded border border-slate-300">
                        @foreach($instrument->dimensions as $d)
                            <option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teks Pernyataan Soal</label>
                    <textarea name="question_text" rows="3" required placeholder="Tuliskan butir soal asesmen..." class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Pertanyaan</label>
                        <select name="type" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="likert">Likert Scale (1-5)</option>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="yes_no">Yes / No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Scoring Direction</label>
                        <select name="scoring_direction" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="normal">NORMAL (1->1, 5->5)</option>
                            <option value="reverse">REVERSE (1->5, 5->1)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Urutan Soal</label>
                    <input type="number" name="order" value="1" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="qModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
