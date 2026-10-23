@extends('layouts.admin')

@section('title', 'Data Pendaftaran Pelatihan — Training Center')

@section('content')
<div x-data="{
    proofModal: false,
    proofUrl: '',
    proofCode: '',
    editModal: false,
    selectedReg: null,
    viewProof(url, code) {
        this.proofUrl = url;
        this.proofCode = code;
        this.proofModal = true;
    },
    openEdit(reg) {
        this.selectedReg = reg;
        this.editModal = true;
    }
}" class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">
                <a href="{{ route('admin.training.index') }}" class="hover:text-primary-600 transition">
                    Training Center
                </a>
                <span>/</span>
                <span class="text-slate-600">Pendaftaran Peserta</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-slate-800">Verifikasi & Data Peserta Training</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola data pendaftaran, bukti pembayaran transfer, dan persetujuan kepesertaan.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.training.registrations.export_csv', request()->query()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm shadow-emerald-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel / CSV
            </a>
            <a href="{{ route('admin.training.index') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs transition shadow-sm">
                Kelola Program Pelatihan
            </a>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.training.registrations') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Cari Peserta / Kode</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="No. Reg, nama, email, WA, instansi..." 
                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <!-- Training Filter -->
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Program Pelatihan</label>
                <select name="training_id" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Program</option>
                    @foreach($trainings as $tr)
                        <option value="{{ $tr->id }}" {{ request('training_id') == $tr->id ? 'selected' : '' }}>
                            {{ Str::limit($tr->title, 24) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Payment Status -->
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Status Bayar</label>
                <select name="payment_status" class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status Bayar</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('payment_status') === 'verified' ? 'selected' : '' }}>Verified (Lunas)</option>
                    <option value="rejected" {{ request('payment_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Actions Button -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white font-medium text-xs rounded-lg transition text-center">
                    Terapkan
                </button>
                @if(request()->anyFilled(['q', 'training_id', 'payment_status', 'registration_status']))
                    <a href="{{ route('admin.training.registrations') }}" class="py-2 px-3 border border-slate-300 hover:bg-slate-50 text-slate-600 text-xs rounded-lg text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Registrations Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4 min-w-[130px]">No. Registrasi</th>
                        <th class="py-3 px-4 min-w-[200px]">Data Peserta</th>
                        <th class="py-3 px-4 min-w-[180px]">Program & Batch</th>
                        <th class="py-3 px-4 min-w-[110px]">Investasi</th>
                        <th class="py-3 px-4 min-w-[120px]">Bukti Bayar</th>
                        <th class="py-3 px-4 min-w-[120px]">Status</th>
                        <th class="py-3 px-4 text-right min-w-[130px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($registrations as $index => $reg)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 text-center text-slate-400">
                                {{ $registrations->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-primary-700 bg-primary-50 px-2 py-0.5 rounded border border-primary-100">
                                    {{ $reg->registration_number }}
                                </span>
                                <div class="text-[10px] text-slate-400 mt-1">
                                    {{ $reg->created_at ? $reg->created_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <strong class="text-slate-800 text-sm block">{{ $reg->participant_name }}</strong>
                                <div class="text-slate-500 text-[11px]">{{ $reg->institution ?? 'Independen / Mandiri' }}</div>
                                <div class="flex items-center gap-2 mt-1 text-[11px]">
                                    <span class="text-slate-500">{{ $reg->email }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->phone) }}" target="_blank" class="text-emerald-600 hover:underline flex items-center gap-0.5">
                                        {{ $reg->phone }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-800 block">{{ $reg->training->title ?? '-' }}</span>
                                <span class="text-primary-700 font-semibold text-[11px] block mt-0.5">{{ $reg->batch->batch_name ?? '-' }}</span>
                                @if($reg->batch?->start_date)
                                    <span class="text-slate-400 text-[10px] block">Tgl: {{ $reg->batch->start_date->format('d M Y') }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold text-navy">
                                Rp {{ number_format($reg->price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($reg->payment_proof_path)
                                    <button type="button" 
                                            @click="viewProof('{{ $reg->payment_proof_path }}', '{{ $reg->registration_number }}')"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-semibold hover:bg-emerald-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Belum diunggah</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 space-y-1">
                                <!-- Payment status pill -->
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $reg->payment_status === 'verified' ? 'bg-emerald-100 text-emerald-800' : ($reg->payment_status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    Bayar: {{ $reg->payment_status }}
                                </span>
                                <!-- Registration status pill -->
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $reg->registration_status === 'approved' || $reg->registration_status === 'completed' ? 'bg-primary-100 text-primary-800' : ($reg->registration_status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700') }}">
                                    Reg: {{ $reg->registration_status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button type="button" @click='openEdit(@json($reg))'
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-navy hover:bg-navy-dark text-white text-[11px] font-semibold transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Verifikasi
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <p class="font-medium text-slate-600">Belum ada data pendaftar yang cocok</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registrations->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>

    <!-- Proof Viewer Modal -->
    <div x-show="proofModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="proofModal = false" class="bg-white rounded-2xl shadow-xl max-w-xl w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="font-serif font-bold text-slate-800 text-sm">Bukti Transfer Pembayaran</h3>
                    <p class="text-[11px] text-slate-500 font-mono" x-text="proofCode"></p>
                </div>
                <button type="button" @click="proofModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 max-h-[75vh] overflow-y-auto flex items-center justify-center bg-slate-100">
                <template x-if="proofUrl.endsWith('.pdf')">
                    <div class="p-8 text-center">
                        <p class="text-sm font-semibold text-slate-700 mb-3">Berkas format PDF</p>
                        <a :href="proofUrl" target="_blank" class="px-4 py-2 rounded-xl bg-navy text-white text-xs font-bold inline-block">
                            Buka Berkas PDF di Tab Baru ↗
                        </a>
                    </div>
                </template>
                <template x-if="!proofUrl.endsWith('.pdf')">
                    <img :src="proofUrl" alt="Bukti Transfer" class="max-w-full h-auto rounded-lg shadow-sm border border-slate-200">
                </template>
            </div>
            <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 flex justify-between items-center">
                <a :href="proofUrl" target="_blank" class="text-xs text-primary-600 hover:underline font-semibold">Buka Ukuran Penuh ↗</a>
                <button type="button" @click="proofModal = false" class="px-4 py-2 rounded-xl bg-slate-200 text-slate-700 text-xs font-semibold">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Edit Status Modal -->
    <div x-show="editModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="editModal = false" class="bg-white rounded-2xl shadow-xl max-w-lg w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="font-serif font-bold text-slate-800 text-base">Verifikasi Kepesertaan & Pembayaran</h3>
                    <p class="text-xs text-slate-500 font-mono" x-text="selectedReg ? '#' + selectedReg.registration_number + ' — ' + selectedReg.participant_name : ''"></p>
                </div>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="selectedReg ? '/admin/training-registrations/' + selectedReg.id : '#'" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Pembayaran</label>
                    <select name="payment_status" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-300" x-model="selectedReg && selectedReg.payment_status">
                        <option value="pending">Pending (Menunggu Pembayaran / Verifikasi)</option>
                        <option value="verified">Verified (Lunas Terkonfirmasi)</option>
                        <option value="rejected">Rejected (Bukti Ditolak / Tidak Valid)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Kepesertaan</label>
                    <select name="registration_status" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-300" x-model="selectedReg && selectedReg.registration_status">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved (Diterima / Kursi Terkunci)</option>
                        <option value="completed">Completed (Telah Selesai Pelatihan)</option>
                        <option value="rejected">Rejected (Ditolak)</option>
                        <option value="cancelled">Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Admin (Internal)</label>
                    <textarea name="admin_notes" rows="3" placeholder="Catatan nomor rekening pengirim, alasan penolakan, dsb..." 
                              class="w-full text-xs px-3 py-2 rounded-xl border border-slate-300" x-model="selectedReg && selectedReg.admin_notes"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl bg-slate-200 text-slate-700 text-xs font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-navy hover:bg-navy-dark text-white text-xs font-bold shadow-md transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
