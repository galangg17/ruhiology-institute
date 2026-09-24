@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Artikel & Berita</h2>
            <p class="text-xs text-slate-500">Publikasi kajian, rilis pers, dan berita institusi.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow">
            + Tambah Konten Baru
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Judul Artikel / Berita</th>
                        <th class="p-4">Tipe</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Penulis</th>
                        <th class="p-4">Tanggal Rilis</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($articles as $art)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-bold text-slate-900">{{ $art->title }}</td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded uppercase text-[10px]">{{ $art->type }}</span></td>
                            <td class="p-4">{{ $art->category->name ?? '-' }}</td>
                            <td class="p-4">{{ $art->author }}</td>
                            <td class="p-4 text-slate-600">{{ $art->published_at ? $art->published_at->format('d/m/Y') : '-' }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $art->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $art->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $articles->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4">
            <h3 class="font-bold font-serif text-slate-900 text-base">Buat Konten Artikel / Berita Baru</h3>
            <form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Konten</label>
                    <input type="text" name="title" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe</label>
                        <select name="type" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="article">Artikel / Kajian Teori</option>
                            <option value="news">Berita Institusi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full p-2.5 rounded border border-slate-300">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Ringkasan (Excerpt)</label>
                    <textarea name="excerpt" rows="2" class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Isi Lengkap (HTML Content)</label>
                    <textarea name="content" rows="6" required placeholder="<p>Tuliskan konten artikel dalam format HTML atau paragraf...</p>" class="w-full p-2.5 rounded border border-slate-300 font-mono"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Penulis</label>
                        <input type="text" name="author" value="Prof. Dr. Iskandar Nazari" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded shadow">Simpan Konten</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
