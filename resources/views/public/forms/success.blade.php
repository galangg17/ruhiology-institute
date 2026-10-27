@extends('layouts.app')

@section('title', 'Tanggapan Berhasil Dikirim — ' . $form->title)

@section('content')
<div class="min-h-screen bg-slate-100/70 py-12 md:py-20 px-4 sm:px-6 flex items-center justify-center">
    <div class="max-w-xl w-full">

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden text-center p-8 md:p-12 border-t-8 border-t-emerald-500">
            <!-- Success Icon -->
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6 border border-emerald-100 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <h1 class="text-2xl md:text-3xl font-serif font-bold text-navy">
                Terima Kasih!
            </h1>
            <p class="text-xs uppercase font-bold tracking-wider text-emerald-600 mt-1">
                Tanggapan Anda Telah Berhasil Terkirim
            </p>

            <!-- Custom or Default Success Message -->
            <div class="mt-4 text-xs md:text-sm text-slate-600 leading-relaxed max-w-md mx-auto">
                {!! $form->formatted_success_message !!}
            </div>

            <!-- Response Code Card -->
            <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 max-w-sm mx-auto text-left" 
                 x-data="{ copied: false }">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Kode Bukti Respon</span>
                        <span class="font-mono text-base font-bold text-navy">{{ $response->response_code }}</span>
                    </div>
                    <button type="button" 
                            @click="navigator.clipboard.writeText('{{ $response->response_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition flex items-center gap-1 shadow-sm">
                        <template x-if="!copied">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                Salin
                            </span>
                        </template>
                        <template x-if="copied">
                            <span class="text-emerald-600 font-bold">Tersalin!</span>
                        </template>
                    </button>
                </div>
                <div class="text-[10px] text-slate-400 mt-2 pt-2 border-t border-slate-200 flex justify-between">
                    <span>Waktu: {{ $response->submitted_at ? $response->submitted_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }} WIB</span>
                    <span>Tersimpan Resmi</span>
                </div>
            </div>

            <!-- Event Certificate Card (if enabled) -->
            @if($form->has_certificate)
                <div class="mt-6 p-5 rounded-2xl border text-left max-w-sm mx-auto {{ $form->isCertificateReleased() ? 'bg-emerald-50/80 border-emerald-200' : 'bg-amber-50/80 border-amber-200' }}">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl shrink-0">{{ $form->isCertificateReleased() ? '🎓' : '🔒' }}</span>
                        <div class="space-y-1 flex-1">
                            <h4 class="font-bold text-xs {{ $form->isCertificateReleased() ? 'text-emerald-950' : 'text-amber-950' }}">
                                {{ $form->isCertificateReleased() ? 'Sertifikat Resmi Telah Terbit' : 'Sertifikat Resmi Ber-JP' }}
                            </h4>
                            <p class="text-[11px] leading-relaxed {{ $form->isCertificateReleased() ? 'text-emerald-800' : 'text-amber-800' }}">
                                @if($form->isCertificateReleased())
                                    Sertifikat dengan bobot <strong>{{ $form->calculated_total_jp }}</strong> siap diunduh.
                                @else
                                    Sertifikat dengan bobot <strong>{{ $form->calculated_total_jp }}</strong> akan dapat diunduh setelah sesi kegiatan selesai ditutup.
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t {{ $form->isCertificateReleased() ? 'border-emerald-200' : 'border-amber-200' }}">
                        @if($form->isCertificateReleased())
                            <a href="{{ route('public.form.view_certificate', [$form->slug, $response->response_code]) }}" target="_blank"
                               class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs text-center flex items-center justify-center gap-1.5 shadow-sm transition">
                                <span>Unduh Sertifikat PDF ↗</span>
                            </a>
                        @else
                            <a href="{{ route('public.form.claim_certificate', $form->slug) }}"
                               class="w-full py-2 px-3 rounded-xl bg-white border border-amber-300 text-amber-900 font-semibold text-[11px] text-center block hover:bg-amber-50 transition">
                                Buka Halaman Klaim Sertifikat ↗
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-center gap-3">
                @if($form->isAcceptingResponses())
                    <a href="{{ route('public.form.show', $form->slug) }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                        Kirim Tanggapan Lain
                    </a>
                @endif
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-navy hover:bg-navy-dark text-white text-xs font-semibold transition shadow-sm">
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
