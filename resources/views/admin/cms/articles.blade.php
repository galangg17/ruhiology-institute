@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ 
    createModal: false, 
    editModal: false,
    editData: {
        id: '',
        title: '',
        type: 'article',
        category_id: '',
        excerpt: '',
        content: '',
        author: 'Prof. Dr. Iskandar Nazari',
        published_at: '',
        status: 'published',
        featured_image: ''
    },
    editPreviewUrl: '',
    createPreviewUrl: '',
    openEdit(art) {
        let pubAtFormatted = '';
        if (art.published_at) {
            let d = new Date(art.published_at);
            pubAtFormatted = d.toISOString().slice(0, 16);
        }
        this.editData = {
            id: art.id,
            title: art.title,
            type: art.type || 'article',
            category_id: art.category_id || '',
            excerpt: art.excerpt || '',
            content: art.content || '',
            author: art.author || 'Prof. Dr. Iskandar Nazari',
            published_at: pubAtFormatted,
            status: art.status || 'published',
            featured_image: art.featured_image || ''
        };
        this.editPreviewUrl = art.featured_image || '';
        this.editModal = true;
    },
    previewFile(event, mode) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (mode === 'create') this.createPreviewUrl = e.target.result;
                if (mode === 'edit') this.editPreviewUrl = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">

    <!-- Page Title & Top Action -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold font-serif text-slate-900 tracking-tight">Kelola Artikel & Berita Institusi</h2>
            <p class="text-xs text-slate-500 mt-0.5">Publikasi karya ilmiah, kajian ruhiologi, rilis pers, serta tanggal & jam tayang berita resmi institute.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-md border border-[#C9A24D]/30 transition flex items-center gap-1.5 cursor-pointer">
            <span>+</span> <span>Tambah Artikel / Berita Baru</span>
        </button>
    </div>

    <!-- Stat Summary Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 text-xs font-sans">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-mono text-slate-400 font-bold uppercase tracking-wider block">TOTAL KONTEN</span>
            <div class="text-2xl font-black font-mono text-[#0B2A43]">
                {{ $articles->total() }}
            </div>
            <span class="text-[10px] text-slate-500 block">Artikel & Berita Terdaftar</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-mono text-slate-400 font-bold uppercase tracking-wider block">PUBLISHED</span>
            <div class="text-2xl font-black font-mono text-emerald-600">
                {{ \App\Models\Article::where('status', 'published')->count() }}
            </div>
            <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-bold inline-block">Tayang di Public</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-mono text-slate-400 font-bold uppercase tracking-wider block">KATEGORI ARTIKEL</span>
            <div class="text-2xl font-black font-mono text-amber-600">
                {{ \App\Models\Article::where('type', 'article')->count() }}
            </div>
            <span class="text-[10px] text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full font-bold inline-block">Kajian Teori & Monograf</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-mono text-slate-400 font-bold uppercase tracking-wider block">KATEGORI BERITA</span>
            <div class="text-2xl font-black font-mono text-sky-700">
                {{ \App\Models\Article::where('type', 'news')->count() }}
            </div>
            <span class="text-[10px] text-sky-800 bg-sky-50 px-2 py-0.5 rounded-full font-bold inline-block">Rilis Pers & Kegiatan</span>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden font-sans">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs font-mono font-bold text-slate-600">
            <span>DAFTAR ARTIKEL & BERITA ({{ $articles->total() }} Konten)</span>
            <span class="text-[11px] text-slate-400 font-normal">Halaman {{ $articles->currentPage() }} dari {{ $articles->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[900px]">
                <thead class="bg-slate-100 text-slate-700 uppercase font-bold border-b border-slate-200 text-[11px] tracking-wider font-mono">
                    <tr>
                        <th class="px-4 py-3.5 whitespace-nowrap">Gambar Sampul</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Judul Artikel / Berita</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Tipe</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Penulis</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Tanggal & Jam Rilis</th>
                        <th class="px-4 py-3.5 whitespace-nowrap text-center">Status</th>
                        <th class="px-4 py-3.5 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50 transition">
                            
                            <!-- Thumbnail Image -->
                            <td class="p-4">
                                <div class="w-16 h-12 rounded-xl bg-slate-200 overflow-hidden border border-slate-300 shrink-0">
                                    <img src="{{ $art->featured_image ?: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=400' }}" 
                                         alt="{{ $art->title }}" 
                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=400';"
                                         class="w-full h-full object-cover">
                                </div>
                            </td>

                            <!-- Title & Excerpt -->
                            <td class="p-4">
                                <strong class="font-bold text-slate-900 text-xs block leading-snug">{{ $art->title }}</strong>
                                <span class="text-slate-500 text-[11px] line-clamp-1 mt-0.5 font-normal">{{ $art->excerpt ?: Str::limit(strip_tags($art->content), 80) }}</span>
                            </td>

                            <!-- Type -->
                            <td class="p-4">
                                @if($art->type === 'news')
                                    <span class="px-2.5 py-0.5 bg-sky-100 text-sky-800 font-extrabold rounded-full text-[10px] uppercase">
                                        📰 Berita
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-amber-100 text-amber-900 font-extrabold rounded-full text-[10px] uppercase">
                                        📘 Artikel
                                    </span>
                                @endif
                            </td>

                            <!-- Category -->
                            <td class="p-4">
                                <span class="font-bold text-slate-700 text-xs">{{ $art->category->name ?? 'Umum' }}</span>
                            </td>

                            <!-- Author -->
                            <td class="p-4">
                                <span class="text-slate-800 font-bold text-xs">{{ $art->author ?: 'Prof. Dr. Iskandar Nazari' }}</span>
                            </td>

                            <!-- Date and Time -->
                            <td class="p-4 font-mono text-[11px] text-slate-600">
                                @if($art->published_at)
                                    <span class="font-bold text-slate-800 block">{{ $art->published_at->format('d M Y') }}</span>
                                    <span class="text-[10px] text-amber-800 font-bold">⏰ {{ $art->published_at->format('H:i') }} WIB</span>
                                @else
                                    <span>-</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="p-4 text-center">
                                @if($art->status === 'published')
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase rounded-full border border-emerald-300">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded-full border border-slate-300">
                                        Draft
                                    </span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition" title="Pratinjau di Publik">
                                        👁️ Lihat
                                    </a>
                                    <button type="button" @click="openEdit({{ json_encode($art) }})" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-lg text-xs font-bold transition cursor-pointer" title="Edit Konten">
                                        ✏️ Edit
                                    </button>
                                    <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus artikel/berita ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition cursor-pointer" title="Hapus Konten">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 font-sans">
                                <span>📝 Belum ada artikel atau berita yang dibuat. Klik tombol "+ Tambah Artikel / Berita Baru" untuk memulai.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200">
            {{ $articles->links() }}
        </div>
    </div>

    <!-- MODAL 1: CREATE NEW ARTICLE / NEWS WITH IMAGE UPLOADER & DATETIME PICKER -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-lg flex items-center gap-2">
                    <span>✍️</span> <span>Buat Artikel / Berita Baru</span>
                </h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-sans">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Utama Konten *</label>
                    <input type="text" name="title" required placeholder="Contoh: Peran Ruhiology Quotient dalam Pembangunan Karakter Gen Z..." class="w-full p-2.5 rounded-xl border border-slate-300 font-bold text-slate-900">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Konten *</label>
                        <select name="type" required class="w-full p-2.5 rounded-xl border border-slate-300 font-bold bg-white">
                            <option value="article">📘 Artikel / Kajian Teori Ruhiologi</option>
                            <option value="news">📰 Berita Resmi Institusi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Konten</label>
                        <select name="category_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Featured Image Upload & Preview Box -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                    <span class="font-bold text-slate-800 text-xs block">🖼️ Gambar Sampul (Featured Image / Banner Foto)</span>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-8 space-y-2">
                            <label class="block font-semibold text-slate-600">Upload Foto Sampul Baru (JPG, PNG, WebP)</label>
                            <input type="file" name="image_file" accept="image/*" @change="previewFile($event, 'create')" class="w-full p-2 rounded-xl border border-slate-300 bg-white text-xs">
                            
                            <label class="block font-semibold text-slate-600 pt-1">Atau Gunakan URL Gambar Sampul</label>
                            <input type="text" name="featured_image" x-model="createPreviewUrl" placeholder="https://..." class="w-full p-2 rounded-xl border border-slate-300 text-xs">
                        </div>
                        
                        <div class="sm:col-span-4 text-center">
                            <span class="text-[10px] text-slate-400 block mb-1">Pratinjau Foto</span>
                            <div class="w-full aspect-[4/3] bg-slate-200 rounded-xl overflow-hidden border border-slate-300 flex items-center justify-center">
                                <template x-if="createPreviewUrl">
                                    <img :src="createPreviewUrl" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!createPreviewUrl">
                                    <span class="text-slate-400 text-[10px]">Belum ada gambar</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Ringkasan Singkat (Excerpt / Snippet)</label>
                    <textarea name="excerpt" rows="2" placeholder="Ringkasan 2-3 kalimat yang akan tampil di kartu berita..." class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Isi Narasi Lengkap Konten (Format Paragraf / HTML) *</label>
                    <textarea name="content" rows="6" required placeholder="Tuliskan narasi artikel secara lengkap di sini..." class="w-full p-2.5 rounded-xl border border-slate-300 font-sans"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Penulis / Author *</label>
                        <input type="text" name="author" value="Prof. Dr. Iskandar Nazari" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal & Jam Rilis ⏰</label>
                        <input type="datetime-local" name="published_at" value="{{ date('Y-m-d\TH:i') }}" class="w-full p-2.5 rounded-xl border border-slate-300 font-mono text-xs">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Publikasi *</label>
                        <select name="status" class="w-full p-2.5 rounded-xl border border-slate-300 font-bold bg-white">
                            <option value="published">Published (Tayang Langsung)</option>
                            <option value="draft">Draft (Simpan Dulu)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border rounded-xl font-bold text-slate-600">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow-md cursor-pointer">Simpan Konten Baru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT ARTICLE / NEWS WITH IMAGE UPLOADER & DATETIME PICKER -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold font-serif text-slate-900 text-lg flex items-center gap-2">
                    <span>✏️</span> <span>Edit Artikel / Berita</span>
                </h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form :action="'{{ url('admin/articles') }}/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-sans">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Utama Konten *</label>
                    <input type="text" name="title" x-model="editData.title" required class="w-full p-2.5 rounded-xl border border-slate-300 font-bold text-slate-900">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Konten *</label>
                        <select name="type" x-model="editData.type" required class="w-full p-2.5 rounded-xl border border-slate-300 font-bold bg-white">
                            <option value="article">📘 Artikel / Kajian Teori Ruhiologi</option>
                            <option value="news">📰 Berita Resmi Institusi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Konten</label>
                        <select name="category_id" x-model="editData.category_id" class="w-full p-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Featured Image Upload & Preview Box -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                    <span class="font-bold text-slate-800 text-xs block">🖼️ Gambar Sampul (Featured Image / Banner Foto)</span>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-8 space-y-2">
                            <label class="block font-semibold text-slate-600">Ganti Foto Sampul (JPG, PNG, WebP)</label>
                            <input type="file" name="image_file" accept="image/*" @change="previewFile($event, 'edit')" class="w-full p-2 rounded-xl border border-slate-300 bg-white text-xs">
                            
                            <label class="block font-semibold text-slate-600 pt-1">Atau URL Gambar Sampul</label>
                            <input type="text" name="featured_image" x-model="editData.featured_image" @input="editPreviewUrl = editData.featured_image" placeholder="https://..." class="w-full p-2 rounded-xl border border-slate-300 text-xs">
                        </div>
                        
                        <div class="sm:col-span-4 text-center">
                            <span class="text-[10px] text-slate-400 block mb-1">Pratinjau Foto</span>
                            <div class="w-full aspect-[4/3] bg-slate-200 rounded-xl overflow-hidden border border-slate-300 flex items-center justify-center">
                                <template x-if="editPreviewUrl">
                                    <img :src="editPreviewUrl" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!editPreviewUrl">
                                    <span class="text-slate-400 text-[10px]">Belum ada gambar</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Ringkasan Singkat (Excerpt / Snippet)</label>
                    <textarea name="excerpt" x-model="editData.excerpt" rows="2" class="w-full p-2.5 rounded-xl border border-slate-300"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Isi Narasi Lengkap Konten (Format Paragraf / HTML) *</label>
                    <textarea name="content" x-model="editData.content" rows="6" required class="w-full p-2.5 rounded-xl border border-slate-300 font-sans"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Penulis / Author *</label>
                        <input type="text" name="author" x-model="editData.author" required class="w-full p-2.5 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal & Jam Rilis ⏰</label>
                        <input type="datetime-local" name="published_at" x-model="editData.published_at" class="w-full p-2.5 rounded-xl border border-slate-300 font-mono text-xs">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Publikasi *</label>
                        <select name="status" x-model="editData.status" class="w-full p-2.5 rounded-xl border border-slate-300 font-bold bg-white">
                            <option value="published">Published (Tayang Langsung)</option>
                            <option value="draft">Draft (Simpan Dulu)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border rounded-xl font-bold text-slate-600">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-[#0B2A43] text-white font-bold rounded-xl shadow-md cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
