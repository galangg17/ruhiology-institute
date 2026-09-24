@extends('layouts.app')

@section('content')
<div x-data="rqTakeAssessment()" class="bg-[#F8F6F0] min-h-screen">
    
    <!-- Mobile-First Sticky Header Banner -->
    <div class="bg-[#0B2A43] text-white py-3 sm:py-6 border-b border-slate-800 sticky top-0 sm:top-[82px] z-30 shadow-md">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-row items-center justify-between gap-3">
            <div class="space-y-0.5 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-[9px] sm:text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest bg-white/10 px-2 py-0.5 rounded-full border border-white/20">
                        {{ strtoupper($type) }}
                    </span>
                    <span class="text-[10px] sm:text-xs text-slate-300 font-mono truncate" x-text="'Kode: ' + '{{ $participant->assessment_code ?? $participant->participant_code }}'"></span>
                </div>
                <h1 class="text-xs sm:text-xl font-serif font-bold text-white truncate">{{ $period->title }}</h1>
                <p class="text-[10px] sm:text-xs text-slate-300 hidden sm:block">Peserta: <strong class="text-white">{{ $participant->name }}</strong> ({{ $participant->category }})</p>
            </div>

            <!-- Realtime Progress Indicator Bar -->
            <div class="bg-slate-900/90 p-2 sm:p-3 rounded-xl sm:rounded-2xl border border-white/15 text-right shrink-0 space-y-1">
                <div class="text-[9px] sm:text-[10px] text-slate-400 font-bold uppercase tracking-wider flex justify-between gap-2">
                    <span x-text="answeredCount + ' / ' + totalCount" class="text-[#C9A24D] font-mono"></span>
                    <span x-text="progressPercentage + '%'" class="text-white font-mono"></span>
                </div>
                <div class="w-24 sm:w-48 bg-slate-700 rounded-full h-1.5 sm:h-2 overflow-hidden">
                    <div class="bg-[#C9A24D] h-full rounded-full transition-all duration-300" :style="'width: ' + progressPercentage + '%'"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Form Area -->
    <div class="py-6 sm:py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
            
            <!-- Instructions Notice -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border-l-4 border-[#C9A24D] shadow-sm text-xs text-slate-700 leading-relaxed font-normal">
                <strong class="text-[#0B2A43] block font-bold mb-1">📖 Petunjuk Pengisian Asesmen:</strong>
                {{ $period->instrument->instructions ?? 'Bacalah setiap pernyataan di bawah ini dengan seksama. Pilihlah opsi yang paling mencerminkan kondisi batiniah dan pengalaman diri Anda.' }}
            </div>

            <!-- Auto-save Live Indicator Badge -->
            <div class="flex items-center justify-between text-[11px] text-slate-500 bg-white/80 px-4 py-2 rounded-xl border border-slate-200 shadow-2xs">
                <span class="flex items-center gap-1.5 text-emerald-700 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>💾 Jawaban tersimpan otomatis di perangkat Anda secara realtime</span>
                </span>
                <span class="font-mono text-slate-400 text-[10px]" x-text="answeredCount > 0 ? answeredCount + ' tersimpan' : 'Belum diisi'"></span>
            </div>

            <form action="{{ route('assessment.submit', ['period_code' => $period->period_code, 'type' => $type]) }}" method="POST" class="space-y-6">
                @csrf

                <!-- SECTION 1: INSTRUMEN RQI-15 (15 Questions) -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-300 pb-2">
                        <span class="text-sm sm:text-base font-serif font-bold text-[#0B2A43]">Bagian I: INVENTORI KECERDASAN RUHIOLOGI (RQI-15)</span>
                        <span class="text-[10px] sm:text-xs font-mono font-bold text-[#C9A24D] bg-[#0B2A43] px-2.5 py-0.5 rounded-full">15 Pertanyaan</span>
                    </div>

                    @foreach($rqiQuestions as $index => $q)
                        <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 sm:space-y-4 hover:border-slate-300 transition-all">
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-[#0B2A43] text-[#C9A24D] font-mono font-bold text-xs flex items-center justify-center shrink-0 border border-[#C9A24D]/40">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] font-bold text-[#C9A24D] uppercase tracking-widest block mb-0.5 font-mono">
                                        ✦ {{ $q->dimension->name ?? 'Tahap Ruhiologi' }}
                                    </span>
                                    <p class="text-xs sm:text-sm font-medium text-[#0B2A43] leading-relaxed">
                                        {{ $q->question_text }}
                                    </p>
                                </div>
                            </div>

                            <!-- Options Radio Cards (Text Only Minimalist Pill Grid) -->
                            <div class="pt-1 grid grid-cols-2 sm:grid-cols-5 gap-2 sm:gap-2.5 text-xs">
                                @foreach($q->options as $opt)
                                    <label class="border-2 border-slate-200/90 rounded-2xl p-3 sm:p-4 text-center cursor-pointer hover:bg-slate-50 hover:border-[#0B2A43]/40 has-[:checked]:bg-[#0B2A43] has-[:checked]:border-[#0B2A43] has-[:checked]:text-white transition-all duration-200 flex flex-col justify-center items-center group shadow-2xs min-h-[50px] sm:min-h-[58px]">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="opt_{{ $opt->id }}" required @change="onAnswerChange()" class="peer sr-only">
                                        
                                        <span class="text-xs font-semibold text-slate-700 group-has-[:checked]:text-[#C9A24D] group-has-[:checked]:font-extrabold transition-colors leading-tight text-center">
                                            {{ $opt->option_text }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- SECTION 2: INSTRUMEN WHO-5 (5 Questions) -->
                @if(isset($who5Questions) && count($who5Questions) > 0)
                    <div class="space-y-4 pt-4 sm:pt-6">
                        <div class="flex items-center gap-2 border-b border-slate-300 pb-2">
                            <span class="text-sm sm:text-base font-serif font-bold text-[#0B2A43]">Bagian II: INDEKS KESEJAHTERAAN MENTAL (WHO-5)</span>
                            <span class="text-[10px] sm:text-xs font-mono font-bold text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full border border-emerald-300">5 Pertanyaan</span>
                        </div>
                        <p class="text-xs text-slate-600 font-normal">Pilihlah frekuensi perasaan yang menggambarkan kondisi Anda dalam 2 minggu terakhir:</p>

                        @foreach($who5Questions as $wIdx => $wq)
                            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 sm:space-y-4 hover:border-slate-300 transition-all">
                                <div class="flex items-start gap-3">
                                    <span class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-emerald-800 text-white font-mono font-bold text-xs flex items-center justify-center shrink-0">
                                        W{{ $wIdx + 1 }}
                                    </span>
                                    <div>
                                        <span class="text-[9px] sm:text-[10px] font-bold text-emerald-700 uppercase tracking-widest block mb-0.5 font-mono">
                                            🌿 INDEKS KESEJAHTERAAN MENTAL (WHO-5)
                                        </span>
                                        <p class="text-xs sm:text-sm font-medium text-[#0B2A43] leading-relaxed">
                                            {{ $wq->question_text }}
                                        </p>
                                    </div>
                                </div>

                                <div class="pt-1 grid grid-cols-2 sm:grid-cols-5 gap-2 sm:gap-2.5 text-xs">
                                    @foreach($wq->options as $wOpt)
                                        <label class="border-2 border-slate-200/90 rounded-2xl p-3 sm:p-4 text-center cursor-pointer hover:bg-emerald-50 hover:border-emerald-500 has-[:checked]:bg-emerald-800 has-[:checked]:border-emerald-800 has-[:checked]:text-white transition-all duration-200 flex flex-col justify-center items-center group shadow-2xs min-h-[50px] sm:min-h-[58px]">
                                            <input type="radio" name="answers[{{ $wq->id }}]" value="opt_{{ $wOpt->id }}" required @change="onAnswerChange()" class="peer sr-only">
                                            
                                            <span class="text-xs font-semibold text-slate-700 group-has-[:checked]:text-white group-has-[:checked]:font-extrabold transition-colors leading-tight text-center">
                                                {{ $wOpt->option_text }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Submit Action Footer -->
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-md flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-xs text-slate-500 font-normal text-center sm:text-left">
                        <span>⚠️ Pastikan seluruh {{ (count($rqiQuestions) + count($who5Questions ?? [])) }} pertanyaan telah terisi.</span>
                    </div>
                    <button type="button" @click="openSubmitModal()" class="w-full sm:w-auto px-8 py-4 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-bold rounded-2xl text-xs uppercase tracking-wider shadow-lg transition-transform hover:scale-105 cursor-pointer border border-[#C9A24D]/30 min-h-[44px]">
                        Submit Sesi Asesmen Sekarang →
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Custom Branded Confirm Modal -->
    <div x-show="showConfirmModal" x-cloak class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fadeIn">
        <div @click.away="showConfirmModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-center space-y-5">
            <!-- Icon Badge -->
            <div class="w-16 h-16 bg-amber-50 text-[#C9A24D] rounded-full border-2 border-[#C9A24D]/30 flex items-center justify-center mx-auto text-2xl shadow-inner">
                📋
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1">
                <h3 class="text-xl font-serif font-bold text-[#0B2A43]">Konfirmasi Submit Asesmen</h3>
                <p class="text-xs text-slate-500 font-medium">Ruhiology Institute Assessment Engine</p>
            </div>

            <!-- Progress Summary Pill -->
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-xs flex justify-between items-center">
                <span class="text-slate-500 font-semibold">Total Terjawab:</span>
                <span class="font-bold text-[#0B2A43]" x-text="answeredCount + ' / ' + totalCount + ' (' + progressPercentage + '%)'"></span>
            </div>

            <!-- Description Message -->
            <div class="text-xs text-slate-600 leading-relaxed space-y-2">
                <p>Apakah Anda yakin ingin mengirimkan seluruh jawaban asesmen ini?</p>
                <div class="p-2.5 bg-amber-50 text-amber-900 rounded-xl border border-amber-200 text-[11px] font-bold">
                    ⚠️ Jawaban bersifat final & tidak dapat diubah kembali setelah disubmit.
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-2 flex flex-col sm:flex-row gap-2.5">
                <button type="button" @click="showConfirmModal = false" class="w-full py-3.5 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-100 transition cursor-pointer">
                    Batal & Periksa Kembali
                </button>
                <button type="button" @click="submitForm()" class="w-full py-3.5 rounded-xl bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-bold text-xs uppercase tracking-wider shadow-lg transition-transform hover:scale-[1.02] cursor-pointer border border-[#C9A24D]/30">
                    Ya, Kirim Sekarang →
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function rqTakeAssessment() {
    return {
        totalCount: {{ (count($rqiQuestions) + count($who5Questions ?? [])) }},
        answeredCount: 0,
        progressPercentage: 0,
        showConfirmModal: false,
        storageKey: 'rq_answers_' + '{{ $participant->assessment_code ?? $participant->participant_code }}',

        init() {
            this.restoreAutoSavedAnswers();
            this.onAnswerChange();
        },

        restoreAutoSavedAnswers() {
            try {
                const saved = localStorage.getItem(this.storageKey);
                if (saved) {
                    const answers = JSON.parse(saved);
                    Object.keys(answers).forEach(name => {
                        const val = answers[name];
                        const radio = document.querySelector(`input[name="${name}"][value="${val}"]`);
                        if (radio) {
                            radio.checked = true;
                        }
                    });
                }
            } catch (e) {
                console.warn('Auto-restore failed:', e);
            }
        },

        onAnswerChange() {
            const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
            this.answeredCount = checkedRadios.length;
            this.progressPercentage = this.totalCount > 0 ? Math.round((this.answeredCount / this.totalCount) * 100) : 0;

            // Auto-save to localStorage
            try {
                const answersMap = {};
                checkedRadios.forEach(radio => {
                    answersMap[radio.name] = radio.value;
                });
                localStorage.setItem(this.storageKey, JSON.stringify(answersMap));
            } catch (e) {
                console.warn('Auto-save failed:', e);
            }
        },

        openSubmitModal() {
            if (this.answeredCount < this.totalCount) {
                if (!confirm(`Perhatian: Anda baru menjawab ${this.answeredCount} dari ${this.totalCount} pertanyaan. Apakah Anda tetap ingin mengirimkan?`)) {
                    return;
                }
            }
            this.showConfirmModal = true;
        },

        submitForm() {
            try {
                localStorage.removeItem(this.storageKey);
            } catch (e) {}

            const form = document.querySelector('form');
            if (form) {
                form.submit();
            }
        }
    }
}
</script>
@endpush
@endsection
