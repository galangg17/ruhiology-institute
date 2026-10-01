@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, importModal: false, editData: {} }">
    <!-- Header Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 bg-[#0B2A43]/10 text-[#0B2A43] text-[10px] font-mono font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-1.5">
                <span>📦 BANK SOAL & INSTRUMEN ASESMEN</span>
            </div>
            <h2 class="text-2xl font-bold font-serif text-[#0B2A43]">Kelola Paket Soal</h2>
            <p class="text-xs text-slate-500 mt-1">Buat, impor/ekspor Excel, ganti nama, edit, dan atur akses publik vs event untuk setiap paket instrumen.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.instruments.template.download') }}" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300/80 font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5" title="Download Template Format Excel 20 Soal Terstruktur">
                <span>📥</span> <span>Template Excel (20 Soal)</span>
            </a>
            <button @click="importModal = true" class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                <span>📊</span> <span>Import Excel</span>
            </button>
            <button @click="createModal = true" class="px-5 py-2.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold text-xs rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer">
                <span>✨</span> <span>+ Buat Paket Baru</span>
            </button>
        </div>
    </div>

    <!-- Instruments / Question Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($instruments as $inst)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden relative group">
                <div class="p-5 space-y-3">
                    <div class="flex justify-between items-start gap-2">
                        <span class="text-[10px] font-mono font-extrabold text-[#C9A24D] bg-[#0B2A43] px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $inst->code }} • V{{ $inst->version }}
                        </span>
                        
                        <!-- Interactive Access Toggle Badge Button -->
                        <form action="{{ route('admin.instruments.toggle_access', $inst->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all flex items-center gap-1 shadow-2xs cursor-pointer {{ $inst->access_type === 'public' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-300 hover:bg-slate-200' }}" title="Klik untuk mengubah status akses publik / event">
                                @if($inst->access_type === 'public')
                                    <span>🌐 Publik (ON)</span>
                                @else
                                    <span>🔒 Khusus Event</span>
                                @endif
                            </button>
                        </form>
                    </div>

                    <h3 class="font-bold font-serif text-[#0B2A43] text-lg leading-snug group-hover:text-[#C9A24D] transition-colors line-clamp-1" title="{{ $inst->name }}">
                        {{ $inst->name }}
                    </h3>

                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 min-h-[36px]">
                        {{ $inst->description ?? 'Tidak ada deskripsi tambahan.' }}
                    </p>

                    <div class="flex items-center gap-3 text-xs text-slate-500 pt-3 border-t border-slate-100 font-medium">
                        <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-xl border border-slate-200/60 shadow-2xs">
                            <span>🧩</span> <strong>{{ $inst->dimensions_count }}</strong> <span class="text-slate-400">Dimensi</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-xl border border-slate-200/60 shadow-2xs">
                            <span>❓</span> <strong>{{ $inst->questions_count }}</strong> <span class="text-slate-400">Soal</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50/70 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-1.5">
                        <button @click="editData = {
                            id: {{ $inst->id }},
                            code: '{{ addslashes($inst->code) }}',
                            name: '{{ addslashes($inst->name) }}',
                            version: '{{ addslashes($inst->version) }}',
                            description: '{{ addslashes($inst->description ?? '') }}',
                            instructions: '{{ addslashes($inst->instructions ?? '') }}',
                            status: '{{ $inst->status }}'
                        }; editModal = true;" class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold text-xs rounded-xl transition cursor-pointer flex items-center gap-1">
                            <span>✏️ Edit</span>
                        </button>
                        
                        <a href="{{ route('admin.instruments.export', $inst->id) }}" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition" title="Export Paket Soal ke Excel">
                            <span>📤 Export</span>
                        </a>

                        <form action="{{ route('admin.instruments.destroy', $inst->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket soal \'{{ addslashes($inst->name) }}\' ({{ $inst->code }}) beserta seluruh soal di dalamnya? Tindakan ini tidak dapat dibatalkan.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition cursor-pointer flex items-center gap-1" title="Hapus Paket Soal Ini">
                                <span>🗑️ Hapus</span>
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('admin.instruments.show', $inst->id) }}" class="px-4 py-2 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1">
                        <span>Kelola Soal</span> <span>→</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3 shadow-sm">
                <div class="text-4xl">📦</div>
                <h3 class="font-serif font-bold text-slate-800 text-lg">Belum Ada Paket Soal</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">Klik tombol "+ Buat Paket Baru" atau "Import Excel" di atas untuk menambahkan paket instrumen pertama Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="pt-4">
        {{ $instruments->links() }}
    </div>

    <!-- PREMUM CREATE PAKET SOAL MODAL -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="createModal = false" class="bg-white rounded-3xl max-w-xl w-full shadow-2xl relative border border-slate-100 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0B2A43] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#C9A24D] text-[#0B2A43] font-bold flex items-center justify-center text-base shadow">
                        ✨
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">NEW INSTRUMENT SETUP</span>
                        <h3 class="text-base font-serif font-bold text-white">Buat Paket Soal Baru</h3>
                    </div>
                </div>
                <button @click="createModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">✕</button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.instruments.store') }}" method="POST" class="p-6 sm:p-7 text-xs overflow-y-auto flex-1 space-y-4">
                @csrf
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block font-bold text-slate-800 text-xs mb-1">Kode Paket Soal *</label>
                        <input type="text" name="code" required placeholder="Contoh: RQI-20 / RQI-30M" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] uppercase font-bold text-xs outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 text-xs mb-1">Versi Paket *</label>
                        <input type="text" name="version" value="1.0" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] text-center font-mono font-bold text-xs outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Nama Paket Soal *</label>
                    <input type="text" name="name" required placeholder="Contoh: Inventori Kecerdasan Ruhiologi (20 Butir & WHO-5)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Deskripsi Paket</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan peruntukan dan cakupan dimensi paket soal ini..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none text-xs font-medium"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Instruksi Pengerjaan untuk Peserta</label>
                    <textarea name="instructions" rows="2" placeholder="Pilihlah frekuensi yang paling menggambarkan kondisi dan perasaan Anda yang sebenarnya..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none text-xs font-medium"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Status Paket *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                        <option value="active">🟢 Active (Dapat Digunakan Event / Publik)</option>
                        <option value="draft">⚪ Draft (Pengembangan / Belum Publik)</option>
                        <option value="archived">🔴 Archived (Diarsipkan)</option>
                    </select>
                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                    <button @click="createModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer text-xs flex items-center gap-2">
                        <span>🚀 Simpan & Buat Paket Soal →</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PREMIUM EDIT PAKET SOAL MODAL -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-xl w-full shadow-2xl relative border border-slate-100 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0B2A43] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white font-bold flex items-center justify-center text-base shadow">
                        ✏️
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">UPDATE INSTRUMENT DATA</span>
                        <h3 class="text-base font-serif font-bold text-white">Edit Identitas Paket Soal</h3>
                    </div>
                </div>
                <button @click="editModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">✕</button>
            </div>

            <!-- Modal Body -->
            <form :action="'/admin/instruments/' + editData.id" method="POST" class="p-6 sm:p-7 text-xs overflow-y-auto flex-1 space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block font-bold text-slate-800 text-xs mb-1">Kode Paket Soal *</label>
                        <input type="text" name="code" x-model="editData.code" required placeholder="Contoh: RQI-20" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] uppercase font-bold text-xs outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 text-xs mb-1">Versi Paket *</label>
                        <input type="text" name="version" x-model="editData.version" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] text-center font-mono font-bold text-xs outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Nama Paket Soal *</label>
                    <input type="text" name="name" x-model="editData.name" required placeholder="Nama Paket Soal..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Deskripsi Paket</label>
                    <textarea name="description" x-model="editData.description" rows="2" placeholder="Deskripsi peruntukan paket..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none text-xs font-medium"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Instruksi Pengerjaan untuk Peserta</label>
                    <textarea name="instructions" x-model="editData.instructions" rows="2" placeholder="Instruksi pengerjaan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none text-xs font-medium"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Status Paket *</label>
                    <select name="status" x-model="editData.status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                        <option value="active">🟢 Active (Dapat Digunakan Event / Publik)</option>
                        <option value="draft">⚪ Draft (Pengembangan / Belum Publik)</option>
                        <option value="archived">🔴 Archived (Diarsipkan)</option>
                    </select>
                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                    <button @click="editModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer text-xs flex items-center gap-2">
                        <span>💾 Simpan Perubahan Paket →</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PREMIUM IMPORT EXCEL MODAL -->
    <div x-show="importModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="importModal = false" class="bg-white rounded-3xl max-w-lg w-full shadow-2xl relative border border-slate-100 flex flex-col max-h-[92vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0B2A43] text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-bold flex items-center justify-center text-base shadow">
                        📊
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">EXCEL IMPORTER</span>
                        <h3 class="text-base font-serif font-bold text-white">Import Paket Soal dari Excel</h3>
                    </div>
                </div>
                <button @click="importModal = false" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">✕</button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.instruments.import') }}" method="POST" enctype="multipart/form-data" class="p-6 text-xs overflow-y-auto flex-1 space-y-4">
                @csrf
                
                <div class="p-3.5 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-900 text-xs leading-relaxed space-y-1">
                    <strong class="font-bold block">💡 Petunjuk Format File Excel / CSV:</strong>
                    <p>File terstruktur dengan 4 kolom (Kode Dimensi, Nama Dimensi, Teks Pertanyaan, Skoring normal/reverse).</p>
                    <a href="{{ route('admin.instruments.template.download') }}" class="inline-flex items-center gap-1.5 text-emerald-800 font-extrabold underline mt-1 hover:text-emerald-950">
                        <span>📥 Download Template Contoh Excel (20 Soal)</span>
                    </a>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Target Paket Soal (Opsional)</label>
                    <select name="instrument_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-slate-50 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-[#0B2A43] text-xs">
                        <option value="">-- Buat Paket Soal Baru Otomatis --</option>
                        @foreach($instruments as $targetInst)
                            <option value="{{ $targetInst->id }}">Gabungkan ke: {{ $targetInst->name }} ({{ $targetInst->code }})</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Jika dikosongkan, sistem akan otomatis membuatkan Paket Soal baru dari file Excel yang diunggah.</p>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">Pilih File Excel / CSV (.csv) *</label>
                    <input type="file" name="file" accept=".csv,.xlsx,.txt" required class="w-full p-2 rounded-xl border border-slate-300 bg-slate-50/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0B2A43] file:text-white hover:file:bg-[#123B59] cursor-pointer text-xs">
                </div>

                <!-- Modal Footer Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                    <button @click="importModal = false" type="button" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition cursor-pointer text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer text-xs flex items-center gap-2">
                        <span>📊 Proses Import Excel →</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
