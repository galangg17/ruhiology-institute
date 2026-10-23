@extends('layouts.app')

@section('title', 'Invoice Pendaftaran: ' . $registration->registration_number . ' — Ruhiology Institute')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 md:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        <!-- Top breadcrumbs -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('training.show', $registration->training->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-navy transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Info Pelatihan
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 shadow-sm transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs md:text-sm font-medium flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs md:text-sm font-medium flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- MAIN INVOICE CONTAINER -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            
            <!-- Invoice Header Bar -->
            <div class="bg-navy p-6 md:p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6 border-b-4 border-gold">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-gold text-[11px] font-bold uppercase tracking-wider mb-2">
                        Ruhiology Training Center — Invoice Resmi
                    </span>
                    <h1 class="text-2xl md:text-3xl font-serif font-bold text-white">Bukti Pendaftaran Pelatihan</h1>
                    <p class="text-xs text-slate-300 mt-1">Harap simpan nomor registrasi ini sebagai identitas kepesertaan Anda.</p>
                </div>

                <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20" x-data="{ copied: false }">
                    <span class="text-[10px] uppercase font-bold text-slate-300 block tracking-wider">No. Registrasi</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-lg md:text-xl font-mono font-bold text-gold">{{ $registration->registration_number }}</span>
                        <button type="button" @click="navigator.clipboard.writeText('{{ $registration->registration_number }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition" title="Salin No. Registrasi">
                            <template x-if="!copied">
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </template>
                            <template x-if="copied">
                                <span class="text-[10px] text-emerald-400 font-bold">Tersalin!</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Workflow Status Progress Bar -->
            <div class="p-6 md:p-8 bg-slate-50 border-b border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Step 1 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-200">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 font-bold text-sm">
                            ✓
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Langkah 1</span>
                            <span class="text-xs font-bold text-slate-800">Registrasi Terkirim</span>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-white border {{ $registration->payment_status === 'verified' ? 'border-emerald-300 bg-emerald-50/30' : ($registration->payment_proof_path ? 'border-amber-300 bg-amber-50/30' : 'border-slate-200') }}">
                        <div class="w-9 h-9 rounded-xl {{ $registration->payment_status === 'verified' ? 'bg-emerald-100 text-emerald-600' : ($registration->payment_proof_path ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500') }} flex items-center justify-center shrink-0 font-bold text-sm">
                            {{ $registration->payment_status === 'verified' ? '✓' : '2' }}
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Langkah 2</span>
                            <span class="text-xs font-bold text-slate-800">
                                @if($registration->payment_status === 'verified')
                                    Pembayaran Lunas
                                @elseif($registration->payment_proof_path)
                                    Menunggu Verifikasi
                                @else
                                    Unggah Bukti Transfer
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-white border {{ $registration->registration_status === 'approved' || $registration->registration_status === 'completed' ? 'border-emerald-300 bg-emerald-50/30' : 'border-slate-200' }}">
                        <div class="w-9 h-9 rounded-xl {{ $registration->registration_status === 'approved' || $registration->registration_status === 'completed' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center shrink-0 font-bold text-sm">
                            {{ $registration->registration_status === 'approved' || $registration->registration_status === 'completed' ? '✓' : '3' }}
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Langkah 3</span>
                            <span class="text-xs font-bold text-slate-800">
                                @if($registration->registration_status === 'approved' || $registration->registration_status === 'completed')
                                    Kepesertaan Aktif
                                @else
                                    Penerbitan Akses / Kelas
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Program & Participant Details -->
            <div class="p-6 md:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Left: Program Info -->
                    <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-primary-700 bg-primary-50 px-2.5 py-1 rounded-full border border-primary-200 inline-block">
                            Program Pelatihan
                        </span>
                        <h3 class="text-base font-bold font-serif text-navy leading-snug">{{ $registration->training->title }}</h3>
                        
                        <div class="text-xs space-y-1.5 text-slate-600 border-t border-slate-200 pt-3">
                            <div><strong>Angkatan / Batch:</strong> {{ $registration->batch->batch_name }}</div>
                            <div><strong>Tanggal:</strong> {{ $registration->batch->start_date ? $registration->batch->start_date->format('d F Y') : '-' }} s/d {{ $registration->batch->end_date ? $registration->batch->end_date->format('d F Y') : '-' }}</div>
                            <div><strong>Format:</strong> {{ $registration->training->is_online ? '🌐 Online (Zoom Meeting)' : '🏛️ Tatap Muka (Offline: ' . $registration->training->location . ')' }}</div>
                            <div><strong>Trainer:</strong> {{ $registration->training->trainer }}</div>
                        </div>
                    </div>

                    <!-- Right: Participant Info -->
                    <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-white px-2.5 py-1 rounded-full border border-slate-200 inline-block">
                            Data Peserta
                        </span>
                        <h3 class="text-base font-bold text-slate-800">{{ $registration->participant_name }}</h3>
                        
                        <div class="text-xs space-y-1.5 text-slate-600 border-t border-slate-200 pt-3">
                            <div><strong>Email:</strong> {{ $registration->email }}</div>
                            <div><strong>WhatsApp / HP:</strong> {{ $registration->phone }}</div>
                            <div><strong>Institusi / Lembaga:</strong> {{ $registration->institution ?? '-' }}</div>
                            <div><strong>Waktu Daftar:</strong> {{ $registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-' }} WIB</div>
                        </div>
                    </div>
                </div>

                <!-- Price & Billing Summary -->
                <div class="p-6 rounded-2xl bg-amber-50/60 border border-amber-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs uppercase font-bold text-amber-800 block">Total Investasi Pelatihan</span>
                        <span class="text-2xl md:text-3xl font-extrabold text-navy">
                            Rp {{ number_format($registration->price, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $registration->payment_status === 'verified' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                            Status: {{ strtoupper($registration->payment_status) }}
                        </span>
                    </div>
                </div>

                <!-- Payment Instructions -->
                @if($registration->payment_status !== 'verified')
                    <div class="border-t border-slate-200 pt-6 space-y-4">
                        <h3 class="text-sm font-bold font-serif text-slate-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Instruksi Pembayaran & Transfer Bank Resmi
                        </h3>
                        <p class="text-xs text-slate-500">
                            Silakan lakukan transfer sebesar nominal investasi di atas ke salah satu rekening resmi Ruhiology Institute:
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <!-- Bank BCA -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-1.5" x-data="{ copied: false }">
                                <div class="font-bold text-slate-800 flex items-center justify-between">
                                    <span>Bank Central Asia (BCA)</span>
                                    <span class="text-[10px] text-slate-400">Atas Nama: Yayasan Ruhiology</span>
                                </div>
                                <div class="flex items-center justify-between font-mono font-bold text-sm text-navy bg-slate-50 p-2 rounded-lg">
                                    <span>8831 2940 18</span>
                                    <button type="button" @click="navigator.clipboard.writeText('8831294018'); copied = true; setTimeout(() => copied = false, 2000)" class="text-primary-600 hover:text-primary-700 text-xs font-sans">
                                        <span x-show="!copied">Salin</span>
                                        <span x-show="copied" class="text-emerald-600 font-bold">Tersalin!</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Bank Syariah Indonesia (BSI) -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-1.5" x-data="{ copied: false }">
                                <div class="font-bold text-slate-800 flex items-center justify-between">
                                    <span>Bank Syariah Indonesia (BSI)</span>
                                    <span class="text-[10px] text-slate-400">Atas Nama: Ruhiology Institute</span>
                                </div>
                                <div class="flex items-center justify-between font-mono font-bold text-sm text-navy bg-slate-50 p-2 rounded-lg">
                                    <span>7219 0451 92</span>
                                    <button type="button" @click="navigator.clipboard.writeText('7219045192'); copied = true; setTimeout(() => copied = false, 2000)" class="text-primary-600 hover:text-primary-700 text-xs font-sans">
                                        <span x-show="!copied">Salin</span>
                                        <span x-show="copied" class="text-emerald-600 font-bold">Tersalin!</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Proof Section -->
                        <div class="mt-6 p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unggah Bukti Transfer / Kwitansi Pembayaran
                            </h4>

                            @if($registration->payment_proof_path)
                                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between text-xs text-emerald-800">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span>Bukti transfer telah tersimpan. Anda dapat mengunggah ulang jika ingin memperbarui.</span>
                                    </div>
                                    <a href="{{ $registration->payment_proof_path }}" target="_blank" class="font-bold underline text-emerald-900">Lihat Berkas ↗</a>
                                </div>
                            @endif

                            <form action="{{ route('training.invoice.upload_proof', $registration->registration_number) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                @csrf
                                <input type="file" name="payment_proof" required accept=".jpg,.jpeg,.png,.pdf"
                                       class="flex-1 text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer border border-slate-300 rounded-xl p-1.5 bg-white">
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-navy hover:bg-navy-dark text-white text-xs font-bold transition shadow-sm shrink-0">
                                    Unggah Bukti
                                </button>
                            </form>
                            <p class="text-[10px] text-slate-400">Format yang didukung: JPG, PNG, atau PDF (maksimal 5 MB).</p>
                        </div>
                    </div>
                @endif

                <!-- WhatsApp Confirmation CTA Button -->
                <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ $registration->whatsapp_confirmation_url }}" target="_blank"
                       class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs md:text-sm shadow-lg shadow-emerald-200 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Konfirmasi Pembayaran via WhatsApp
                    </a>

                    <div class="text-xs text-slate-500 text-center sm:text-right">
                        Butuh bantuan teknis? Hubungi helpdesk kami di <span class="font-bold text-navy">support@ruhiology.com</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
