@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Data Peserta</h2>
            <p class="text-xs text-slate-500">Pendaftaran dan manajemen Kode Peserta unik per program institusi.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Tambah Peserta Baru
        </button>
    </div>

    <!-- Filter & Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <form action="{{ route('admin.participants.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, kode, atau email..." class="px-3 py-1.5 rounded border border-slate-300 text-xs">
                <select name="institution_id" class="px-3 py-1.5 rounded border border-slate-300 text-xs">
                    <option value="">Semua Institusi</option>
                    @foreach($institutions as $inst)
                        <option value="{{ $inst->id }}" {{ request('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                    @endforeach
                </select>
                <select name="program_id" class="px-3 py-1.5 rounded border border-slate-300 text-xs">
                    <option value="">Semua Program</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-1.5 bg-slate-800 text-white font-bold text-xs rounded">Cari & Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[900px]">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200 text-[11px] tracking-wider font-mono">
                    <tr>
                        <th class="px-5 py-4 whitespace-nowrap">Kode Peserta</th>
                        <th class="px-5 py-4 whitespace-nowrap">Nama Peserta</th>
                        <th class="px-5 py-4 whitespace-nowrap">Email / Telepon</th>
                        <th class="px-5 py-4 whitespace-nowrap">Institusi & Program</th>
                        <th class="px-5 py-4 whitespace-nowrap">Angkatan (Batch)</th>
                        <th class="px-5 py-4 whitespace-nowrap">Riwayat Test</th>
                        <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($participants as $part)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-mono font-bold text-amber-700">{{ $part->participant_code }}</td>
                            <td class="p-4 text-slate-900 font-bold">{{ $part->name }}</td>
                            <td class="p-4">{{ $part->email }} <br><span class="text-slate-400 text-[10px]">{{ $part->phone ?? '-' }}</span></td>
                            <td class="p-4">
                                <span class="font-bold text-slate-800 block">{{ $part->institution->name ?? '-' }}</span>
                                <span class="text-slate-500 text-[10px] block">{{ $part->program->name ?? '-' }}</span>
                            </td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded">{{ $part->batch }}</span></td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-amber-100 text-amber-900 font-bold rounded">{{ $part->submissions_count }} Sesi</span></td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.participants.destroy', $part->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus peserta ini?')">
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
            {{ $participants->links() }}
        </div>
    </div>

    <!-- Create Participant Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base">Registrasi Peserta Baru</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('admin.participants.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Institusi</label>
                        <select name="institution_id" required class="w-full p-2.5 rounded border border-slate-300">
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Program</label>
                        <select name="program_id" required class="w-full p-2.5 rounded border border-slate-300">
                            @foreach($programs as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode Peserta (Unique Code)</label>
                    <input type="text" name="participant_code" required placeholder="Contoh: MHS-2026-004" class="w-full p-2.5 rounded border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Peserta</label>
                    <input type="text" name="name" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor HP/WA</label>
                        <input type="text" name="phone" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Angkatan / Batch</label>
                        <input type="text" name="batch" value="Angkatan 2026" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Daftarkan Peserta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
