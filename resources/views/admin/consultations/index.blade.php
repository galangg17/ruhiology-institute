@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Permintaan Konsultasi</h2>
            <p class="text-xs text-slate-500">Daftar pemohon audiensi dan konsultasi ruhiologi personal/institusi.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">No. Referensi</th>
                        <th class="p-4">Pemohon</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Jenis Konsultasi</th>
                        <th class="p-4">Rencana Jadwal</th>
                        <th class="p-4">Pesan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($consultations as $c)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-mono font-bold text-amber-700">{{ $c->consultation_number }}</td>
                            <td class="p-4">
                                <strong class="text-slate-900 block">{{ $c->name }}</strong>
                                <span class="text-slate-400 text-[10px]">{{ $c->institution ?? 'Personal' }}</span>
                            </td>
                            <td class="p-4">{{ $c->email }} <br><span class="text-slate-400 text-[10px]">{{ $c->phone }}</span></td>
                            <td class="p-4 font-bold text-slate-800">{{ $c->consultation_type }}</td>
                            <td class="p-4 text-slate-600">{{ $c->preferred_date->format('d/m/Y') }} <br><span class="text-[10px] text-slate-400">{{ $c->preferred_time }}</span></td>
                            <td class="p-4 text-slate-600 max-w-xs truncate">{{ $c->message }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $c->status === 'new' ? 'bg-rose-100 text-rose-800' : ($c->status === 'scheduled' ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    {{ $c->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.consultations.update_status', $c->id) }}" method="POST" class="flex gap-1 justify-end">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="p-1 border rounded text-[10px]">
                                        <option value="new" {{ $c->status === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="contacted" {{ $c->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="scheduled" {{ $c->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="completed" {{ $c->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $c->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $consultations->links() }}
        </div>
    </div>
</div>
@endsection
