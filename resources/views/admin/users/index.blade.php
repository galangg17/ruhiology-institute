@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Akun System & Role Permissions</h2>
            <p class="text-xs text-slate-500">Manajemen 8-Tier Hak Akses (Super Admin, Admin, Manager Modul, Peserta).</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Tambah User Baru
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Hak Akses (Role)</th>
                        <th class="p-4">Institusi Penugasan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Update Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-bold text-slate-900">{{ $u->name }}</td>
                            <td class="p-4 font-mono text-slate-700">{{ $u->email }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-900 border border-amber-300">
                                    {{ $u->role_display_name }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600">{{ $u->institution->name ?? '-' }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $u->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $u->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.users.update_role', $u->id) }}" method="POST" class="flex gap-1 justify-end">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="active">
                                    <select name="role" onchange="this.form.submit()" class="p-1 border rounded text-[10px]">
                                        <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin Operasional</option>
                                        <option value="assessment_manager" {{ $u->role === 'assessment_manager' ? 'selected' : '' }}>Assessment Manager</option>
                                        <option value="training_manager" {{ $u->role === 'training_manager' ? 'selected' : '' }}>Training Manager</option>
                                        <option value="store_manager" {{ $u->role === 'store_manager' ? 'selected' : '' }}>Store Manager</option>
                                        <option value="consultation_manager" {{ $u->role === 'consultation_manager' ? 'selected' : '' }}>Consultation Manager</option>
                                        <option value="content_manager" {{ $u->role === 'content_manager' ? 'selected' : '' }}>Content Manager</option>
                                        <option value="participant" {{ $u->role === 'participant' ? 'selected' : '' }}>Participant</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Create User Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-base">Tambah Akun Admin / Staff Baru</h3>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Official</label>
                    <input type="email" name="email" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Role Hak Akses</label>
                    <select name="role" required class="w-full p-2.5 rounded border border-slate-300">
                        <option value="super_admin">Super Admin (Akses Penuh)</option>
                        <option value="admin">Admin Operasional</option>
                        <option value="assessment_manager">Assessment Manager</option>
                        <option value="training_manager">Training Manager</option>
                        <option value="store_manager">Store Manager</option>
                        <option value="consultation_manager">Consultation Manager</option>
                        <option value="content_manager">Content Manager</option>
                        <option value="participant">Participant / User Umum</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Akun</label>
                    <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
