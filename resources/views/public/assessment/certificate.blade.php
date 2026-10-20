<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Resmi RQI & WHO-5 — {{ $submission->participant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        /*
         * UI/UX Color System (60-30-10 Editorial Principle):
         * 60% Canvas: Warm Alabaster (#FCFBF9) & Pure Surface (#FFFFFF)
         * 30% Structure & Ink: Oxford Navy (#0F2537) & Slate (#475569)
         * 10% Accent: Muted Champagne Gold (#B5945B) & Deep Sage (#1E4E44)
         */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F172A;
            color: #0F2537;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .font-heading {
            font-family: 'Poppins', sans-serif;
            letter-spacing: -0.01em;
        }
        .font-cinzel {
            font-family: 'Cinzel', serif;
        }
        .font-serif-editorial {
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
                background: #FCFBF9 !important;
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
          theme: 'light',
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
                  const bgHex = this.theme === 'dark' ? '#0D1F30' : '#FCFBF9';
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
              this.exportStatus = 'Menyiapkan PNG...';
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
                  const imgData = canvas.toDataURL('image/jpeg', 0.97);
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

    <!-- Top Action Bar -->
    <div class="no-print max-w-[1050px] w-full mb-4 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 bg-slate-900 text-white px-5 py-3.5 rounded-2xl border border-slate-800 shadow-xl">
        <div class="flex items-center justify-between sm:justify-start gap-3">
            <a href="{{ route('assessment.result', $submission->submission_code) }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-xl text-xs transition border border-slate-700 flex items-center gap-2">
                <span>←</span>
                <span>Kembali ke Laporan</span>
            </a>

            <!-- Clean 2-Mode Editorial Theme Switcher -->
            <div class="flex items-center gap-1 bg-slate-950 p-1 rounded-xl border border-slate-800">
                <button type="button" @click="theme = 'light'"
                        :class="theme === 'light' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-heading transition cursor-pointer">
                    Classic Ivory
                </button>
                <button type="button" @click="theme = 'dark'"
                        :class="theme === 'dark' ? 'bg-[#162E44] text-white font-semibold border border-[#B5945B]/40' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-heading transition cursor-pointer">
                    Oxford Navy
                </button>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <template x-if="isExporting">
                <span class="text-xs text-slate-300 px-3 py-1.5 bg-slate-800 rounded-xl border border-slate-700" x-text="exportStatus"></span>
            </template>

            <button type="button" @click="downloadPNG()" :disabled="isExporting" class="flex-1 sm:flex-none justify-center px-4 py-2 bg-slate-800 hover:bg-slate-700 disabled:opacity-50 text-white font-heading font-medium rounded-xl text-xs border border-slate-700 transition cursor-pointer flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#C5A059]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Unduh PNG</span>
            </button>

            <button type="button" @click="downloadPDF()" :disabled="isExporting" class="flex-1 sm:flex-none justify-center px-4 py-2 bg-[#B5945B] hover:bg-[#A3834B] disabled:opacity-50 text-white font-heading font-semibold rounded-xl text-xs shadow-sm transition cursor-pointer flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh PDF (A4)</span>
            </button>

            <button type="button" onclick="window.print()" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-heading font-medium rounded-xl text-xs border border-slate-700 transition cursor-pointer flex items-center gap-1.5" title="Cetak Langsung">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span class="hidden sm:inline">Cetak</span>
            </button>
        </div>
    </div>

    <!-- Scalable Responsive Wrapper (True A4 Landscape Ratio 1050px x 742px) -->
    <div x-ref="wrapper" class="cert-scaler-wrapper w-full max-w-[1070px] mx-auto flex flex-col items-center">

        <div id="certificateScaleBox"
             class="cert-canvas-box transition-transform duration-200 origin-top" 
             :style="'width: 1050px; height: 742px; transform: scale(' + scale + '); margin-bottom: -' + ((1 - scale) * 742) + 'px;'">

            <!-- Official Printable Certificate Container -->
            <div id="certificateNode"
                 :class="theme === 'dark' ? 'bg-[#0D1F30] border-[#1B354D] text-[#F8FAFC]' : 'bg-[#FCFBF9] border-[#0F2537] text-[#0F2537]'"
                 class="cert-container w-[1050px] h-[742px] relative px-12 py-8 border-[10px] rounded-xl shadow-2xl overflow-hidden flex flex-col justify-between transition-colors duration-200"
                 @if(!empty($settings['certificate_bg'])) style="background-image: url('{{ $settings['certificate_bg'] }}'); background-size: cover; background-position: center;" @endif>
                
                <!-- Architectural Double Hairline Frame (Swiss/Ivy League Standard) -->
                <div class="absolute inset-3 border pointer-events-none"
                     :class="theme === 'dark' ? 'border-[#B5945B]/50' : 'border-[#B5945B]/70'"></div>
                <div class="absolute inset-[16px] border pointer-events-none"
                     :class="theme === 'dark' ? 'border-[#B5945B]/20' : 'border-[#E5E0D5]'"></div>

                <!-- Minimalist Corner Accents (Fine L-brackets) -->
                <div class="absolute top-3 left-3 w-4 h-4 border-t-2 border-l-2 border-[#B5945B] pointer-events-none"></div>
                <div class="absolute top-3 right-3 w-4 h-4 border-t-2 border-r-2 border-[#B5945B] pointer-events-none"></div>
                <div class="absolute bottom-3 left-3 w-4 h-4 border-b-2 border-l-2 border-[#B5945B] pointer-events-none"></div>
                <div class="absolute bottom-3 right-3 w-4 h-4 border-b-2 border-r-2 border-[#B5945B] pointer-events-none"></div>

                <!-- Subtle Watermark Logo -->
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.025] pointer-events-none">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0F2537&color=B5945B';" class="w-80 h-80 object-contain" alt="Ruhiology Watermark">
                </div>

                <!-- 1. HEADER SECTION -->
                <div class="relative z-10 text-center space-y-2 pt-1">
                    <div class="flex flex-col items-center justify-center">
                        <img src="{{ asset('images/ruhiology-logo.png') }}" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0F2537&color=B5945B';" class="h-11 w-11 object-contain mx-auto mb-1.5" alt="Logo">
                        <span class="block font-cinzel text-[13px] font-bold tracking-[0.28em] uppercase leading-none"
                              :class="theme === 'dark' ? 'text-[#E2C792]' : 'text-[#0F2537]'">
                            {{ $settings['institute_name'] ?? 'RUHIOLOGY INSTITUTE' }}
                        </span>
                        <span class="block text-[8.5px] tracking-[0.2em] uppercase font-heading font-medium mt-1"
                              :class="theme === 'dark' ? 'text-slate-400' : 'text-[#8C703E]'">
                            {{ $settings['tagline'] ?? 'Mengenal Diri · Mengembangkan Potensi · Menumbuhkan Ruh' }}
                        </span>
                    </div>

                    <div class="pt-1">
                        <h1 class="font-cinzel text-[24px] font-bold tracking-[0.12em] uppercase leading-tight"
                            :class="theme === 'dark' ? 'text-white' : 'text-[#0F2537]'">
                            {{ app()->getLocale() === 'en' ? 'OFFICIAL CERTIFICATE OF ASSESSMENT' : 'SERTIFIKAT HASIL ASESMEN RQI' }}
                        </h1>
                        <p class="text-[10.5px] font-serif-editorial italic tracking-wide mt-0.5"
                           :class="theme === 'dark' ? 'text-[#D5BC8A]' : 'text-slate-500'">
                            Ruhiology Quotient (RQI-15) & WHO-5 Mental Well-Being Official Assessment Report
                        </p>
                    </div>
                </div>

                <!-- 2. RECIPIENT & BODY SECTION -->
                <div class="relative z-10 text-center space-y-3 my-auto">
                    <span class="text-[9px] uppercase font-heading tracking-[0.28em] font-semibold block"
                          :class="theme === 'dark' ? 'text-slate-400' : 'text-slate-400'">
                        DIBERIKAN KEPADA
                    </span>

                    <!-- Participant Name -->
                    <div>
                        <h2 class="font-serif-editorial text-[32px] font-bold tracking-normal border-b inline-block px-12 pb-1 leading-tight"
                            :class="theme === 'dark' ? 'text-white border-[#B5945B]/60' : 'text-[#0F2537] border-[#C5A870]'">
                            {{ $submission->participant->name }}
                        </h2>
                        @php
                            $instName = $submission->participant->sub_category 
                                ?: $submission->participant->detail_institution
                                ?: $submission->participant->university_custom 
                                ?: $submission->participant->school_custom 
                                ?: $submission->participant->occupation_custom 
                                ?: $submission->participant->school?->name 
                                ?: $submission->participant->university?->name 
                                ?: null;
                            $domisili = trim(($submission->participant->regency?->name ?? '') . ($submission->participant->regency && $submission->participant->province ? ', ' : '') . ($submission->participant->province?->name ?? ''));
                        @endphp
                        <p class="text-[11px] font-normal mt-1.5 tracking-wide"
                           :class="theme === 'dark' ? 'text-slate-300' : 'text-slate-600'">
                            <span>{{ app()->getLocale() === 'en' ? 'Category:' : 'Kategori:' }} <strong class="font-semibold" :class="theme === 'dark' ? 'text-white' : 'text-[#0F2537]'">{{ ucwords(strtolower($submission->participant->category ?: 'General')) }}</strong></span>
                            @if($instName)
                                <span class="mx-2 text-[#B5945B]">|</span>
                                <span>{{ app()->getLocale() === 'en' ? 'Institution:' : 'Instansi:' }} <strong class="font-semibold" :class="theme === 'dark' ? 'text-white' : 'text-[#0F2537]'">{{ $instName }}</strong></span>
                            @endif
                            @if($domisili)
                                <span class="mx-2 text-[#B5945B]">|</span>
                                <span>{{ app()->getLocale() === 'en' ? 'Location:' : 'Domisili:' }} <strong class="font-semibold" :class="theme === 'dark' ? 'text-white' : 'text-[#0F2537]'">{{ $domisili }}</strong></span>
                            @endif
                        </p>
                    </div>

                    <p class="text-[11px] max-w-2xl mx-auto leading-relaxed font-normal"
                       :class="theme === 'dark' ? 'text-slate-300' : 'text-slate-600'">
                        {{ app()->getLocale() === 'en' ? 'Has successfully completed the psychometric evaluation mapping the 6 Unified Dimensions: 5 Pillars of Ruhiology Quotient (RQI-15) & Mental Well-Being Index (WHO-5) with the following achievements:' : 'Telah menyelesaikan evaluasi psikometrik terstruktur pemetaan 6 Dimensi Terpadu: 5 Pilar Kecerdasan Ruhiologi (RQI-15) & Indeks Kesejahteraan Mental (WHO-5) dengan rincian capaian sebagai berikut:' }}
                    </p>

                    <!-- DUAL SCORE CARDS (UNIFIED ARCHITECTURAL CARDS — CALM & AUTHORITATIVE) -->
                    @php
                        $allCertDims = $submission->result->dimensionResults ?? collect();
                        $rqiDimensions = $allCertDims->filter(function($dr) {
                            $code = strtolower($dr->dimension->code ?? '');
                            $name = strtolower($dr->dimension->name ?? '');
                            return !str_contains($code, 'who') && !str_contains($name, 'who');
                        })->take(5)->values();

                        $who5CertDim = $allCertDims->first(function($dr) {
                            $code = strtolower($dr->dimension->code ?? '');
                            $name = strtolower($dr->dimension->name ?? '');
                            return str_contains($code, 'who') || str_contains($name, 'who');
                        });

                        $rqiVal = $submission->result->rqi_score ?? $submission->result->total_score ?? 0;
                        $maxRqiVal = $submission->result->max_score ?: 75;
                        $rqiPctVal = round(($rqiVal / $maxRqiVal) * 100);
                        $who5RawVal = $submission->result->who5_raw_score
                            ?? ($who5CertDim ? $who5CertDim->score : round(($rqiPctVal / 100) * 25));
                        $who5PctVal = $submission->result->who5_percentage
                            ?? ($who5CertDim ? $who5CertDim->percentage : round(($who5RawVal / 25) * 100));

                        if ($who5RawVal >= 20 || $who5PctVal >= 80) {
                            $who5Label = 'Level 5: Thriving & Energetic (Sangat Sejahtera)';
                        } elseif ($who5RawVal >= 16 || $who5PctVal >= 64) {
                            $who5Label = 'Level 4: Good Vibe & Fresh (Sejahtera)';
                        } elseif ($who5RawVal >= 11 || $who5PctVal >= 44) {
                            $who5Label = 'Level 3: Moderate Well-Being (Cukup Stabil)';
                        } elseif ($who5RawVal >= 6 || $who5PctVal >= 24) {
                            $who5Label = 'Level 2: Low Energy (Perlu Perhatian)';
                        } else {
                            $who5Label = 'Level 1: Mental Exhausted (Perlu Pendampingan)';
                        }

                        $cleanPillarLabel = function($rawName, $isWho5 = false) {
                            $isEn = app()->getLocale() === 'en';
                            $lower = strtolower($rawName);
                            if ($isWho5 || str_contains($lower, 'who')) return $isEn ? 'WHO-5 Well-Being' : 'Kesejahteraan WHO-5';
                            if (str_contains($lower, 'diri')) return $isEn ? 'Authentic Self-Awareness' : 'Kesadaran Diri & Ruh';
                            if (str_contains($lower, 'puncak')) return $isEn ? 'Peak Divine Consciousness' : 'Kesadaran Puncak Ilahi';
                            if (str_contains($lower, 'god spot') || str_contains($lower, 'ketuhanan') || str_contains($lower, 'spiritual connection')) return $isEn ? 'Divine Recognition' : 'Kesadaran Ketuhanan';
                            if (str_contains($lower, 'ibadah') || str_contains($lower, 'ketaatan')) return $isEn ? 'Spiritual Devotion' : 'Ketaatan Spiritual';
                            if (str_contains($lower, 'tazkiyah') || str_contains($lower, 'purifikasi') || str_contains($lower, 'inner peace')) return $isEn ? 'Inner Purification' : 'Purifikasi Batin';
                            if (str_contains($lower, 'perilaku') || str_contains($lower, 'akhlak') || str_contains($lower, 'altruistic')) return $isEn ? 'Noble Character & Ethics' : 'Akhlak & Perilaku';
                            if (str_contains($lower, 'resiliensi') || str_contains($lower, 'ketangguhan')) return $isEn ? 'Spiritual Resilience' : 'Resiliensi Jiwa';
                            $clean = preg_replace('/^\d+\.\s*/', '', trim($rawName));
                            return $clean;
                        };
                            $clean = preg_replace('/\s*\(.*?\)/', '', $clean);
                            return \Illuminate\Support\Str::limit($clean, 20, '');
                        };

                        $sixCertDimensions = $rqiDimensions->map(function($dr, $idx) use ($cleanPillarLabel) {
                            return (object) [
                                'header' => 'DIMENSI 0' . ($idx + 1),
                                'label' => $cleanPillarLabel($dr->dimension->name ?? ('Dimensi ' . ($idx + 1)), false),
                                'score' => $dr->score,
                                'max_score' => $dr->max_score,
                                'pct' => round($dr->percentage),
                                'is_who5' => false,
                            ];
                        })->values();

                        $sixCertDimensions->push((object) [
                            'header' => 'DIMENSI 06 · WHO-5',
                            'label' => 'Kesejahteraan Mental',
                            'score' => $who5RawVal,
                            'max_score' => 25,
                            'pct' => round($who5PctVal),
                            'is_who5' => true,
                        ]);
                    @endphp
                    <div class="grid grid-cols-2 gap-5 max-w-4xl mx-auto pt-0.5">
                        
                        <!-- CARD 1: SKOR RUHIOLOGI (RQI-15) -->
                        <div class="px-5 py-3.5 rounded-xl border border-l-4 border-l-[#0F2537] text-left flex items-center justify-between gap-4 shadow-2xs"
                             :class="theme === 'dark' ? 'bg-[#13293D] border-[#26435E] border-l-[#C5A059]' : 'bg-white border-[#E5E0D5] border-l-[#0F2537]'">
                            <div class="space-y-1 min-w-0 flex-1">
                                <span class="text-[8.5px] uppercase font-heading font-semibold tracking-[0.14em] block"
                                      :class="theme === 'dark' ? 'text-[#D5BC8A]' : 'text-[#8C703E]'">
                                    PILAR 01 · INDEKS RUHIOLOGI (RQI-15)
                                </span>
                                <div class="text-[10.5px] font-heading font-semibold truncate"
                                     :class="theme === 'dark' ? 'text-slate-200' : 'text-[#0F2537]'">
                                    {{ $submission->result->category_name ?? 'Level 3: Developing Soul' }}
                                </div>
                                <div class="w-full h-1.5 rounded-full overflow-hidden"
                                     :class="theme === 'dark' ? 'bg-slate-800' : 'bg-[#EFECE6]'">
                                    <div class="h-full bg-[#B5945B] rounded-full" style="width: {{ min(100, $rqiPctVal) }}%"></div>
                                </div>
                            </div>
                            <div class="text-right shrink-0 pl-3 border-l"
                                 :class="theme === 'dark' ? 'border-slate-700' : 'border-slate-100'">
                                <div class="font-heading text-2xl font-bold leading-none"
                                     :class="theme === 'dark' ? 'text-white' : 'text-[#0F2537]'">
                                    {{ number_format($rqiVal, 0) }}<span class="text-xs font-normal text-slate-400">/{{ $maxRqiVal }}</span>
                                </div>
                                <span class="text-[9.5px] font-mono font-semibold text-[#B5945B] block mt-1">
                                    Capaian {{ $rqiPctVal }}%
                                </span>
                            </div>
                        </div>

                        <!-- CARD 2: SKOR KESEJAHTERAAN MENTAL (WHO-5) -->
                        <div class="px-5 py-3.5 rounded-xl border border-l-4 text-left flex items-center justify-between gap-4 shadow-2xs"
                             :class="theme === 'dark' ? 'bg-[#13293D] border-[#26435E] border-l-[#3E8E7E]' : 'bg-white border-[#E5E0D5] border-l-[#1E4E44]'">
                            <div class="space-y-1 min-w-0 flex-1">
                                <span class="text-[8.5px] uppercase font-heading font-semibold tracking-[0.14em] block"
                                      :class="theme === 'dark' ? 'text-[#7CC4B5]' : 'text-[#1E4E44]'">
                                    PILAR 02 · KESEJAHTERAAN MENTAL (WHO-5)
                                </span>
                                <div class="text-[10.5px] font-heading font-semibold truncate"
                                     :class="theme === 'dark' ? 'text-slate-200' : 'text-[#0F2537]'">
                                    {{ $who5Label }}
                                </div>
                                <div class="w-full h-1.5 rounded-full overflow-hidden"
                                     :class="theme === 'dark' ? 'bg-slate-800' : 'bg-[#EFECE6]'">
                                    <div class="h-full bg-[#1E4E44] rounded-full" style="width: {{ min(100, $who5PctVal) }}%"></div>
                                </div>
                            </div>
                            <div class="text-right shrink-0 pl-3 border-l"
                                 :class="theme === 'dark' ? 'border-slate-700' : 'border-slate-100'">
                                <div class="font-heading text-2xl font-bold leading-none"
                                     :class="theme === 'dark' ? 'text-white' : 'text-[#1E4E44]'">
                                    {{ number_format($who5PctVal, 0) }}<span class="text-xs font-normal text-slate-400">%</span>
                                </div>
                                <span class="text-[9.5px] font-mono font-semibold text-slate-400 block mt-1">
                                    Skor {{ $who5RawVal }}/25
                                </span>
                            </div>
                        </div>

                    </div>

                    <!-- 6-DIMENSION COMPETENCY MATRIX (5 PILAR RQI + DIMENSI 06 WHO-5) -->
                    @if($sixCertDimensions->count() > 0)
                        <div class="max-w-4xl mx-auto rounded-xl border px-3.5 py-2.5 shadow-2xs"
                             :class="theme === 'dark' ? 'bg-[#13293D] border-[#26435E]' : 'bg-white border-[#E5E0D5]'">
                            <div class="grid grid-cols-6 divide-x"
                                 :class="theme === 'dark' ? 'divide-slate-700/80' : 'divide-[#EFECE6]'">
                                @foreach($sixCertDimensions as $idx => $dr)
                                    <div class="{{ $idx === 0 ? 'pr-2.5' : ($idx === ($sixCertDimensions->count() - 1) ? 'pl-2.5' : 'px-2.5') }} text-left flex flex-col justify-between">
                                        <div>
                                            <span class="block text-[7px] font-mono uppercase tracking-wider font-semibold"
                                                  :class="{{ $dr->is_who5 ? "theme === 'dark' ? 'text-[#7CC4B5]' : 'text-[#1E4E44]'" : "'text-[#B5945B]'" }}">
                                                {{ $dr->header }}
                                            </span>
                                            <span class="block text-[8.5px] font-heading font-semibold leading-snug mt-0.5"
                                                  :class="theme === 'dark' ? 'text-slate-100' : 'text-[#0F2537]'">
                                                {{ $dr->label }}
                                            </span>
                                        </div>
                                        <div class="mt-2 space-y-1">
                                            <div class="flex items-baseline justify-between font-mono">
                                                <span class="text-[7.5px] text-slate-400">{{ $dr->score }}/{{ $dr->max_score }}</span>
                                                <span class="text-[10px] font-bold"
                                                      :class="{{ $dr->is_who5 ? "theme === 'dark' ? 'text-[#7CC4B5]' : 'text-[#1E4E44]'" : "theme === 'dark' ? 'text-[#E2C792]' : 'text-[#0F2537]'" }}">
                                                    {{ $dr->pct }}%
                                                </span>
                                            </div>
                                            <div class="w-full h-1 rounded-full overflow-hidden"
                                                 :class="theme === 'dark' ? 'bg-slate-800' : 'bg-[#EFECE6]'">
                                                <div class="h-full rounded-full"
                                                     :class="{{ $dr->is_who5 ? "theme === 'dark' ? 'bg-[#7CC4B5]' : 'bg-[#1E4E44]'" : "theme === 'dark' ? 'bg-[#C5A059]' : 'bg-[#0F2537]'" }}"
                                                     style="width: {{ min(100, $dr->pct) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- 3. FOOTER SECTION -->
                <div class="relative z-10 space-y-2 pt-2.5 border-t font-sans"
                     :class="theme === 'dark' ? 'border-[#26435E]' : 'border-[#E5E0D5]'">
                    
                    <div class="grid grid-cols-3 items-end gap-4">
                        
                        <!-- BOTTOM LEFT: DOCUMENT METADATA -->
                        <div class="text-left space-y-0.5">
                            <span class="block text-[8px] font-mono uppercase tracking-widest text-[#B5945B] font-semibold">
                                AUTENTIKASI DOKUMEN RESMI
                            </span>
                            <div class="text-[9.5px] font-mono font-semibold"
                                 :class="theme === 'dark' ? 'text-white' : 'text-[#0F2537]'">
                                NO: CERT/RQI/{{ date('Y') }}/{{ strtoupper($submission->submission_code) }}
                            </div>
                            <span class="block text-[8.5px]"
                                  :class="theme === 'dark' ? 'text-slate-400' : 'text-slate-500'">
                                Tanggal Terbit: {{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d F Y') : date('d F Y') }}
                            </span>
                        </div>

                        <!-- BOTTOM CENTER: MINIMALIST ARCHITECTURAL DRY-EMBOSS SEAL -->
                        <div class="text-center flex flex-col items-center justify-center">
                            <div class="w-14 h-14 rounded-full border flex items-center justify-center p-1"
                                 :class="theme === 'dark' ? 'border-[#B5945B]/60 bg-[#13293D]' : 'border-[#C5A870] bg-[#FAF8F3]'">
                                <div class="w-full h-full rounded-full border border-dashed border-[#B5945B]/70 flex flex-col items-center justify-center text-center">
                                    <span class="text-[5.5px] font-cinzel font-bold tracking-widest leading-none"
                                          :class="theme === 'dark' ? 'text-[#E2C792]' : 'text-[#0F2537]'">RUHIOLOGY</span>
                                    <div class="w-5 h-[1px] bg-[#B5945B] my-1"></div>
                                    <span class="text-[4.5px] font-mono font-semibold text-[#B5945B] tracking-widest leading-none">VERIFIED</span>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM RIGHT: SIGNATURE & QR CODE -->
                        <div class="text-right flex items-end justify-end gap-3.5">
                            <div class="text-right">
                                <p class="text-[8.5px] mb-3"
                                   :class="theme === 'dark' ? 'text-slate-400' : 'text-slate-500'">
                                    Jambi, {{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d F Y') : date('d F Y') }}
                                </p>
                                <h3 class="font-serif-editorial text-[12px] font-bold leading-tight border-b pb-0.5 inline-block"
                                    :class="theme === 'dark' ? 'text-white border-[#B5945B]/50' : 'text-[#0F2537] border-slate-300'">
                                    {{ $settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.' }}
                                </h3>
                                <p class="text-[8.5px] font-heading font-medium mt-0.5"
                                   :class="theme === 'dark' ? 'text-[#D5BC8A]' : 'text-[#8C703E]'">
                                    {{ $settings['founder_title'] ?? 'Founder & Guru Besar Ruhiology Institute' }}
                                </p>
                            </div>

                            <!-- QR Code Validasi -->
                            <div class="flex flex-col items-center shrink-0">
                                <div class="p-1 bg-white border rounded-lg"
                                     :class="theme === 'dark' ? 'border-[#B5945B]/50' : 'border-[#E5E0D5]'">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&color=0F2537&data={{ urlencode(route('assessment.result', $submission->submission_code)) }}" 
                                         crossorigin="anonymous"
                                         alt="QR Verifikasi" 
                                         class="w-11 h-11 object-contain">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
