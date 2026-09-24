@extends('layouts.app')

@section('content')
<div class="bg-navy-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Portal Peserta</span>
            <h1 class="text-3xl font-bold font-serif">Selamat Datang, {{ $user->name }}</h1>
            <p class="text-xs text-slate-300 mt-1">Email: {{ $user->email }} • Telepon: {{ $user->phone ?? '-' }}</p>
        </div>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Section 1: Active Assessment Periods -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="font-bold font-serif text-slate-900 text-base flex items-center gap-2">
                <span>📝</span> Asesmen Aktif Yang Dapat Diikuti
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($activePeriods as $p)
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-slate-900 text-sm">{{ $p->title }}</h3>
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded">Active</span>
                        </div>
                        <p class="text-xs text-slate-500">Program: {{ $p->program->name ?? '-' }} • Instrument: {{ $p->instrument->name }}</p>
                        <div class="pt-2 flex gap-2">
                            <a href="{{ route('assessment.index') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded shadow">
                                Ikuti Asesmen →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: Submissions History -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="font-bold font-serif text-slate-900 text-base flex items-center gap-2">
                <span>📊</span> Riwayat Assessment Saya
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-200">
                        <tr>
                            <th class="p-3">Kode Submission</th>
                            <th class="p-3">Periode</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3">Skor (%)</th>
                            <th class="p-3">Interpretasi</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($submissions as $sub)
                            <tr>
                                <td class="p-3 font-mono font-bold text-amber-700">{{ $sub->submission_code }}</td>
                                <td class="p-3 font-semibold text-slate-900">{{ $sub->period->title ?? '-' }}</td>
                                <td class="p-3"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded uppercase text-[10px]">{{ $sub->submission_type }}</span></td>
                                <td class="p-3 font-bold text-slate-900">{{ number_format($sub->result->percentage ?? 0, 1) }}%</td>
                                <td class="p-3 text-slate-600">{{ $sub->result->overall_interpretation ?? '-' }}</td>
                                <td class="p-3">
                                    <a href="{{ route('assessment.result', $sub->submission_code) }}" class="text-amber-700 font-bold hover:underline">
                                        Lihat Hasil ↗
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-slate-400 text-center">Belum ada riwayat pengerjaan assessment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 3: Training & Orders History -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold font-serif text-slate-900 text-sm">🎯 Riwayat Pelatihan</h3>
                <div class="space-y-3 text-xs">
                    @forelse($trainingRegistrations as $trg)
                        <div class="p-3 bg-slate-50 rounded border border-slate-200 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-slate-900 block">{{ $trg->training->title }}</span>
                                <span class="text-[10px] text-slate-500">No. Reg: {{ $trg->registration_number }}</span>
                            </div>
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded uppercase">
                                {{ $trg->registration_status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-400">Belum terdaftar pada program pelatihan.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold font-serif text-slate-900 text-sm">🛒 Riwayat Pesanan Buku</h3>
                <div class="space-y-3 text-xs">
                    @forelse($orders as $ord)
                        <div class="p-3 bg-slate-50 rounded border border-slate-200 flex justify-between items-center">
                            <div>
                                <span class="font-mono font-bold text-slate-900 block">{{ $ord->order_number }}</span>
                                <span class="text-[10px] text-slate-500">Total: Rp {{ number_format($ord->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('catalog.invoice', $ord->order_number) }}" class="text-amber-700 font-bold hover:underline">
                                Invoice ↗
                            </a>
                        </div>
                    @empty
                        <p class="text-slate-400">Belum ada riwayat pesanan buku.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
