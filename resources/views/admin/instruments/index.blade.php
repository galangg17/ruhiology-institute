@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, importModal: false, editData: {} }">
    <!-- Header Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-1.5 bg-[#F4F1EA] text-[#8C703E] border border-[#DFD8C8] text-[10px] font-mono font-bold px-2.5 py-0.5 rounded-md uppercase tracking-wider mb-1.5">
                <svg class="w-3.5 h-3.5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>BANK SOAL & INSTRUMEN ASESMEN</span>
            </div>
            <h2 class="text-2xl font-bold font-serif text-[#0F2537]">Kelola Paket Soal</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola paket instrumen, bank soal terstruktur, impor/ekspor data Excel, dan hak akses instrumen.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.instruments.template.download') }}" class="px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs rounded-xl shadow-2xs transition flex items-center gap-2" title="Unduh Template Format Excel 20 Soal">
                <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Template Excel (20 Soal)</span>
            </a>
            <button @click="importModal = true" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>Import Excel</span>
            </button>
            <button @click="createModal = true" class="px-5 py-2.5 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buat Paket Baru</span>
            </button>
        </div>
    </div>

    <!-- Instruments / Question Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($instruments as $inst)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden relative group">
                <div class="p-5 space-y-3">
                    <div class="flex justify-between items-center gap-2">
                        <span class="font-mono font-bold text-[11px] text-[#8C703E] bg-[#F4F1EA] border border-[#DFD8C8] px-2.5 py-0.5 rounded-lg">
                            {{ $inst->code }} <span class="text-slate-400 font-normal">• v{{ $inst->version }}</span>
                        </span>
                        
                        <!-- Interactive Access Toggle Badge Button -->
                        <form action="{{ route('admin.instruments.toggle_access', $inst->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer {{ $inst->access_type === 'public' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200' }}" title="Klik untuk mengubah akses publik / event">
                                @if($inst->access_type === 'public')
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Publik</span>
                                @else
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Khusus Event</span>
                                @endif
                            </button>
                        </form>
                    </div>

                    <div>
                        <h3 class="font-bold font-serif text-[#0F2537] text-base leading-snug group-hover:text-[#8C703E] transition line-clamp-1" title="{{ $inst->name }}">
                            {{ $inst->name }}
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 min-h-[36px] mt-1">
                            {{ $inst->description ?? 'Tidak ada deskripsi tambahan.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-xs">
                        <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200 font-semibold text-slate-700">
                            <svg class="w-3.5 h-3.5 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>{{ $inst->dimensions_count }} Dimensi</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200 font-semibold text-slate-700">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>{{ $inst->questions_count }} Soal</span>
                        </div>
                    </div>
                </div>

                <div class="p-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-1">
                        <button @click="editData = {
                            id: {{ $inst->id }},
                            code: '{{ addslashes($inst->code) }}',
                            name: '{{ addslashes($inst->name) }}',
                            version: '{{ addslashes($inst->version) }}',
                            description: '{{ addslashes($inst->description ?? '') }}',
                            instructions: '{{ addslashes($inst->instructions ?? '') }}',
                            status: '{{ $inst->status }}'
                        }; editModal = true;" class="p-2 bg-white hover:bg-slate-100 text-slate-700 rounded-xl border border-slate-200 transition cursor-pointer" title="Edit Paket Soal">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        
                        <a href="{{ route('admin.instruments.export', $inst->id) }}" class="p-2 bg-white hover:bg-slate-100 text-slate-700 rounded-xl border border-slate-200 transition" title="Export Paket Soal ke Excel">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>

                        <form action="{{ route('admin.instruments.destroy', $inst->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket soal \'{{ addslashes($inst->name) }}\' ({{ $inst->code }}) beserta seluruh butir soal di dalamnya? Tindakan ini tidak dapat dibatalkan.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-xl border border-slate-200 transition cursor-pointer" title="Hapus Paket Soal Ini">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('admin.instruments.show', $inst->id) }}" class="px-4 py-2 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                        <span>Kelola Soal</span>
                        <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-3 shadow-xs">
                <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="font-serif font-bold text-slate-800 text-base">Belum Ada Paket Soal</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">Klik tombol "Buat Paket Baru" atau "Import Excel" di atas untuk menambahkan paket instrumen pertama Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="pt-2">
        {{ $instruments->links() }}
    </div>

    <!-- CREATE PAKET SOAL MODAL -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="createModal = false" class="bg-white rounded-2xl max-w-xl w-full shadow-2xl relative border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0F2537] text-white flex items-center justify-between shrink-0 border-b border-[#1E3E5B]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#8C703E] text-white font-bold flex items-center justify-center text-base shadow">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#D4AF37] uppercase tracking-widest block">INSTRUMENT CONSOLE</span>
                        <h3 class="text-base font-serif font-bold text-white tracking-tight">Buat Paket Soal Baru</h3>
                    </div>
                </div>
                <button @click="createModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.instruments.store') }}" method="POST" class="p-6 sm:p-7 text-xs overflow-y-auto flex-1 space-y-4">
                @csrf
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block font-bold text-slate-700 text-xs mb-1">Kode Paket Soal *</label>
                        <input type="text" name="code" required placeholder="Contoh: RQI-20 / RQI-30M" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-mono bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none uppercase font-bold text-xs transition">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 text-xs mb-1">Versi Paket *</label>
                        <input type="text" name="version" value="1.0" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none text-center font-mono font-bold text-xs transition">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Nama Paket Soal *</label>
                    <input type="text" name="name" required placeholder="Contoh: Inventori Kecerdasan Ruhiologi (20 Butir & WHO-5)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none font-medium text-xs transition">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Deskripsi Paket</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan peruntukan dan cakupan dimensi paket soal ini..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none text-xs font-medium transition"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Instruksi Pengerjaan untuk Peserta</label>
                    <textarea name="instructions" rows="2" placeholder="Pilihlah frekuensi yang paling menggambarkan kondisi dan perasaan Anda yang sebenarnya..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none text-xs font-medium transition"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Status Paket *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-semibold text-slate-800 outline-none focus:border-[#0F2537] text-xs transition">
                        <option value="active">Active (Dapat Digunakan Event / Publik)</option>
                        <option value="draft">Draft (Pengembangan / Belum Publik)</option>
                        <option value="archived">Archived (Diarsipkan)</option>
                    </select>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-800">
                        <input type="checkbox" name="auto_seed" value="1" checked class="w-4 h-4 text-[#0F2537] rounded border-slate-300 focus:ring-[#0F2537]">
                        <span>Isi Otomatis dengan 20 Butir Soal Standar (RQI-15 + WHO-5)</span>
                    </label>
                    <p class="text-[10px] text-slate-500 mt-1 ml-6 leading-relaxed">
                        6 Dimensi Standar akan dibuat otomatis. Jika dicentang, 20 butir soal bawaan akan langsung terisi lengkap.
                    </p>
                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3 shrink-0">
                    <button @click="createModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold rounded-xl shadow-sm transition cursor-pointer text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan & Buat Paket Soal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT PAKET SOAL MODAL -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="editModal = false" class="bg-white rounded-2xl max-w-xl w-full shadow-2xl relative border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0F2537] text-white flex items-center justify-between shrink-0 border-b border-[#1E3E5B]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#8C703E] text-white font-bold flex items-center justify-center text-base shadow">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#D4AF37] uppercase tracking-widest block">INSTRUMENT CONSOLE</span>
                        <h3 class="text-base font-serif font-bold text-white tracking-tight">Edit Identitas Paket Soal</h3>
                    </div>
                </div>
                <button @click="editModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form :action="'/admin/instruments/' + editData.id" method="POST" class="p-6 sm:p-7 text-xs overflow-y-auto flex-1 space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block font-bold text-slate-700 text-xs mb-1">Kode Paket Soal *</label>
                        <input type="text" name="code" x-model="editData.code" required placeholder="Contoh: RQI-20" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-mono bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none uppercase font-bold text-xs transition">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 text-xs mb-1">Versi Paket *</label>
                        <input type="text" name="version" x-model="editData.version" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none text-center font-mono font-bold text-xs transition">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Nama Paket Soal *</label>
                    <input type="text" name="name" x-model="editData.name" required placeholder="Nama Paket Soal..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none font-medium text-xs transition">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Deskripsi Paket</label>
                    <textarea name="description" x-model="editData.description" rows="2" placeholder="Deskripsi peruntukan paket..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none text-xs font-medium transition"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Instruksi Pengerjaan untuk Peserta</label>
                    <textarea name="instructions" x-model="editData.instructions" rows="2" placeholder="Instruksi pengerjaan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0F2537] outline-none text-xs font-medium transition"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Status Paket *</label>
                    <select name="status" x-model="editData.status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white font-semibold text-slate-800 outline-none focus:border-[#0F2537] text-xs transition">
                        <option value="active">Active (Dapat Digunakan Event / Publik)</option>
                        <option value="draft">Draft (Pengembangan / Belum Publik)</option>
                        <option value="archived">Archived (Diarsipkan)</option>
                    </select>
                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3 shrink-0">
                    <button @click="editModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#8C703E] hover:bg-[#72592F] text-white font-bold rounded-xl shadow-sm transition cursor-pointer text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan Paket</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- IMPORT EXCEL MODAL -->
    <div x-show="importModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="importModal = false" class="bg-white rounded-2xl max-w-lg w-full shadow-2xl relative border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0F2537] text-white flex items-center justify-between shrink-0 border-b border-[#1E3E5B]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#8C703E] text-white font-bold flex items-center justify-center text-base shadow">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#D4AF37] uppercase tracking-widest block">EXCEL IMPORTER</span>
                        <h3 class="text-base font-serif font-bold text-white tracking-tight">Import Paket Soal dari Excel</h3>
                    </div>
                </div>
                <button @click="importModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.instruments.import') }}" method="POST" enctype="multipart/form-data" class="p-6 text-xs overflow-y-auto flex-1 space-y-4">
                @csrf
                
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-xs leading-relaxed space-y-1.5">
                    <strong class="font-bold text-[#0F2537] block flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#8C703E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Petunjuk Format File Excel:</span>
                    </strong>
                    <p class="text-slate-600 text-[11px]">Format file memiliki 4 kolom (Kode Dimensi, Nama Dimensi, Teks Butir Soal, Skoring normal/reverse).</p>
                    <a href="{{ route('admin.instruments.template.download') }}" class="inline-flex items-center gap-1.5 text-[#0F2537] font-bold underline mt-1 hover:text-[#8C703E]">
                        <span>Unduh Template Contoh Excel (20 Soal)</span>
                    </a>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Target Paket Soal (Opsional)</label>
                    <select name="instrument_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 font-semibold text-slate-800 outline-none focus:border-[#0F2537] text-xs transition">
                        <option value="">-- Buat Paket Soal Baru Otomatis --</option>
                        @foreach($instruments as $targetInst)
                            <option value="{{ $targetInst->id }}">Gabungkan ke: {{ $targetInst->name }} ({{ $targetInst->code }})</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Jika dikosongkan, sistem akan otomatis membuatkan paket soal baru dari file Excel yang diunggah.</p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1">Pilih File Excel / CSV (.xlsx / .csv) *</label>
                    <input type="file" name="file" accept=".csv,.xlsx,.txt" required class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#0F2537] file:text-white hover:file:bg-[#1E3E5B] cursor-pointer text-xs">
                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3 shrink-0">
                    <button @click="importModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#0F2537] hover:bg-[#1E3E5B] text-white font-bold rounded-xl shadow-sm transition cursor-pointer text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span>Proses Import Excel</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
