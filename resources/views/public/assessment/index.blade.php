@extends('layouts.app')

@section('content')
<script>
window.rqAssessmentIndex = function rqAssessmentIndex() {
    return {
        showRegModal: false,
        showGeneratedCodeModal: false,
        showCheckScoreModal: false,
        showEventSelectorModal: false,
        
        activeEventsList: @json($activeEvents ?? []),
        selectedEventId: '',
        inputEventCode: '',
        verifyingEvent: false,
        eventCheckError: '',
        
        isSubmitting: false,
        copied: false,
        generatedCode: '',
        takeUrl: '#',
        eventCode: new URLSearchParams(window.location.search).get('event') || '',
        eventName: '',
        eventInstitution: '',
        isCategoryLocked: false,
        eventGroupLabel: '',
        eventSubcategories: [],
        publicInstruments: @json($publicInstruments ?? []),
        regenciesMap: @json($regenciesMap ?? []),
        participantCategoriesList: @json($participantCategories ?? []),

        form: {
            event_code: new URLSearchParams(window.location.search).get('event') || '',
            instrument_id: '',
            name: '',
            birth_date: '',
            gender: 'Laki-laki',
            phone: '',
            email: '',
            category: 'MURID',
            sub_category_type: '',
            detail_institution: '',
            sub_category: '',
            country_id: 1,
            province_id: null,
            regency_id: null,
            school_level: '',
            school_class: '',
            school_custom: '',
            university_id: null,
            university_custom: '',
            faculty_id: null,
            study_program_id: null,
            semester: 1,
            entry_year: new Date().getFullYear().toString(),
            occupation_id: null,
            occupation_custom: '',
            last_education: 'S1/D4'
        },

        provincesList: @json($provinces ?? []),
        fallbackProvinces: [
            { id: 1, name: 'Aceh' }, { id: 2, name: 'Sumatera Utara' }, { id: 3, name: 'Sumatera Barat' },
            { id: 4, name: 'Riau' }, { id: 5, name: 'Jambi' }, { id: 6, name: 'Sumatera Selatan' },
            { id: 7, name: 'Bengkulu' }, { id: 8, name: 'Lampung' }, { id: 9, name: 'Kepulauan Bangka Belitung' },
            { id: 10, name: 'Kepulauan Riau' }, { id: 11, name: 'DKI Jakarta' }, { id: 12, name: 'Jawa Barat' },
            { id: 13, name: 'Jawa Tengah' }, { id: 14, name: 'DI Yogyakarta' }, { id: 15, name: 'Jawa Timur' },
            { id: 16, name: 'Banten' }, { id: 17, name: 'Bali' }, { id: 18, name: 'Nusa Tenggara Barat' },
            { id: 19, name: 'Nusa Tenggara Timur' }, { id: 20, name: 'Kalimantan Barat' }, { id: 21, name: 'Kalimantan Tengah' },
            { id: 22, name: 'Kalimantan Selatan' }, { id: 23, name: 'Kalimantan Timur' }, { id: 24, name: 'Kalimantan Utara' },
            { id: 25, name: 'Sulawesi Utara' }, { id: 26, name: 'Sulawesi Tengah' }, { id: 27, name: 'Sulawesi Selatan' },
            { id: 28, name: 'Sulawesi Tenggara' }, { id: 29, name: 'Gorontalo' }, { id: 30, name: 'Sulawesi Barat' },
            { id: 31, name: 'Maluku' }, { id: 32, name: 'Maluku Utara' }, { id: 33, name: 'Papua Barat' },
            { id: 34, name: 'Papua' }, { id: 35, name: 'Papua Selatan' }, { id: 36, name: 'Papua Tengah' },
            { id: 37, name: 'Papua Pegunungan' }, { id: 38, name: 'Papua Barat Daya' }
        ],
        filteredRegencies: [],
        filteredUniversities: [],
        occupations: [],

        init() {
            if (this.publicInstruments && this.publicInstruments.length > 0) {
                if (!this.form.instrument_id) {
                    this.form.instrument_id = this.publicInstruments[0].id;
                }
            }
            if (this.participantCategoriesList && this.participantCategoriesList.length > 0) {
                const firstCat = this.participantCategoriesList[0];
                if (firstCat && firstCat.name && !this.form.category) {
                    this.form.category = firstCat.name;
                }
            }
            const subs = this.getCurrentCategorySubCategories();
            if (subs && subs.length > 0 && !this.form.sub_category_type) {
                this.form.sub_category_type = subs[0].name;
            }
            if (this.eventCode) {
                this.inputEventCode = this.eventCode;
                this.verifyEventCodeAsync(this.eventCode);
            }
        },

        getCurrentCategorySubCategories() {
            if (!this.participantCategoriesList || this.participantCategoriesList.length === 0) return [];
            const catObj = this.participantCategoriesList.find(c => (c.name || '').toUpperCase() === (this.form.category || '').toUpperCase());
            if (!catObj) return [];
            return catObj.sub_categories || catObj.sub_categories_list || catObj.subCategories || [];
        },

        getDetailLabel() {
            const subs = this.getCurrentCategorySubCategories();
            if (!subs || subs.length === 0) return 'Nama Instansi / Detail Tempat';
            const sel = subs.find(s => s.name === this.form.sub_category_type);
            return (sel && sel.detail_label) ? sel.detail_label : 'Nama Instansi / Detail Tempat';
        },

        getDetailPlaceholder() {
            const lbl = this.getDetailLabel();
            return 'Contoh: ' + (lbl || 'Nama Instansi / Unit Kerja Anda');
        },

        setCategory(catName) {
            if (this.isCategoryLocked) return;
            this.form.category = catName;
            const subs = this.getCurrentCategorySubCategories();
            if (subs && subs.length > 0) {
                this.form.sub_category_type = subs[0].name;
            } else {
                this.form.sub_category_type = '';
            }
            this.form.detail_institution = '';
        },

        onSubCategoryTypeChange() {
            this.form.detail_institution = '';
        },

        openRegistrationModal(track = 'PUBLIC_SELF', targetInstId = null) {
            if (track === 'PUBLIC_SELF') {
                this.eventCode = '';
                this.eventName = '';
                this.eventInstitution = '';
                this.form.event_code = '';
                this.form.sub_category = '';
                this.form.sub_category_type = '';
                this.form.detail_institution = '';
                this.eventGroupLabel = '';
                this.eventSubcategories = [];
                this.isCategoryLocked = false;
                if (targetInstId) {
                    this.form.instrument_id = targetInstId;
                } else if (this.publicInstruments && this.publicInstruments.length > 0 && !this.form.instrument_id) {
                    this.form.instrument_id = this.publicInstruments[0].id;
                }
            }
            this.showRegModal = true;
        },

        openEventSelectorModal() {
            this.eventCheckError = '';
            this.showEventSelectorModal = true;
        },

        onEventSelectChange() {
            this.eventCheckError = '';
        },

        proceedWithEventSelection() {
            const code = (this.inputEventCode || '').trim().toUpperCase();
            if (!code) {
                this.eventCheckError = 'Mohon masukkan Kode Event / Kunci Sesi resmi dari panitia.';
                return;
            }

            this.verifyEventCodeAsync(code);
        },

        verifyEventCodeAsync(code) {
            this.verifyingEvent = true;
            this.eventCheckError = '';

            fetch('/api/events/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ event_code: code })
            })
            .then(res => res.json())
            .then(data => {
                this.verifyingEvent = false;
                if (data.status === 'success' && data.event) {
                    const evt = data.event;
                    this.eventCode = evt.event_code;
                    this.eventName = evt.title;
                    this.eventInstitution = evt.institution_name || '';
                    this.form.event_code = evt.event_code;

                    if (evt.custom_subcategories && evt.custom_subcategories.length > 0) {
                        this.eventGroupLabel = evt.group_label || 'Pilih Sub-Kategori / Kelas / Bidang';
                        this.eventSubcategories = evt.custom_subcategories;
                    } else {
                        this.eventGroupLabel = '';
                        this.eventSubcategories = [];
                    }

                    if (evt.target_category) {
                        this.form.category = evt.target_category;
                        this.isCategoryLocked = true;
                    } else {
                        this.isCategoryLocked = false;
                    }

                    if (evt.province_id && !this.form.province_id) {
                        this.form.province_id = evt.province_id;
                        this.onProvinceChange();
                        if (evt.regency_id) {
                            this.form.regency_id = evt.regency_id;
                        }
                    }

                    this.showEventSelectorModal = false;
                    this.showRegModal = true;
                } else {
                    this.eventCheckError = data.message || 'Kode Event tidak valid atau tidak aktif.';
                }
            })
            .catch(() => {
                this.verifyingEvent = false;
                this.eventCheckError = 'Terjadi kesalahan saat memverifikasi Kode Event.';
            });
        },

        onProvinceChange() {
            this.form.regency_id = null;
            if (!this.form.province_id) {
                this.filteredRegencies = [];
                return;
            }
            const pid = String(this.form.province_id);
            const cached = this.regenciesMap ? (this.regenciesMap[pid] || this.regenciesMap[Number(pid)]) : null;
            if (cached && cached.length > 0) {
                this.filteredRegencies = cached;
            } else {
                this.searchRegencies();
            }
        },

        getProvincesHtml() {
            const items = (this.provincesList && this.provincesList.length > 0) ? this.provincesList : this.fallbackProvinces;
            let html = '<option value="">-- Pilih --</option>';
            items.forEach(p => {
                const selected = String(p.id) === String(this.form.province_id) ? 'selected' : '';
                html += `<option value="${p.id}" ${selected}>${p.name}</option>`;
            });
            return html;
        },

        getRegenciesHtml() {
            if (!this.form.province_id) {
                return '<option value="">Provinsi dahulu</option>';
            }
            if (!this.filteredRegencies || this.filteredRegencies.length === 0) {
                return '<option value="">-- Memuat / Tidak Ada Data --</option>';
            }
            let html = '<option value="">-- Pilih --</option>';
            this.filteredRegencies.forEach(r => {
                const displayName = r.formatted_name || ((r.name.startsWith('Kota') || r.name.startsWith('Kabupaten') || !r.type) ? r.name : (r.type + ' ' + r.name));
                const selected = String(r.id) === String(this.form.regency_id) ? 'selected' : '';
                html += `<option value="${r.id}" ${selected}>${displayName}</option>`;
            });
            return html;
        },

        searchRegencies() {
            if (!this.form.province_id) return;
            fetch(`/api/master/regencies?province_id=${this.form.province_id}`)
                .then(res => res.json())
                .then(res => {
                    if (res && res.data) {
                        this.filteredRegencies = res.data;
                    }
                })
                .catch(() => {});
        },

        submitRegistration() {
            this.isSubmitting = true;

            if (this.form.sub_category_type) {
                this.form.sub_category = this.form.sub_category_type + (this.form.detail_institution ? ' — ' + this.form.detail_institution : '');
            } else {
                this.form.sub_category = this.form.detail_institution || '';
            }

            const catUpper = (this.form.category || '').toUpperCase();
            if (['MURID', 'PELAJAR', 'GURU'].includes(catUpper)) {
                this.form.school_custom = this.form.detail_institution || '';
                this.form.school_level = this.form.sub_category_type || '';
            }
            if (['MAHASISWA', 'DOSEN'].includes(catUpper)) {
                this.form.university_custom = this.form.detail_institution || '';
            }
            this.form.occupation_custom = this.form.detail_institution || '';

            fetch('/assessment/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.form)
            })
            .then(async res => {
                const data = await res.json().catch(() => ({}));
                this.isSubmitting = false;

                if (res.ok && data.status === 'success') {
                    this.generatedCode = data.assessment_code;
                    this.takeUrl = data.take_url;
                    this.showRegModal = false;
                    this.showGeneratedCodeModal = true;
                } else {
                    let errMsg = data.message || 'Gagal mendaftar. Silakan periksa kembali formulir Anda.';
                    if (data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        if (firstKey && data.errors[firstKey][0]) {
                            errMsg = data.errors[firstKey][0];
                        }
                    }
                    alert(errMsg);
                }
            })
            .catch(err => {
                this.isSubmitting = false;
                alert('Terjadi kesalahan koneksi. Silakan periksa jaringan internet Anda.');
            });
        },

        copyCode() {
            navigator.clipboard.writeText(this.generatedCode);
            this.copied = true;
            setTimeout(() => { this.copied = false; }, 3000);
        }
    };
};
document.addEventListener('alpine:init', () => {
    Alpine.data('rqAssessmentIndex', window.rqAssessmentIndex);
});
</script>

<div x-data="rqAssessmentIndex()" class="bg-[#F8F9FA] min-h-screen">

    <!-- HERO SECTION (MINIMALIST, HIGH-END CORPORATE) -->
    <section class="relative bg-[#0B2A43] text-white pt-16 pb-20 lg:pt-24 lg:pb-28 overflow-hidden border-b border-slate-800">
        <!-- Background Overlay Glow -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-br from-[#C9A24D]/20 via-[#123B59]/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-[1280px] mx-auto px-6 sm:px-10 relative z-10 text-center space-y-6">
            
            <!-- Eyebrow Badge -->
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 text-[#C9A24D] text-xs font-mono font-bold px-4 py-2 rounded-full shadow-sm uppercase tracking-widest">
                <span>✦ RUHIOLOGY ASSESSMENT ENGINE</span>
            </div>

            <!-- Title -->
            <h1 class="font-serif font-bold text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-tight">
                Kenali Kesadaran Batin Anda
            </h1>

            <!-- Subtitle -->
            <p class="text-slate-200 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed font-normal opacity-95">
                Evaluasi tingkat kesadaran batiniah (RQI-15) & vitalitas kesehatan emosional (WHO-5) secara terstruktur, terpercaya, dan aman.
            </p>

            <!-- Action Buttons -->
            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <button type="button" @click="openRegistrationModal('PUBLIC_SELF')" class="bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold text-xs sm:text-sm px-8 py-4 rounded-2xl shadow-xl hover:shadow-[#C9A24D]/30 transition transform hover:-translate-y-0.5 flex items-center space-x-2 cursor-pointer">
                    <span>✨ MULAI ASESMEN MANDIRI</span>
                </button>

                <button type="button" @click="openEventSelectorModal()" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs sm:text-sm font-bold px-8 py-4 rounded-2xl transition backdrop-blur-md flex items-center space-x-2 cursor-pointer">
                    <span>🎯 PILIH EVENT / KODE ACARA</span>
                </button>
            </div>

            <!-- Informative Notice Badges Grid -->
            <div class="pt-10 max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-left">
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-1.5">
                        <span>🔒</span> <span>Personal & Rahasia</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">Hasil asesmen diproses khusus untuk pengembangan diri Anda.</p>
                </div>
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-1.5">
                        <span>🛡️</span> <span>Tersimpan Aman</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">Jawaban dan identitas dilindungi dengan standar privasi ketat.</p>
                </div>
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-1.5">
                        <span>🩺</span> <span>Bukan Medis</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">Hasil ini merupakan refleksi batin, bukan klaim/diagnosis medis.</p>
                </div>
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-1.5">
                        <span>📊</span> <span>Skrining WHO-5</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">Instrumen terstandar untuk memetakan kesejahteraan batiniah.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- EXISTING PARTICIPANT CODE ACCESS SECTION -->
    <section class="py-12">
        <div class="max-w-[1280px] mx-auto px-6 sm:px-10">
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm max-w-3xl mx-auto space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="font-serif font-bold text-lg text-[#0B2A43]">Punya Kode Peserta / Kode Assessment Lama?</h3>
                        <p class="text-xs text-slate-500 font-normal mt-0.5">Masukkan kode Anda di bawah ini untuk verifikasi langsung ke sesi Pretest/Posttest.</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Sesi Aktif</span>
                </div>

                <form action="{{ route('assessment.verify') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-sans">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Assessment / Peserta</label>
                        <input type="text" name="participant_code" required placeholder="Contoh: RQI-7F82K9" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Sesi</label>
                        <select name="type" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium bg-slate-50">
                            <option value="pretest">PRETEST (Pengukuran Awal)</option>
                            <option value="posttest">POSTTEST (Evaluasi Akhir)</option>
                        </select>
                    </div>
                    <input type="hidden" name="period_code" value="{{ $defaultPeriod->period_code ?? 'RQI-PERIOD-2026' }}">
                    <div class="flex items-end">
                        <button type="submit" class="w-full py-3 px-4 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl shadow transition-colors cursor-pointer">
                            Masuk Sesi →
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- EVENT SELECTION MODAL (DROPDOWN EVENT + KODE PASSPHRASE VERIFICATION) -->
    <div x-show="showEventSelectorModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md animate-fadeIn">
        <div @click.away="showEventSelectorModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100 space-y-5">
            <button @click="showEventSelectorModal = false" type="button" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700">✕</button>

            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-12 h-12 bg-[#0B2A43] text-[#C9A24D] rounded-2xl flex items-center justify-center text-xl font-bold shadow">
                    🎯
                </div>
                <div>
                    <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">EVENT & ACARA ASESMEN</span>
                    <h3 class="text-xl font-serif font-bold text-[#0B2A43]">Verifikasi Akses Event</h3>
                </div>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Silakan pilih nama event dari daftar atau ketik Kode Event / Kunci Sesi yang diberikan oleh panitia untuk memverifikasi akses Anda.
            </p>

            <div class="space-y-4 text-xs font-sans">
                <!-- Dropdown Select Active Events -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Pilih Event Aktif (Opsional)</label>
                    <select x-model="selectedEventId" @change="onEventSelectChange()" class="w-full p-3.5 rounded-2xl border border-slate-300 bg-slate-50 font-medium focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none text-xs">
                        <option value="">-- Pilih Nama Event dari Daftar --</option>
                        <template x-for="evt in activeEventsList" :key="evt.id">
                            <option :value="evt.id" x-text="evt.title + (evt.institution_name ? ' — ' + evt.institution_name : '')"></option>
                        </template>
                    </select>
                </div>

                <!-- Input Event Code / Kunci Passcode -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Masukkan Kode Event / Kunci Sesi *</label>
                    <input type="text" x-model="inputEventCode" placeholder="Masukkan Kode Event (Contoh: RQ-JAMBI-26)" class="w-full p-3.5 rounded-2xl border-2 border-slate-300 focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/20 font-mono font-bold uppercase text-sm tracking-wider text-center outline-none">
                    <span class="text-[10px] text-slate-400 mt-1 block text-center">Kode resmi diberikan oleh panitia / narasumber event Anda.</span>
                </div>

                <template x-if="eventCheckError">
                    <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 font-semibold rounded-xl text-xs flex items-center gap-2">
                        <span>⚠️</span> <span x-text="eventCheckError"></span>
                    </div>
                </template>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="showEventSelectorModal = false" class="flex-1 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-xs transition cursor-pointer">
                        Batal
                    </button>
                    <button type="button" @click="proceedWithEventSelection()" :disabled="verifyingEvent" class="flex-1 py-3.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold rounded-2xl text-xs shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50">
                        <span x-text="verifyingEvent ? 'Memverifikasi...' : 'Verifikasi & Lanjut →'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- REGISTRATION INTAKE MODAL (2-COLUMN NO-SCROLL LAYOUT) -->
    <div x-show="showRegModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
        <div @click.away="showRegModal = false" class="bg-white rounded-3xl max-w-4xl w-full p-5 sm:p-7 shadow-2xl relative border border-slate-100 max-h-[92vh] overflow-y-auto" @click.stop>
            
            <!-- Close Button -->
            <button @click="showRegModal = false" type="button" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition-colors">
                ✕
            </button>

            <div class="mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest block font-mono">REGISTRASI PESERTA</span>
                    <h3 class="text-lg sm:text-xl font-bold font-serif text-[#0B2A43]">Form Intake Asesmen Ruhiologi</h3>
                </div>
                <template x-if="eventCode">
                    <span class="text-xs font-mono font-bold bg-[#0B2A43] text-[#C9A24D] px-3 py-1 rounded-full border border-[#C9A24D]/30">
                        📌 Mode Event: <span x-text="eventCode"></span>
                    </span>
                </template>
            </div>

            <form @submit.prevent="submitRegistration()" class="space-y-4 text-xs font-sans">
                
                <!-- EVENT MODE: 2-COLUMN LAYOUT -->
                <template x-if="eventCode">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                        <!-- LEFT COLUMN (5 cols): Locked Event Metadata Card -->
                        <div class="md:col-span-5 bg-[#0B2A43] text-white p-5 rounded-2xl shadow-md border border-slate-800 space-y-4">
                            <div class="border-b border-slate-700/80 pb-3">
                                <span class="text-[9px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block mb-1">🎯 TERVERIFIKASI EVENT</span>
                                <h4 class="font-serif font-bold text-base text-white leading-tight" x-text="eventName || eventCode"></h4>
                                <p class="text-xs text-slate-300 mt-1 font-medium" x-text="eventInstitution || 'Instansi Terdaftar'"></p>
                            </div>

                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between items-center bg-white/10 p-2.5 rounded-xl border border-white/10">
                                    <span class="text-slate-300">Kode Event:</span>
                                    <span class="font-mono font-bold text-[#C9A24D]" x-text="eventCode"></span>
                                </div>
                                <div class="flex justify-between items-center bg-white/10 p-2.5 rounded-xl border border-white/10">
                                    <span class="text-slate-300">Kategori Peserta:</span>
                                    <span class="font-bold text-emerald-400" x-text="form.category"></span>
                                </div>
                            </div>

                            <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl text-[11px] text-amber-200/90 leading-relaxed space-y-1">
                                <div class="font-bold text-amber-400 flex items-center gap-1">
                                    <span>🔒 Data Instansi Dikunci</span>
                                </div>
                                <p class="text-[10px] text-slate-300">
                                    Profil sekolah, instansi, & wilayah sudah diset otomatis oleh panitia event. Anda cukup mengisi identitas diri di samping.
                                </p>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN (7 cols): Quick Personal Intake Inputs -->
                        <div class="md:col-span-7 space-y-3.5 bg-slate-50/80 p-5 rounded-2xl border border-slate-200">
                            <h4 class="font-serif font-bold text-sm text-[#0B2A43] border-b border-slate-200 pb-2">Identitas Peserta Event</h4>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                                <input type="text" x-model="form.name" required placeholder="Masukkan nama lengkap Anda..." class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tanggal Lahir *</label>
                                    <input type="date" x-model="form.birth_date" required class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">No. WA / HP (Opsional)</label>
                                    <input type="tel" x-model="form.phone" placeholder="081234567890" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin *</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="form.gender = 'Laki-laki'" :class="form.gender === 'Laki-laki' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="py-2 px-3 rounded-xl border text-xs transition-colors text-center cursor-pointer">
                                        👨 Laki-laki
                                    </button>
                                    <button type="button" @click="form.gender = 'Perempuan'" :class="form.gender === 'Perempuan' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="py-2 px-3 rounded-xl border text-xs transition-colors text-center cursor-pointer">
                                        👩 Perempuan
                                    </button>
                                </div>
                            </div>

                            <!-- Sub-category / Kelas Dropdown or Input -->
                            <template x-if="eventSubcategories && eventSubcategories.length > 0">
                                <div>
                                    <label class="block font-bold text-[#0B2A43] mb-1">
                                        <span x-text="eventGroupLabel || 'Pilih Sub-Kategori / Kelas / Bidang'"></span> *
                                    </label>
                                    <select x-model="form.sub_category" required class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                        <option value="">-- Pilih Opsi Kelas --</option>
                                        <template x-for="item in eventSubcategories" :key="item">
                                            <option :value="item" x-text="item"></option>
                                        </template>
                                    </select>
                                </div>
                            </template>
                            <template x-if="!eventSubcategories || eventSubcategories.length === 0">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Kelas / Rombel / Jurusan (Opsional)</label>
                                    <input type="text" x-model="form.school_class" placeholder="Contoh: Kelas X IPA 1 / XII IPS 2" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                </div>
                            </template>
                        </div>
                    </div>
                </template>


                <!-- ASESMEN MANDIRI MODE: 2-COLUMN LAYOUT -->
                <template x-if="!eventCode">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-[#0B2A43]/5 border border-[#0B2A43]/15 rounded-2xl">
                            <span class="font-bold text-[#0B2A43] text-xs">🟢 Track Asesmen Mandiri / Personal</span>
                            <button type="button" @click="showRegModal = false; openEventSelectorModal()" class="text-[11px] font-bold text-[#C9A24D] hover:underline cursor-pointer flex items-center gap-1">
                                <span>🎯 Punya Kode Event Panitia?</span>
                            </button>
                        </div>

                        <!-- Paket Soal / Instrumen Selector (jika > 1 instrumen publik aktif) -->
                        <template x-if="publicInstruments && publicInstruments.length > 1">
                            <div class="p-3.5 bg-amber-500/10 border border-amber-400/40 rounded-2xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block font-serif font-bold text-xs text-[#0B2A43] flex items-center gap-1.5">
                                        <span>📋</span> <span>Pilih Paket Soal / Instrumen Asesmen *</span>
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-amber-800 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-full" x-text="publicInstruments.length + ' Paket Soal Publik'"></span>
                                </div>
                                <select x-model="form.instrument_id" required class="w-full p-2.5 text-xs font-bold rounded-xl border border-amber-400 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-slate-800 shadow-xs">
                                    <template x-for="inst in publicInstruments" :key="inst.id">
                                        <option :value="inst.id" x-text="inst.name + (inst.title ? ' — ' + inst.title : '') + (inst.questions_count ? ' (' + inst.questions_count + ' Soal)' : '')"></option>
                                    </template>
                                </select>
                            </div>
                        </template>

                        <!-- Single Active Instrument Info Badge -->
                        <template x-if="publicInstruments && publicInstruments.length === 1">
                            <div class="p-3 bg-slate-100/90 border border-slate-200 rounded-2xl flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">📝</span>
                                    <div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase font-mono tracking-wider block">PAKET SOAL ASESMEN</span>
                                        <strong class="font-bold text-[#0B2A43]" x-text="publicInstruments[0].name"></strong>
                                        <span class="text-slate-500 text-[11px]" x-text="publicInstruments[0].questions_count ? ' (' + publicInstruments[0].questions_count + ' Soal)' : ''"></span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                    ✓ Aktif
                                </span>
                            </div>
                        </template>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                            <!-- LEFT COLUMN: Identitas Diri & Wilayah -->
                            <div class="space-y-3.5 bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200">
                                <h4 class="font-serif font-bold text-xs text-[#0B2A43] border-b border-slate-200 pb-1.5 uppercase font-mono tracking-wider">Langkah 1: Identitas & Wilayah</h4>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" x-model="form.name" required placeholder="Masukkan nama lengkap Anda..." class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                </div>

                                <div class="grid grid-cols-2 gap-2.5">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Tanggal Lahir *</label>
                                        <input type="date" x-model="form.birth_date" required class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">No. WA (Opsional)</label>
                                        <input type="tel" x-model="form.phone" placeholder="081234567890" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin *</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" @click="form.gender = 'Laki-laki'" :class="form.gender === 'Laki-laki' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="py-2 px-3 rounded-xl border text-xs transition-colors text-center cursor-pointer">
                                            👨 Laki-laki
                                        </button>
                                        <button type="button" @click="form.gender = 'Perempuan'" :class="form.gender === 'Perempuan' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="py-2 px-3 rounded-xl border text-xs transition-colors text-center cursor-pointer">
                                            👩 Perempuan
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2.5">
                                    <div>
                                        <label class="block font-bold text-slate-700 text-xs mb-1">Provinsi *</label>
                                        <select x-model="form.province_id" x-html="getProvincesHtml()" @change="onProvinceChange()" required class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 text-xs mb-1">Kab/Kota *</label>
                                        <select x-model="form.regency_id" x-html="getRegenciesHtml()" :disabled="!form.province_id" required class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white disabled:bg-slate-100">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: Profil & Kategori 3-Tingkat -->
                            <div class="space-y-3.5 bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200">
                                <h4 class="font-serif font-bold text-xs text-[#0B2A43] border-b border-slate-200 pb-1.5 uppercase font-mono tracking-wider">Langkah 2: Kategori & Instansi Peserta</h4>

                                <!-- Level 1: Kategori Utama Dropdown -->
                                <div>
                                    <label class="block font-bold text-slate-800 text-xs mb-1">1. Kategori Utama *</label>
                                    <select x-model="form.category" @change="setCategory(form.category)" required class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-slate-900 shadow-xs">
                                        <template x-for="cat in (participantCategoriesList && participantCategoriesList.length > 0 ? participantCategoriesList : [
                                            { name: 'GURU', icon: '👨‍🏫' },
                                            { name: 'DOSEN', icon: '🧑‍🏫' },
                                            { name: 'MURID', icon: '🏫' },
                                            { name: 'MAHASISWA', icon: '🎓' },
                                            { name: 'APH', icon: '⚖️' },
                                            { name: 'ASN', icon: '🏛️' },
                                            { name: 'TENAGA KEPENDIDIKAN', icon: '💼' },
                                            { name: 'WARGA BINAAN', icon: '🤝' },
                                            { name: 'UMUM', icon: '👤' }
                                        ])" :key="cat.name">
                                            <option :value="cat.name" x-text="(cat.icon ? cat.icon + ' ' : '') + cat.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Level 2: Sub-Kategori / Jenis Dropdown -->
                                <template x-if="getCurrentCategorySubCategories() && getCurrentCategorySubCategories().length > 0">
                                    <div>
                                        <label class="block font-bold text-slate-800 text-xs mb-1">
                                            2. Sub-Kategori / Jenis (<span x-text="form.category"></span>) *
                                        </label>
                                        <select x-model="form.sub_category_type" @change="onSubCategoryTypeChange()" required class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-slate-800">
                                            <template x-for="sub in getCurrentCategorySubCategories()" :key="sub.id || sub.name">
                                                <option :value="sub.name" x-text="sub.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </template>

                                <!-- Level 3: Detail Tempat / Satuan / Instansi Input -->
                                <div>
                                    <label class="block font-bold text-slate-800 text-xs mb-1">
                                        3. <span x-text="getDetailLabel()"></span> *
                                    </label>
                                    <input type="text" x-model="form.detail_institution" required :placeholder="getDetailPlaceholder()" class="w-full p-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white font-medium text-slate-900">
                                </div>

                                <!-- Secondary Optional Fields for Student/Mahasiswa -->
                                <template x-if="form.category === 'MAHASISWA'">
                                    <div class="grid grid-cols-2 gap-2.5 pt-1">
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Semester (Opsional)</label>
                                            <select x-model="form.semester" class="w-full p-2 text-xs font-medium rounded-xl border border-slate-300 outline-none bg-white">
                                                <template x-for="s in 12" :key="s">
                                                    <option :value="s" x-text="'Semester ' + s"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Angkatan (Opsional)</label>
                                            <input type="text" x-model="form.entry_year" placeholder="2024" class="w-full p-2 text-xs rounded-xl border border-slate-300 outline-none bg-white">
                                        </div>
                                    </div>
                                </template>

                                <template x-if="['MURID', 'GURU'].includes(form.category)">
                                    <div class="pt-1">
                                        <label class="block font-bold text-slate-700 text-[11px] mb-1">Kelas / Rombel / Jurusan (Opsional)</label>
                                        <input type="text" x-model="form.school_class" placeholder="Contoh: Kelas X IPA 1 / XII IPS 2" class="w-full p-2 text-xs rounded-xl border border-slate-300 outline-none bg-white">
                                    </div>
                                </template>

                            </div>
                        </div>
                    </div>
                </template>

                <!-- Full-Width Bottom Action Bar -->
                <div class="pt-2">
                    <button type="submit" :disabled="isSubmitting" class="w-full py-3.5 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-extrabold rounded-2xl text-xs uppercase tracking-wider shadow-lg transition-transform hover:scale-[1.005] cursor-pointer disabled:opacity-50 border border-[#C9A24D]/30">
                        <span x-text="isSubmitting ? 'Memproses Registrasi...' : 'Generate Kode Assessment & Mulai Test →'"></span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- GENERATED CODE MODAL -->
    <div x-show="showGeneratedCodeModal" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-900/75 backdrop-blur-md animate-fadeIn">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 text-center space-y-5 border border-slate-100 shadow-2xl">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto border border-emerald-300">
                ✨
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-widest block font-mono">ASSESSMENT BERHASIL DIBUAT</span>
                <h3 class="text-xl font-serif font-bold text-[#0B2A43] mt-1">Kode Assessment Unik Anda</h3>
                <p class="text-xs text-slate-500 mt-1">Simpan kode ini untuk melihat hasil dan melanjutkan assessment di lain waktu.</p>
            </div>

            <!-- Prominent Code Box -->
            <div class="p-4 bg-slate-900 text-[#C9A24D] rounded-2xl border-2 border-[#C9A24D]/40 font-mono text-2xl font-black tracking-widest flex items-center justify-center space-x-3 shadow-inner">
                <span x-text="generatedCode"></span>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="copyCode()" class="flex-1 py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>📋</span> <span x-text="copied ? 'Tersalin!' : 'SALIN KODE'"></span>
                </button>
                <a :href="takeUrl" class="flex-1 py-3 px-4 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl text-xs shadow-md transition-colors flex items-center justify-center gap-1.5">
                    <span>MULAI ASSESSMENT →</span>
                </a>
            </div>
        </div>
    </div>

    <!-- CHECK SCORE MODAL -->
    <div x-show="showCheckScoreModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
        <div @click.away="showCheckScoreModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100" @click.stop>
            <button @click="showCheckScoreModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700">✕</button>
            
            <div class="mb-5 pb-3 border-b border-slate-100">
                <span class="text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest block font-mono">VERIFIKASI KEAMANAN</span>
                <h3 class="text-xl font-serif font-bold text-[#0B2A43]">Cek Skor Assessment</h3>
                <p class="text-xs text-slate-500 mt-1">Masukkan Kode Assessment dan Tanggal Lahir untuk memverifikasi identitas Anda.</p>
            </div>

            <form action="{{ route('assessment.check_score') }}" method="POST" class="space-y-4 text-xs font-sans">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode Assessment *</label>
                    <input type="text" name="assessment_code" required placeholder="Contoh: RQI-7F82K9" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-mono uppercase font-bold text-sm">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Lahir *</label>
                    <input type="date" name="birth_date" required class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-md transition-colors cursor-pointer">
                        Verifikasi & Lihat Hasil →
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
