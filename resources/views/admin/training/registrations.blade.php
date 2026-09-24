@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Verifikasi Pendaftaran Training</h2>
            <p class="text-xs text-slate-500">Verifikasi status pembayaran dan kelayakan pendaftar pelatihan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">No. Registrasi</th>
                        <th class="p-4">Nama Pendaftar</th>
                        <th class="p-4">Email & WA</th>
                        <th class="p-4">Training & Batch</th>
                        <th class="p-4">Investasi</th>
                        <th class="p-4">Status Bayar</th>
                        <th class="p-4">Status Reg</th>
                        <th class="p-4 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($registrations as $reg)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-mono font-bold text-amber-700">{{ $reg->registration_number }}</td>
                            <td class="p-4">
                                <strong class="text-slate-900 block">{{ $reg->participant_name }}</strong>
                                <span class="text-slate-500 text-[10px]">{{ $reg->institution ?? '-' }}</span>
                            </td>
                            <td class="p-4">{{ $reg->email }} <br><span class="text-slate-400 text-[10px]">{{ $reg->phone }}</span></td>
                            <td class="p-4">
                                <span class="font-bold text-slate-800 block">{{ $reg->training->title ?? '-' }}</span>
                                <span class="text-slate-500 text-[10px] block">{{ $reg->batch->batch_name ?? '-' }}</span>
                            </td>
                            <td class="p-4 font-bold text-slate-900">Rp {{ number_format($reg->price, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $reg->payment_status === 'verified' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $reg->payment_status }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $reg->registration_status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($reg->registration_status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $reg->registration_status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.training.registrations.update', $reg->id) }}" method="POST" class="flex gap-1 justify-end">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="payment_status" value="verified">
                                    <input type="hidden" name="registration_status" value="approved">
                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 text-white font-bold text-[10px] rounded hover:bg-emerald-700">
                                        Approve ✓
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $registrations->links() }}
        </div>
    </div>
</div>
@endsection
