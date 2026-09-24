@extends('layouts.app')

@section('content')
<div class="py-12 bg-slate-100 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex justify-between items-center">
            <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">← Kembali ke Katalog</a>
            <button onclick="window.print()" class="px-4 py-2 bg-navy-900 text-white font-bold text-xs rounded shadow hover:bg-navy-800">
                🖨️ Cetak Invoice Pesanan
            </button>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-lg text-xs space-y-6">
            
            <!-- Invoice Header -->
            <div class="flex justify-between items-start border-b border-slate-200 pb-6">
                <div>
                    <h2 class="text-xl font-bold font-serif text-slate-900">RUHIOLOGY INSTITUTE PRESS</h2>
                    <p class="text-slate-500 text-[11px]">Invoice Pemesanan Buku Resmi</p>
                    <p class="text-slate-500 text-[11px]">{{ \App\Models\Setting::get('address') }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-amber-700 font-mono block">INVOICE</span>
                    <span class="font-bold text-slate-900 text-sm font-mono">{{ $order->order_number }}</span>
                    <span class="block text-slate-400 text-[10px]">Tanggal: {{ $order->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <!-- Customer & Status Grid -->
            <div class="grid grid-cols-2 gap-6 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div>
                    <span class="text-slate-400 uppercase font-bold text-[10px] block">Tujuan Pengiriman</span>
                    <strong class="text-slate-900 block text-xs">{{ $order->customer_name }}</strong>
                    <span class="text-slate-600 block">{{ $order->customer_phone }} • {{ $order->customer_email }}</span>
                    <p class="text-slate-500 mt-1 leading-relaxed">{{ $order->shipping_address }}</p>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-bold text-[10px] block">Status Pembayaran & Pesanan</span>
                    <span class="inline-block px-2.5 py-1 bg-amber-100 text-amber-800 font-bold rounded uppercase text-[10px] mb-1">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                    <span class="block text-slate-500 text-[11px]">Status Pengiriman: <strong class="text-slate-900">{{ strtoupper($order->order_status) }}</strong></span>
                </div>
            </div>

            <!-- Items Table -->
            <div>
                <h4 class="font-bold text-slate-900 mb-3">Item Pesanan</h4>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-[10px] font-bold border-b border-slate-200">
                            <th class="p-3">Judul Buku</th>
                            <th class="p-3 text-center">Qty</th>
                            <th class="p-3 text-right">Harga Satuan</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="p-3 font-semibold text-slate-900">{{ $item->product_title }}</td>
                                <td class="p-3 text-center">{{ $item->quantity }}</td>
                                <td class="p-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-right font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-slate-200">
                        <tr>
                            <td colspan="3" class="p-2 text-right text-slate-500">Subtotal:</td>
                            <td class="p-2 text-right font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="p-2 text-right text-slate-500">Ongkos Kirim:</td>
                            <td class="p-2 text-right font-semibold">Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="text-sm font-bold text-slate-900">
                            <td colspan="3" class="p-3 text-right">Total Pembayaran:</td>
                            <td class="p-3 text-right text-amber-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Instructions Box -->
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 space-y-1">
                <h4 class="font-bold text-xs uppercase tracking-wider">Petunjuk Transfer Pembayaran:</h4>
                <p>Bank Transfer: <strong>{{ \App\Models\Setting::get('payment_bank_name', 'Bank Mandiri') }}</strong></p>
                <p>Nomor Rekening: <strong class="font-mono text-sm">{{ \App\Models\Setting::get('payment_account_number', '110-00-1988273-1') }}</strong></p>
                <p>Atas Nama: <strong>{{ \App\Models\Setting::get('payment_account_holder', 'RUHIOLOGY INSTITUTE INDONESIA') }}</strong></p>
                <p class="text-[11px] text-amber-800 pt-1">Harap cantumkan Nomor Invoice ({{ $order->order_number }}) pada berita transfer.</p>
            </div>

        </div>
    </div>
</div>
@endsection
