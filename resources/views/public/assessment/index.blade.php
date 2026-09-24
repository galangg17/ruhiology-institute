@extends('layouts.app')

@section('content')
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

    <!-- REGISTRATION INTAKE MODAL -->
    <div x-show="showRegModal" x-cloak class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
        <div @click.away="showRegModal = false" class="bg-white rounded-t-3xl sm:rounded-3xl max-w-2xl w-full p-5 sm:p-8 shadow-2xl relative border border-slate-100 max-h-[85vh] sm:max-h-[90vh] overflow-y-auto" @click.stop>
            
            <!-- Close Button -->
            <button @click="showRegModal = false" type="button" class="absolute top-5 right-5 p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition-colors">
                ✕
            </button>

            <div class="mb-6 pb-3 border-b border-slate-100">
                <span class="text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest block font-mono">REGISTRASI PESERTA</span>
                <h3 class="text-xl sm:text-2xl font-bold font-serif text-[#0B2A43]">Form Intake Asesmen Ruhiologi</h3>
                <p class="text-xs text-slate-500 font-normal mt-1">Lengkapi data pribadi Anda di bawah ini untuk mendapatkan Kode Assessment unik.</p>
            </div>

            <form @submit.prevent="submitRegistration()" class="space-y-4 text-xs font-sans">
                
                <!-- Track Indicator Banner -->
                <div class="p-3.5 bg-[#0B2A43]/5 border border-[#0B2A43]/15 rounded-2xl space-y-1">
                    <span class="text-[10px] font-mono font-bold text-[#C9A24D] uppercase tracking-wider block">Jalur Asesmen Dipilih</span>
                    <template x-if="eventCode">
                        <div class="flex items-center justify-between">
                            <div class="space-y-0.5">
                                <span class="font-bold text-[#0B2A43] text-xs block">📌 Event: <span x-text="eventName || eventCode"></span></span>
                                <template x-if="eventInstitution">
                                    <span class="text-[11px] text-slate-500 block font-medium">Instansi: <strong class="text-slate-800" x-text="eventInstitution"></strong></span>
                                </template>
                            </div>
                            <span class="text-[10px] font-mono font-bold bg-[#0B2A43] text-white px-2.5 py-1 rounded-full shrink-0" x-text="eventCode"></span>
                        </div>
                    </template>
                    <template x-if="!eventCode">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">🟢 Asesmen Mandiri / Personal (Umum)</span>
                            <button type="button" @click="showRegModal = false; openEventSelectorModal()" class="text-[11px] font-bold text-[#C9A24D] hover:underline cursor-pointer">
                                🎯 Pilih Event Acara?
                            </button>
                        </div>
                    </template>
                </div>
                
                <!-- 1. Nama & Tanggal Lahir -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" x-model="form.name" required placeholder="Masukkan nama lengkap Anda..." class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Lahir *</label>
                        <input type="date" x-model="form.birth_date" required class="w-full p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none">
                    </div>
                </div>

                <!-- 2. Kategori Selection (Preset Locked if Event restricts it) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        Kategori Peserta *
                        <template x-if="isCategoryLocked">
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded ml-2 border border-emerald-200">🔒 Terkunci dari Event</span>
                        </template>
                    </label>

                    <template x-if="isCategoryLocked">
                        <div class="p-3 bg-emerald-50/80 rounded-xl border border-emerald-200 text-xs font-bold text-emerald-900 flex items-center justify-between">
                            <span x-text="'Kategori Event: ' + (form.category === 'Umum' ? 'Personal / Mandiri' : form.category)"></span>
                            <span>✅ Terverifikasi</span>
                        </div>
                    </template>

                    <template x-if="!isCategoryLocked">
                        <div class="grid grid-cols-3 gap-3">
                            <template x-for="cat in [
                                { key: 'Pelajar', label: 'Pelajar' },
                                { key: 'Mahasiswa/i', label: 'Mahasiswa/i' },
                                { key: 'Umum', label: 'Personal / Mandiri' }
                            ]" :key="cat.key">
                                <button type="button" @click="setCategory(cat.key)"
                                    :class="form.category === cat.key ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                    class="py-2.5 px-3 rounded-xl border text-xs transition-colors text-center">
                                    <span x-text="cat.label"></span>
                                </button>
                    </template>
                </div>

                <!-- Dynamic Sub-Category / Group Dropdown (If configured on event) -->
                <template x-if="eventSubcategories && eventSubcategories.length > 0">
                    <div class="p-4 bg-blue-50/70 border border-blue-200/90 rounded-2xl space-y-1.5">
                        <label class="block font-bold text-[#0B2A43] text-xs">
                            <span x-text="eventGroupLabel || 'Pilih Sub-Kategori / Kelas / Bidang'"></span> *
                        </label>
                        <select x-model="form.sub_category" required class="w-full p-3 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                            <option value="">-- Pilih Opsi --</option>
                            <template x-for="item in eventSubcategories" :key="item">
                                <option :value="item" x-text="item"></option>
                            </template>
                        </select>
                    </div>
                </template>

                <!-- 3. Regional Cascading: Province -> Regency -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 text-xs mb-1">Provinsi *</label>
                        <select x-model="form.province_id" @change="onProvinceChange()" required class="w-full p-3 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white transition">
                            <option value="">-- Pilih Provinsi --</option>
                            <template x-for="p in filteredProvinces" :key="p.id">
                                <option :value="p.id" x-text="p.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 text-xs mb-1">Kabupaten/Kota *</label>
                        <select x-model="form.regency_id" @change="onRegencyChange()" :disabled="!form.province_id" required class="w-full p-3 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white disabled:bg-slate-100 disabled:cursor-not-allowed transition">
                            <option value="" x-text="form.province_id ? '-- Pilih Kabupaten/Kota --' : 'Pilih provinsi dahulu'"></option>
                            <template x-for="r in filteredRegencies" :key="r.id">
                                <option :value="r.id" x-text="(r.type ? r.type + ' ' : '') + r.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- 4A. Dynamic Fields for PELAJAR -->
                <template x-if="form.category === 'Pelajar'">
                    <div class="p-4 bg-amber-50/70 rounded-2xl border border-amber-200/90 space-y-2">
                        <label class="block font-bold text-slate-800 text-xs mb-1">Jenjang Pendidikan *</label>
                        <select x-model="form.school_level" class="w-full p-3 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white">
                            <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                            <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
                            <option value="MA">MA (Madrasah Aliyah)</option>
                            <option value="SMP">SMP / MTs (Sekolah Menengah Pertama)</option>
                            <option value="SD">SD / MI (Sekolah Dasar)</option>
                            <option value="Sederajat">Sederajat / Lainnya</option>
                        </select>
                    </div>
                </template>

                <!-- 4B. Dynamic Fields for MAHASISWA/I -->
                <template x-if="form.category === 'Mahasiswa/i'">
                    <div class="p-4 bg-blue-50/60 rounded-2xl border border-blue-200/80 space-y-4">
                        <div class="relative">
                            <div class="flex justify-between items-center mb-1">
                                <label class="block font-bold text-slate-800 text-xs">Perguruan Tinggi / Kampus *</label>
                                <button type="button" @click="addInstantUniversity()" class="text-[11px] font-bold text-[#C9A24D] hover:underline flex items-center gap-1 cursor-pointer">
                                    <span>✨ + Tambah Kampus Baru</span>
                                </button>
                            </div>
                            <input type="text" x-model="universityQuery" @focus="showUniversityDropdown = true" @input="searchUniversities()" placeholder="Ketik nama perguruan tinggi..." class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white transition">
                            
                            <div x-show="showUniversityDropdown" @click.away="showUniversityDropdown = false" class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-52 overflow-y-auto">
                                <template x-for="u in filteredUniversities" :key="u.id">
                                    <div @click="selectUniversity(u)" class="p-2.5 hover:bg-slate-100 cursor-pointer text-xs border-b border-slate-100 flex justify-between items-center">
                                        <span x-text="u.name" class="font-bold text-[#0B2A43]"></span>
                                        <span class="text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded font-mono">Pilih</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Semester</label>
                                <select x-model="form.semester" class="w-full p-3 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                                    <template x-for="s in 12" :key="s">
                                        <option :value="s" x-text="'Semester ' + s"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Angkatan</label>
                                <input type="text" x-model="form.entry_year" placeholder="Contoh: 2024" class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                            </div>
                        </div>
                    </div>
                </template>

                <!-- 4C. Dynamic Fields for PERSONAL / MANDIRI (UMUM) -->
                <template x-if="form.category === 'Umum'">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                        <label class="block font-bold text-slate-700 text-xs mb-1">Pekerjaan / Sektor Kegiatan</label>
                        <select x-model="form.occupation_id" class="w-full p-3 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white">
                            <option value="">-- Pilih Pekerjaan --</option>
                            <template x-for="occ in occupations" :key="occ.id">
                                <option :value="occ.id" x-text="occ.name"></option>
                            </template>
                        </select>
                        <input type="text" x-model="form.occupation_custom" placeholder="Atau tuliskan pekerjaan Anda secara spesifik..." class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] outline-none bg-white">
                    </div>
                </template>

                <div class="pt-4">
                    <button type="submit" :disabled="isSubmitting" class="w-full py-4 bg-[#0B2A43] hover:bg-[#123B59] text-white font-bold rounded-2xl text-xs uppercase tracking-wider shadow-lg transition-transform hover:scale-[1.01] cursor-pointer disabled:opacity-50">
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

@push('scripts')
<script>
function rqAssessmentIndex() {
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

        form: {
            event_code: new URLSearchParams(window.location.search).get('event') || '',
            name: '',
            birth_date: '',
            category: 'Pelajar',
            sub_category: '',
            country_id: 1,
            province_id: null,
            regency_id: null,
            school_level: 'SMA',
            university_id: null,
            faculty_id: null,
            study_program_id: null,
            semester: 1,
            entry_year: new Date().getFullYear().toString(),
            occupation_id: null,
            occupation_custom: ''
        },

        filteredProvinces: [
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
            { id: 31, name: 'Maluku' }, { id: 32, name: 'Maluku Utara' }, { id: 33, name: 'Papua' },
            { id: 34, name: 'Papua Barat' }, { id: 35, name: 'Papua Selatan' }, { id: 36, name: 'Papua Tengah' },
            { id: 37, name: 'Papua Pegunungan' }, { id: 38, name: 'Papua Barat Daya' }
        ],
        filteredRegencies: [],
        filteredUniversities: [],
        occupations: [],

        init() {
            this.loadOccupations();
            if (this.eventCode) {
                this.inputEventCode = this.eventCode;
                this.verifyEventCodeAsync(this.eventCode);
            }
        },

        openRegistrationModal(track = 'PUBLIC_SELF') {
            if (track === 'PUBLIC_SELF') {
                this.eventCode = '';
                this.eventName = '';
                this.eventInstitution = '';
                this.form.event_code = '';
                this.form.sub_category = '';
                this.eventGroupLabel = '';
                this.eventSubcategories = [];
                this.isCategoryLocked = false;
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

                    // Group / Subcategory Options
                    if (evt.custom_subcategories && evt.custom_subcategories.length > 0) {
                        this.eventGroupLabel = evt.group_label || 'Pilih Sub-Kategori / Kelas / Bidang';
                        this.eventSubcategories = evt.custom_subcategories;
                    } else {
                        this.eventGroupLabel = '';
                        this.eventSubcategories = [];
                    }

                    // Preset Category Lock
                    if (evt.target_category) {
                        this.form.category = evt.target_category;
                        this.isCategoryLocked = true;
                    } else {
                        this.isCategoryLocked = false;
                    }

                    // Preset Province / Regency if configured
                    if (evt.province_id) {
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

        setCategory(cat) {
            if (this.isCategoryLocked) return;
            this.form.category = cat;
        },

        onProvinceChange() {
            this.form.regency_id = null;
            this.filteredRegencies = [];
            if (this.form.province_id) {
                this.searchRegencies();
            }
        },

        searchRegencies() {
            if (!this.form.province_id) return;
            fetch(`/api/master/regencies?province_id=${this.form.province_id}`)
                .then(res => res.json())
                .then(res => {
                    this.filteredRegencies = res.data || [];
                })
                .catch(() => {});
        },

        searchUniversities() {
            fetch(`/api/master/universities?province_id=${this.form.province_id || ''}&q=${encodeURIComponent(this.universityQuery)}`)
                .then(res => res.json())
                .then(res => { this.filteredUniversities = res.data || []; });
        },

        selectUniversity(u) {
            this.form.university_id = u.id;
            this.universityQuery = u.name;
            this.showUniversityDropdown = false;
        },

        loadOccupations() {
            fetch(`/api/master/occupations`)
                .then(res => res.json())
                .then(res => { this.occupations = res.data || []; });
        },

        submitRegistration() {
            this.isSubmitting = true;

            fetch('{{ route("assessment.register") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.form)
            })
            .then(res => res.json())
            .then(data => {
                this.isSubmitting = false;
                if (data.status === 'success') {
                    this.generatedCode = data.assessment_code;
                    this.takeUrl = data.take_url;
                    this.showRegModal = false;
                    this.showGeneratedCodeModal = true;
                } else {
                    alert(data.message || 'Gagal mendaftar. Silakan cek form Anda.');
                }
            })
            .catch(err => {
                this.isSubmitting = false;
                alert('Terjadi kesalahan jaringan.');
            });
        },

        copyCode() {
            navigator.clipboard.writeText(this.generatedCode);
            this.copied = true;
            setTimeout(() => { this.copied = false; }, 3000);
        }
    }
}
</script>
@endpush
@endsection
