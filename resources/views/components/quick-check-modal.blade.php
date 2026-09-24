<div 
    x-data="{
        open: false,
        inputCode: '',
        loading: false,
        resultData: null,
        errorMessage: null,
        
        openModal(detail) {
            this.open = true;
            this.errorMessage = null;
            if (detail && detail.code) {
                this.inputCode = detail.code;
                this.fetchResult();
            }
        },

        closeModal() {
            this.open = false;
        },

        async fetchResult() {
            if (!this.inputCode || !this.inputCode.trim()) {
                this.errorMessage = 'Mohon masukkan kode assessment atau kode peserta.';
                return;
            }

            this.loading = true;
            this.errorMessage = null;
            this.resultData = null;

            try {
                let response = await fetch('{{ route('api.assessment.quick_check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code: this.inputCode })
                });

                let data = await response.json();
                this.loading = false;

                if (data.found) {
                    this.resultData = data;
                } else {
                    this.errorMessage = data.message || 'Kode tidak ditemukan dalam database.';
                }
            } catch (err) {
                this.loading = false;
                this.errorMessage = 'Terjadi kesalahan jaringan. Silakan coba kembali.';
            }
        }
    }"
    @open-quick-check.window="openModal($event.detail)"
    x-show="open" 
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm transition-opacity"
>
    <!-- Modal Container -->
    <div 
        @click.away="closeModal()" 
        class="bg-white rounded-3xl max-w-xl w-full border border-slate-200 shadow-2xl overflow-hidden transform transition-all relative font-sans"
    >
        <!-- Modal Top Bar -->
        <div class="bg-[#0B2A43] text-white p-6 relative">
            <button @click="closeModal()" class="absolute top-5 right-5 text-slate-300 hover:text-white text-xl font-mono w-8 h-8 rounded-full bg-white/10 flex items-center justify-center transition">
                ✕
            </button>

            <div class="flex items-center gap-2.5 mb-1 text-[#C9A24D] text-xs font-mono font-bold uppercase tracking-wider">
                <span>🔍 EVALUASI & VERIFIKASI ASESMEN</span>
            </div>
            <h3 class="font-serif text-2xl font-bold text-white tracking-tight">
                Cek Skor & Status Posttest
            </h3>
            <p class="text-xs text-slate-300 mt-1 font-normal">
                Masukkan Kode Submission (<code class="bg-white/10 px-1.5 py-0.5 rounded text-amber-300">SUB-XXXX</code>), Kode Peserta (<code class="bg-white/10 px-1.5 py-0.5 rounded text-amber-300">PAR-XXXX</code>), atau Kode Unique.
            </p>
        </div>

        <!-- Modal Search Form Body -->
        <div class="p-6 space-y-6">
            
            <form @submit.prevent="fetchResult()" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        x-model="inputCode" 
                        placeholder="Contoh: SUB-LIIGMH9LQL atau PAR-ABCD1234" 
                        class="w-full bg-slate-50 border border-slate-300 text-slate-800 placeholder-slate-400 text-xs px-4 py-3 rounded-2xl focus:outline-none focus:border-[#0B2A43] font-mono uppercase font-bold"
                    >
                </div>
                <button 
                    type="submit" 
                    :disabled="loading" 
                    class="bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold text-xs px-6 py-3 rounded-2xl transition shrink-0 shadow-md flex items-center gap-2 cursor-pointer disabled:opacity-50"
                >
                    <span x-show="!loading">Cek Hasil →</span>
                    <span x-show="loading" class="animate-spin">⏳</span>
                </button>
            </form>

            <!-- Loading Spinner State -->
            <div x-show="loading" class="text-center py-8 space-y-2">
                <div class="inline-block w-8 h-8 border-3 border-[#0B2A43] border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs font-bold text-slate-600">Mencari data asesmen peserta...</p>
            </div>

            <!-- Error / Not Found State -->
            <div x-show="errorMessage && !loading" x-cloak class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-center space-y-3">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-lg mx-auto">
                    ⚠️
                </div>
                <div>
                    <h4 class="font-bold text-xs text-amber-900" x-text="errorMessage"></h4>
                    <p class="text-[11px] text-amber-700 mt-1">
                        Belum pernah membuat asesmen? Anda dapat mendaftar sesi asesmen baru secara gratis.
                    </p>
                </div>
                <div>
                    <a href="{{ route('assessment.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#0B2A43] hover:bg-[#123B59] text-white rounded-xl text-xs font-bold transition shadow-sm">
                        <span>Mulai Assessment Baru</span> <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Result Found State -->
            <template x-if="resultData && resultData.found">
                <div class="space-y-5">
                    
                    <!-- Participant Details Header Card -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block" x-text="resultData.participant.institution"></span>
                            <h4 class="font-serif font-bold text-base text-[#0B2A43]" x-text="resultData.participant.name"></h4>
                            <div class="flex items-center gap-2 text-[11px] text-slate-500 font-mono">
                                <span>Kode Peserta:</span>
                                <span class="font-bold text-[#0B2A43]" x-text="resultData.participant.participant_code"></span>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold border border-emerald-300" x-text="resultData.participant.category"></span>
                    </div>

                    <!-- Latest RQ Score Badge -->
                    <template x-if="resultData.latest_result">
                        <div class="bg-gradient-to-r from-[#0B2A43] to-[#123B59] text-white rounded-2xl p-5 border border-[#C9A24D]/30 shadow-md relative overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-wider block">
                                        HASIL RQ TERKINI (<span x-text="resultData.latest_result.type"></span>)
                                    </span>
                                    <div class="font-serif font-bold text-lg text-white" x-text="resultData.latest_result.rq_level_name"></div>
                                    <div class="text-[11px] text-slate-300">
                                        Disubmit pada: <span x-text="resultData.latest_result.submitted_at"></span>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-3xl font-black font-mono text-[#C9A24D]" x-text="resultData.latest_result.total_score"></div>
                                    <div class="text-[10px] text-slate-300 font-bold uppercase">Skor Total</div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Posttest Status Banner -->
                    <div 
                        class="p-4 rounded-2xl border text-xs flex items-center justify-between gap-3"
                        :class="{
                            'bg-amber-50 border-amber-200 text-amber-900': resultData.posttest.badge_color === 'amber',
                            'bg-emerald-50 border-emerald-200 text-emerald-900': resultData.posttest.badge_color === 'emerald',
                            'bg-slate-50 border-slate-200 text-slate-800': resultData.posttest.badge_color === 'slate'
                        }"
                    >
                        <div class="flex items-center gap-2">
                            <span class="text-base" x-text="resultData.posttest.can_take ? '🚀' : (resultData.posttest.badge_color === 'emerald' ? '✅' : 'ℹ️')"></span>
                            <span class="font-bold" x-text="resultData.posttest.status_message"></span>
                        </div>
                    </div>

                    <!-- 2 ACTION BUTTONS (CHOICE OPTIONS) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        
                        <!-- Button 1: Lihat Detail Hasil & Refleksi -->
                        <template x-if="resultData.latest_result && resultData.latest_result.result_url">
                            <a 
                                :href="resultData.latest_result.result_url" 
                                class="w-full py-3 px-4 bg-white border-2 border-[#0B2A43] text-[#0B2A43] hover:bg-slate-50 font-bold text-xs rounded-2xl transition shadow-sm flex items-center justify-center gap-2 text-center"
                            >
                                <span>📊</span> <span>Lihat Hasil & Refleksi</span>
                            </a>
                        </template>

                        <!-- Button 2: Lanjut Kerjakan Posttest (Form submit to verify) -->
                        <template x-if="resultData.posttest.can_take">
                            <form :action="resultData.posttest.verify_url" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="participant_code" :value="resultData.participant.participant_code">
                                <input type="hidden" name="period_code" :value="resultData.period ? resultData.period.period_code : 'RQI-PERIOD-2026'">
                                <input type="hidden" name="type" value="posttest">
                                
                                <button 
                                    type="submit" 
                                    class="w-full py-3 px-4 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-bold text-xs rounded-2xl transition shadow-md flex items-center justify-center gap-2 text-center cursor-pointer"
                                >
                                    <span>🚀</span> <span>Lanjut Kerjakan Posttest</span>
                                </button>
                            </form>
                        </template>

                        <!-- If Posttest is already done or unavailable -->
                        <template x-if="!resultData.posttest.can_take && !resultData.latest_result">
                            <a 
                                href="{{ route('assessment.index') }}" 
                                class="w-full py-3 px-4 bg-[#0B2A43] text-white font-bold text-xs rounded-2xl transition shadow-sm text-center block"
                            >
                                <span>Mulai Evaluasi Baru</span>
                            </a>
                        </template>

                    </div>

                </div>
            </template>

        </div>
    </div>
</div>
