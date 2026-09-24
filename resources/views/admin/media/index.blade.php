@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ uploadModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Media Library</h2>
            <p class="text-xs text-slate-500">Manajemen berkas, gambar, dan dokumen instrumen.</p>
        </div>
        <button @click="uploadModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Unggah Berkas Media
        </button>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
        @foreach($mediaFiles as $m)
            <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between text-xs space-y-2">
                <div class="w-full h-32 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 font-mono text-[10px] border border-slate-200 overflow-hidden">
                    🖼️ {{ $m->mime_type }}
                </div>
                <div>
                    <span class="font-bold text-slate-900 truncate block">{{ $m->file_name }}</span>
                    <span class="text-[10px] text-slate-400 block">{{ number_format(($m->file_size ?? 0)/1024, 1) }} KB</span>
                </div>
                <div class="pt-2 border-t border-slate-100 flex justify-between items-center text-[11px]">
                    <a href="{{ $m->file_path }}" target="_blank" class="text-amber-700 font-bold hover:underline">Buka ↗</a>
                    <form action="{{ route('admin.media.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus file media ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-rose-600 font-bold hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Upload Modal -->
    <div x-show="uploadModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-base">Unggah Berkas Media Baru</h3>
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih File (JPG, PNG, WEBP, PDF, Max 5MB)</label>
                    <input type="file" name="file" required class="w-full p-2 border border-slate-300 rounded">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alt Text (Keterangan Gambar)</label>
                    <input type="text" name="alt_text" placeholder="Deskripsi singkat gambar..." class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="uploadModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Unggah Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
