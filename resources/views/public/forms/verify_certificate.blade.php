@extends('layouts.app')

@section('title', 'Verifikasi Keaslian Sertifikat — Ruhiology Institute')

@section('content')
<div class="min-h-screen bg-slate-100/70 py-12 md:py-20 px-4 sm:px-6 flex items-center justify-center">
    <div class="max-w-xl w-full">

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden text-center p-8 md:p-10 border-t-8 border-t-emerald-500">
            <!-- Verified Icon -->
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6 border border-emerald-100 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider mb-2">
                Dokumen Terverifikasi Resmi
            </span>

            <h1 class="text-2xl font-serif font-bold text-navy">
                Sertifikat Keabsahan Digital
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Data sertifikat ini tercatat resmi di pangkalan data Ruhiology Institute.
            </p>

            <!-- Verification Metadata Card -->
            <div class="mt-6 p-5 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-3.5 text-xs">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Nama Penerima</span>
                    <span class="font-bold text-slate-900 text-base font-serif">{{ $response->participant_name }}</span>
                    @if($response->participant_institution)
                        <span class="block text-slate-500 text-xs">{{ $response->participant_institution }}</span>
                    @endif
                </div>

                <div class="pt-2 border-t border-slate-200/80">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Nomor Sertifikat</span>
                    <span class="font-mono font-bold text-primary-700 text-sm">{{ $response->certificate_number ?: $response->response_code }}</span>
                </div>

                <div class="pt-2 border-t border-slate-200/80">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Kegiatan / Program Pelatihan</span>
                    <span class="font-semibold text-slate-800 text-xs block leading-snug">
                        {{ $form->certificate_event_name ?: $form->title }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200/80">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Bobot Pelatihan</span>
                        <span class="font-bold text-[#8C703E]">{{ $form->calculated_total_jp }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Tanggal Penetapan</span>
                        <span class="font-medium text-slate-700">{{ $form->certificate_event_date ?: ($response->submitted_at ? $response->submitted_at->format('d/m/Y') : now()->format('d/m/Y')) }}</span>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Penyelenggara:</span>
                    <span class="font-semibold text-navy">Ruhiology Institute</span>
                </div>
            </div>

            <!-- View Certificate Button -->
            @if($form->isCertificateReleased())
                <div class="mt-6">
                    <a href="{{ route('public.form.view_certificate', [$form->slug, $response->response_code]) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-navy hover:bg-navy-dark text-white font-bold text-xs shadow-md transition">
                        <span>Lihat Dokumen Sertifikat PDF (2 Halaman) ↗</span>
                    </a>
                </div>
            @endif

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-slate-600 transition font-medium">
                    ← Kembali ke Beranda Ruhiology
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
