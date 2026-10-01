@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, deleteModal: false, activeUser: {} }">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900 flex items-center gap-2">
                <span>👥</span> <span>Kelola Akun System & Role Permissions</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Manajemen Hak Akses Admin, Manager Modul, Staff, dan Peserta Sistem.</p>
        </div>
        <button @click="createModal = true" type="button" class="px-4 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 shrink-0 cursor-pointer">
            <span>+</span> <span>Tambah User Admin Baru</span>
        </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama user atau email..." class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:outline-none focus:border-[#0B2A43]">
            </div>
            <div class="w-full sm:w-48">
                <select name="role" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:outline-none focus:border-[#0B2A43]">
                    <option value="">-- Semua Role --</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin Operasional</option>
                    <option value="assessment_manager" {{ request('role') === 'assessment_manager' ? 'selected' : '' }}>Assessment Manager</option>
                    <option value="training_manager" {{ request('role') === 'training_manager' ? 'selected' : '' }}>Training Manager</option>
                    <option value="store_manager" {{ request('role') === 'store_manager' ? 'selected' : '' }}>Store Manager</option>
                    <option value="consultation_manager" {{ request('role') === 'consultation_manager' ? 'selected' : '' }}>Consultation Manager</option>
                    <option value="content_manager" {{ request('role') === 'content_manager' ? 'selected' : '' }}>Content Manager</option>
                    <option value="participant" {{ request('role') === 'participant' ? 'selected' : '' }}>Participant</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl border border-slate-200">
                Filter
            </button>
            @if(request()->anyFilled(['q', 'role']))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Users -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/80 text-slate-700 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Hak Akses (Role)</th>
                        <th class="p-4">Institusi Penugasan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi / Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block text-sm">{{ $u->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">ID: #{{ $u->id }}</span>
                            </td>
                            <td class="p-4 font-mono text-slate-700">{{ $u->email }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $u->role === 'super_admin' ? 'bg-rose-100 text-rose-900 border border-rose-300' : ($u->role === 'admin' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-800 border border-slate-300') }}">
                                    {{ $u->role_display_name }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600">{{ $u->institution->name ?? '-' }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->status === 'active' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $u->status }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <button type="button" 
                                            @click="activeUser = {
                                                id: '{{ $u->id }}',
                                                name: '{{ addslashes($u->name) }}',
                                                email: '{{ addslashes($u->email) }}',
                                                phone: '{{ addslashes($u->phone ?? '') }}',
                                                role: '{{ $u->role }}',
                                                institution_id: '{{ $u->institution_id ?? '' }}',
                                                status: '{{ $u->status }}'
                                            }; editModal = true" 
                                            class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-[11px] rounded-lg border border-amber-300/80 transition flex items-center gap-1 cursor-pointer">
                                        <span>✏️</span> <span>Edit</span>
                                    </button>

                                    <!-- Delete Button -->
                                    @if(auth()->id() !== $u->id)
                                        <button type="button" 
                                                @click="activeUser = {
                                                    id: '{{ $u->id }}',
                                                    name: '{{ addslashes($u->name) }}',
                                                    email: '{{ addslashes($u->email) }}'
                                                }; deleteModal = true" 
                                                class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded-lg border border-rose-300/80 transition flex items-center gap-1 cursor-pointer">
                                            <span>🗑️</span> <span>Hapus</span>
                                        </button>
                                    @else
                                        <span class="px-2 py-1 bg-slate-100 text-slate-400 text-[10px] rounded border border-slate-200 font-bold" title="Akun Anda yang sedang login">
                                            Akun Anda
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 font-medium">
                                Tidak ada data user yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- CREATE USER MODAL -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200 animate-fadeIn">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base flex items-center gap-2">
                    <span>➕</span> <span>Tambah Akun Admin / Staff Baru</span>
                </h3>
                <button type="button" @click="createModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">✕</button>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Contoh: Ahmad Subagyo" class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Official *</label>
                    <input type="email" name="email" required placeholder="ahmad@ruhiologyinstitute.com" class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" placeholder="08xxxxxxxxxx" class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Password *</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Role Hak Akses System *</label>
                    <select name="role" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                        <option value="super_admin">Super Admin (Akses Penuh Seluruh Fitur)</option>
                        <option value="admin">Admin Operasional</option>
                        <option value="assessment_manager">Assessment Manager</option>
                        <option value="training_manager">Training Manager</option>
                        <option value="store_manager">Store Manager</option>
                        <option value="consultation_manager">Consultation Manager</option>
                        <option value="content_manager">Content Manager</option>
                        <option value="participant">Participant / Peserta Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Institusi Penugasan (Opsional)</label>
                    <select name="institution_id" class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                        <option value="">-- Tanpa Spesifikasi Instansi (Publik / Utama) --</option>
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->name }} ({{ $inst->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Akun *</label>
                    <select name="status" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                        <option value="active">Active (Aktif)</option>
                        <option value="inactive">Inactive (Non-Aktifkan)</option>
                    </select>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow hover:bg-[#123B59]">Simpan User Baru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT USER MODAL -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200 animate-fadeIn">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-base flex items-center gap-2">
                    <span>✏️</span> <span>Edit Data User Admin</span>
                </h3>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">✕</button>
            </div>
            
            <form :action="'{{ url('/admin/users') }}/' + activeUser.id" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" x-model="activeUser.name" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Official *</label>
                    <input type="email" name="email" x-model="activeUser.email" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" x-model="activeUser.phone" placeholder="08xxxxxxxxxx" class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                    <span class="text-[10px] text-slate-400 italic">Isi hanya jika ingin mengganti password user ini.</span>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Role Hak Akses System *</label>
                    <select name="role" x-model="activeUser.role" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                        <option value="super_admin">Super Admin (Akses Penuh)</option>
                        <option value="admin">Admin Operasional</option>
                        <option value="assessment_manager">Assessment Manager</option>
                        <option value="training_manager">Training Manager</option>
                        <option value="store_manager">Store Manager</option>
                        <option value="consultation_manager">Consultation Manager</option>
                        <option value="content_manager">Content Manager</option>
                        <option value="participant">Participant / Peserta Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Institusi Penugasan</label>
                    <select name="institution_id" x-model="activeUser.institution_id" class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                        <option value="">-- Tanpa Spesifikasi Instansi --</option>
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}">{{ $inst->name }} ({{ $inst->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Akun *</label>
                    <select name="status" x-model="activeUser.status" required class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-[#0B2A43]">
                        <option value="active">Active (Aktif)</option>
                        <option value="inactive">Inactive (Non-Aktifkan)</option>
                    </select>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded-xl shadow hover:bg-amber-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE USER CONFIRMATION MODAL -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-rose-200 animate-fadeIn">
            <div class="flex items-center gap-3 text-rose-600">
                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center font-bold text-lg">
                    ⚠️
                </div>
                <div>
                    <h3 class="font-bold font-serif text-slate-900 text-base">Konfirmasi Hapus Akun User</h3>
                    <p class="text-xs text-rose-600">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus akun user <strong class="text-slate-900" x-text="activeUser.name"></strong> (<span class="font-mono" x-text="activeUser.email"></span>)?
            </p>

            <form :action="'{{ url('/admin/users') }}/' + activeUser.id" method="POST" class="pt-2 flex justify-end gap-2 border-t border-slate-100">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModal = false" class="px-4 py-2 border rounded-xl font-semibold text-slate-600 hover:bg-slate-50 text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow">Ya, Hapus Akun Ini</button>
            </form>
        </div>
    </div>

</div>
@endsection
