@extends('layouts.admin')

@section('title', 'Data Respon: ' . $form->title)

@section('content')
<div x-data="{
    detailModal: false,
    selectedResponse: null,
    viewDetail(resp) {
        this.selectedResponse = resp;
        this.detailModal = true;
    }
}" class="space-y-6">

    <!-- Top Navigation & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">
                <a href="{{ route('admin.forms.index') }}" class="hover:text-primary-600 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Semua Formulir
                </a>
                <span>/</span>
                <span class="text-slate-600">Respon Masuk</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-slate-800">{{ $form->title }}</h1>
            <div class="flex flex-wrap items-center gap-2 mt-2 text-xs">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-semibold {{ $form->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($form->status === 'draft' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $form->status === 'published' ? 'bg-emerald-500' : ($form->status === 'draft' ? 'bg-amber-500' : 'bg-rose-500') }} mr-1.5"></span>
                    {{ ucfirst($form->status) }}
                </span>
                <span class="text-slate-500">Total: <strong>{{ number_format($form->responses()->count()) }}</strong> jawaban terkumpul</span>
                @if($form->closes_at)
                    <span class="text-slate-400">&bull;</span>
                    <span class="text-slate-500">Batas: {{ $form->closes_at->translatedFormat('d F Y H:i') }}</span>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('public.form.show', $form->slug) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-medium text-xs hover:bg-slate-50 transition shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Buka Link Publik
            </a>
            <a href="{{ route('admin.forms.export_csv', $form->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm shadow-emerald-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel / CSV
            </a>
            <a href="{{ route('admin.forms.edit', $form->id) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-medium text-xs transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Formulir
            </a>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.forms.responses', $form->id) }}" class="flex-1 flex items-center gap-2 max-w-md">
            <div class="relative w-full">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode respon atau isi jawaban..." 
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-xs font-medium rounded-lg hover:bg-slate-900 transition">
                Cari
            </button>
            @if(request('q'))
                <a href="{{ route('admin.forms.responses', $form->id) }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800">Reset</a>
            @endif
        </form>
        <div class="text-xs text-slate-500">
            Menampilkan {{ $responses->firstItem() ?? 0 }}-{{ $responses->lastItem() ?? 0 }} dari {{ $responses->total() }} respon
        </div>
    </div>

    <!-- Table of Responses -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4 min-w-[140px]">Kode Respon</th>
                        <th class="py-3 px-4 min-w-[140px]">Waktu Pengisian</th>
                        @foreach($form->fields->take(4) as $field)
                            <th class="py-3 px-4 min-w-[160px]">{{ Str::limit($field->label, 28) }}</th>
                        @endforeach
                        <th class="py-3 px-4 text-right min-w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($responses as $index => $r)
                        @php
                            $ansMap = $r->answers->keyBy('field_id');
                            // Prepare data for JSON modal
                            $modalData = [
                                'code' => $r->response_code,
                                'time' => $r->submitted_at ? $r->submitted_at->format('d F Y, H:i:s') : '-',
                                'ip' => $r->ip_address ?? '-',
                                'user_agent' => $r->user_agent ?? '-',
                                'answers' => []
                            ];
                            foreach($form->fields as $fld) {
                                $a = $ansMap->get($fld->id);
                                $modalData['answers'][] = [
                                    'label' => $fld->label,
                                    'type' => $fld->field_type,
                                    'value' => $a ? $a->formatted_answer : '-',
                                    'raw' => $a ? $a->answer_value : null,
                                ];
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition group">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                {{ $responses->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-semibold text-primary-700 bg-primary-50 px-2 py-0.5 rounded border border-primary-100">
                                    {{ $r->response_code }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                <div>{{ $r->submitted_at ? $r->submitted_at->format('d M Y') : '-' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $r->submitted_at ? $r->submitted_at->format('H:i') . ' WIB' : '' }}</div>
                            </td>
                            @foreach($form->fields->take(4) as $field)
                                @php
                                    $ans = $ansMap->get($field->id);
                                    $val = $ans ? $ans->formatted_answer : '-';
                                @endphp
                                <td class="py-3.5 px-4 text-slate-700">
                                    @if($field->field_type === 'file' && $ans && $ans->answer_value)
                                        <a href="{{ $ans->answer_value }}" target="_blank" class="inline-flex items-center gap-1 text-primary-600 hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            Lihat File
                                        </a>
                                    @else
                                        <span title="{{ $val }}">{{ Str::limit($val, 32) }}</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click='viewDetail(@json($modalData))'
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-primary-50 hover:text-primary-700 text-slate-600 font-medium text-[11px] transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Detail
                                    </button>
                                    <form method="POST" action="{{ route('admin.forms.responses.destroy', $r->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus respon {{ $r->response_code }} ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Respon">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 4 + min(4, count($form->fields)) }}" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="font-medium text-slate-600">Belum ada respon yang masuk</p>
                                <p class="text-xs text-slate-400 mt-1">Bagikan link formulir kepada audiens atau target responden Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($responses->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $responses->links() }}
            </div>
        @endif
    </div>

    <!-- Response Detail Modal (Alpine.js) -->
    <div x-show="detailModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="detailModal = false" class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-slate-800 text-base">Detail Respon Pengisian</h3>
                        <p class="text-xs text-slate-500 font-mono" x-text="selectedResponse ? '#' + selectedResponse.code : ''"></p>
                    </div>
                </div>
                <button type="button" @click="detailModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body (All answers) -->
            <div class="p-6 overflow-y-auto space-y-4 flex-1">
                <!-- Meta Info -->
                <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Waktu Submit</span>
                        <span class="font-medium text-slate-700" x-text="selectedResponse ? selectedResponse.time : '-'"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">IP Address</span>
                        <span class="font-medium font-mono text-slate-700" x-text="selectedResponse ? selectedResponse.ip : '-'"></span>
                    </div>
                </div>

                <!-- Questions & Answers List -->
                <div class="space-y-3">
                    <template x-for="(ans, idx) in (selectedResponse ? selectedResponse.answers : [])" :key="idx">
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition">
                            <span class="text-[11px] font-bold text-slate-400 block uppercase mb-1" x-text="(idx + 1) + '. ' + ans.label"></span>
                            
                            <!-- File answer -->
                            <template x-if="ans.type === 'file' && ans.raw">
                                <div class="mt-1">
                                    <a :href="ans.raw" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-50 text-primary-700 text-xs font-semibold hover:bg-primary-100 transition border border-primary-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Buka / Unduh Berkas
                                    </a>
                                </div>
                            </template>

                            <!-- Text/Other answer -->
                            <template x-if="ans.type !== 'file' || !ans.raw">
                                <p class="text-sm text-slate-800 font-medium whitespace-pre-wrap break-words" x-text="ans.value || '-'"></p>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 flex justify-end">
                <button type="button" @click="detailModal = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
