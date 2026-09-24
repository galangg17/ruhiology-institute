@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Periode Pretest & Posttest</h2>
            <p class="text-xs text-slate-500">Pengaturan jadwal pembukaan dan penutupan asesmen per program.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Buat Periode Asesmen Baru
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Kode Periode</th>
                        <th class="p-4">Judul Periode</th>
                        <th class="p-4">Program & Instrumen</th>
                        <th class="p-4">Jadwal Pretest</th>
                        <th class="p-4">Jadwal Posttest</th>
                        <th class="p-4">Submissions</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($periods as $p)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-mono font-bold text-amber-700">{{ $p->period_code }}</td>
                            <td class="p-4 text-slate-900 font-bold">{{ $p->title }}</td>
                            <td class="p-4">
                                <span class="font-bold text-slate-800 block">{{ $p->program->name ?? '-' }}</span>
                                <span class="text-slate-500 text-[10px] block">{{ $p->instrument->name ?? '-' }}</span>
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $p->pretest_start ? $p->pretest_start->format('d/m/Y') : '-' }} s/d {{ $p->pretest_end ? $p->pretest_end->format('d/m/Y') : '-' }}
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $p->posttest_start ? $p->posttest_start->format('d/m/Y') : '-' }} s/d {{ $p->posttest_end ? $p->posttest_end->format('d/m/Y') : '-' }}
                            </td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded">{{ $p->submissions_count }} Submits</span></td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $p->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $periods->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Buat Periode Asesmen Baru</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.periods.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Program</label>
                        <select name="program_id" required class="w-full p-2.5 rounded border border-slate-300">
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Instrumen</label>
                        <select name="instrument_id" required class="w-full p-2.5 rounded border border-slate-300">
                            @foreach($instruments as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->code }} - {{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode Periode (Unique Code)</label>
                    <input type="text" name="period_code" required placeholder="Contoh: PER-RQ-2026-02" class="w-full p-2.5 rounded border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Periode Asesmen</label>
                    <input type="text" name="title" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mulai Pretest</label>
                        <input type="datetime-local" name="pretest_start" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Selesai Pretest</label>
                        <input type="datetime-local" name="pretest_end" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mulai Posttest</label>
                        <input type="datetime-local" name="posttest_start" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Selesai Posttest</label>
                        <input type="datetime-local" name="posttest_end" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Periode</label>
                    <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="closed">Closed</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan Periode</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
