@extends('layouts.app')

@section('content')
<div x-data="rqAssessmentIndex()" class="bg-[#F8F6F0] min-h-screen">

    <!-- HERO SECTION (MODERN, PREMIUM, MINIMAL, BERSIH) -->
    <section class="relative bg-[#0B2A43] text-white pt-16 pb-20 lg:pt-24 lg:pb-28 overflow-hidden border-b border-slate-800">
        <!-- Background Overlay Glow -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-br from-[#C9A24D]/20 via-[#123B59]/30 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-[1280px] mx-auto px-6 sm:px-10 relative z-10 text-center space-y-6">
            
            <!-- Eyebrow Badge -->
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 text-[#C9A24D] text-xs font-mono font-bold px-4 py-2 rounded-full shadow-sm uppercase tracking-widest">
                <span>✦ RQ ASSESSMENT</span>
            </div>

            <!-- Title -->
            <h1 class="font-serif font-bold text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-tight">
                Kenali Diri Anda Lebih Dalam
            </h1>

            <!-- Subtitle -->
            <p class="text-slate-200 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed font-normal opacity-95">
                Assessment Ruhiologi membantu Anda memahami karakter, kesadaran diri, dan kesejahteraan Anda melalui instrumen yang terstruktur.
            </p>

            <!-- Action Buttons -->
            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <button type="button" @click="openRegistrationModal()" class="bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] text-[#0B2A43] font-bold text-xs sm:text-sm px-8 py-4 rounded-2xl shadow-xl hover:shadow-[#C9A24D]/30 transition transform hover:-translate-y-0.5 flex items-center space-x-2 cursor-pointer">
                    <span>✨ MULAI ASSESSMENT</span>
                </button>

                <button type="button" @click="showCheckScoreModal = true" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs sm:text-sm font-bold px-8 py-4 rounded-2xl transition backdrop-blur-md flex items-center space-x-2 cursor-pointer">
                    <span>🔍 CEK SKOR</span>
                </button>
            </div>

            <!-- Informative Notice Badges Grid -->
            <div class="pt-10 max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-left">
                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm space-y-1">
                    <div class="text-xs font-bold text-[#C9A24D] flex items-center gap-1.5">
                        <span>🔒</span> <span>Bersifat Personal</span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-normal">Hasil assessment diproses khusus untuk pengembangan diri Anda.</p>
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

    <!-- ACTIVE PERIODS & EXISTING ACCESS SECTION -->
    <section class="py-12">
        <div class="max-w-[1280px] mx-auto px-6 sm:px-10">
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm max-w-3xl mx-auto space-y-6">
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

    <!-- REGISTRATION MODAL WITH CASCADING AUTOCOMPLETE -->
    <div x-show="showRegModal" x-cloak class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">
        <div @click.away="showRegModal = false" class="bg-white rounded-t-3xl sm:rounded-3xl max-w-2xl w-full p-5 sm:p-8 shadow-2xl relative border border-slate-100 max-h-[85vh] sm:max-h-[90vh] overflow-y-auto" @click.stop>
            
            <!-- Close Button -->
            <button @click="showRegModal = false" type="button" class="absolute top-5 right-5 p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition-colors">
                ✕
            </button>

            <div class="mb-6 pb-3 border-b border-slate-100">
                <span class="text-[10px] font-extrabold text-[#C9A24D] uppercase tracking-widest block font-mono">REGISTRASI PESERTA</span>
                <h3 class="text-xl sm:text-2xl font-bold font-serif text-[#0B2A43]">Form Intake Assessment RQ</h3>
                <p class="text-xs text-slate-500 font-normal mt-1">Lengkapi data pribadi Anda di bawah ini untuk mendapatkan Kode Assessment unik.</p>
            </div>

            <form @submit.prevent="submitRegistration()" class="space-y-4 text-xs font-sans">
                
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

                <!-- 2. Kategori Selection -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Kategori Peserta *</label>
                    <div class="grid grid-cols-3 gap-3">
                        <template x-for="cat in ['Pelajar', 'Mahasiswa/i', 'Umum']" :key="cat">
                            <button type="button" @click="setCategory(cat)"
                                :class="form.category === cat ? 'bg-[#0B2A43] text-white font-bold border-[#0B2A43]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                class="py-2.5 px-3 rounded-xl border text-xs transition-colors text-center">
                                <span x-text="cat"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- 3. Regional Cascading: Province -> Regency -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Province Select Dropdown -->
                    <div>
                        <label class="block font-bold text-slate-700 text-xs mb-1">Provinsi *</label>
                        <select x-model="form.province_id" @change="onProvinceChange()" required class="w-full p-3 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white transition">
                            <option value="">-- Pilih Provinsi --</option>
                            <template x-for="p in filteredProvinces" :key="p.id">
                                <option :value="p.id" x-text="p.name"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Regency Select Dropdown -->
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
                        
                        <!-- University Autocomplete -->
                        <div class="relative">
                            <div class="flex justify-between items-center mb-1">
                                <label class="block font-bold text-slate-800 text-xs">Perguruan Tinggi / Kampus *</label>
                                <button type="button" @click="addInstantUniversity()" class="text-[11px] font-bold text-[#C9A24D] hover:underline flex items-center gap-1 cursor-pointer">
                                    <span>✨ + Tambah Kampus Baru</span>
                                </button>
                            </div>
                            <input type="text" x-model="universityQuery" @focus="showUniversityDropdown = true" @input="searchUniversities()" placeholder="Ketik nama perguruan tinggi (misal: UIN STS Jambi, UNJA, UI, ITB)..." class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white transition">
                            
                            <div x-show="showUniversityDropdown" @click.away="showUniversityDropdown = false" class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-52 overflow-y-auto">
                                <template x-for="u in filteredUniversities" :key="u.id">
                                    <div @click="selectUniversity(u)" class="p-2.5 hover:bg-slate-100 cursor-pointer text-xs border-b border-slate-100 flex justify-between items-center">
                                        <span x-text="u.name" class="font-bold text-[#0B2A43]"></span>
                                        <span class="text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded font-mono">Pilih</span>
                                    </div>
                                </template>
                                <div @click="addInstantUniversity()" class="p-3 bg-blue-100 hover:bg-blue-200 text-blue-900 cursor-pointer text-xs font-bold text-center border-t border-blue-300 flex items-center justify-center gap-1.5">
                                    <span>✨ Kampus tidak ada di daftar? Klik di sini untuk menambahkan langsung.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Faculty & Study Program -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Faculty Autocomplete -->
                            <div class="relative">
                                <label class="block font-bold text-slate-700 text-xs mb-1">Fakultas</label>
                                <input type="text" x-model="facultyQuery" @focus="showFacultyDropdown = true" @input="searchFaculties()" placeholder="Ketik nama fakultas..." class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white transition">
                                
                                <div x-show="showFacultyDropdown && filteredFaculties.length > 0" @click.away="showFacultyDropdown = false" class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                    <template x-for="f in filteredFaculties" :key="f.id">
                                        <div @click="selectFaculty(f)" class="p-2.5 hover:bg-slate-100 cursor-pointer text-xs border-b border-slate-100">
                                            <span x-text="f.name" class="font-semibold text-slate-800"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Study Program Autocomplete -->
                            <div class="relative">
                                <label class="block font-bold text-slate-700 text-xs mb-1">Program Studi (Prodi)</label>
                                <input type="text" x-model="studyProgramQuery" @focus="showStudyProgramDropdown = true" @input="searchStudyPrograms()" placeholder="Ketik nama prodi..." class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white transition">
                                
                                <div x-show="showStudyProgramDropdown && filteredStudyPrograms.length > 0" @click.away="showStudyProgramDropdown = false" class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                    <template x-for="sp in filteredStudyPrograms" :key="sp.id">
                                        <div @click="selectStudyProgram(sp)" class="p-2.5 hover:bg-slate-100 cursor-pointer text-xs border-b border-slate-100">
                                            <span x-text="sp.name" class="font-semibold text-slate-800"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Semester & Angkatan -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Semester</label>
                                <select x-model="form.semester" class="w-full p-3 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white">
                                    <template x-for="s in 12" :key="s">
                                        <option :value="s" x-text="'Semester ' + s"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Angkatan (Tahun Masuk)</label>
                                <input type="text" x-model="form.entry_year" placeholder="Contoh: 2024" class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white">
                            </div>
                        </div>

                    </div>
                </template>

                <!-- 4C. Dynamic Fields for UMUM -->
                <template x-if="form.category === 'Umum'">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                        <label class="block font-bold text-slate-700 text-xs mb-1">Pekerjaan / Sektor Kegiatan</label>
                        <select x-model="form.occupation_id" class="w-full p-3 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white">
                            <option value="">-- Pilih Pekerjaan --</option>
                            <template x-for="occ in occupations" :key="occ.id">
                                <option :value="occ.id" x-text="occ.name"></option>
                            </template>
                        </select>
                        <input type="text" x-model="form.occupation_custom" placeholder="Atau tuliskan pekerjaan/jabatan Anda secara spesifik..." class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-[#0B2A43] outline-none bg-white">
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

    <!-- GENERATED CODE MODAL (POPUP UPON SUCCESSFUL REGISTRATION) -->
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

    <!-- CHECK SCORE MODAL (SECURE CODE + BIRTH DATE VERIFICATION) -->
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
        isSubmitting: false,
        copied: false,
        generatedCode: '',
        takeUrl: '#',

        form: {
            name: '',
            birth_date: '',
            category: 'Pelajar',
            country_id: 1,
            province_id: null,
            regency_id: null,
            school_level: 'SMA',
            school_class: '',
            school_id: null,
            university_id: null,
            faculty_id: null,
            study_program_id: null,
            semester: 1,
            entry_year: new Date().getFullYear().toString(),
            occupation_id: null,
            occupation_custom: ''
        },

        provinceQuery: '',
        regencyQuery: '',
        schoolQuery: '',
        universityQuery: '',
        facultyQuery: '',
        studyProgramQuery: '',

        showProvinceDropdown: false,
        showRegencyDropdown: false,
        showSchoolDropdown: false,
        showUniversityDropdown: false,
        showFacultyDropdown: false,
        showStudyProgramDropdown: false,

        defaultProvinces: [
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
        filteredSchools: [],
        filteredUniversities: [],
        filteredFaculties: [],
        filteredStudyPrograms: [],
        occupations: [],

        init() {
            this.searchProvinces();
            this.loadOccupations();
        },

        openRegistrationModal() {
            if (!this.filteredProvinces || this.filteredProvinces.length === 0) {
                this.searchProvinces();
            }
            if (!this.occupations || this.occupations.length === 0) {
                this.loadOccupations();
            }
            this.showRegModal = true;
        },

        setCategory(cat) {
            this.form.category = cat;
        },

        searchProvinces() {
            fetch(`/api/master/provinces?q=${encodeURIComponent(this.provinceQuery || '')}`)
                .then(res => res.json())
                .then(res => {
                    if (res.data && res.data.length > 0) {
                        this.filteredProvinces = res.data;
                    }
                })
                .catch(() => {});
        },

        selectProvince(p) {
            this.form.province_id = p.id;
            this.provinceQuery = p.name;
            this.showProvinceDropdown = false;
            
            // Reset child regency & schools/universities
            this.form.regency_id = null;
            this.regencyQuery = '';
            this.searchRegencies();
        },

        onProvinceChange() {
            this.form.regency_id = null;
            this.filteredRegencies = [];
            if (this.form.province_id) {
                this.searchRegencies();
            }
        },

        onRegencyChange() {
            this.searchSchools();
            this.searchUniversities();
        },

        searchRegencies() {
            if (!this.form.province_id) return;
            fetch(`/api/master/regencies?province_id=${this.form.province_id}&q=${encodeURIComponent(this.regencyQuery || '')}`)
                .then(res => res.json())
                .then(res => {
                    if (res.data && res.data.length > 0) {
                        this.filteredRegencies = res.data;
                    } else {
                        this.filteredRegencies = [
                            { id: 1, name: 'Kota Jambi', type: 'Kota' },
                            { id: 2, name: 'Muaro Jambi', type: 'Kabupaten' },
                            { id: 3, name: 'Bungo', type: 'Kabupaten' },
                            { id: 4, name: 'Tebo', type: 'Kabupaten' },
                            { id: 5, name: 'Kota Bandung', type: 'Kota' },
                            { id: 6, name: 'Kota Jakarta Selatan', type: 'Kota' },
                            { id: 7, name: 'Kota Surabaya', type: 'Kota' },
                            { id: 8, name: 'Kota Medan', type: 'Kota' },
                            { id: 9, name: 'Kota Makassar', type: 'Kota' },
                            { id: 10, name: 'Lainnya / Kota Lain', type: 'Kabupaten/Kota' }
                        ];
                    }
                })
                .catch(() => {
                    this.filteredRegencies = [
                        { id: 1, name: 'Kota Jambi', type: 'Kota' },
                        { id: 2, name: 'Muaro Jambi', type: 'Kabupaten' },
                        { id: 3, name: 'Bungo', type: 'Kabupaten' },
                        { id: 4, name: 'Tebo', type: 'Kabupaten' },
                        { id: 5, name: 'Kota Bandung', type: 'Kota' },
                        { id: 6, name: 'Kota Jakarta Selatan', type: 'Kota' },
                        { id: 7, name: 'Kota Surabaya', type: 'Kota' },
                        { id: 8, name: 'Kota Medan', type: 'Kota' },
                        { id: 9, name: 'Kota Makassar', type: 'Kota' },
                        { id: 10, name: 'Lainnya / Kota Lain', type: 'Kabupaten/Kota' }
                    ];
                });
        },

        selectRegency(r) {
            this.form.regency_id = r.id;
            this.regencyQuery = r.type + ' ' + r.name;
            this.showRegencyDropdown = false;

            this.searchSchools();
            this.searchUniversities();
        },

        searchSchools() {
            fetch(`/api/master/schools?province_id=${this.form.province_id || ''}&regency_id=${this.form.regency_id || ''}&level=${this.form.school_level || ''}&q=${encodeURIComponent(this.schoolQuery)}`)
                .then(res => res.json())
                .then(res => { this.filteredSchools = res.data || []; });
        },

        selectSchool(s) {
            this.form.school_id = s.id;
            this.schoolQuery = s.name;
            this.showSchoolDropdown = false;
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

            this.form.faculty_id = null;
            this.form.study_program_id = null;
            this.facultyQuery = '';
            this.studyProgramQuery = '';
            this.searchFaculties();
        },

        searchFaculties() {
            if (!this.form.university_id) return;
            fetch(`/api/master/faculties?university_id=${this.form.university_id}&q=${encodeURIComponent(this.facultyQuery)}`)
                .then(res => res.json())
                .then(res => { this.filteredFaculties = res.data || []; });
        },

        selectFaculty(f) {
            this.form.faculty_id = f.id;
            this.facultyQuery = f.name;
            this.showFacultyDropdown = false;

            this.form.study_program_id = null;
            this.studyProgramQuery = '';
            this.searchStudyPrograms();
        },

        searchStudyPrograms() {
            if (!this.form.university_id) return;
            fetch(`/api/master/study-programs?university_id=${this.form.university_id}&faculty_id=${this.form.faculty_id || ''}&q=${encodeURIComponent(this.studyProgramQuery)}`)
                .then(res => res.json())
                .then(res => { this.filteredStudyPrograms = res.data || []; });
        },

        selectStudyProgram(sp) {
            this.form.study_program_id = sp.id;
            this.studyProgramQuery = sp.name;
            this.showStudyProgramDropdown = false;
        },

        loadOccupations() {
            fetch(`/api/master/occupations`)
                .then(res => res.json())
                .then(res => { this.occupations = res.data || []; });
        },

        openPendingModal() {
            const name = prompt('Masukkan nama Sekolah/Kampus Anda yang tidak ada di daftar:');
            if (name && name.trim()) {
                fetch('/api/master/pending-institutions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: name.trim(), category: this.form.category })
                }).then(res => res.json()).then(res => {
                    alert(res.message || 'Data telah diajukan.');
                });
            }
        },

        addInstantUniversity() {
            let name = this.universityQuery ? this.universityQuery.trim() : '';
            if (!name) {
                name = prompt('Masukkan nama Perguruan Tinggi / Kampus Baru:');
            }
            if (!name || !name.trim()) return;

            fetch('/api/master/instant-university', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name.trim(),
                    province_id: this.form.province_id,
                    regency_id: this.form.regency_id
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success' && res.data) {
                    this.selectUniversity(res.data);
                } else {
                    alert(res.message || 'Gagal menambahkan kampus baru.');
                }
            })
            .catch(() => alert('Gagal menambahkan kampus baru.'));
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
