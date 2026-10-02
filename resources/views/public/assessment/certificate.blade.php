<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Resmi RQI & WHO-5 — {{ $submission->participant->name }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1a28;
            color: #0B2A43;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .font-serif-gold {
            font-family: 'Cinzel', serif;
        }
        .font-serif-classic {
            font-family: 'Playfair Display', serif;
        }
        
        @page {
            size: A4 landscape;
            margin: 0;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .cert-scaler-wrapper {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .cert-canvas-box {
                width: 297mm !important;
                height: 210mm !important;
                transform: none !important;
                margin-bottom: 0 !important;
            }
            .cert-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 297mm !important;
                height: 210mm !important;
                max-width: none !important;
                border-width: 8px !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-start sm:justify-center p-2 sm:p-6 md:p-8">

    <!-- Top Action Navigation Bar (Hidden when printing) -->
    <div class="no-print max-w-[1050px] w-full mb-3 sm:mb-5 flex flex-wrap justify-between items-center bg-slate-900/90 text-white p-3.5 rounded-2xl backdrop-blur border border-amber-500/30 shadow-2xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('assessment.result', $submission->submission_code) }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-amber-300 font-semibold rounded-xl text-xs transition border border-amber-500/20 flex items-center gap-2">
                <span>←</span>
                <span>Kembali ke Halaman Hasil</span>
            </a>
            <span class="text-xs text-slate-300 border-l border-slate-700 pl-3 hidden md:inline">
                Sertifikat Hasil Asesmen Ruhiology (RQI) & WHO-5 Mental Well-being
            </span>
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <button onclick="window.print()" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-extrabold rounded-xl text-xs shadow-lg shadow-amber-500/20 transition cursor-pointer flex items-center gap-2">
                <span>🖨️</span>
                <span>Cetak / Simpan PDF (A4 Landscape)</span>
            </button>
        </div>
    </div>

    <!-- Scalable Responsive Wrapper (Locks Certificate to True A4 Landscape Ratio 1050px x 742px) -->
    <div x-data="{ 
            scale: 1, 
            updateScale() { 
                const wrapperWidth = this.$refs.wrapper.clientWidth; 
                if (wrapperWidth < 1070) { 
                    this.scale = Math.max(0.28, (wrapperWidth - 12) / 1050); 
                } else { 
                    this.scale = 1; 
                } 
            } 
         }" 
         x-init="updateScale(); window.addEventListener('resize', () => updateScale()); $nextTick(() => updateScale())" 
         x-ref="wrapper" 
         class="cert-scaler-wrapper w-full max-w-[1070px] mx-auto flex flex-col items-center">

        <!-- Fixed A4 Canvas Box (1050px x 742px) with Smooth Scaler -->
        <div class="cert-canvas-box transition-transform duration-200 origin-top" 
             :style="'width: 1050px; height: 742px; transform: scale(' + scale + '); margin-bottom: -' + ((1 - scale) * 742) + 'px;'">

            <!-- Official Printable Certificate Container -->
            <div class="cert-container w-[1050px] h-[742px] bg-[#F8F6F0] relative p-8 border-[12px] border-[#0B2A43] rounded-3xl shadow-2xl overflow-hidden flex flex-col justify-between"
                 @if(!empty($settings['certificate_bg'])) style="background-image: url('{{ $settings['certificate_bg'] }}'); background-size: cover; background-position: center;" @endif>
                
                <!-- Outer Gold Frame Overlay -->
                <div class="absolute inset-2.5 border-2 border-[#C9A24D] rounded-2xl pointer-events-none"></div>
                <div class="absolute inset-3.5 border border-[#C9A24D]/40 rounded-xl pointer-events-none"></div>
                
                <!-- Corner Ornaments -->
                <div class="absolute top-4 left-4 text-[#C9A24D] text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute top-4 right-4 text-[#C9A24D] text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute bottom-4 left-4 text-[#C9A24D] text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute bottom-4 right-4 text-[#C9A24D] text-xl font-serif leading-none select-none">❖</div>

                <!-- Watermark Background Logo -->
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="w-96 h-96 object-contain" alt="Ruhiology Watermark">
                </div>

                <!-- 1. HEADER SECTION -->
                <div class="relative z-10 text-center space-y-1">
                    <div class="flex flex-col items-center justify-center space-y-0.5 mb-1">
                        <img src="{{ asset('images/ruhiology-logo.png') }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="h-11 w-11 object-contain mx-auto" alt="Logo Ruhiology Institute">
                        <span class="block font-serif-gold text-sm font-bold tracking-widest text-[#0B2A43] leading-none">
                            {{ $settings['institute_name'] ?? 'RUHIOLOGY INSTITUTE' }}
                        </span>
                        <span class="block text-[8px] tracking-widest uppercase text-amber-800 font-bold">
                            {{ $settings['tagline'] ?? 'Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.' }}
                        </span>
                    </div>

                    <div>
                        <h1 class="font-serif-gold text-2xl font-extrabold text-[#0B2A43] tracking-wide uppercase">
                            SERTIFIKAT HASIL ASESMEN RQI
                        </h1>
                        <p class="text-[10px] font-serif italic text-amber-800 tracking-wider mt-0.5">
                            Ruhiology Quotient & Mental Well-being Assessment Certificate
                        </p>
                    </div>
                </div>

                <!-- 2. RECIPIENT & BODY SECTION -->
                <div class="relative z-10 text-center space-y-2">
                    <span class="text-[10px] uppercase font-serif tracking-widest text-slate-500 font-bold block">DIBERIKAN KEPADA :</span>

                    <!-- Participant Name -->
                    <div>
                        <h2 class="font-serif-classic text-3xl font-extrabold text-[#0B2A43] tracking-wide border-b-2 border-amber-400/80 inline-block px-8 pb-0.5">
                            {{ $submission->participant->name }}
                        </h2>
                        <p class="text-[11px] text-slate-600 font-medium mt-1">
                            Kategori: <strong class="text-slate-800">{{ $submission->participant->category }}</strong>
                            @php
                                $instName = $submission->participant->school_custom 
                                    ?? $submission->participant->university_custom 
                                    ?? $submission->participant->school->name 
                                    ?? $submission->participant->university->name 
                                    ?? $submission->participant->occupation 
                                    ?? null;
                            @endphp
                            @if($instName)
                                • {{ $instName }}
                            @endif
                            @if($submission->participant->regency)
                                • {{ $submission->participant->regency->name }}
                            @endif
                            @if($submission->participant->province)
                                , {{ $submission->participant->province->name }}
                            @endif
                        </p>
                    </div>

                    <p class="text-xs text-slate-700 max-w-2xl mx-auto leading-relaxed font-normal">
                        Diberikan kepada peserta di atas atas partisipasi dan pencapaian evaluasi potensi diri dalam Asesmen Ruhiology Quotient (RQI) serta Skrining Kesejahteraan Emosional (WHO-5 Index).
                    </p>

                    <!-- DUAL SCORE CARDS (SIDE-BY-SIDE: RQI SCORE + WHO-5 SCORE) -->
                    <div class="grid grid-cols-2 gap-4 max-w-xl mx-auto my-1">
                        
                        <!-- CARD 1: SKOR RUHIOLOGI (RQI) -->
                        <div class="bg-gradient-to-br from-white to-amber-50/80 p-2.5 rounded-2xl border border-amber-300 shadow-2xs text-center space-y-0.5">
                            <span class="text-[9px] uppercase font-mono font-bold text-amber-900 block tracking-wider">SKOR RQI (0-100)</span>
                            <strong class="font-serif-gold text-2xl font-black text-[#0B2A43] block">
                                {{ number_format($submission->result->rqi_score ?? $submission->result->total_score ?? 0, 1) }}
                            </strong>
                            <span class="text-[9px] font-serif italic font-bold text-amber-900 bg-amber-100/90 px-2.5 py-0.5 rounded-full border border-amber-300 inline-block truncate max-w-full">
                                ✦ {{ $submission->result->category_name ?? $submission->result->overall_interpretation ?? 'Mindful Youth' }}
                            </span>
                        </div>

                        <!-- CARD 2: SKOR KESEJAHTERAAN MENTAL (WHO-5) -->
                        <div class="bg-gradient-to-br from-white to-emerald-50/80 p-2.5 rounded-2xl border border-emerald-300 shadow-2xs text-center space-y-0.5">
                            <span class="text-[9px] uppercase font-mono font-bold text-emerald-900 block tracking-wider">INDEKS KESEJAHTERAAN MENTAL (WHO-5)</span>
                            <strong class="font-serif-gold text-2xl font-black text-emerald-700 block">
                                {{ number_format($submission->result->who5_percentage ?? 0, 0) }}%
                            </strong>
                            @php
                                $who5Note = $submission->result->who5_screening_note ?? (($submission->result->who5_percentage ?? 0) >= 50 ? 'Kesejahteraan Baik' : 'Indikasi Perlu Skrining');
                                $isGood = str_contains(strtolower($who5Note), 'baik') || str_contains(strtolower($who5Note), 'sehat');
                            @endphp
                            <span class="text-[9px] font-serif italic font-bold {{ $isGood ? 'text-emerald-900 bg-emerald-100/90 border-emerald-300' : 'text-rose-900 bg-rose-100/90 border-rose-300' }} px-2.5 py-0.5 rounded-full border inline-block truncate max-w-full">
                                {{ $isGood ? '🌱' : '⚠️' }} {{ $who5Note }}
                            </span>
                        </div>

                    </div>

                    <!-- 5-DIMENSION COMPETENCY MATRIX GRID -->
                    @if(isset($submission->result->dimensionResults) && count($submission->result->dimensionResults) > 0)
                        <div class="max-w-xl mx-auto bg-white/90 p-2 rounded-2xl border border-amber-200/90 shadow-2xs space-y-0.5">
                            <span class="text-[8px] font-mono font-bold uppercase tracking-wider text-slate-400 block text-center">Capaian 5 Dimensi Utama Ruhiologi</span>
                            <div class="grid grid-cols-5 gap-1.5">
                                @foreach($submission->result->dimensionResults as $dr)
                                    <div class="bg-amber-50/80 p-1 rounded-xl border border-amber-200 text-center">
                                        <span class="block text-[8px] font-bold text-slate-700 truncate" title="{{ $dr->dimension->name }}">
                                            {{ Str::limit($dr->dimension->name, 12) }}
                                        </span>
                                        <span class="font-mono font-bold text-xs text-[#0B2A43]">
                                            {{ round($dr->percentage) }}%
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- 3. FOOTER SECTION -->
                <div class="relative z-10 space-y-1.5 pt-2 border-t border-amber-200/80 font-sans">
                    
                    <!-- Ruhiology Philosophical Quote Line -->
                    <div class="text-center">
                        <p class="text-[9px] font-serif italic text-slate-600 max-w-xl mx-auto">
                            "Ruh adalah pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, serta komitmen etis." — Prof. Dr. Iskandar Nazari
                        </p>
                    </div>

                    <div class="flex justify-between items-end gap-4">
                        
                        <!-- BOTTOM LEFT: DOCUMENT NO & OFFICIAL VERIFICATION METADATA -->
                        <div class="text-left space-y-0.5">
                            <div class="inline-block px-2.5 py-1 bg-amber-100/90 border border-amber-300 rounded-lg text-[9px] font-mono font-bold text-amber-950">
                                NO: CERT/RQI/{{ date('Y') }}/{{ strtoupper($submission->submission_code) }}
                            </div>
                            <span class="block text-[8px] font-mono text-emerald-800 font-bold">✓ Terverifikasi Resmi Sistem Ruhiology</span>
                            <span class="block text-[8px] font-mono text-slate-500">
                                Tanggal Terbit: {{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d F Y') : date('d F Y') }}
                            </span>
                        </div>

                        <!-- BOTTOM CENTER: OFFICIAL INSTITUTE EMBOSS SEAL -->
                        <div class="text-center">
                            <div class="w-11 h-11 rounded-full border-2 border-dashed border-amber-500/70 p-0.5 flex items-center justify-center mx-auto shadow-sm">
                                <div class="w-full h-full rounded-full bg-amber-50 flex flex-col items-center justify-center text-[5.5px] font-serif font-bold text-amber-900 border border-amber-300 leading-tight">
                                    <span>RUHIOLOGY</span>
                                    <span class="text-[4px] text-amber-700 font-mono">VERIFIED</span>
                                    <span>INSTITUTE</span>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM RIGHT: DIGITAL QR CODE VERIFICATION & FOUNDER TITLE -->
                        <div class="text-center space-y-1 min-w-[200px]">
                            <p class="text-[8.5px] text-slate-500 font-medium">Jambi, {{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d F Y') : date('d F Y') }}</p>
                            
                            <!-- QR Code Validasi -->
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-1 bg-white border border-amber-300 rounded-xl shadow-xs inline-block">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=85x85&data={{ urlencode(route('assessment.result', $submission->submission_code)) }}" 
                                         alt="QR Code Verifikasi Sertifikat" 
                                         class="w-12 h-12 object-contain">
                                </div>
                                <span class="text-[7.5px] font-mono font-bold text-amber-900 mt-0.5 block">
                                    🔑 Verifikasi Keabsahan QR Code
                                </span>
                            </div>

                            <div class="border-t border-slate-400/80 pt-0.5">
                                <h3 class="font-serif-classic text-[10.5px] font-bold text-[#0B2A43] leading-tight">
                                    {{ $settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.' }}
                                </h3>
                                <p class="text-[8px] text-amber-800 font-medium">
                                    {{ $settings['founder_title'] ?? 'Founder Ruhiology Institute' }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
