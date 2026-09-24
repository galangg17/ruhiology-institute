@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold font-serif text-slate-900 tracking-tight">Kelola Pesanan Buku Press</h2>
            <p class="text-xs text-slate-500 mt-0.5">Daftar pesanan buku, status pembayaran, dan pengiriman invoice.</p>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor order / pemesan..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]/20 focus:border-[#0B2A43] focus:bg-white transition">
            </div>
            <div>
                <select name="payment_status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]/20 focus:border-[#0B2A43] focus:bg-white transition">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <select name="order_status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0B2A43]/20 focus:border-[#0B2A43] focus:bg-white transition">
                    <option value="">Semua Status Pesanan</option>
                    <option value="new" {{ request('order_status') === 'new' ? 'selected' : '' }}>Baru</option>
                    <option value="processing" {{ request('order_status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ request('order_status') === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="completed" {{ request('order_status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-5 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter Pesanan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[950px]">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200 text-[11px] tracking-wider font-mono">
                    <tr>
                        <th class="px-5 py-4 whitespace-nowrap">No. Order</th>
                        <th class="px-5 py-4 whitespace-nowrap">Pemesan</th>
                        <th class="px-5 py-4 whitespace-nowrap">Item Buku</th>
                        <th class="px-5 py-4 whitespace-nowrap">Total Bayar</th>
                        <th class="px-5 py-4 whitespace-nowrap">Status Bayar</th>
                        <th class="px-5 py-4 whitespace-nowrap">Status Pesanan</th>
                        <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 text-[11px] inline-block">
                                    {{ $ord->order_number }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <strong class="text-slate-900 font-bold block text-xs">{{ $ord->customer_name }}</strong>
                                <span class="text-slate-400 font-mono text-[10px] block mt-0.5">{{ $ord->customer_phone }} • {{ $ord->customer_email }}</span>
                            </td>
                            <td class="px-5 py-4 max-w-[250px]">
                                @foreach($ord->items as $it)
                                    <div class="text-slate-700 font-semibold truncate">• {{ $it->product_title }} (x{{ $it->quantity }})</div>
                                @endforeach
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-extrabold text-slate-900 text-xs">
                                Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.orders.update_status', $ord->id) }}" method="POST" class="inline-block">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="order_status" value="{{ $ord->order_status }}">
                                    <select name="payment_status" onchange="this.form.submit()" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase border cursor-pointer {{ $ord->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-amber-50 text-amber-800 border-amber-300' }}">
                                        <option value="pending" {{ $ord->payment_status === 'pending' ? 'selected' : '' }}>⏳ PENDING</option>
                                        <option value="paid" {{ $ord->payment_status === 'paid' ? 'selected' : '' }}>✅ PAID (LUNAS)</option>
                                        <option value="failed" {{ $ord->payment_status === 'failed' ? 'selected' : '' }}>❌ FAILED</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.orders.update_status', $ord->id) }}" method="POST" class="inline-block">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="payment_status" value="{{ $ord->payment_status }}">
                                    <select name="order_status" onchange="this.form.submit()" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-100 text-slate-800 border border-slate-300 cursor-pointer">
                                        <option value="new" {{ $ord->order_status === 'new' ? 'selected' : '' }}>🆕 BARU</option>
                                        <option value="processing" {{ $ord->order_status === 'processing' ? 'selected' : '' }}>⚙️ DIPROSES</option>
                                        <option value="shipped" {{ $ord->order_status === 'shipped' ? 'selected' : '' }}>🚚 DIKIRIM</option>
                                        <option value="completed" {{ $ord->order_status === 'completed' ? 'selected' : '' }}>🎉 SELESAI</option>
                                        <option value="cancelled" {{ $ord->order_status === 'cancelled' ? 'selected' : '' }}>🚫 BATAL</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-lg text-[11px] shadow-sm transition">
                                    <span>Detail / Invoice</span> <span>↗</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-xs">
                                Belum ada pesanan buku ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
