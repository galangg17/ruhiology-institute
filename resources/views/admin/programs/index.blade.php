@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Program Ruhiologi</h2>
            <p class="text-xs text-slate-500">Program assessment, pendidikan, dan pelatihan per institusi mitra.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Buat Program Baru
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Kode Program</th>
                        <th class="p-4">Nama Program</th>
                        <th class="p-4">Institusi Mitra</th>
                        <th class="p-4">Total Peserta</th>
                        <th class="p-4">Jumlah Periode</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($programs as $prog)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-mono font-bold text-amber-700">{{ $prog->code }}</td>
                            <td class="p-4 text-slate-900 font-bold">{{ $prog->name }}</td>
                            <td class="p-4">{{ $prog->institution->name ?? 'N/A' }}</td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded">{{ $prog->participants_count }}</span></td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded">{{ $prog->periods_count }}</span></td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $prog->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $prog->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <form action="{{ route('admin.programs.destroy', $prog->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus program ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-600 font-bold hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $programs->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Buat Program Baru</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.programs.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Institusi Penyelenggara</label>
                    <select name="institution_id" required class="w-full p-2.5 rounded border border-slate-300">
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->name }} ({{ $inst->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode Program (Unique)</label>
                    <input type="text" name="code" required placeholder="Contoh: PROG-RQ-STUDENT-2026" class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Program</label>
                    <input type="text" name="name" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Ringkas</label>
                    <textarea name="description" rows="2" class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="completed">Completed</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
