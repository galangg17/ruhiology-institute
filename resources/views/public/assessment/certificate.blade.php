<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Resmi RQI & WHO-5 — {{ $submission->participant->name }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- html2canvas & jsPDF for 1-Click Direct Download on Mobile & Desktop -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #071521;
            color: #0B2A43;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .font-heading {
            font-family: 'Poppins', sans-serif;
            letter-spacing: -0.015em;
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
                border-width: 10px !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-start sm:justify-center p-2 sm:p-6 md:p-8"
      x-data="{
          scale: 1,
          isExporting: false,
          exportStatus: '',
          updateScale() {
              const wrapperWidth = this.$refs.wrapper ? this.$refs.wrapper.clientWidth : window.innerWidth;
              if (wrapperWidth < 1070) {
                  this.scale = Math.max(0.28, (wrapperWidth - 12) / 1050);
              } else {
                  this.scale = 1;
              }
          },
          async captureCanvas() {
              const node = document.getElementById('certificateNode');
              const box = document.getElementById('certificateScaleBox');
              const prevTransform = box.style.transform;
              const prevMargin = box.style.marginBottom;
              box.style.transform = 'none';
              box.style.marginBottom = '0px';
              await new Promise(r => setTimeout(r, 120));
              try {
                  const canvas = await html2canvas(node, {
                      scale: 2.5,
                      useCORS: true,
                      allowTaint: true,
                      backgroundColor: '#FAF8F5',
                      width: 1050,
                      height: 742,
                      windowWidth: 1200,
                      windowHeight: 900
                  });
                  return canvas;
              } finally {
                  box.style.transform = prevTransform;
                  box.style.marginBottom = prevMargin;
              }
          },
          async downloadPNG() {
              if (this.isExporting) return;
              this.isExporting = true;
              this.exportStatus = 'Menyiapkan Gambar HD...';
              try {
                  const canvas = await this.captureCanvas();
                  const link = document.createElement('a');
                  link.download = 'Sertifikat_RQI_{{ $submission->submission_code }}.png';
                  link.href = canvas.toDataURL('image/png', 1.0);
                  link.click();
              } catch (e) {
                  console.error(e);
                  window.print();
              } finally {
                  this.isExporting = false;
                  this.exportStatus = '';
              }
          },
          async downloadPDF() {
              if (this.isExporting) return;
              this.isExporting = true;
              this.exportStatus = 'Menyiapkan PDF A4...';
              try {
                  const canvas = await this.captureCanvas();
                  const imgData = canvas.toDataURL('image/jpeg', 0.96);
                  const { jsPDF } = window.jspdf;
                  const pdf = new jsPDF({
                      orientation: 'landscape',
                      unit: 'mm',
                      format: 'a4'
                  });
                  pdf.addImage(imgData, 'JPEG', 0, 0, 297, 210);
                  pdf.save('Sertifikat_RQI_{{ $submission->submission_code }}.pdf');
              } catch (e) {
                  console.error(e);
                  window.print();
              } finally {
                  this.isExporting = false;
                  this.exportStatus = '';
              }
          }
      }"
      x-init="updateScale(); window.addEventListener('resize', () => updateScale()); $nextTick(() => updateScale())">

    <!-- Top Action Navigation Bar (Hidden when printing) -->
    <div class="no-print max-w-[1050px] w-full mb-3 sm:mb-5 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 bg-slate-900/95 text-white p-3.5 sm:px-5 rounded-2xl backdrop-blur-md border border-[#C9A24D]/35 shadow-2xl">
        <div class="flex items-center justify-between sm:justify-start gap-3">
            <a href="{{ route('assessment.result', $submission->submission_code) }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-[#C9A24D] font-bold rounded-xl text-xs transition border border-[#C9A24D]/25 flex items-center gap-2">
                <span>←</span>
                <span>Kembali ke Hasil</span>
            </a>
            <div class="hidden lg:block border-l border-slate-700 pl-3">
                <span class="text-xs font-heading font-semibold text-white block">Sertifikat Resmi RQI-15 & WHO-5</span>
                <span class="text-[10px] text-slate-400">Kode Dokumen: {{ $submission->submission_code }}</span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <template x-if="isExporting">
                <span class="text-xs font-mono text-amber-300 px-3 py-1.5 bg-amber-500/10 rounded-xl border border-amber-400/30 animate-pulse" x-text="exportStatus"></span>
            </template>

            <button type="button" @click="downloadPNG()" :disabled="isExporting" class="flex-1 sm:flex-none justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-heading font-bold rounded-xl text-xs shadow-lg transition cursor-pointer flex items-center gap-1.5">
                <span>🖼️</span>
                <span>Unduh Gambar (PNG HD)</span>
            </button>

            <button type="button" @click="downloadPDF()" :disabled="isExporting" class="flex-1 sm:flex-none justify-center px-4 py-2.5 bg-gradient-to-r from-[#C9A24D] to-[#B48A16] hover:from-[#B48A16] hover:to-[#96710E] disabled:opacity-50 text-[#0B2A43] font-heading font-extrabold rounded-xl text-xs shadow-lg shadow-amber-500/20 transition cursor-pointer flex items-center gap-1.5">
                <span>📄</span>
                <span>Unduh PDF (A4)</span>
            </button>

            <button type="button" onclick="window.print()" class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-heading font-semibold rounded-xl text-xs border border-slate-700 transition cursor-pointer flex items-center gap-1.5" title="Cetak Langsung">
                <span>🖨️</span>
                <span class="hidden sm:inline">Cetak</span>
            </button>
        </div>
    </div>

    <!-- Scalable Responsive Wrapper (Locks Certificate to True A4 Landscape Ratio 1050px x 742px) -->
    <div x-ref="wrapper" class="cert-scaler-wrapper w-full max-w-[1070px] mx-auto flex flex-col items-center">

        <!-- Fixed A4 Canvas Box (1050px x 742px) with Smooth Scaler -->
        <div id="certificateScaleBox"
             class="cert-canvas-box transition-transform duration-200 origin-top" 
             :style="'width: 1050px; height: 742px; transform: scale(' + scale + '); margin-bottom: -' + ((1 - scale) * 742) + 'px;'">

            <!-- Official Printable Certificate Container -->
            <div id="certificateNode"
                 class="cert-container w-[1050px] h-[742px] bg-[#FAF8F5] relative px-10 py-7 border-[12px] border-[#0B2A43] rounded-3xl shadow-2xl overflow-hidden flex flex-col justify-between"
                 @if(!empty($settings['certificate_bg'])) style="background-image: url('{{ $settings['certificate_bg'] }}'); background-size: cover; background-position: center;" @endif>
                
                <!-- Subtle Luxury Guilloche / Radial Glow Background -->
                <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-[#C9A24D]/10 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-72 h-72 rounded-full bg-[#0B2A43]/8 blur-3xl pointer-events-none"></div>

                <!-- Outer Gold Frame Overlay -->
                <div class="absolute inset-2.5 border-2 border-[#C9A24D] rounded-2xl pointer-events-none"></div>
                <div class="absolute inset-4 border border-[#C9A24D]/45 rounded-xl pointer-events-none"></div>
                
                <!-- Corner Ornaments -->
                <div class="absolute top-4 left-5 text-[#C9A24D] text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute top-4 right-5 text-[#C9A24D] text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute bottom-4 left-5 text-[#C9A24D] text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute bottom-4 right-5 text-[#C9A24D] text-xl font-serif leading-none select-none">❖</div>

                <!-- Watermark Background Logo -->
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.035] pointer-events-none">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="w-96 h-96 object-contain" alt="Ruhiology Watermark">
                </div>

                <!-- 1. HEADER SECTION -->
                <div class="relative z-10 text-center space-y-1">
                    <div class="flex flex-col items-center justify-center space-y-0.5">
                        <img src="{{ asset('images/ruhiology-logo.png') }}" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="h-11 w-11 object-contain mx-auto mb-0.5" alt="Logo Ruhiology Institute">
                        <span class="block font-serif-gold text-sm font-extrabold tracking-[0.22em] text-[#0B2A43] leading-none">
                            {{ $settings['institute_name'] ?? 'RUHIOLOGY INSTITUTE' }}
                        </span>
                        <span class="block text-[8.5px] tracking-[0.18em] uppercase text-[#9A7420] font-heading font-bold">
                            {{ $settings['tagline'] ?? 'Mengenal Diri · Mengembangkan Potensi · Menumbuhkan Ruh' }}
                        </span>
                    </div>

                    <div class="pt-1">
                        <h1 class="font-serif-gold text-[26px] font-extrabold text-[#0B2A43] tracking-wider uppercase leading-tight">
                            SERTIFIKAT HASIL ASESMEN RQI
                        </h1>
                        <p class="text-[10px] font-serif-classic italic text-[#9A7420] tracking-wider">
                            Ruhiology Quotient (RQI-15) & WHO-5 Mental Well-Being Official Assessment Certificate
                        </p>
                    </div>
                </div>

                <!-- 2. RECIPIENT & BODY SECTION -->
                <div class="relative z-10 text-center space-y-2.5 my-auto py-1">
                    <span class="text-[9.5px] uppercase font-heading tracking-[0.25em] text-slate-500 font-bold block">DIBERIKAN SECARA RESMI KEPADA :</span>

                    <!-- Participant Name -->
                    <div>
                        <h2 class="font-serif-classic text-[32px] font-bold text-[#0B2A43] tracking-wide border-b-2 border-[#C9A24D] inline-block px-10 pb-0.5 leading-tight">
                            {{ $submission->participant->name }}
                        </h2>
                        <p class="text-[11px] text-slate-600 font-medium mt-1">
                            Kategori: <strong class="text-[#0B2A43] font-bold">{{ $submission->participant->category ?: 'UMUM' }}</strong>
                            @php
                                $instName = $submission->participant->sub_category 
                                    ?: $submission->participant->detail_institution
                                    ?: $submission->participant->university_custom 
                                    ?: $submission->participant->school_custom 
                                    ?: $submission->participant->occupation_custom 
                                    ?: $submission->participant->school?->name 
                                    ?: $submission->participant->university?->name 
                                    ?: null;
                            @endphp
                            @if($instName)
                                <span class="text-[#C9A24D] mx-1">•</span> <strong class="text-slate-800">{{ $instName }}</strong>
                            @endif
                            @if($submission->participant->regency || $submission->participant->province)
                                <span class="text-[#C9A24D] mx-1">•</span>
                                <span>{{ $submission->participant->regency?->name }}{{ $submission->participant->regency && $submission->participant->province ? ', ' : '' }}{{ $submission->participant->province?->name }}</span>
                            @endif
                        </p>
                    </div>

                    <p class="text-[11.5px] text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                        Telah menyelesaikan evaluasi komprehensif pemetaan <strong class="text-[#0B2A43]">5 Pilar Dimensi Kecerdasan Ruhiologi (RQI-15)</strong> serta <strong class="text-[#0B2A43]">Skrining Kesejahteraan Emosional (WHO-5 Well-Being Index)</strong> dengan capaian profil sebagai berikut:
                    </p>

                    <!-- DUAL SCORE CARDS (SIDE-BY-SIDE: RQI SCORE + WHO-5 SCORE) -->
                    @php
                        $rqiVal = $submission->result->rqi_score ?? $submission->result->total_score ?? 0;
                        $maxRqiVal = $submission->result->max_score ?: 75;
                        $rqiPctVal = round(($rqiVal / $maxRqiVal) * 100);
                        $who5PctVal = $submission->result->who5_percentage ?? 0;
                        $who5RawVal = $submission->result->who5_raw_score ?? 0;

                        if ($who5RawVal >= 20 || $who5PctVal >= 80) {
                            $who5Label = 'Level 5: Thriving & Energetic (Sangat Sejahtera)';
                            $badgeStyle = 'text-emerald-900 bg-emerald-100/90 border-emerald-300';
                        } elseif ($who5RawVal >= 16 || $who5PctVal >= 64) {
                            $who5Label = 'Level 4: Good Vibe & Fresh (Sejahtera)';
                            $badgeStyle = 'text-sky-900 bg-sky-100/90 border-sky-300';
                        } elseif ($who5RawVal >= 11 || $who5PctVal >= 44) {
                            $who5Label = 'Level 3: Moderate Well-Being (Cukup Stabil)';
                            $badgeStyle = 'text-amber-900 bg-amber-100/90 border-amber-300';
                        } elseif ($who5RawVal >= 6 || $who5PctVal >= 24) {
                            $who5Label = 'Level 2: Low Energy (Rentan Lelah Emosi)';
                            $badgeStyle = 'text-orange-900 bg-orange-100/90 border-orange-300';
                        } else {
                            $who5Label = 'Level 1: Mental Exhausted (Perlu Pemulihan)';
                            $badgeStyle = 'text-rose-900 bg-rose-100/90 border-rose-300';
                        }
                    @endphp
                    <div class="grid grid-cols-2 gap-4 max-w-2xl mx-auto">
                        
                        <!-- CARD 1: SKOR RUHIOLOGI (RQI-15) -->
                        <div class="bg-gradient-to-br from-white via-amber-50/40 to-amber-100/50 px-4 py-2.5 rounded-2xl border border-[#C9A24D]/70 shadow-xs text-center space-y-1">
                            <span class="text-[8.5px] uppercase font-heading font-bold text-[#8B6914] block tracking-wider">PILAR 1 · SKOR KECERDASAN RUHIOLOGI (RQI-15)</span>
                            <div class="flex items-baseline justify-center gap-1.5">
                                <strong class="font-heading text-2xl font-extrabold text-[#0B2A43]">{{ number_format($rqiVal, 0) }}</strong>
                                <span class="text-xs font-semibold text-slate-500">/ {{ $maxRqiVal }} Poin ({{ $rqiPctVal }}%)</span>
                            </div>
                            <span class="text-[9.5px] font-heading font-bold text-[#0B2A43] bg-[#C9A24D]/25 px-3 py-0.5 rounded-full border border-[#C9A24D]/50 inline-block truncate max-w-full">
                                ✦ {{ $submission->result->category_name ?? 'Developing Soul' }}
                            </span>
                        </div>

                        <!-- CARD 2: SKOR KESEJAHTERAAN MENTAL (WHO-5) -->
                        <div class="bg-gradient-to-br from-white via-emerald-50/40 to-emerald-100/50 px-4 py-2.5 rounded-2xl border border-emerald-300 shadow-xs text-center space-y-1">
                            <span class="text-[8.5px] uppercase font-heading font-bold text-emerald-900 block tracking-wider">PILAR 2 · INDEKS KESEJAHTERAAN MENTAL (WHO-5)</span>
                            <div class="flex items-baseline justify-center gap-1.5">
                                <strong class="font-heading text-2xl font-extrabold text-emerald-700">{{ number_format($who5PctVal, 0) }}%</strong>
                                <span class="text-xs font-semibold text-slate-500">({{ $who5RawVal }} / 25 Poin)</span>
                            </div>
                            <span class="text-[9.5px] font-heading font-bold {{ $badgeStyle }} px-3 py-0.5 rounded-full border inline-block truncate max-w-full">
                                🌱 {{ $who5Label }}
                            </span>
                        </div>

                    </div>

                    <!-- 5-DIMENSION COMPETENCY MATRIX GRID (CLEAN FULL NAMES + PROGRESS BAR) -->
                    @if(isset($submission->result->dimensionResults) && count($submission->result->dimensionResults) > 0)
                        @php
                            $rqiDimensions = $submission->result->dimensionResults->filter(function($dr) {
                                $code = strtolower($dr->dimension->code ?? '');
                                $name = strtolower($dr->dimension->name ?? '');
                                return !str_contains($code, 'who') && !str_contains($name, 'who');
                            })->take(5);

                            $shortDimensionTitle = function($rawName) {
                                $clean = preg_replace('/^\d+\.\s*/', '', trim($rawName));
                                $clean = preg_replace('/\s*\(.*?\)/', '', $clean);
                                return Str::limit($clean, 22);
                            };
                        @endphp
                        @if($rqiDimensions->count() > 0)
                            <div class="max-w-3xl mx-auto bg-white/95 px-3.5 py-2.5 rounded-2xl border border-[#C9A24D]/45 shadow-2xs space-y-1.5">
                                <div class="flex items-center justify-between px-1">
                                    <span class="text-[8.5px] font-heading font-bold uppercase tracking-widest text-[#0B2A43]">Profil Capaian 5 Pilar Dimensi RQI-15</span>
                                    <span class="text-[8px] font-mono font-bold text-[#8B6914]">Skala Proporsional 0–100%</span>
                                </div>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach($rqiDimensions as $dr)
                                        @php $pct = round($dr->percentage); @endphp
                                        <div class="bg-[#FAF8F5] px-2.5 py-1.5 rounded-xl border border-amber-200/90 text-left flex flex-col justify-between">
                                            <span class="block text-[8.5px] font-heading font-bold text-[#0B2A43] leading-tight truncate" title="{{ $dr->dimension->name }}">
                                                {{ $shortDimensionTitle($dr->dimension->name) }}
                                            </span>
                                            <div class="mt-1.5 space-y-0.5">
                                                <div class="flex items-center justify-between text-[8px] font-mono font-bold">
                                                    <span class="text-slate-400">{{ $dr->score }}/{{ $dr->max_score }}</span>
                                                    <span class="text-[#0B2A43] text-[10px]">{{ $pct }}%</span>
                                                </div>
                                                <div class="w-full h-1.5 bg-amber-100 rounded-full overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-[#0B2A43] to-[#C9A24D] rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                </div>

                <!-- 3. FOOTER SECTION -->
                <div class="relative z-10 space-y-1.5 pt-2 border-t border-[#C9A24D]/40 font-sans">
                    
                    <!-- Ruhiology Philosophical Quote Line -->
                    <div class="text-center">
                        <p class="text-[9px] font-serif-classic italic text-slate-600 max-w-2xl mx-auto leading-normal">
                            "Ruh adalah pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, serta komitmen etis." — Prof. Dr. Iskandar Nazari
                        </p>
                    </div>

                    <div class="grid grid-cols-3 items-end gap-4 pt-0.5">
                        
                        <!-- BOTTOM LEFT: DOCUMENT NO & OFFICIAL VERIFICATION METADATA -->
                        <div class="text-left space-y-0.5">
                            <div class="inline-block px-2.5 py-1 bg-amber-100/90 border border-[#C9A24D]/60 rounded-lg text-[9px] font-mono font-bold text-[#0B2A43]">
                                NO: CERT/RQI/{{ date('Y') }}/{{ strtoupper($submission->submission_code) }}
                            </div>
                            <span class="block text-[8.5px] font-heading text-emerald-800 font-bold">✓ Dokumen Terverifikasi Sistem Ruhiology</span>
                            <span class="block text-[8px] font-mono text-slate-500">
                                Diterbitkan: {{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d F Y') : date('d F Y') }}
                            </span>
                        </div>

                        <!-- BOTTOM CENTER: LUXURY GOLD FOIL EMBOSS SEAL -->
                        <div class="text-center flex flex-col items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#F3E2B3] via-[#C9A24D] to-[#96710E] p-1 shadow-md flex items-center justify-center border border-amber-300">
                                <div class="w-full h-full rounded-full border border-dashed border-[#0B2A43]/60 bg-[#FAF8F5]/90 flex flex-col items-center justify-center text-center px-1">
                                    <span class="text-[6px] font-serif-gold font-extrabold text-[#0B2A43] tracking-wider leading-none">RUHIOLOGY</span>
                                    <span class="text-[8px] text-[#C9A24D] leading-none my-0.5">★ ★ ★</span>
                                    <span class="text-[5px] font-mono font-bold text-emerald-800 tracking-widest leading-none">OFFICIAL SEAL</span>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM RIGHT: DIGITAL QR CODE VERIFICATION & FOUNDER TITLE -->
                        <div class="text-right flex items-end justify-end gap-3">
                            <div class="text-right space-y-0.5">
                                <p class="text-[8.5px] text-slate-500 font-medium">Jambi, {{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d F Y') : date('d F Y') }}</p>
                                <div class="pt-1">
                                    <h3 class="font-serif-classic text-[11.5px] font-bold text-[#0B2A43] leading-tight border-b border-slate-400/80 pb-0.5 inline-block">
                                        {{ $settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.' }}
                                    </h3>
                                    <p class="text-[8.5px] text-[#9A7420] font-heading font-semibold mt-0.5">
                                        {{ $settings['founder_title'] ?? 'Founder & Guru Besar Ruhiology Institute' }}
                                    </p>
                                </div>
                            </div>

                            <!-- QR Code Validasi -->
                            <div class="flex flex-col items-center shrink-0">
                                <div class="p-1 bg-white border border-[#C9A24D]/70 rounded-xl shadow-xs">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(route('assessment.result', $submission->submission_code)) }}" 
                                         crossorigin="anonymous"
                                         alt="QR Verifikasi" 
                                         class="w-12 h-12 object-contain">
                                </div>
                                <span class="text-[7px] font-mono font-bold text-[#0B2A43] mt-0.5">SCAN VALIDASI</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
