@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Kelola Testimonial</h2>
            <p class="text-xs text-slate-500">Persetujuan pengulas dan testimonial pengalaman peserta.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Nama Pengulas</th>
                        <th class="p-4">Role / Peran</th>
                        <th class="p-4">Institusi</th>
                        <th class="p-4">Isi Testimonial</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($testimonials as $t)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 font-bold text-slate-900">{{ $t->name }}</td>
                            <td class="p-4">{{ $t->role }}</td>
                            <td class="p-4 text-slate-600">{{ $t->institution ?? '-' }}</td>
                            <td class="p-4 text-slate-700 max-w-xs truncate">{{ $t->testimonial }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $t->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $t->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.testimonials.update_status', $t->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $t->status === 'approved' ? 'pending' : 'approved' }}">
                                    <button type="submit" class="px-3 py-1 text-[10px] font-bold rounded {{ $t->status === 'approved' ? 'bg-rose-100 text-rose-800' : 'bg-emerald-600 text-white' }}">
                                        {{ $t->status === 'approved' ? 'Unapprove ✕' : 'Approve ✓' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
