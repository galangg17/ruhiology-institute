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
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #061320;
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
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-start sm:justify-center p-2 sm:p-6"
      x-data="{
          scale: 1,
          theme: 'royal',
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
              await new Promise(r => setTimeout(r, 140));
              try {
                  const bgHex = this.theme === 'midnight' ? '#07192B' : '#FFFDF9';
                  const canvas = await html2canvas(node, {
                      scale: 2.5,
                      useCORS: true,
                      allowTaint: true,
                      backgroundColor: bgHex,
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
                  link.download = 'Sertifikat_RQI_{{ $submission->submission_code }}_' + this.theme + '.png';
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
                  const imgData = canvas.toDataURL('image/jpeg', 0.97);
                  const { jsPDF } = window.jspdf;
                  const pdf = new jsPDF({
                      orientation: 'landscape',
                      unit: 'mm',
                      format: 'a4'
                  });
                  pdf.addImage(imgData, 'JPEG', 0, 0, 297, 210);
                  pdf.save('Sertifikat_RQI_{{ $submission->submission_code }}_' + this.theme + '.pdf');
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

    <!-- Top Action & Theme Navigation Bar (Hidden when printing) -->
    <div class="no-print max-w-[1050px] w-full mb-3 sm:mb-5 flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-3 bg-slate-900/95 text-white p-3.5 sm:px-5 rounded-2xl backdrop-blur-md border border-amber-500/35 shadow-2xl">
        <div class="flex flex-wrap items-center justify-between lg:justify-start gap-3">
            <a href="{{ route('assessment.result', $submission->submission_code) }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-amber-400 font-bold rounded-xl text-xs transition border border-amber-500/30 flex items-center gap-2">
                <span>←</span>
                <span>Kembali ke Hasil</span>
            </a>

            <!-- Theme Selector Pills -->
            <div class="flex items-center gap-1.5 bg-slate-950/90 p-1 rounded-xl border border-slate-800">
                <span class="text-[10px] font-mono text-slate-400 px-2 hidden sm:inline">TEMA:</span>
                <button type="button" @click="theme = 'royal'"
                        :class="theme === 'royal' ? 'bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-extrabold shadow' : 'text-slate-300 hover:text-white'"
                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-heading transition cursor-pointer flex items-center gap-1">
                    <span>👑</span> <span>Royal Navy</span>
                </button>
                <button type="button" @click="theme = 'midnight'"
                        :class="theme === 'midnight' ? 'bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-extrabold shadow' : 'text-slate-300 hover:text-white'"
                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-heading transition cursor-pointer flex items-center gap-1">
                    <span>🌙</span> <span>Midnight Gold</span>
                </button>
                <button type="button" @click="theme = 'emerald'"
                        :class="theme === 'emerald' ? 'bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-950 font-extrabold shadow' : 'text-slate-300 hover:text-white'"
                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-heading transition cursor-pointer flex items-center gap-1">
                    <span>🌿</span> <span>Emerald Prestige</span>
                </button>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <template x-if="isExporting">
                <span class="text-xs font-mono text-amber-300 px-3 py-1.5 bg-amber-500/10 rounded-xl border border-amber-400/30 animate-pulse" x-text="exportStatus"></span>
            </template>

            <button type="button" @click="downloadPNG()" :disabled="isExporting" class="flex-1 sm:flex-none justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-heading font-bold rounded-xl text-xs shadow-lg transition cursor-pointer flex items-center gap-1.5">
                <span>🖼️</span>
                <span>Unduh PNG HD</span>
            </button>

            <button type="button" @click="downloadPDF()" :disabled="isExporting" class="flex-1 sm:flex-none justify-center px-4 py-2.5 bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-600 hover:from-amber-500 hover:to-yellow-700 disabled:opacity-50 text-slate-950 font-heading font-extrabold rounded-xl text-xs shadow-lg shadow-amber-500/20 transition cursor-pointer flex items-center gap-1.5">
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
                 :class="{
                     'bg-[#FFFDF9] border-[#0B2A43] text-[#0B2A43]': theme === 'royal',
                     'bg-gradient-to-br from-[#061524] via-[#0B2640] to-[#05121F] border-[#D4AF37] text-white': theme === 'midnight',
                     'bg-[#FAFDFB] border-[#064E3B] text-[#064E3B]': theme === 'emerald'
                 }"
                 class="cert-container w-[1050px] h-[742px] relative px-10 py-6 border-[12px] rounded-3xl shadow-2xl overflow-hidden flex flex-col justify-between transition-colors duration-300"
                 @if(!empty($settings['certificate_bg'])) style="background-image: url('{{ $settings['certificate_bg'] }}'); background-size: cover; background-position: center;" @endif>
                
                <!-- Top Decorative Luxury Corner Ribbons -->
                <div class="absolute -top-12 -left-12 w-28 h-28 rotate-45 pointer-events-none"
                     :class="theme === 'emerald' ? 'bg-gradient-to-br from-emerald-700 to-amber-500' : 'bg-gradient-to-br from-[#0B2A43] via-[#16476E] to-amber-500'"></div>
                <div class="absolute -top-12 -right-12 w-28 h-28 -rotate-45 pointer-events-none"
                     :class="theme === 'emerald' ? 'bg-gradient-to-bl from-emerald-700 to-amber-500' : 'bg-gradient-to-bl from-[#0B2A43] via-[#16476E] to-amber-500'"></div>
                <div class="absolute -bottom-12 -left-12 w-28 h-28 -rotate-45 pointer-events-none"
                     :class="theme === 'emerald' ? 'bg-gradient-to-tr from-emerald-700 to-amber-500' : 'bg-gradient-to-tr from-[#0B2A43] via-[#16476E] to-amber-500'"></div>
                <div class="absolute -bottom-12 -right-12 w-28 h-28 rotate-45 pointer-events-none"
                     :class="theme === 'emerald' ? 'bg-gradient-to-tl from-emerald-700 to-amber-500' : 'bg-gradient-to-tl from-[#0B2A43] via-[#16476E] to-amber-500'"></div>

                <!-- Vibrant Gold Double Frame Overlay -->
                <div class="absolute inset-2.5 border-[2.5px] border-amber-500/90 rounded-2xl pointer-events-none"></div>
                <div class="absolute inset-4 border border-amber-500/45 rounded-xl pointer-events-none"></div>
                
                <!-- Corner Ornaments -->
                <div class="absolute top-4 left-5 text-amber-500 text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute top-4 right-5 text-amber-500 text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute bottom-4 left-5 text-amber-500 text-xl font-serif leading-none select-none">❖</div>
                <div class="absolute bottom-4 right-5 text-amber-500 text-xl font-serif leading-none select-none">❖</div>

                <!-- Watermark Background Logo -->
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.04] pointer-events-none">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="w-96 h-96 object-contain" alt="Ruhiology Watermark">
                </div>

                <!-- 1. HEADER SECTION WITH ROYAL RIBBON BANNER -->
                <div class="relative z-10 text-center space-y-1.5">
                    <div class="flex flex-col items-center justify-center">
                        <div class="inline-flex items-center gap-2.5 px-5 py-1.5 rounded-full border shadow-sm mb-1"
                             :class="{
                                 'bg-[#0B2A43] border-amber-400/70 text-white': theme === 'royal',
                                 'bg-white/10 border-amber-400/60 text-white': theme === 'midnight',
                                 'bg-[#064E3B] border-amber-400/70 text-white': theme === 'emerald'
                             }">
                            <img src="{{ asset('images/ruhiology-logo.png') }}" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="h-7 w-7 object-contain rounded-full bg-white p-0.5" alt="Logo">
                            <div class="text-left">
                                <span class="block font-serif-gold text-xs font-extrabold tracking-[0.2em] text-amber-300 leading-none">
                                    {{ $settings['institute_name'] ?? 'RUHIOLOGY INSTITUTE' }}
                                </span>
                                <span class="block text-[7.5px] tracking-[0.14em] uppercase text-slate-200 font-heading font-semibold mt-0.5">
                                    {{ $settings['tagline'] ?? 'Mengenal Diri · Mengembangkan Potensi · Menumbuhkan Ruh' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h1 class="font-serif-gold text-[26px] font-black tracking-wider uppercase leading-none"
                            :class="{
                                'text-[#0B2A43]': theme === 'royal',
                                'text-amber-300': theme === 'midnight',
                                'text-[#064E3B]': theme === 'emerald'
                            }">
                            SERTIFIKAT HASIL ASESMEN RQI
                        </h1>
                        <p class="text-[10.5px] font-serif-classic italic font-semibold tracking-wider mt-0.5"
                           :class="theme === 'midnight' ? 'text-amber-200/90' : 'text-amber-700'">
                            Ruhiology Quotient (RQI-15) & WHO-5 Mental Well-Being Official Assessment Certificate
                        </p>
                    </div>
                </div>

                <!-- 2. RECIPIENT & BODY SECTION -->
                <div class="relative z-10 text-center space-y-2.5 my-auto">
                    <span class="text-[9.5px] uppercase font-heading tracking-[0.25em] font-extrabold block"
                          :class="theme === 'midnight' ? 'text-slate-300' : 'text-slate-500'">
                        DIBERIKAN SECARA RESMI KEPADA :
                    </span>

                    <!-- Participant Name -->
                    <div>
                        <h2 class="font-serif-classic text-[33px] font-extrabold tracking-wide border-b-2 border-amber-500 inline-block px-10 pb-0.5 leading-tight"
                            :class="{
                                'text-[#0B2A43]': theme === 'royal',
                                'text-white': theme === 'midnight',
                                'text-[#064E3B]': theme === 'emerald'
                            }">
                            {{ $submission->participant->name }}
                        </h2>
                        <p class="text-[11px] font-medium mt-1"
                           :class="theme === 'midnight' ? 'text-slate-300' : 'text-slate-600'">
                            Kategori: <strong class="font-bold" :class="theme === 'midnight' ? 'text-amber-300' : 'text-[#0B2A43]'">{{ $submission->participant->category ?: 'UMUM' }}</strong>
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
                                <span class="text-amber-500 mx-1">•</span> <strong :class="theme === 'midnight' ? 'text-white' : 'text-slate-800'">{{ $instName }}</strong>
                            @endif
                            @if($submission->participant->regency || $submission->participant->province)
                                <span class="text-amber-500 mx-1">•</span>
                                <span>{{ $submission->participant->regency?->name }}{{ $submission->participant->regency && $submission->participant->province ? ', ' : '' }}{{ $submission->participant->province?->name }}</span>
                            @endif
                        </p>
                    </div>

                    <p class="text-[11px] max-w-2xl mx-auto leading-relaxed font-medium"
                       :class="theme === 'midnight' ? 'text-slate-300' : 'text-slate-600'">
                        Telah menyelesaikan evaluasi komprehensif pemetaan <strong :class="theme === 'midnight' ? 'text-amber-300' : 'text-[#0B2A43]'">5 Pilar Dimensi Kecerdasan Ruhiologi (RQI-15)</strong> serta <strong :class="theme === 'midnight' ? 'text-emerald-300' : 'text-emerald-800'">Skrining Kesejahteraan Emosional (WHO-5 Well-Being Index)</strong> dengan capaian profil sebagai berikut:
                    </p>

                    <!-- DUAL SCORE CARDS (HIGH CONTRAST, VIBRANT ROYAL NAVY & EMERALD) -->
                    @php
                        $rqiVal = $submission->result->rqi_score ?? $submission->result->total_score ?? 0;
                        $maxRqiVal = $submission->result->max_score ?: 75;
                        $rqiPctVal = round(($rqiVal / $maxRqiVal) * 100);
                        $who5PctVal = $submission->result->who5_percentage ?? 0;
                        $who5RawVal = $submission->result->who5_raw_score ?? 0;

                        if ($who5RawVal >= 20 || $who5PctVal >= 80) {
                            $who5Label = 'Level 5: Thriving & Energetic (Sangat Sejahtera)';
                        } elseif ($who5RawVal >= 16 || $who5PctVal >= 64) {
                            $who5Label = 'Level 4: Good Vibe & Fresh (Sejahtera)';
                        } elseif ($who5RawVal >= 11 || $who5PctVal >= 44) {
                            $who5Label = 'Level 3: Moderate Well-Being (Cukup Stabil)';
                        } elseif ($who5RawVal >= 6 || $who5PctVal >= 24) {
                            $who5Label = 'Level 2: Low Energy (Rentan Lelah Emosi)';
                        } else {
                            $who5Label = 'Level 1: Mental Exhausted (Perlu Pemulihan)';
                        }
                    @endphp
                    <div class="grid grid-cols-2 gap-4 max-w-2xl mx-auto">
                        
                        <!-- CARD 1: SKOR RUHIOLOGI (RQI-15) - VIBRANT ROYAL NAVY & GOLD -->
                        <div class="bg-gradient-to-br from-[#0B2A43] via-[#123E63] to-[#081F33] px-5 py-3 rounded-2xl border-2 border-amber-400 shadow-lg text-center space-y-1 relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-amber-400/15 blur-xl pointer-events-none"></div>
                            <span class="text-[8.5px] uppercase font-heading font-extrabold text-amber-300 block tracking-wider">✦ PILAR 1 · SKOR KECERDASAN RUHIOLOGI (RQI-15)</span>
                            <div class="flex items-baseline justify-center gap-1.5">
                                <strong class="font-heading text-3xl font-black text-amber-400 leading-none">{{ number_format($rqiVal, 0) }}</strong>
                                <span class="text-xs font-bold text-slate-200">/ {{ $maxRqiVal }} Poin ({{ $rqiPctVal }}%)</span>
                            </div>
                            <div class="pt-0.5">
                                <span class="text-[9.5px] font-heading font-extrabold text-slate-950 bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-500 px-3.5 py-0.5 rounded-full shadow-xs inline-block truncate max-w-full">
                                    ✦ {{ $submission->result->category_name ?? 'Developing Soul' }}
                                </span>
                            </div>
                        </div>

                        <!-- CARD 2: SKOR KESEJAHTERAAN MENTAL (WHO-5) - VIBRANT EMERALD TEAL -->
                        <div class="bg-gradient-to-br from-[#064E3B] via-[#047857] to-[#065F46] px-5 py-3 rounded-2xl border-2 border-emerald-400 shadow-lg text-center space-y-1 relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-emerald-300/15 blur-xl pointer-events-none"></div>
                            <span class="text-[8.5px] uppercase font-heading font-extrabold text-emerald-200 block tracking-wider">🌿 PILAR 2 · INDEKS KESEJAHTERAAN MENTAL (WHO-5)</span>
                            <div class="flex items-baseline justify-center gap-1.5">
                                <strong class="font-heading text-3xl font-black text-white leading-none">{{ number_format($who5PctVal, 0) }}%</strong>
                                <span class="text-xs font-bold text-emerald-100">({{ $submission->result->who5_raw_score ?? 0 }} / 25 Poin)</span>
                            </div>
                            <div class="pt-0.5">
                                <span class="text-[9.5px] font-heading font-extrabold text-emerald-950 bg-gradient-to-r from-emerald-200 via-emerald-300 to-teal-200 px-3.5 py-0.5 rounded-full shadow-xs inline-block truncate max-w-full">
                                    🌱 {{ $who5Label }}
                                </span>
                            </div>
                        </div>

                    </div>

                    <!-- 5-DIMENSION COMPETENCY MATRIX GRID (UNCUT CLEAN NAMES + VIBRANT PROGRESS BARS) -->
                    @if(isset($submission->result->dimensionResults) && count($submission->result->dimensionResults) > 0)
                        @php
                            $rqiDimensions = $submission->result->dimensionResults->filter(function($dr) {
                                $code = strtolower($dr->dimension->code ?? '');
                                $name = strtolower($dr->dimension->name ?? '');
                                return !str_contains($code, 'who') && !str_contains($name, 'who');
                            })->take(5)->values();

                            $cleanPillarLabel = function($rawName, $idx) {
                                $lower = strtolower($rawName);
                                if (str_contains($lower, 'god spot') || str_contains($lower, 'diri')) return 'Kesadaran Diri & Ruh';
                                if (str_contains($lower, 'puncak')) return 'Kesadaran Puncak Ilahi';
                                if (str_contains($lower, 'ketuhanan') || str_contains($lower, 'spiritual connection')) return 'Kesadaran Ketuhanan';
                                if (str_contains($lower, 'ibadah') || str_contains($lower, 'ketaatan')) return 'Ketaatan Spiritual';
                                if (str_contains($lower, 'tazkiyah') || str_contains($lower, 'purifikasi') || str_contains($lower, 'inner peace')) return 'Purifikasi Batin';
                                if (str_contains($lower, 'perilaku') || str_contains($lower, 'akhlak') || str_contains($lower, 'altruistic')) return 'Akhlak & Sosial';
                                if (str_contains($lower, 'resiliensi') || str_contains($lower, 'ketangguhan')) return 'Resiliensi Jiwa';
                                $clean = preg_replace('/^\d+\.\s*/', '', trim($rawName));
                                $clean = preg_replace('/\s*\(.*?\)/', '', $clean);
                                return \Illuminate\Support\Str::limit($clean, 20, '');
                            };
                        @endphp
                        @if($rqiDimensions->count() > 0)
                            <div class="max-w-3xl mx-auto px-4 py-2.5 rounded-2xl border-2 shadow-sm space-y-1.5"
                                 :class="theme === 'midnight' ? 'bg-slate-900/90 border-amber-500/50' : 'bg-gradient-to-r from-amber-50/90 via-white to-amber-50/90 border-amber-400/80'">
                                <div class="flex items-center justify-between px-1">
                                    <span class="text-[8.5px] font-heading font-extrabold uppercase tracking-widest"
                                          :class="theme === 'midnight' ? 'text-amber-300' : 'text-[#0B2A43]'">
                                        ✦ PROFIL CAPAIAN 5 PILAR DIMENSI RQI-15
                                    </span>
                                    <span class="text-[8px] font-mono font-bold"
                                          :class="theme === 'midnight' ? 'text-emerald-300' : 'text-amber-800'">
                                        SKALA PROPORSIONAL 0–100%
                                    </span>
                                </div>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach($rqiDimensions as $idx => $dr)
                                        @php $pct = round($dr->percentage); @endphp
                                        <div class="px-2.5 py-2 rounded-xl border-t-4 border-t-amber-500 border border-amber-300/80 text-left flex flex-col justify-between shadow-2xs"
                                             :class="theme === 'midnight' ? 'bg-slate-800/90 border-slate-700 border-t-amber-400' : 'bg-white'">
                                            <span class="block text-[8.5px] font-heading font-extrabold leading-tight"
                                                  :class="theme === 'midnight' ? 'text-white' : 'text-[#0B2A43]'">
                                                {{ $idx + 1 }}. {{ $cleanPillarLabel($dr->dimension->name, $idx) }}
                                            </span>
                                            <div class="mt-1.5 space-y-1">
                                                <div class="flex items-center justify-between font-mono font-bold">
                                                    <span class="text-[8px]" :class="theme === 'midnight' ? 'text-slate-400' : 'text-slate-500'">{{ $dr->score }}/{{ $dr->max_score }}</span>
                                                    <span class="text-[11px] font-extrabold" :class="theme === 'midnight' ? 'text-amber-300' : 'text-[#0B2A43]'">{{ $pct }}%</span>
                                                </div>
                                                <div class="w-full h-2 bg-slate-200/80 rounded-full overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-amber-500 via-yellow-500 to-emerald-500 rounded-full" style="width: {{ min(100, $pct) }}%"></div>
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
                <div class="relative z-10 space-y-1.5 pt-2 border-t-2 border-amber-400/60 font-sans">
                    
                    <!-- Ruhiology Philosophical Quote Line -->
                    <div class="text-center">
                        <p class="text-[9px] font-serif-classic italic max-w-2xl mx-auto leading-normal"
                           :class="theme === 'midnight' ? 'text-amber-100/80' : 'text-slate-600'">
                            "Ruh adalah pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, serta komitmen etis." — Prof. Dr. Iskandar Nazari
                        </p>
                    </div>

                    <div class="grid grid-cols-3 items-end gap-4">
                        
                        <!-- BOTTOM LEFT: DOCUMENT NO & OFFICIAL VERIFICATION METADATA -->
                        <div class="text-left space-y-0.5">
                            <div class="inline-block px-3 py-1 bg-gradient-to-r from-[#0B2A43] to-[#16476E] border border-amber-400 rounded-lg text-[9px] font-mono font-bold text-amber-300 shadow-xs">
                                NO: CERT/RQI/{{ date('Y') }}/{{ strtoupper($submission->submission_code) }}
                            </div>
                            <span class="block text-[8.5px] font-heading font-extrabold"
                                  :class="theme === 'midnight' ? 'text-emerald-400' : 'text-emerald-700'">
                                ✓ Terverifikasi Resmi Sistem Ruhiology
                            </span>
                            <span class="block text-[8px] font-mono"
                                  :class="theme === 'midnight' ? 'text-slate-400' : 'text-slate-500'">
                                Diterbitkan: {{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d F Y') : date('d F Y') }}
                            </span>
                        </div>

                        <!-- BOTTOM CENTER: 3D GOLD MEDAL SEAL WITH RIBBON -->
                        <div class="text-center flex flex-col items-center justify-center relative">
                            <div class="relative flex flex-col items-center">
                                <!-- Gold Medal Circle -->
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-yellow-300 via-amber-500 to-amber-700 p-1 shadow-lg flex items-center justify-center border-2 border-yellow-200 relative z-10">
                                    <div class="w-full h-full rounded-full border border-dashed border-[#0B2A43] bg-gradient-to-b from-[#0B2A43] to-[#123E63] flex flex-col items-center justify-center text-center px-1">
                                        <span class="text-[5.5px] font-serif-gold font-black text-amber-300 tracking-wider leading-none">RUHIOLOGY</span>
                                        <span class="text-[8px] text-yellow-400 leading-none my-0.5">★ ★ ★</span>
                                        <span class="text-[4.5px] font-mono font-bold text-emerald-300 tracking-widest leading-none">VERIFIED</span>
                                    </div>
                                </div>
                                <!-- Bottom Ribbon Tails -->
                                <div class="flex items-center justify-center -mt-2 z-0 gap-1">
                                    <div class="w-3.5 h-4 bg-gradient-to-b from-amber-600 to-amber-800 transform -rotate-12 rounded-b-sm border-x border-amber-400/50"></div>
                                    <div class="w-3.5 h-4 bg-gradient-to-b from-amber-600 to-amber-800 transform rotate-12 rounded-b-sm border-x border-amber-400/50"></div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM RIGHT: DIGITAL QR CODE VERIFICATION & FOUNDER TITLE -->
                        <div class="text-right flex items-end justify-end gap-3">
                            <div class="text-right space-y-0.5">
                                <p class="text-[8.5px] font-semibold"
                                   :class="theme === 'midnight' ? 'text-slate-300' : 'text-slate-600'">
                                    Jambi, {{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d F Y') : date('d F Y') }}
                                </p>
                                <div class="pt-1">
                                    <h3 class="font-serif-classic text-[11.5px] font-extrabold leading-tight border-b-2 border-amber-500 pb-0.5 inline-block"
                                        :class="theme === 'midnight' ? 'text-white' : 'text-[#0B2A43]'">
                                        {{ $settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.' }}
                                    </h3>
                                    <p class="text-[8.5px] font-heading font-bold mt-0.5"
                                       :class="theme === 'midnight' ? 'text-amber-300' : 'text-amber-800'">
                                        {{ $settings['founder_title'] ?? 'Founder & Guru Besar Ruhiology Institute' }}
                                    </p>
                                </div>
                            </div>

                            <!-- QR Code Validasi -->
                            <div class="flex flex-col items-center shrink-0">
                                <div class="p-1 bg-white border-2 border-amber-500 rounded-xl shadow-md">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(route('assessment.result', $submission->submission_code)) }}" 
                                         crossorigin="anonymous"
                                         alt="QR Verifikasi" 
                                         class="w-12 h-12 object-contain">
                                </div>
                                <span class="text-[7px] font-mono font-extrabold mt-0.5"
                                      :class="theme === 'midnight' ? 'text-amber-300' : 'text-[#0B2A43]'">
                                    SCAN VALIDASI
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
