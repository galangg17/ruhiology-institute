@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Institusi Mitra</h2>
            <p class="text-xs text-slate-500">Daftar universitas, sekolah, kementerian, dan instansi mitra program RQ.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Tambah Institusi
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex gap-4">
            <form action="{{ route('admin.institutions.index') }}" method="GET" class="flex-1 flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau kode institusi..." class="flex-1 px-3 py-1.5 rounded border border-slate-300 text-xs">
                <button type="submit" class="px-4 py-1.5 bg-slate-800 text-white font-bold text-xs rounded">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Kode</th>
                        <th class="p-4">Nama Institusi</th>
                        <th class="p-4">Tipe</th>
                        <th class="p-4">Kontak Person</th>
                        <th class="p-4">Program</th>
                        <th class="p-4">Peserta</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($institutions as $inst)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-mono font-bold text-amber-700">{{ $inst->code }}</td>
                            <td class="p-4 text-slate-900 font-bold">{{ $inst->name }}</td>
                            <td class="p-4">{{ $inst->type }}</td>
                            <td class="p-4">{{ $inst->contact_person ?? '-' }} ({{ $inst->phone ?? '-' }})</td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded">{{ $inst->programs_count }}</span></td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded">{{ $inst->participants_count }}</span></td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $inst->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $inst->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <form action="{{ route('admin.institutions.destroy', $inst->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus institusi ini?')">
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
            {{ $institutions->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Tambah Institusi Baru</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.institutions.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode Institusi (Unique)</label>
                    <input type="text" name="code" required placeholder="Contoh: UIN-STS" class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Institusi</label>
                    <input type="text" name="name" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Institusi</label>
                        <select name="type" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="University">Universitas / Perguruan Tinggi</option>
                            <option value="School">Madrasah / Sekolah</option>
                            <option value="Government">Kementerian / Instansi Pemerintah</option>
                            <option value="Corporate">Perusahaan / Swasta</option>
                            <option value="Institute">Institute / Lembaga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email Official</label>
                        <input type="email" name="email" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Contact Person (Penanggung Jawab)</label>
                    <input type="text" name="contact_person" class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alamat Institusi</label>
                    <textarea name="address" rows="2" class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan Institusi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
