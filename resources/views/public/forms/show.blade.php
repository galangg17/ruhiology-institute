@extends('layouts.app')

@section('title', $form->title . ' — Ruhiology Institute')

@section('content')
<div class="min-h-screen bg-slate-100/70 py-8 md:py-14 px-4 sm:px-6">
    <div class="max-w-2xl mx-auto space-y-6">

        @if($isClosed)
            <!-- CLOSED FORM NOTICE -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden text-center p-8 md:p-12">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h1 class="text-2xl font-serif font-bold text-slate-800">{{ $form->title }}</h1>
                <p class="text-rose-600 font-semibold text-sm mt-3">Formulir Ini Sudah Ditutup</p>
                <p class="text-slate-500 text-xs md:text-sm mt-2 max-w-md mx-auto leading-relaxed">
                    Mohon maaf, pengisian data atau kuisioner ini sudah tidak menerima respon baru. Jika ada pertanyaan lebih lanjut, silakan hubungi tim penyelenggara Ruhiology Institute.
                </p>
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-navy text-white text-xs font-semibold hover:bg-navy-dark transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

        @elseif($requiresPasscode && !$passcodeVerified)
            <!-- PASSCODE ENTRY CARD -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden p-8 md:p-10">
                <div class="text-center max-w-md mx-auto">
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mx-auto mb-4 border border-primary-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h2 class="text-xl font-serif font-bold text-slate-800">{{ $form->title }}</h2>
                    <p class="text-xs md:text-sm text-slate-500 mt-2">
                        Formulir ini dilindungi kode akses. Masukkan kode akses resmi yang diberikan oleh pihak penyelenggara untuk mulai mengisi.
                    </p>

                    @if(session('passcode_error'))
                        <div class="mt-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                            {{ session('passcode_error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('public.form.show', $form->slug) }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <input type="text" name="access_code" required autofocus placeholder="Ketik Kode Akses (Contoh: RUH-2026)" 
                                   class="w-full text-center tracking-wider uppercase font-mono font-bold text-base py-3 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-navy hover:bg-navy-dark text-white font-semibold text-sm transition shadow-md">
                            Buka Formulir
                        </button>
                    </form>
                </div>
            </div>

        @else
            <!-- MAIN SUBMISSION FORM -->
            <form method="POST" action="{{ route('public.form.submit', $form->slug) }}" enctype="multipart/form-data" class="space-y-5" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                <!-- Form Header Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden relative border-t-8 border-t-navy">
                    @if($form->header_image)
                        <div class="h-44 md:h-56 w-full overflow-hidden bg-slate-100">
                            <img src="{{ $form->header_image }}" alt="{{ $form->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary-50 text-primary-800 border border-primary-200">
                                Ruhiology Formulir
                            </span>
                            @if($form->closes_at)
                                <span class="text-xs text-slate-400">
                                    Batas: {{ $form->closes_at->translatedFormat('d F Y, H:i') }}
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl md:text-3xl font-serif font-bold text-navy leading-snug">
                            {{ $form->title }}
                        </h1>

                        @if($form->description)
                            <div class="mt-4 text-xs md:text-sm text-slate-600 leading-relaxed whitespace-pre-line border-t border-slate-100 pt-4">
                                {{ $form->description }}
                            </div>
                        @endif

                        <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-rose-500 font-medium">
                            * Wajib diisi
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-xs text-rose-800">
                        <div class="font-bold flex items-center gap-1.5 mb-1 text-rose-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Mohon periksa kembali isian formulir:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Dynamic Form Fields -->
                @foreach($form->fields as $index => $field)
                    @php
                        $inputName = 'field_' . $field->id;
                        $oldVal = old($inputName);
                    @endphp
                    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-200/90 transition hover:border-slate-300">
                        <label for="{{ $inputName }}" class="block text-sm font-semibold text-slate-800 mb-1">
                            {{ $field->label }}
                            @if($field->is_required)
                                <span class="text-rose-500 font-bold ml-0.5">*</span>
                            @endif
                        </label>

                        @if($field->description)
                            <p class="text-xs text-slate-500 mb-3 leading-relaxed">{{ $field->description }}</p>
                        @endif

                        <!-- Field Type: TEXT -->
                        @if($field->field_type === 'text')
                            <input type="text" name="{{ $inputName }}" id="{{ $inputName }}" value="{{ $oldVal }}"
                                   placeholder="{{ $field->placeholder ?? 'Ketik jawaban Anda...' }}"
                                   @if($field->is_required) required @endif
                                   class="w-full text-xs md:text-sm px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">

                        <!-- Field Type: TEXTAREA -->
                        @elseif($field->field_type === 'textarea')
                            <textarea name="{{ $inputName }}" id="{{ $inputName }}" rows="4"
                                      placeholder="{{ $field->placeholder ?? 'Ketik penjelasan lengkap...' }}"
                                      @if($field->is_required) required @endif
                                      class="w-full text-xs md:text-sm px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">{{ $oldVal }}</textarea>

                        <!-- Field Type: NUMBER -->
                        @elseif($field->field_type === 'number')
                            <input type="number" name="{{ $inputName }}" id="{{ $inputName }}" value="{{ $oldVal }}"
                                   placeholder="{{ $field->placeholder ?? '0' }}"
                                   @if($field->is_required) required @endif
                                   class="w-full text-xs md:text-sm px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">

                        <!-- Field Type: DATE -->
                        @elseif($field->field_type === 'date')
                            <input type="date" name="{{ $inputName }}" id="{{ $inputName }}" value="{{ $oldVal }}"
                                   @if($field->is_required) required @endif
                                   class="w-full text-xs md:text-sm px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">

                        <!-- Field Type: DROPDOWN -->
                        @elseif($field->field_type === 'dropdown')
                            <select name="{{ $inputName }}" id="{{ $inputName }}"
                                    @if($field->is_required) required @endif
                                    class="w-full text-xs md:text-sm px-4 py-2.5 rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                                <option value="">-- Pilih salah satu --</option>
                                @foreach($field->options ?? [] as $option)
                                    <option value="{{ $option }}" {{ $oldVal == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>

                        <!-- Field Type: RADIO -->
                        @elseif($field->field_type === 'radio')
                            <div class="space-y-2.5 pt-1">
                                @foreach($field->options ?? [] as $optIdx => $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                                        <input type="radio" name="{{ $inputName }}" value="{{ $option }}"
                                               {{ $oldVal == $option ? 'checked' : '' }}
                                               @if($field->is_required) required @endif
                                               class="w-4 h-4 text-primary-600 focus:ring-primary-500 border-slate-300">
                                        <span class="text-xs md:text-sm text-slate-700 font-medium">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>

                        <!-- Field Type: CHECKBOX -->
                        @elseif($field->field_type === 'checkbox')
                            <div class="space-y-2.5 pt-1">
                                @php
                                    $oldArr = is_array($oldVal) ? $oldVal : [];
                                @endphp
                                @foreach($field->options ?? [] as $optIdx => $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                                        <input type="checkbox" name="{{ $inputName }}[]" value="{{ $option }}"
                                               {{ in_array($option, $oldArr) ? 'checked' : '' }}
                                               class="w-4 h-4 text-primary-600 focus:ring-primary-500 rounded border-slate-300">
                                        <span class="text-xs md:text-sm text-slate-700 font-medium">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>

                        <!-- Field Type: FILE -->
                        @elseif($field->field_type === 'file')
                            <div class="mt-1">
                                <input type="file" name="{{ $inputName }}" id="{{ $inputName }}"
                                       @if($field->is_required) required @endif
                                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer border border-slate-200 rounded-xl p-2 bg-slate-50/50">
                                <p class="text-[11px] text-slate-400 mt-1.5">Format file yang diperbolehkan: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP (Maksimal 10MB)</p>
                            </div>
                        @endif

                        @error($inputName)
                            <p class="text-[11px] text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <!-- Submit Button Bar -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <button type="submit" 
                            :disabled="submitting"
                            class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-navy hover:bg-navy-dark text-white font-bold text-sm shadow-lg shadow-navy/20 transition flex items-center justify-center gap-2">
                        <template x-if="!submitting">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Kirim Jawaban
                            </span>
                        </template>
                        <template x-if="submitting">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Menyimpan Respon...
                            </span>
                        </template>
                    </button>

                    <div class="text-[11px] text-slate-400 text-center sm:text-right">
                        <span>Ruhiology Secure Forms Engine</span>
                    </div>
                </div>

            </form>
        @endif

    </div>
</div>
@endsection
