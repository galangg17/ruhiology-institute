@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, activeBook: {} }">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Katalog Buku (Store)</h2>
            <p class="text-xs text-slate-500">Manajemen buku, stok, harga, dan publikasi pers.</p>
        </div>
        <button @click="createModal = true" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow transition">
            + Tambah Buku Baru
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Sampul Buku</th>
                        <th class="p-4">Judul Buku</th>
                        <th class="p-4">Penulis & Penerbit</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Stok</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($products as $p)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-3">
                                @if($p->cover_image)
                                    <img src="{{ $p->cover_image }}" alt="{{ $p->title }}" class="w-12 h-16 object-cover rounded shadow-sm border border-slate-200">
                                @else
                                    <div class="w-12 h-16 bg-slate-100 rounded border border-slate-200 flex flex-col items-center justify-center text-slate-400 text-[9px] font-mono text-center p-1">
                                        <span>📕</span>
                                        <span>No Cover</span>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 font-bold text-slate-900 max-w-xs">{{ $p->title }}</td>
                            <td class="p-4">{{ $p->author }} <br><span class="text-slate-400 text-[10px]">{{ $p->publisher }}</span></td>
                            <td class="p-4">{{ $p->category->name ?? '-' }}</td>
                            <td class="p-4 font-bold text-amber-700">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="p-4 font-bold text-slate-800">{{ $p->stock }} eksemplar</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $p->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="activeBook = {{ json_encode($p) }}; editModal = true" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded transition">
                                        ✏️ Edit
                                    </button>
                                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini dari katalog?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <h3 class="font-bold font-serif text-slate-900 text-base">Tambah Buku Baru Ke Katalog</h3>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Buku</label>
                    <input type="text" name="title" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="category_id" required class="w-full p-2.5 rounded border border-slate-300">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Penulis</label>
                        <input type="text" name="author" value="Prof. Dr. Iskandar Nazari" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>

                <!-- Sampul/Cover Buku Upload Field -->
                <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200 space-y-2">
                    <label class="block font-bold text-amber-900">Upload Sampul / Cover Buku (PNG, JPG, WEBP max 5MB)</label>
                    <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="w-full p-2 rounded border border-slate-300 bg-white text-xs">
                    <div class="text-[10px] text-slate-500 italic">Atau gunakan URL Gambar eksternal (opsional):</div>
                    <input type="text" name="cover_image" placeholder="https://domain.com/path-to-cover.jpg" class="w-full p-2 rounded border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Ringkas Buku</label>
                    <textarea name="description" rows="3" required class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" value="135000" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Stok Awal</label>
                        <input type="number" name="stock" value="100" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">ISBN</label>
                        <input type="text" name="isbn" placeholder="978-..." class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Penerbit</label>
                        <input type="text" name="publisher" value="Ruhiology Press" class="w-full p-2.5 rounded border border-slate-300">
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
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded shadow transition">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <h3 class="font-bold font-serif text-slate-900 text-base">Edit Buku Katalog</h3>
            <form :action="'{{ url('/admin/products') }}/' + activeBook.id" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Buku</label>
                    <input type="text" name="title" x-model="activeBook.title" required class="w-full p-2.5 rounded border border-slate-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="category_id" x-model="activeBook.category_id" required class="w-full p-2.5 rounded border border-slate-300">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Penulis</label>
                        <input type="text" name="author" x-model="activeBook.author" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>

                <!-- Sampul/Cover Buku Upload Field Edit -->
                <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200 space-y-2">
                    <label class="block font-bold text-amber-900">Ganti Sampul / Cover Buku (Opsional)</label>
                    <template x-if="activeBook.cover_image">
                        <div class="flex items-center gap-3">
                            <img :src="activeBook.cover_image" class="w-12 h-16 object-cover rounded border border-slate-300">
                            <span class="text-[10px] text-slate-500">Gambar saat ini terpasang</span>
                        </div>
                    </template>
                    <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="w-full p-2 rounded border border-slate-300 bg-white text-xs">
                    <div class="text-[10px] text-slate-500 italic">Atau sesuaikan URL Sampul:</div>
                    <input type="text" name="cover_image" x-model="activeBook.cover_image" class="w-full p-2 rounded border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Ringkas Buku</label>
                    <textarea name="description" rows="3" x-model="activeBook.description" required class="w-full p-2.5 rounded border border-slate-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" x-model="activeBook.price" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Stok</label>
                        <input type="number" name="stock" x-model="activeBook.stock" required class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">ISBN</label>
                        <input type="text" name="isbn" x-model="activeBook.isbn" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Penerbit</label>
                        <input type="text" name="publisher" x-model="activeBook.publisher" class="w-full p-2.5 rounded border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" x-model="activeBook.status" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border rounded font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded shadow transition">Perbarui Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
