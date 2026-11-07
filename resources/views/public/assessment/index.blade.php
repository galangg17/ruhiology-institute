@extends('layouts.app')

@section('content')
@php
    $isEn = app()->getLocale() === 'en';
@endphp
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
        eventCountryCode: 'ID',
        eventCountryName: 'Indonesia',
        isCategoryLocked: false,
        eventGroupLabel: '',
        eventSubcategories: [],
        publicInstruments: @json($publicInstruments ?? []),
        regenciesMap: @json($regenciesMap ?? []),
        appLocale: '{{ app()->getLocale() }}',
        participantCategoriesList: @json($participantCategories ?? []),

        eventSessionTab: new URLSearchParams(window.location.search).get('type') === 'posttest' ? 'posttest' : 'pretest',
        posttestIdentifier: '',
        posttestChecking: false,
        posttestStatus: null,
        posttestError: '',

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
            country_code: 'ID',
            country_name: 'Indonesia',
            state_custom: '',
            city_custom: '',
            phone_country_code: '+62',
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
            if (window.location.hash === '#cek-hasil') {
                this.showCheckScoreModal = true;
            } else if (window.location.hash === '#mulai-mandiri') {
                this.openRegistrationModal('PUBLIC_SELF');
            } else if (window.location.hash === '#pilih-event') {
                this.openEventSelectorModal();
            }
        },

        getCurrentCategorySubCategories() {
            if (!this.participantCategoriesList || this.participantCategoriesList.length === 0) return [];
            const catObj = this.participantCategoriesList.find(c => (c.name || '').toUpperCase() === (this.form.category || '').toUpperCase());
            if (!catObj) return [];
            return catObj.sub_categories || catObj.sub_categories_list || catObj.subCategories || [];
        },

        getDetailLabel() {
            if (this.appLocale === 'en') {
                return 'Institution / School / Organization Name';
            }
            const subs = this.getCurrentCategorySubCategories();
            if (!subs || subs.length === 0) return 'Nama Instansi / Detail Tempat';
            const sel = subs.find(s => s.name === this.form.sub_category_type);
            return (sel && sel.detail_label) ? sel.detail_label : 'Nama Instansi / Detail Tempat';
        },

        getDetailPlaceholder() {
            if (this.appLocale === 'en') {
                return 'e.g. University / High School / Public Organization';
            }
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
                this.form.detail_institution = '';
                this.eventGroupLabel = '';
                this.eventSubcategories = [];
                this.isCategoryLocked = false;
                if (!this.form.category && this.participantCategoriesList && this.participantCategoriesList.length > 0) {
                    this.form.category = this.participantCategoriesList[0].name;
                }
                const subs = this.getCurrentCategorySubCategories();
                this.form.sub_category_type = (subs && subs.length > 0) ? subs[0].name : '';
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
                    this.eventCountryCode = evt.country_code || 'ID';
                    this.eventCountryName = evt.country_name || 'Indonesia';
                    this.form.event_code = evt.event_code;
                    if (evt.country_code && evt.country_code !== 'ID') {
                        this.form.country_code = evt.country_code;
                        this.form.country_name = evt.country_name;
                    }
                    if (evt.institution_name) {
                        this.form.detail_institution = evt.institution_name;
                    }

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

        onCountryChange() {
            const countryDialCodes = {
                'ID': { name: 'Indonesia', dial: '+62' },
                'MY': { name: 'Malaysia', dial: '+60' },
                'SG': { name: 'Singapore', dial: '+65' },
                'BN': { name: 'Brunei Darussalam', dial: '+673' },
                'SA': { name: 'Saudi Arabia', dial: '+966' },
                'AE': { name: 'United Arab Emirates', dial: '+971' },
                'AU': { name: 'Australia', dial: '+61' },
                'US': { name: 'United States', dial: '+1' },
                'GB': { name: 'United Kingdom', dial: '+44' },
                'JP': { name: 'Japan', dial: '+81' },
                'OTHER': { name: 'International', dial: '+' }
            };
            const c = countryDialCodes[this.form.country_code] || { name: 'International', dial: '+' };
            this.form.country_name = c.name;
            this.form.phone_country_code = c.dial;

            if (this.form.country_code !== 'ID') {
                this.form.province_id = null;
                this.form.regency_id = null;
            }
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

            if (this.eventCode) {
                if (this.eventInstitution) {
                    this.form.detail_institution = this.eventInstitution;
                }
            } else {
                if (this.form.sub_category_type) {
                    this.form.sub_category = this.form.sub_category_type + (this.form.detail_institution ? ' — ' + this.form.detail_institution : '');
                } else {
                    this.form.sub_category = this.form.detail_institution || '';
                }
            }

            const catUpper = (this.form.category || '').toUpperCase();
            if (['MURID', 'PELAJAR', 'GURU'].includes(catUpper)) {
                this.form.school_custom = this.form.detail_institution || '';
                this.form.school_level = (this.form.sub_category_type || '').substring(0, 20);
            } else {
                this.form.school_custom = '';
                this.form.school_level = '';
            }
            if (['MAHASISWA', 'DOSEN'].includes(catUpper)) {
                this.form.university_custom = this.form.detail_institution || '';
            } else {
                this.form.university_custom = '';
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

        checkPosttestStatus() {
            const id = (this.posttestIdentifier || '').trim();
            if (!id) {
                this.posttestError = 'Mohon masukkan nomor WhatsApp atau Email Anda.';
                this.posttestStatus = null;
                return;
            }
            this.posttestChecking = true;
            this.posttestError = '';
            this.posttestStatus = null;

            fetch('{{ route("api.assessment.check_status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    identifier: id,
                    event_code: this.eventCode || ''
                })
            })
            .then(res => res.json())
            .then(data => {
                this.posttestChecking = false;
                if (data.found) {
                    this.posttestStatus = data;
                } else {
                    this.posttestError = data.message || 'Data tidak ditemukan. Pastikan nomor/email sama saat mendaftar Pretest.';
                }
            })
            .catch(err => {
                this.posttestChecking = false;
                this.posttestError = 'Terjadi kesalahan jaringan. Silakan coba kembali.';
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
                <span>✦ {{ $isEn ? 'RUHIOLOGY ASSESSMENT ENGINE' : 'RUHIOLOGY ASSESSMENT ENGINE' }}</span>
            </div>

            <!-- Title -->
            <h1 class="font-serif font-bold text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-tight">
                {{ $isEn ? 'Discover Your Inner Consciousness' : 'Kenali Kesadaran Batin Anda' }}
            </h1>

            <!-- Subtitle -->
            <p class="text-slate-200 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed font-normal opacity-95">
                {{ $isEn ? 'Evaluate your inner spiritual quotient (RQI-15) and emotional vitality (WHO-5) through structured, scientifically validated self-assessment.' : 'Evaluasi tingkat kesadaran batiniah (RQI-15) & vitalitas kesehatan emosional (WHO-5) secara terstruktur, terpercaya, dan aman.' }}
            </p>

            <!-- Action Buttons -->
            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <button type="button" @click="openRegistrationModal('PUBLIC_SELF')" class="bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold text-xs sm:text-sm px-8 py-4 rounded-2xl shadow-xl hover:shadow-[#C9A24D]/30 transition transform hover:-translate-y-0.5 flex items-center space-x-2.5 cursor-pointer">
                    <svg class="w-4 h-4 shrink-0 text-[#0B2A43]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>{{ $isEn ? 'START SELF ASSESSMENT' : 'MULAI ASESMEN MANDIRI' }}</span>
                </button>

                <button type="button" @click="openEventSelectorModal()" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs sm:text-sm font-bold px-8 py-4 rounded-2xl transition backdrop-blur-md flex items-center space-x-2.5 cursor-pointer">
                    <svg class="w-4 h-4 shrink-0 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <span>{{ $isEn ? 'SELECT EVENT / EVENT CODE' : 'PILIH EVENT / KODE ACARA' }}</span>
                </button>
            </div>

            <!-- Informative Notice Badges Grid -->
            <div class="pt-10 max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-left">
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>{{ $isEn ? 'Confidential & Private' : 'Personal & Rahasia' }}</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">{{ $isEn ? 'Assessment results are strictly processed for your personal development.' : 'Hasil asesmen diproses khusus untuk pengembangan diri Anda.' }}</p>
                </div>
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>{{ $isEn ? 'Securely Stored' : 'Tersimpan Aman' }}</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">{{ $isEn ? 'Responses and identities are protected under strict privacy standards.' : 'Jawaban dan identitas dilindungi dengan standar privasi ketat.' }}</p>
                </div>
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>{{ $isEn ? 'Non-Medical' : 'Bukan Medis' }}</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">{{ $isEn ? 'This result is a reflective spiritual assessment, not a medical diagnosis.' : 'Hasil ini merupakan refleksi batin, bukan klaim/diagnosis medis.' }}</p>
                </div>
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>{{ $isEn ? 'WHO-5 Well-Being Index' : 'Skrining WHO-5' }}</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">{{ $isEn ? 'Standardized international instrument to map psychological well-being.' : 'Instrumen terstandar untuk memetakan kesejahteraan batiniah.' }}</p>
                </div>
            </div>

        </div>
    </section>

    <!-- MAIN ACCESS PATHWAYS (EVENT VS MANDIRI) -->
    <section class="py-10 sm:py-14">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-10">

            <!-- A. EVENT MODE ACTIVE BANNER & PRETEST/POSTTEST TABS -->
            <template x-if="eventCode">
                <div class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-[#0B2A43]/15 shadow-xl max-w-4xl mx-auto space-y-6">
                    <!-- Event Header Bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                        <div class="space-y-1">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#0B2A43] text-[#C9A24D] border border-[#C9A24D]/30">
                                <span>✦ SESI PELATIHAN RESMI TERDAFTAR</span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-serif font-bold text-[#0B2A43]" x-text="eventName || eventCode"></h2>
                            <p class="text-xs text-slate-500 font-medium flex items-center gap-2">
                                <span x-text="eventInstitution || 'Instansi Mitra Ruhiology Institute'"></span>
                                <span class="text-slate-300">•</span>
                                <span class="font-mono font-bold text-[#0B2A43]" x-text="'Kode: ' + eventCode"></span>
                            </p>
                        </div>
                        <button type="button" @click="eventCode = ''; inputEventCode = '';" class="self-start sm:self-auto text-xs text-slate-400 hover:text-slate-700 underline font-medium cursor-pointer">
                            Ganti Event / Keluar Mode Event
                        </button>
                    </div>

                    <!-- 2 Big Tabs: Pretest vs Posttest -->
                    <div class="grid grid-cols-2 p-1.5 bg-slate-100/80 rounded-2xl gap-1">
                        <button type="button" @click="eventSessionTab = 'pretest'" 
                                :class="eventSessionTab === 'pretest' ? 'bg-[#0B2A43] text-[#C9A24D] shadow-md font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                                class="py-3 px-4 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                            <span>📝</span>
                            <span>1. SESI PRETEST (Awal)</span>
                        </button>
                        <button type="button" @click="eventSessionTab = 'posttest'" 
                                :class="eventSessionTab === 'posttest' ? 'bg-[#0B2A43] text-[#C9A24D] shadow-md font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                                class="py-3 px-4 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                            <span>🎯</span>
                            <span>2. SESI POSTTEST (Akhir)</span>
                        </button>
                    </div>

                    <!-- TAB 1: PRETEST PANEL -->
                    <div x-show="eventSessionTab === 'pretest'" x-cloak class="space-y-6 pt-2">
                        <div class="p-5 bg-sky-50/70 border border-sky-100 rounded-2xl flex items-start gap-3.5">
                            <span class="text-2xl">💡</span>
                            <div class="space-y-1 text-xs text-slate-700 leading-relaxed">
                                <strong class="text-sky-900 block font-semibold text-sm">Petunjuk Pengisian Pretest</strong>
                                <p>Sesi Pretest dilakukan sebelum materi kegiatan/pelatihan dimulai untuk memetakan capaian awal kesadaran ruhiologi Anda. Peserta baru diwajibkan melengkapi profil identitas diri.</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-5 bg-[#FAF9F6] border border-[#EAE6DF] rounded-2xl">
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Belum Mengisi Data Pretest?</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Daftarkan profil diri Anda sekali saja untuk mendapatkan nomor peserta resmi.</p>
                            </div>
                            <button type="button" @click="openRegistrationModal('EVENT_PROGRAM')" class="w-full sm:w-auto px-6 py-3 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-bold rounded-xl text-xs shadow-md transition cursor-pointer flex items-center justify-center gap-2">
                                <span>📝 Mulai Isi Pretest Sekarang</span>
                                <span>→</span>
                            </button>
                        </div>

                        <!-- Resume Pretest form -->
                        <div class="pt-4 border-t border-slate-100 space-y-3">
                            <span class="text-xs font-bold text-slate-700 block">Sudah pernah isi profil tapi sesi tertutup? Lanjutkan di sini:</span>
                            <form action="{{ route('assessment.verify') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <input type="hidden" name="type" value="pretest">
                                <input type="hidden" name="event_code" :value="eventCode">
                                <input type="hidden" name="period_code" value="{{ $defaultPeriod->period_code ?? 'RQI-PERIOD-2026' }}">
                                <input type="text" name="identifier" required placeholder="Masukkan Nomor WhatsApp atau Email Anda" class="flex-1 p-3 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                <button type="submit" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl transition cursor-pointer">
                                    Lanjut Pretest →
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- TAB 2: POSTTEST PANEL -->
                    <div x-show="eventSessionTab === 'posttest'" x-cloak class="space-y-6 pt-2">
                        <div class="p-5 bg-emerald-50/70 border border-emerald-100 rounded-2xl flex items-start gap-3.5">
                            <span class="text-2xl">🎓</span>
                            <div class="space-y-1 text-xs text-slate-700 leading-relaxed">
                                <strong class="text-emerald-900 block font-semibold text-sm">Petunjuk Pengisian Posttest & Sertifikat</strong>
                                <p>Sesi Post-Test dilakukan setelah rangkaian pelatihan selesai untuk mengukur perkembangan Anda. <strong>Cukup masukkan Nomor WhatsApp atau Email</strong> yang Anda gunakan saat Pretest.</p>
                            </div>
                        </div>

                        <form action="{{ route('assessment.verify') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="type" value="posttest">
                            <input type="hidden" name="event_code" :value="eventCode">
                            <input type="hidden" name="period_code" value="{{ $defaultPeriod->period_code ?? 'RQI-PERIOD-2026' }}">

                            <div>
                                <label class="block font-bold text-slate-800 text-xs mb-1.5">
                                    Nomor WhatsApp atau Email Anda <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="identifier" x-model="posttestIdentifier" @input.debounce.500ms="checkPosttestStatus()" required 
                                           placeholder="Contoh: 08123456789 atau email@domain.com" 
                                           class="w-full p-3.5 rounded-xl border-2 border-slate-300 focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/20 text-sm outline-none font-medium bg-white">
                                    <template x-if="posttestChecking">
                                        <div class="absolute right-3.5 top-3.5 text-xs text-slate-400 flex items-center gap-1.5">
                                            <span class="animate-spin">⏳</span> Memeriksa...
                                        </div>
                                    </template>
                                </div>
                                <span class="text-[11px] text-slate-400 mt-1 block">Pastikan nomor/email persis sama seperti saat Anda mengisi formulir Pretest.</span>
                            </div>

                            <!-- Live Feedback Status Box -->
                            <template x-if="posttestStatus">
                                <div class="p-4 rounded-xl text-xs space-y-2 border transition-all"
                                     :class="posttestStatus.status === 'all_completed' ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900'">
                                    <div class="flex items-center justify-between font-bold">
                                        <span x-text="'✓ Data Ditemukan: ' + posttestStatus.participant_name"></span>
                                        <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded bg-white/70" x-text="posttestStatus.institution || 'Mitra Event'"></span>
                                    </div>
                                    <template x-if="posttestStatus.status === 'pretest_completed'">
                                        <p class="text-[11px] text-emerald-700">Pretest selesai pada tanggal <strong x-text="posttestStatus.pretest_date"></strong>. Anda berhak mengikuti sesi Posttest sekarang!</p>
                                    </template>
                                    <template x-if="posttestStatus.status === 'all_completed'">
                                        <div class="space-y-1">
                                            <p class="text-[11px] text-amber-800">Anda telah menyelesaikan Pretest & Posttest. Sertifikat resmi Anda siap diunduh.</p>
                                            <a :href="posttestStatus.result_url" class="inline-flex items-center gap-1.5 font-bold text-[#0B2A43] underline hover:text-[#B48A16]">
                                                <span>Lihat Hasil & Unduh Sertifikat</span> <span>→</span>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="posttestError">
                                <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-xl flex items-center gap-2">
                                    <span>⚠️</span>
                                    <span x-text="posttestError"></span>
                                </div>
                            </template>

                            <button type="submit" class="w-full py-3.5 px-6 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-bold rounded-xl text-sm shadow-md transition cursor-pointer flex items-center justify-center gap-2">
                                <span>🚀 Mulai Pengerjaan Post-Test & Klaim Sertifikat</span>
                                <span>→</span>
                            </button>
                        </form>
                    </div>
                </div>
            </template>

            <!-- B. PUBLIC MODE: 2 PATHWAYS (EVENT VS MANDIRI) -->
            <template x-if="!eventCode">
                <div class="space-y-10">
                    <div class="text-center max-w-2xl mx-auto space-y-2">
                        <span class="text-xs font-mono font-bold text-[#C9A24D] uppercase tracking-wider block">PILIH JALUR ASESMEN</span>
                        <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#0B2A43]">Silakan Pilih Jalur Kepesertaan Anda</h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-normal">Apakah Anda mengikuti pelatihan bersama mitra/sekolah, atau ingin melakukan asesmen kesadaran jiwa secara mandiri?</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                        
                        <!-- CARD 1: PESERTA EVENT / PELATIHAN -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-slate-200/90 hover:border-[#0B2A43] transition-all shadow-sm hover:shadow-xl flex flex-col justify-between space-y-6 group">
                            <div class="space-y-4">
                                <div class="w-14 h-14 rounded-2xl bg-[#0B2A43] text-[#C9A24D] flex items-center justify-center text-2xl shadow-md group-hover:scale-105 transition-transform">
                                    🏛️
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-mono font-bold text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-200 uppercase tracking-wide">Pelatihan & Mitra Institusi</span>
                                    <h3 class="text-lg font-serif font-bold text-[#0B2A43]">Peserta Event / Mitra</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        Untuk peserta seminar, workshop, sekolah, kampus, atau instansi mitra. Dilengkapi sistem <strong>Pretest & Posttest</strong> serta <strong>Sertifikat Pelatihan Resmi</strong> bertanda tangan Prof. Dr. Iskandar Nazari.
                                    </p>
                                </div>
                                <ul class="text-xs text-slate-600 space-y-1.5 pt-2 border-t border-slate-100">
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-500 font-bold">✓</span> Evaluasi Longitudinal (Pre vs Post)
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-500 font-bold">✓</span> Sertifikat Pelatihan Resmi Ber-QR
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-500 font-bold">✓</span> Akses Mudah Cukup Nomor WhatsApp
                                    </li>
                                </ul>
                            </div>

                            <button type="button" @click="openEventSelectorModal()" class="w-full py-3.5 px-4 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-bold rounded-2xl text-xs transition cursor-pointer flex items-center justify-center gap-2 shadow-sm">
                                <span>Pilih Event / Masukkan Kode Event</span>
                                <span>→</span>
                            </button>
                        </div>

                        <!-- CARD 2: PESERTA MANDIRI (PUBLIK) -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-[#C9A24D]/40 hover:border-[#C9A24D] transition-all shadow-sm hover:shadow-xl flex flex-col justify-between space-y-6 group relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-28 h-28 bg-[#C9A24D]/10 rounded-full blur-xl pointer-events-none"></div>

                            <div class="space-y-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#C9A24D] to-[#B48A16] text-[#0B2A43] flex items-center justify-center text-2xl shadow-md group-hover:scale-105 transition-transform">
                                    🌱
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 uppercase tracking-wide">Personal Self-Checkup</span>
                                    <h3 class="text-lg font-serif font-bold text-[#0B2A43]">Peserta Mandiri (Umum)</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        Untuk masyarakat umum, akademisi, dan profesional yang ingin melakukan <strong>skrining kesadaran spiritual & kesehatan emosional pribadi</strong> secara mandiri dalam 1 sesi lengkap.
                                    </p>
                                </div>
                                <ul class="text-xs text-slate-600 space-y-1.5 pt-2 border-t border-slate-100">
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-500 font-bold">✓</span> 1x Sesi Asesmen Lengkap Langsung Tuntas
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-500 font-bold">✓</span> Grafik Radar 6 Dimensi (RQI-15 & WHO-5)
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-500 font-bold">✓</span> Rapor Refleksi Diri & Kartu Sosial Media
                                    </li>
                                </ul>
                            </div>

                            <button type="button" @click="openRegistrationModal('PUBLIC_SELF')" class="w-full py-3.5 px-4 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold rounded-2xl text-xs transition cursor-pointer flex items-center justify-center gap-2 shadow-md">
                                <span>Mulai Asesmen Mandiri Sekarang</span>
                                <span>→</span>
                            </button>
                        </div>

                    </div>
                </div>
            </template>

            <!-- C. UNIVERSAL QUICK LOOKUP (CEK HASIL & UNDUH SERTIFIKAT TANPA KODE ACAK) -->
            <div class="mt-12 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm max-w-4xl mx-auto space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <h3 class="font-serif font-bold text-base sm:text-lg text-[#0B2A43]">Sudah Pernah Mengerjakan? Cek Hasil & Unduh Sertifikat</h3>
                        <p class="text-xs text-slate-500 font-normal">Cukup masukkan Nomor WhatsApp atau Email yang Anda daftarkan sebelumnya.</p>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200 self-start sm:self-auto">
                        ✓ Verifikasi Cepat
                    </span>
                </div>

                <form action="{{ route('assessment.verify') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
                    @csrf
                    <div class="sm:col-span-6">
                        <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp atau Email</label>
                        <input type="text" name="identifier" required placeholder="Contoh: 08123456789 atau email@domain.com" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium bg-white">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block font-bold text-slate-700 mb-1">Pilihan Sesi</label>
                        <select name="type" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium bg-slate-50">
                            <option value="posttest">POSTTEST / Hasil Akhir</option>
                            <option value="pretest">PRETEST (Awal)</option>
                        </select>
                    </div>
                    <input type="hidden" name="period_code" value="{{ $defaultPeriod->period_code ?? 'RQI-PERIOD-2026' }}">
                    <div class="sm:col-span-3 flex items-end">
                        <button type="submit" class="w-full py-3 px-4 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-bold rounded-xl shadow transition-colors cursor-pointer text-center">
                            Buka Sesi / Hasil →
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
                    <svg class="w-6 h-6 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <div>
                    <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest block">{{ $isEn ? 'ASSESSMENT EVENTS & WORKSHOPS' : 'EVENT & ACARA ASESMEN' }}</span>
                    <h3 class="text-xl font-serif font-bold text-[#0B2A43]">{{ $isEn ? 'Verify Event Access' : 'Verifikasi Akses Event' }}</h3>
                </div>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                {{ $isEn ? 'Please select your active event from the list or enter the Event Passcode provided by your event facilitator.' : 'Silakan pilih nama event dari daftar atau ketik Kode Event / Kunci Sesi yang diberikan oleh panitia untuk memverifikasi akses Anda.' }}
            </p>

            <div class="space-y-4 text-xs font-sans">
                <!-- Dropdown Select Active Events -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">{{ $isEn ? 'Select Active Event (Optional)' : 'Pilih Event Aktif (Opsional)' }}</label>
                    <select x-model="selectedEventId" @change="onEventSelectChange()" class="w-full p-3.5 rounded-2xl border border-slate-300 bg-slate-50 font-medium focus:bg-white focus:ring-2 focus:ring-[#0B2A43] outline-none text-xs">
                        <option value="">{{ $isEn ? '-- Select Event from List --' : '-- Pilih Nama Event dari Daftar --' }}</option>
                        <template x-for="evt in activeEventsList" :key="evt.id">
                            <option :value="evt.id" x-text="evt.title + (evt.institution_name ? ' — ' + evt.institution_name : '')"></option>
                        </template>
                    </select>
                </div>

                <!-- Input Event Code / Kunci Passcode -->
                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">{{ $isEn ? 'Enter Event Code / Session Passcode *' : 'Masukkan Kode Event / Kunci Sesi *' }}</label>
                    <input type="text" x-model="inputEventCode" placeholder="{{ $isEn ? 'Enter Event Code (e.g. RQ-GLOBAL-26)' : 'Masukkan Kode Event (Contoh: RQ-JAMBI-26)' }}" class="w-full p-3.5 rounded-2xl border-2 border-slate-300 focus:border-[#0B2A43] focus:ring-2 focus:ring-[#0B2A43]/20 font-mono font-bold uppercase text-sm tracking-wider text-center outline-none">
                    <span class="text-[10px] text-slate-400 mt-1 block text-center">{{ $isEn ? 'Official code is provided by your event facilitator or host institution.' : 'Kode resmi diberikan oleh panitia / narasumber event Anda.' }}</span>
                </div>

                <template x-if="eventCheckError">
                    <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 font-semibold rounded-xl text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span x-text="eventCheckError"></span>
                    </div>
                </template>

                <div class="pt-2 flex gap-3">
                    <button type="button" @click="showEventSelectorModal = false" class="flex-1 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-xs transition cursor-pointer">
                        {{ $isEn ? 'Cancel' : 'Batal' }}
                    </button>
                    <button type="button" @click="proceedWithEventSelection()" :disabled="verifyingEvent" class="flex-1 py-3.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-extrabold rounded-2xl text-xs shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50">
                        <span x-text="verifyingEvent ? (appLocale === 'en' ? 'Verifying...' : 'Memverifikasi...') : (appLocale === 'en' ? 'Verify & Continue →' : 'Verifikasi & Lanjut →')"></span>
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
                    <span class="text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest block font-mono">{{ $isEn ? 'PARTICIPANT REGISTRATION' : 'REGISTRASI PESERTA' }}</span>
                    <h3 class="text-lg sm:text-xl font-bold font-serif text-[#0B2A43]">{{ $isEn ? 'Ruhiology Assessment Intake Form' : 'Form Intake Asesmen Ruhiologi' }}</h3>
                </div>
                <template x-if="eventCode">
                    <span class="text-xs font-mono font-bold bg-[#0B2A43] text-[#C9A24D] px-3 py-1 rounded-full border border-[#C9A24D]/30 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $isEn ? 'Event Mode: ' : 'Mode Event: ' }}<span x-text="eventCode"></span></span>
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
                                <span class="text-[9px] font-mono font-bold text-[#C9A24D] uppercase tracking-widest flex items-center gap-1.5 mb-1">
                                    <svg class="w-3 h-3 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $isEn ? 'VERIFIED EVENT' : 'TERVERIFIKASI EVENT' }}</span>
                                </span>
                                <h4 class="font-serif font-bold text-base text-white leading-tight" x-text="eventName || eventCode"></h4>
                                <div class="flex items-center gap-2 flex-wrap mt-1">
                                    <p class="text-xs text-slate-300 font-medium" x-text="eventInstitution || '{{ $isEn ? 'Registered Institution' : 'Instansi Terdaftar' }}'"></p>
                                    <template x-if="eventCountryName && eventCountryCode !== 'ID'">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-[#D4AF37]/20 text-[#E5C068] px-2 py-0.5 rounded-full border border-[#D4AF37]/40 font-mono">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span x-text="eventCountryName"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between items-center bg-white/10 p-2.5 rounded-xl border border-white/10">
                                    <span class="text-slate-300">{{ $isEn ? 'Event Code:' : 'Kode Event:' }}</span>
                                    <span class="font-mono font-bold text-[#C9A24D]" x-text="eventCode"></span>
                                </div>
                                <div class="flex justify-between items-center bg-white/10 p-2.5 rounded-xl border border-white/10">
                                    <span class="text-slate-300">{{ $isEn ? 'Participant Category:' : 'Kategori Peserta:' }}</span>
                                    <span class="font-bold text-emerald-400" x-text="form.category"></span>
                                </div>
                            </div>

                            <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl text-[11px] text-amber-200/90 leading-relaxed space-y-1">
                                <div class="font-bold text-amber-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>{{ $isEn ? 'Organization Profile Locked' : 'Data Instansi Dikunci' }}</span>
                                </div>
                                <p class="text-[10px] text-slate-300">
                                    {{ $isEn ? 'Institution and regional details are pre-configured by your host organizer. You only need to fill in your personal identity.' : 'Profil sekolah, instansi, & wilayah sudah diset otomatis oleh panitia event. Anda cukup mengisi identitas diri di samping.' }}
                                </p>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN (7 cols): Quick Personal Intake Inputs -->
                        <div class="md:col-span-7 space-y-3.5 bg-slate-50/80 p-5 rounded-2xl border border-slate-200">
                            <h4 class="font-serif font-bold text-sm text-[#0B2A43] border-b border-slate-200 pb-2">{{ $isEn ? 'Participant Details' : 'Identitas Peserta Event' }}</h4>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">{{ $isEn ? 'Full Name *' : 'Nama Lengkap *' }}</label>
                                <input type="text" x-model="form.name" required placeholder="{{ $isEn ? 'Enter your full legal name...' : 'Masukkan nama lengkap Anda...' }}" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">{{ $isEn ? 'Date of Birth *' : 'Tanggal Lahir *' }}</label>
                                    <input type="date" x-model="form.birth_date" required class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">{{ $isEn ? 'WhatsApp / Phone (Optional)' : 'No. WA / HP (Opsional)' }}</label>
                                    <input type="tel" x-model="form.phone" placeholder="{{ $isEn ? '+1234567890' : '081234567890' }}" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">{{ $isEn ? 'Gender *' : 'Jenis Kelamin *' }}</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="form.gender = 'Laki-laki'" :class="form.gender === 'Laki-laki' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="py-2 px-3 rounded-xl border text-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span>{{ $isEn ? 'Male' : 'Laki-laki' }}</span>
                                    </button>
                                    <button type="button" @click="form.gender = 'Perempuan'" :class="form.gender === 'Perempuan' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="py-2 px-3 rounded-xl border text-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span>{{ $isEn ? 'Female' : 'Perempuan' }}</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Sub-category / Kelas Dropdown or Input -->
                            <template x-if="eventSubcategories && eventSubcategories.length > 0">
                                <div>
                                    <label class="block font-bold text-[#0B2A43] mb-1">
                                        <span x-text="eventGroupLabel || '{{ $isEn ? 'Select Sub-Category / Class / Department' : 'Pilih Sub-Kategori / Kelas / Bidang' }}'"></span> *
                                    </label>
                                    <select x-model="form.sub_category" required class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                        <option value="">{{ $isEn ? '-- Select Class / Stream Option --' : '-- Pilih Opsi Kelas --' }}</option>
                                        <template x-for="item in eventSubcategories" :key="item">
                                            <option :value="item" x-text="item"></option>
                                        </template>
                                    </select>
                                </div>
                            </template>
                            <template x-if="!eventSubcategories || eventSubcategories.length === 0">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">{{ $isEn ? 'Class / Major / Department (Optional)' : 'Kelas / Rombel / Jurusan (Opsional)' }}</label>
                                    <input type="text" x-model="form.school_class" placeholder="{{ $isEn ? 'e.g. Class 10 A / Dept HR' : 'Contoh: Kelas X IPA 1 / XII IPS 2' }}" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                </div>
                            </template>
                        </div>
                    </div>
                </template>


                <!-- ASESMEN MANDIRI MODE: 2-COLUMN LAYOUT -->
                <template x-if="!eventCode">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-[#0B2A43]/5 border border-[#0B2A43]/15 rounded-2xl">
                            <span class="font-bold text-[#0B2A43] text-xs flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>{{ $isEn ? 'Self / Personal Assessment Track' : 'Track Asesmen Mandiri / Personal' }}</span>
                            </span>
                            <button type="button" @click="showRegModal = false; openEventSelectorModal()" class="text-[11px] font-bold text-[#C9A24D] hover:underline cursor-pointer flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                <span>{{ $isEn ? 'Have an Organizer Event Code?' : 'Punya Kode Event Panitia?' }}</span>
                            </button>
                        </div>

                        <!-- Paket Soal / Instrumen Selector (jika > 1 instrumen publik aktif) -->
                        <template x-if="publicInstruments && publicInstruments.length > 1">
                            <div class="p-3.5 bg-amber-500/10 border border-amber-400/40 rounded-2xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block font-serif font-bold text-xs text-[#0B2A43] flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-[#0B2A43]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <span>{{ $isEn ? 'Select Assessment Instrument / Package *' : 'Pilih Paket Soal / Instrumen Asesmen *' }}</span>
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-amber-800 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-full" x-text="publicInstruments.length + ' {{ $isEn ? 'Public Instruments' : 'Paket Soal Publik' }}'"></span>
                                </div>
                                <select x-model="form.instrument_id" required class="w-full p-2.5 text-xs font-bold rounded-xl border border-amber-400 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-slate-800 shadow-xs">
                                    <template x-for="inst in publicInstruments" :key="inst.id">
                                        <option :value="inst.id" x-text="inst.name + (inst.title ? ' — ' + inst.title : '') + (inst.questions_count ? ' (' + inst.questions_count + ' {{ $isEn ? 'Questions' : 'Soal' }})' : '')"></option>
                                    </template>
                                </select>
                            </div>
                        </template>

                        <!-- Single Active Instrument Info Badge -->
                        <template x-if="publicInstruments && publicInstruments.length === 1">
                            <div class="p-3 bg-slate-100/90 border border-slate-200 rounded-2xl flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-[#0B2A43]/10 text-[#0B2A43] flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase font-mono tracking-wider block">{{ $isEn ? 'ASSESSMENT PACKAGE' : 'PAKET SOAL ASESMEN' }}</span>
                                        <strong class="font-bold text-[#0B2A43]" x-text="publicInstruments[0].name"></strong>
                                        <span class="text-slate-500 text-[11px]" x-text="publicInstruments[0].questions_count ? ' (' + publicInstruments[0].questions_count + ' {{ $isEn ? 'Questions' : 'Soal' }})' : ''"></span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                    ✓ {{ $isEn ? 'Active' : 'Aktif' }}
                                </span>
                            </div>
                        </template>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                            <!-- LEFT COLUMN: Identitas Diri & Wilayah -->
                            <div class="space-y-3.5 bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200">
                                <h4 class="font-serif font-bold text-xs text-[#0B2A43] border-b border-slate-200 pb-1.5 uppercase font-mono tracking-wider">{{ app()->getLocale() === 'en' ? 'Step 1: Identity & Location' : 'Langkah 1: Identitas & Wilayah' }}</h4>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'en' ? 'Full Name *' : 'Nama Lengkap *' }}</label>
                                    <input type="text" x-model="form.name" required :placeholder="form.country_code === 'ID' ? 'Masukkan nama lengkap Anda...' : 'Enter your full legal name...'" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                </div>

                                <div class="grid grid-cols-2 gap-2.5">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'en' ? 'Date of Birth *' : 'Tanggal Lahir *' }}</label>
                                        <input type="date" x-model="form.birth_date" required class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'en' ? 'WhatsApp / Phone' : 'No. WA (Opsional)' }}</label>
                                        <input type="tel" x-model="form.phone" placeholder="8123456789" class="w-full p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white text-xs">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'en' ? 'Gender *' : 'Jenis Kelamin *' }}</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" @click="form.gender = 'Laki-laki'" :class="form.gender === 'Laki-laki' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="py-2 px-3 rounded-xl border text-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span>{{ app()->getLocale() === 'en' ? 'Male' : 'Laki-laki' }}</span>
                                        </button>
                                        <button type="button" @click="form.gender = 'Perempuan'" :class="form.gender === 'Perempuan' ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'" class="py-2 px-3 rounded-xl border text-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span>{{ app()->getLocale() === 'en' ? 'Female' : 'Perempuan' }}</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Hidden Country Fields -->
                                <input type="hidden" name="country_code" :value="form.country_code">
                                <input type="hidden" name="country_name" :value="form.country_name">
                                <input type="hidden" name="phone_country_code" :value="form.phone_country_code">

                                <!-- Pilihan Negara / Country Selector -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="font-bold text-slate-700 text-xs">{{ app()->getLocale() === 'en' ? 'Country of Residence *' : 'Negara Domisili *' }}</label>
                                        <span x-show="form.country_code !== 'ID'" class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200 inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>International</span>
                                        </span>
                                    </div>
                                    <select x-model="form.country_code" @change="onCountryChange()" class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                        <option value="ID">🇮🇩 Indonesia</option>
                                        <option value="MY">🇲🇾 Malaysia</option>
                                        <option value="SG">🇸🇬 Singapore</option>
                                        <option value="BN">🇧🇳 Brunei Darussalam</option>
                                        <option value="SA">🇸🇦 Saudi Arabia</option>
                                        <option value="AE">🇦🇪 United Arab Emirates</option>
                                        <option value="AU">🇦🇺 Australia</option>
                                        <option value="US">🇺🇸 United States</option>
                                        <option value="GB">🇬🇧 United Kingdom</option>
                                        <option value="JP">🇯🇵 Japan</option>
                                        <option value="OTHER">🌐 Other / Lainnya</option>
                                    </select>
                                </div>

                                <!-- Wilayah Indonesia (Provinsi & Kab/Kota) -->
                                <div x-show="form.country_code === 'ID'" class="grid grid-cols-2 gap-2.5">
                                    <div>
                                        <label class="block font-bold text-slate-700 text-xs mb-1">Provinsi *</label>
                                        <select x-model="form.province_id" name="province_id" :required="form.country_code === 'ID'" @change="onProvinceChange()" class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <template x-for="p in ((provincesList && provincesList.length > 0) ? provincesList : fallbackProvinces)" :key="p.id">
                                                <option :value="p.id" x-text="p.name" :selected="String(p.id) === String(form.province_id)"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 text-xs mb-1">Kab/Kota</label>
                                        <select x-model="form.regency_id" name="regency_id" :disabled="!form.province_id" class="w-full p-2.5 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white disabled:bg-slate-100">
                                            <option value="">-- Pilih Kab/Kota --</option>
                                            <template x-for="r in filteredRegencies" :key="r.id">
                                                <option :value="r.id" x-text="r.formatted_name || ((r.name && (r.name.startsWith('Kota') || r.name.startsWith('Kabupaten') || !r.type)) ? r.name : ((r.type || '') + ' ' + r.name))" :selected="String(r.id) === String(form.regency_id)"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Wilayah Luar Negeri (State & City) -->
                                <div x-show="form.country_code !== 'ID'" class="grid grid-cols-2 gap-2.5">
                                    <div>
                                        <label class="block font-bold text-slate-700 text-xs mb-1">State / Province</label>
                                        <input type="text" x-model="form.state_custom" name="state_custom" placeholder="e.g. Selangor / California" class="w-full p-2.5 text-xs font-medium rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 text-xs mb-1">City / Town</label>
                                        <input type="text" x-model="form.city_custom" name="city_custom" placeholder="e.g. Kuala Lumpur" class="w-full p-2.5 text-xs font-medium rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: Profil & Kategori 3-Tingkat -->
                            <div class="space-y-3.5 bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200">
                                <h4 class="font-serif font-bold text-xs text-[#0B2A43] border-b border-slate-200 pb-1.5 uppercase font-mono tracking-wider">{{ app()->getLocale() === 'en' ? 'Step 2: Participant Category & Organization' : 'Langkah 2: Kategori & Instansi Peserta' }}</h4>

                                <!-- Level 1: Kategori Utama Dropdown -->
                                <div>
                                    <label class="block font-bold text-slate-800 text-xs mb-1">{{ app()->getLocale() === 'en' ? '1. Primary Category *' : '1. Kategori Utama *' }}</label>
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
                                            2. {{ app()->getLocale() === 'en' ? 'Sub-Category / Stream' : 'Sub-Kategori / Jenis' }} (<span x-text="form.category"></span>) *
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
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">{{ app()->getLocale() === 'en' ? 'Semester (Optional)' : 'Semester (Opsional)' }}</label>
                                            <select x-model="form.semester" class="w-full p-2 text-xs font-medium rounded-xl border border-slate-300 outline-none bg-white">
                                                <template x-for="s in 12" :key="s">
                                                    <option :value="s" x-text="'Semester ' + s"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 text-[11px] mb-1">{{ app()->getLocale() === 'en' ? 'Cohort Year (Optional)' : 'Angkatan (Opsional)' }}</label>
                                            <input type="text" x-model="form.entry_year" placeholder="2024" class="w-full p-2 text-xs rounded-xl border border-slate-300 outline-none bg-white">
                                        </div>
                                    </div>
                                </template>

                                <template x-if="['MURID', 'GURU'].includes(form.category)">
                                    <div class="pt-1">
                                        <label class="block font-bold text-slate-700 text-[11px] mb-1">{{ app()->getLocale() === 'en' ? 'Grade / Class / Major (Optional)' : 'Kelas / Rombel / Jurusan (Opsional)' }}</label>
                                        <input type="text" x-model="form.school_class" :placeholder="appLocale === 'en' ? 'e.g. Grade 10 Science / Major' : 'Contoh: Kelas X IPA 1 / XII IPS 2'" class="w-full p-2 text-xs rounded-xl border border-slate-300 outline-none bg-white">
                                    </div>
                                </template>

                            </div>
                        </div>
                    </div>
                </template>

                <!-- Full-Width Bottom Action Bar -->
                <div class="pt-2">
                    <button type="submit" :disabled="isSubmitting" class="w-full py-3.5 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-extrabold rounded-2xl text-xs uppercase tracking-wider shadow-lg transition-transform hover:scale-[1.005] cursor-pointer disabled:opacity-50 border border-[#C9A24D]/30">
                        <span x-text="isSubmitting ? (appLocale === 'en' ? 'Processing Registration...' : 'Memproses Registrasi...') : (appLocale === 'en' ? 'Generate Assessment Code & Start Test →' : 'Generate Kode Assessment & Mulai Test →')"></span>
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
                <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-widest block font-mono">{{ app()->getLocale() === 'en' ? 'ASSESSMENT CREATED SUCCESSFULLY' : 'ASSESSMENT BERHASIL DIBUAT' }}</span>
                <h3 class="text-xl font-serif font-bold text-[#0B2A43] mt-1">{{ app()->getLocale() === 'en' ? 'Your Unique Assessment Code' : 'Kode Assessment Unik Anda' }}</h3>
                <p class="text-xs text-slate-500 mt-1">{{ app()->getLocale() === 'en' ? 'Save this code to check your results or resume anytime.' : 'Simpan kode ini untuk melihat hasil dan melanjutkan assessment di lain waktu.' }}</p>
            </div>

            <!-- Prominent Code Box -->
            <div class="p-4 bg-slate-900 text-[#C9A24D] rounded-2xl border-2 border-[#C9A24D]/40 font-mono text-2xl font-black tracking-widest flex items-center justify-center space-x-3 shadow-inner">
                <span x-text="generatedCode"></span>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="copyCode()" class="flex-1 py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    <span x-text="copied ? (appLocale === 'en' ? 'Copied!' : 'Tersalin!') : (appLocale === 'en' ? 'COPY CODE' : 'SALIN KODE')"></span>
                </button>
                <a :href="takeUrl" class="flex-1 py-3 px-4 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-xl text-xs shadow-md transition-colors flex items-center justify-center gap-1.5">
                    <span>{{ app()->getLocale() === 'en' ? 'START ASSESSMENT →' : 'MULAI ASSESSMENT →' }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- CHECK SCORE MODAL -->
    <div x-show="showCheckScoreModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
        <div @click.away="showCheckScoreModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100" @click.stop>
            <button @click="showCheckScoreModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
            
            <div class="mb-5 pb-3 border-b border-slate-100">
                <span class="text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest block font-mono">{{ $isEn ? 'OFFICIAL RESULT & CERTIFICATE LOOKUP' : 'CEK HASIL & SERTIFIKAT RESMI' }}</span>
                <h3 class="text-xl font-serif font-bold text-[#0B2A43]">{{ $isEn ? 'Check Score & Download Certificate' : 'Cek Skor & Unduh Sertifikat' }}</h3>
                <p class="text-xs text-slate-500 mt-1">{{ $isEn ? 'Enter your Assessment Code (`RQI-...`), Session Code (`SUB-...`), WhatsApp number, or Full Name.' : 'Masukkan Kode Assessment (`RQI-...`), Kode Sesi (`SUB-...`), Nomor WhatsApp, atau Nama Lengkap Anda.' }}</p>
            </div>

            <form action="{{ route('assessment.check_score') }}" method="POST" class="space-y-4 text-xs font-sans">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">{{ $isEn ? 'Assessment Code / WhatsApp / Full Name *' : 'Kode Assessment / No. WhatsApp / Nama Lengkap *' }}</label>
                    <input type="text" name="assessment_code" required placeholder="{{ $isEn ? 'e.g. RQI-7F82K9 or +123456789 or John Doe...' : 'Contoh: RQI-7F82K9 atau 081234567890 atau Nama...' }}" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-bold text-sm">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">{{ $isEn ? 'Date of Birth' : 'Tanggal Lahir' }} <span class="text-slate-400 font-normal">{{ $isEn ? '(Optional, for additional verification)' : '(Opsional, untuk verifikasi tambahan)' }}</span></label>
                    <input type="date" name="birth_date" class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none font-medium">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-[#0B2A43] hover:bg-[#123B59] text-[#C9A24D] font-bold rounded-xl text-xs uppercase tracking-wider shadow-md transition-colors cursor-pointer">
                        {{ $isEn ? 'Search Report & Certificate →' : 'Cari Laporan Hasil & Sertifikat →' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
