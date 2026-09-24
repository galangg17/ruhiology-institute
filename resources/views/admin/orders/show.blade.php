@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Detail Pesanan Buku</h2>
            <p class="text-xs text-slate-500">Order Number: <span class="font-mono text-amber-700 font-bold">{{ $order->order_number }}</span></p>
        </div>
        <a href="{{ route('catalog.invoice', $order->order_number) }}" target="_blank" class="px-4 py-2 bg-slate-800 text-white font-bold text-xs rounded hover:bg-slate-700">
            🖨️ Cetak Invoice ↗
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Order Details -->
        <div class="lg:col-span-8 space-y-6 text-xs">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 text-sm font-serif border-b border-slate-100 pb-2">Informasi Pemesan & Pengiriman</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 uppercase font-bold text-[10px] block">Nama Pembeli</span>
                        <strong class="text-slate-900 text-xs">{{ $order->customer_name }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 uppercase font-bold text-[10px] block">Kontak HP / Email</span>
                        <span class="text-slate-800">{{ $order->customer_phone }} • {{ $order->customer_email }}</span>
                    </div>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-bold text-[10px] block">Alamat Pengiriman Lengkap</span>
                    <p class="text-slate-700 bg-slate-50 p-3 rounded border border-slate-200 leading-relaxed mt-1">{{ $order->shipping_address }}</p>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 text-sm font-serif border-b border-slate-100 pb-2">Daftar Buku Yang Dipesan</h3>
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="p-3">Judul Buku</th>
                            <th class="p-3 text-center">Qty</th>
                            <th class="p-3 text-right">Harga</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="p-3 font-bold text-slate-900">{{ $item->product_title }}</td>
                                <td class="p-3 text-center font-bold">{{ $item->quantity }}</td>
                                <td class="p-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-right font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Update Status Form -->
        <div class="lg:col-span-4">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-lg space-y-4 text-xs">
                <h3 class="font-bold text-slate-900 text-sm font-serif border-b border-slate-100 pb-2">Update Status & Verifikasi</h3>

                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Pembayaran</label>
                        <select name="payment_status" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending (Belum Diverifikasi)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>PAID (Lunas Diverifikasi)</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed (Gagal)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Pesanan Pengiriman</label>
                        <select name="order_status" class="w-full p-2.5 rounded border border-slate-300">
                            <option value="new" {{ $order->order_status === 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Diproses (Packing)</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan Admin</label>
                        <textarea name="notes" rows="3" class="w-full p-2.5 rounded border border-slate-300" placeholder="Nomor Resi / Catatan internal">{{ $order->notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded shadow transition">
                        Simpan Perubahan Status →
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
