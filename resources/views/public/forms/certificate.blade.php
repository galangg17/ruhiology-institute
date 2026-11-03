<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Resmi — {{ $response->participant_name }} — {{ $form->certificate_event_name ?: $form->title }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 45,
                        spread: 60,
                        origin: { y: 0.2 },
                        colors: ['#D4AF37', '#C9A24D', '#E5C068', '#0B2A43']
                    });
                }
            }, 600);
        });
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F172A;
            color: #0B2A43;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-serif-title { font-family: 'Playfair Display', Georgia, serif; }

        /* A4 Landscape dimensions */
        .a4-landscape {
            width: 297mm;
            height: 210mm;
            box-sizing: border-box;
            background: #FFFFFF;
            position: relative;
            overflow: hidden;
            margin: 0 auto 24px auto;
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
                background: #FFFFFF !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .a4-landscape {
                margin: 0 !important;
                page-break-after: always;
                page-break-inside: avoid;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen py-6 px-2 sm:px-4 flex flex-col items-center justify-start"
      x-data="{
          isExporting: false,
          isExportingStory: false,
          exportStatus: '',
          async downloadPdf() {
              this.isExporting = true;
              this.exportStatus = 'Menyiapkan halaman 1...';
              
              const { jsPDF } = window.jspdf;
              const pdf = new jsPDF({
                  orientation: 'landscape',
                  unit: 'mm',
                  format: 'a4'
              });

              try {
                  // Capture Page 1
                  const page1 = document.getElementById('certPage1');
                  const canvas1 = await html2canvas(page1, {
                      scale: 2,
                      useCORS: true,
                      logging: false
                  });
                  const imgData1 = canvas1.toDataURL('image/jpeg', 0.95);
                  pdf.addImage(imgData1, 'JPEG', 0, 0, 297, 210);

                  // Capture Page 2
                  this.exportStatus = 'Menyiapkan halaman 2...';
                  const page2 = document.getElementById('certPage2');
                  const canvas2 = await html2canvas(page2, {
                      scale: 2,
                      useCORS: true,
                      logging: false
                  });
                  const imgData2 = canvas2.toDataURL('image/jpeg', 0.95);
                  pdf.addPage('a4', 'landscape');
                  pdf.addImage(imgData2, 'JPEG', 0, 0, 297, 210);

                  const safeName = 'Sertifikat_' + '{{ Str::slug($response->participant_name) }}' + '.pdf';
                  pdf.save(safeName);
                  this.exportStatus = 'Selesai!';
              } catch(e) {
                  alert('Gagal mengekspor PDF otomatis. Silakan gunakan tombol Cetak Browser (Print PDF).');
              } finally {
                  setTimeout(() => { this.isExporting = false; }, 1500);
              }
          },
          async downloadStory() {
              this.isExportingStory = true;
              try {
                  const card = document.getElementById('storyCard');
                  card.style.display = 'flex';
                  const canvas = await html2canvas(card, {
                      scale: 2.5,
                      useCORS: true,
                      logging: false,
                      backgroundColor: '#071626'
                  });
                  card.style.display = 'none';
                  const link = document.createElement('a');
                  link.download = 'Story_Sertifikat_' + '{{ Str::slug($response->participant_name) }}' + '.png';
                  link.href = canvas.toDataURL('image/png');
                  link.click();
              } catch(e) {
                  console.error(e);
                  alert('Gagal membuat kartu Story. Silakan coba kembali.');
              } finally {
                  this.isExportingStory = false;
              }
          }
      }">

    <!-- Top Action Navigation Bar (Hidden on print) -->
    <div class="no-print w-full max-w-[297mm] mb-5 bg-slate-900/90 backdrop-blur-md p-4 rounded-2xl border border-slate-700/80 shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-3 text-white">
        <div class="flex items-center gap-3">
            <a href="{{ route('public.form.claim_certificate', $form->slug) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Pencarian
            </a>
            <div class="hidden sm:block text-xs font-medium text-slate-300">
                Sertifikat Resmi: <span class="font-bold text-white">{{ $response->participant_name }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Unduh Kartu Story (9:16) -->
            <button type="button" @click="downloadStory()" :disabled="isExportingStory" 
                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-[#D4AF37] hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-lg transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50">
                <template x-if="!isExportingStory">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span>Unduh Story (9:16)</span>
                    </span>
                </template>
                <template x-if="isExportingStory">
                    <span>Menyiapkan Story...</span>
                </template>
            </button>

            <!-- Cetak Print -->
            <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5 cursor-pointer border border-slate-700">
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Print</span>
            </button>

            <!-- Unduh PDF Resmi (2 Halaman) -->
            <button type="button" @click="downloadPdf()" :disabled="isExporting" 
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold text-xs shadow-lg transition flex items-center gap-2 cursor-pointer disabled:opacity-50">
                <template x-if="!isExporting">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Unduh PDF Resmi (2 Halaman)</span>
                    </span>
                </template>
                <template x-if="isExporting">
                    <span class="flex items-center gap-1.5" x-text="exportStatus"></span>
                </template>
            </button>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 1: SERTIFIKAT MUKA                 -->
    <!-- ========================================== -->
    <div id="certPage1" class="a4-landscape shadow-2xl p-10 flex flex-col justify-between border-[8px] border-[#0B2A43] relative">
        
        <!-- Luxury Double Border Accent (Gold) -->
        <div class="absolute inset-2 border-[2px] border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute inset-3 border-[1px] border-[#D4AF37]/40 pointer-events-none"></div>

        <!-- Corner Ornaments -->
        <div class="absolute top-4 left-4 w-10 h-10 border-t-2 border-l-2 border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute top-4 right-4 w-10 h-10 border-t-2 border-r-2 border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute bottom-4 left-4 w-10 h-10 border-b-2 border-l-2 border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute bottom-4 right-4 w-10 h-10 border-b-2 border-r-2 border-[#D4AF37] pointer-events-none"></div>

        <!-- Security Watermark & Guilloche Motif -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.035] select-none z-0">
            <svg class="w-[520px] h-[520px]" viewBox="0 0 200 200" fill="none" stroke="#0B2A43" stroke-width="0.75">
                <circle cx="100" cy="100" r="92" stroke-dasharray="2,2"/>
                <circle cx="100" cy="100" r="78"/>
                <circle cx="100" cy="100" r="62" stroke-dasharray="3,3"/>
                <polygon points="100,15 125,60 175,60 135,95 150,145 100,115 50,145 65,95 25,60 75,60"/>
                <text x="100" y="102" text-anchor="middle" font-family="Cinzel" font-size="8.5" font-weight="bold" fill="#0B2A43">RUHIOLOGY INSTITUTE</text>
                <text x="100" y="112" text-anchor="middle" font-family="Plus Jakarta Sans" font-size="4.5" font-weight="bold" fill="#0B2A43">OFFICIAL VERIFIED CREDENTIAL</text>
            </svg>
        </div>

        <!-- 1. Header Kop (Logos & Institute Name) -->
        <div class="relative z-10 flex items-center justify-between border-b border-slate-200/90 pb-4 px-4">
            <!-- Left: Ruhiology Logo & Institution Name -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology Logo" class="h-14 w-auto object-contain">
                <div>
                    <h2 class="font-cinzel font-bold text-lg tracking-wider text-[#0B2A43] leading-none">RUHIOLOGY INSTITUTE</h2>
                    <p class="text-[9px] uppercase tracking-widest text-[#B48A16] font-semibold mt-1">Center for Spiritual & Human Potential Development</p>
                    <p class="text-[8px] text-slate-400">SK Kemenkumham RI No. AHU-0012948.AH.01.04 • www.ruhiology-institute.site</p>
                </div>
            </div>

            <!-- Right: Partner Logo (if any) or Event Badge -->
            <div class="flex items-center gap-3">
                @if($form->certificate_partner_logo)
                    <img src="{{ $form->certificate_partner_logo }}" alt="Logo Mitra" class="h-14 w-auto object-contain">
                @else
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 bg-amber-50 text-[#8C703E] border border-amber-200 rounded-full text-[10px] font-bold uppercase tracking-wider font-mono">
                            {{ $form->calculated_total_jp }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. Certificate Body Content -->
        <div class="relative z-10 text-center space-y-3 my-auto py-2 px-6">
            <h1 class="font-cinzel text-3xl md:text-4xl font-extrabold tracking-widest text-[#0B2A43]">
                SERTIFIKAT
            </h1>
            
            <div class="inline-block">
                <p class="font-mono text-xs font-semibold text-[#8C703E] tracking-wider px-4 py-0.5 border-b border-[#D4AF37]">
                    NOMOR: {{ $response->clean_certificate_number }}
                </p>
            </div>

            <p class="text-xs uppercase tracking-widest text-slate-500 font-medium pt-1">
                Diberikan dengan hormat kepada:
            </p>

            <!-- Recipient Name -->
            <div class="pt-1 pb-1">
                <h2 class="font-serif-title font-bold text-2xl md:text-3xl text-[#0B2A43] tracking-wide underline decoration-[#D4AF37] decoration-2 underline-offset-8">
                    {{ $response->participant_name }}
                </h2>
                @if($response->participant_institution)
                    <p class="text-xs font-semibold text-slate-600 mt-2 font-sans tracking-wide">
                        {{ $response->participant_institution }}
                    </p>
                @endif
            </div>

            <!-- Narrative Text -->
            <div class="max-w-3xl mx-auto text-xs text-slate-700 leading-relaxed font-sans pt-2">
                Atas partisipasi aktif dan kelulusannya sebagai <strong class="text-[#0B2A43] uppercase">PESERTA</strong> dalam agenda:
                <h3 class="font-serif-title font-bold text-base md:text-lg text-[#0B2A43] my-1 leading-snug">
                    "{{ $form->clean_certificate_event_name }}"
                </h3>
                <span>
                    yang diselenggarakan pada tanggal <strong>{{ $form->certificate_event_date ?: now()->translatedFormat('d F Y') }}</strong>
                    @if($form->certificate_event_location)
                        bertempat di <strong>{{ $form->certificate_event_location }}</strong>
                    @endif
                    dengan akumulasi bobot materi sebanyak <strong>{{ $form->calculated_total_jp }}</strong>.
                </span>
            </div>
        </div>

        <!-- 3. Signatures & QR Code Section -->
        @php
            $signer1Raw = (string)$form->certificate_signer1_name;
            $signer2Raw = (string)$form->certificate_signer2_name;
            $s1Norm = strtolower(preg_replace('/[^a-z]/i', '', $signer1Raw));
            $s2Norm = strtolower(preg_replace('/[^a-z]/i', '', $signer2Raw));

            $isIskandar1 = str_contains($s1Norm, 'iskandar') || str_contains($s1Norm, 'nazari');
            $isIskandar2 = str_contains($s2Norm, 'iskandar') || str_contains($s2Norm, 'nazari');

            // Jika tanda tangan ke-2 kosong, sama, atau keduanya Prof. Iskandar, gunakan 1 TTD tunggal resmi
            $hasSecondSigner = !empty($signer2Raw) 
                && trim($signer2Raw) !== '' 
                && !($isIskandar1 && $isIskandar2)
                && $s1Norm !== $s2Norm;

            $officialSignerRole = 'Founder & Direktur Ruhiology Institute';
            $officialSignerName = 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.';
        @endphp

        @if($hasSecondSigner)
            <!-- DUA TANDA TANGAN (KEMITRAAN DENGAN INSTANSI LAIN) -->
            <div class="relative z-10 pt-3 border-t border-slate-200/90 px-8 flex items-end justify-between text-xs">
                <!-- Signer 1: Founder & Direktur -->
                <div class="text-center w-56">
                    <p class="text-[11px] font-bold text-[#0B2A43] leading-tight min-h-[28px] flex items-center justify-center">
                        {{ $form->certificate_signer1_role ?: $officialSignerRole }}
                    </p>
                    <div class="h-14 flex items-center justify-center my-1">
                        @if($form->certificate_signer1_signature)
                            <img src="{{ $form->certificate_signer1_signature }}" alt="QR Tanda Tangan" class="h-12 object-contain">
                        @else
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('public.certificate.verify', $response->response_code)) }}" alt="QR Tanda Tangan" class="w-12 h-12 p-0.5 border border-slate-200 rounded-lg">
                        @endif
                    </div>
                    <strong class="block font-bold text-slate-900 border-b border-slate-300 pb-0.5 text-xs">
                        {{ $isIskandar1 ? $officialSignerName : ($form->certificate_signer1_name ?: $officialSignerName) }}
                    </strong>
                </div>

                <!-- Center: QR Code Verification -->
                <div class="text-center px-4 flex flex-col items-center">
                    <div class="p-1 bg-white border border-slate-200 rounded-xl shadow-xs">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('public.certificate.verify', $response->response_code)) }}" 
                             alt="QR Verifikasi" class="w-14 h-14">
                    </div>
                    <span class="text-[8px] font-mono text-slate-400 mt-1 uppercase tracking-wider">Scan untuk Keaslian Digital</span>
                    <span class="text-[8px] font-mono font-bold text-slate-600">{{ $response->response_code }}</span>
                </div>

                <!-- Signer 2 (Pimpinan Mitra / Sekolah) -->
                <div class="text-center w-56">
                    <p class="text-[11px] font-bold text-[#0B2A43] leading-tight min-h-[28px] flex items-center justify-center">
                        {{ $form->certificate_signer2_role ?: 'Pimpinan Mitra / Instansi' }}
                    </p>
                    <div class="h-14 flex items-center justify-center my-1">
                        @if($form->certificate_signer2_signature)
                            <img src="{{ $form->certificate_signer2_signature }}" alt="TTD Mitra" class="h-12 object-contain">
                        @else
                            <div class="w-12 h-12 rounded-full border border-slate-200 flex items-center justify-center text-[8px] text-slate-400 uppercase font-mono">
                                STEMPEL
                            </div>
                        @endif
                    </div>
                    <strong class="block font-bold text-slate-900 border-b border-slate-300 pb-0.5 text-xs">
                        {{ $form->certificate_signer2_name }}
                    </strong>
                </div>
            </div>
        @else
            <!-- SATU TANDA TANGAN TUNGGAL RESMI (FOUNDER & DIREKTUR RUHIOLOGY INSTITUTE) -->
            <div class="relative z-10 pt-3 border-t border-slate-200/90 px-8 flex items-end justify-between text-xs">
                <!-- Sisi Kiri: Verifikasi Keaslian Digital -->
                <div class="flex items-center gap-3 bg-slate-50/90 p-2.5 rounded-xl border border-slate-200/90 shadow-2xs">
                    <div class="p-1 bg-white border border-slate-200 rounded-lg shadow-2xs shrink-0">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('public.certificate.verify', $response->response_code)) }}" 
                             alt="QR Verifikasi" class="w-14 h-14">
                    </div>
                    <div class="space-y-0.5 text-left">
                        <span class="text-[8px] uppercase tracking-wider font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 inline-block font-mono">
                            ✓ Dokumen Sah & Terverifikasi
                        </span>
                        <p class="text-[9px] font-mono font-bold text-slate-700 pt-0.5">KODE: {{ $response->response_code }}</p>
                        <p class="text-[8px] text-slate-400 font-sans max-w-[210px] leading-tight">
                            Pindai QR untuk memeriksa autentikasi & validitas piagam ini pada server digital Ruhiology Institute.
                        </p>
                    </div>
                </div>

                <!-- Sisi Kanan: Format Permintaan: Jabatan -> QR -> Nama -->
                <div class="text-center w-72">
                    <p class="text-[10px] text-slate-500 mb-0.5 font-sans">
                        {{ $form->certificate_event_location ? explode(',', $form->certificate_event_location)[0] : 'Jambi' }}, {{ $form->certificate_event_date ?: now()->translatedFormat('d F Y') }}
                    </p>
                    <p class="text-xs font-bold text-[#0B2A43] leading-snug">
                        {{ $officialSignerRole }}
                    </p>
                    
                    <!-- QR Code / Tanda Tangan Digital Resmi -->
                    <div class="h-14 flex items-center justify-center my-1">
                        @if($form->certificate_signer1_signature)
                            <img src="{{ $form->certificate_signer1_signature }}" alt="QR Tanda Tangan" class="h-12 object-contain">
                        @else
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('public.certificate.verify', $response->response_code)) }}" alt="QR Tanda Tangan" class="w-12 h-12 p-0.5 border border-slate-200 rounded-lg">
                        @endif
                    </div>

                    <!-- Nama Lengkap & Gelar Resmi -->
                    <strong class="block font-bold text-slate-900 border-b border-slate-300 pb-0.5 text-xs tracking-wide">
                        {{ $officialSignerName }}
                    </strong>
                </div>
            </div>
        @endif

    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 2: LEMBAR STRUKTUR MATERI & JP     -->
    <!-- ========================================== -->
    <div id="certPage2" class="a4-landscape shadow-2xl p-6 sm:p-7 flex flex-col justify-between border-[8px] border-[#0B2A43] relative">
        
        <!-- Double Border Accent (Gold) -->
        <div class="absolute inset-2 border-[2px] border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute inset-3 border-[1px] border-[#D4AF37]/40 pointer-events-none"></div>

        <!-- Security Watermark & Guilloche Motif -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.035] select-none z-0">
            <svg class="w-[520px] h-[520px]" viewBox="0 0 200 200" fill="none" stroke="#0B2A43" stroke-width="0.75">
                <circle cx="100" cy="100" r="92" stroke-dasharray="2,2"/>
                <circle cx="100" cy="100" r="78"/>
                <circle cx="100" cy="100" r="62" stroke-dasharray="3,3"/>
                <polygon points="100,15 125,60 175,60 135,95 150,145 100,115 50,145 65,95 25,60 75,60"/>
                <text x="100" y="102" text-anchor="middle" font-family="Cinzel" font-size="8.5" font-weight="bold" fill="#0B2A43">RUHIOLOGY INSTITUTE</text>
                <text x="100" y="112" text-anchor="middle" font-family="Plus Jakarta Sans" font-size="4.5" font-weight="bold" fill="#0B2A43">OFFICIAL VERIFIED CREDENTIAL</text>
            </svg>
        </div>

        <!-- 1. Header Halaman Belakang -->
        <div class="relative z-10 border-b border-slate-200 pb-2 text-center">
            <h2 class="font-cinzel text-lg sm:text-xl font-bold tracking-widest text-[#0B2A43]">
                STRUKTUR PROGRAM & MATRIKS JAM PELAJARAN (JP)
            </h2>
            <p class="font-serif-title font-semibold text-xs sm:text-sm text-[#8C703E] mt-0.5">
                "{{ $form->clean_certificate_event_name }}"
            </p>
            <div class="flex items-center justify-center gap-4 text-[9px] text-slate-500 mt-1 font-mono">
                <span>Nama Peserta: <strong class="text-slate-800">{{ $response->participant_name }}</strong></span>
                <span>•</span>
                <span>No. Sertifikat: <strong class="text-slate-800">{{ $response->clean_certificate_number }}</strong></span>
            </div>
        </div>

        <!-- 2. Curriculum Table -->
        <div class="relative z-10 my-auto py-1 px-4 sm:px-6">
            @php
                $curriculum = $form->parsed_curriculum;
                if (empty($curriculum)) {
                    $curriculum = [
                        ['title' => 'Konsep Dasar & Paradigma Kecerdasan Ruhiologi dalam Pendidikan', 'jp' => '4 JP'],
                        ['title' => 'Identifikasi & Diagnosa Profil Potensi Karakter Peserta Didik', 'jp' => '8 JP'],
                        ['title' => 'Perancangan Strategi Bimbingan & Fasilitasi Ruhiologi di Sekolah', 'jp' => '12 JP'],
                        ['title' => 'Evaluasi, Supervisi Klinis, dan Etika Profesi Fasilitator', 'jp' => '8 JP'],
                    ];
                }
            @endphp

            <div class="overflow-hidden rounded-xl border border-slate-300">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#0B2A43] text-white text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2 px-4 w-12 text-center border-r border-slate-700">No</th>
                            <th class="py-2 px-6 border-r border-slate-700">Pokok Bahasan / Materi Pelatihan</th>
                            <th class="py-2 px-6 w-36 text-center">Alokasi Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 font-medium text-slate-800">
                        @foreach($curriculum as $index => $item)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/70' }}">
                                <td class="py-2 px-4 text-center font-bold text-slate-500 border-r border-slate-200">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-2 px-6 border-r border-slate-200 font-semibold text-slate-800">
                                    {{ $item['title'] }}
                                </td>
                                <td class="py-2 px-6 text-center font-bold text-[#8C703E]">
                                    {{ $item['jp'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-amber-50/80 border-t-2 border-slate-300 text-xs font-bold text-[#0B2A43]">
                            <td colspan="2" class="py-2 px-6 text-right uppercase tracking-wider border-r border-slate-300">
                                Total Alokasi Jam Pelajaran (JP)
                            </td>
                            <td class="py-2 px-6 text-center text-sm font-black text-[#8C703E]">
                                {{ $form->calculated_total_jp }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <p class="text-[9px] text-slate-400 mt-1 italic text-center">
                * 1 (satu) Jam Pelajaran (JP) setara dengan 45 menit pembelajaran aktif/praktik terbimbing.
            </p>
        </div>

        <!-- 3. Signature & Validation Footer Halaman 2 -->
        <div class="relative z-10 pt-2 border-t border-slate-200/90 px-6 sm:px-8 flex items-end justify-between text-xs">
            <div class="text-[9px] text-slate-500 max-w-sm leading-relaxed">
                <p><strong>Catatan Legalitas:</strong></p>
                <p>Dokumen ini merupakan lampiran kurikulum materi resmi dan tak terpisahkan dari lembar sertifikat utama ber-QR digital dengan nomor seri registrasi <strong>{{ $response->clean_certificate_number }}</strong>.</p>
            </div>

            <div class="text-center w-72">
                <p class="text-[10px] text-slate-500 mb-0.5">
                    Ditetapkan di: {{ $form->certificate_event_location ? explode(',', $form->certificate_event_location)[0] : 'Jambi' }}, {{ $form->certificate_event_date ?: now()->translatedFormat('d F Y') }}
                </p>
                <p class="text-xs font-bold text-[#0B2A43] leading-snug">
                    {{ $officialSignerRole }}
                </p>
                <div class="h-14 flex items-center justify-center my-1">
                    @if($form->certificate_signer1_signature)
                        <img src="{{ $form->certificate_signer1_signature }}" alt="QR Pengesahan" class="h-12 object-contain">
                    @else
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('public.certificate.verify', $response->response_code)) }}" alt="QR Pengesahan" class="w-12 h-12 p-0.5 border border-slate-200 rounded-lg">
                    @endif
                </div>
                <strong class="block font-bold text-slate-900 border-b border-slate-300 pb-0.5 text-xs tracking-wide">
                    {{ $officialSignerName }}
                </strong>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- KARTU HASIL / STORY SOSIAL MEDIA (9:16)    -->
    <!-- (Hidden by default, captured by html2canvas)-->
    <!-- ========================================== -->
    <div id="storyCard" class="hidden flex-col justify-between p-10 text-center text-white relative overflow-hidden font-sans select-none" 
         style="width: 480px; height: 854px; background: radial-gradient(circle at 50% 15%, #0F3252 0%, #061524 100%); border: 8px solid #0B2A43;">
        
        <!-- Luxury Gold Borders for Story -->
        <div class="absolute inset-2 border-[2px] border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute inset-3 border-[1px] border-[#D4AF37]/40 pointer-events-none"></div>

        <!-- Corner Accents -->
        <div class="absolute top-4 left-4 w-8 h-8 border-t-2 border-l-2 border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute top-4 right-4 w-8 h-8 border-t-2 border-r-2 border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute bottom-4 left-4 w-8 h-8 border-b-2 border-l-2 border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute bottom-4 right-4 w-8 h-8 border-b-2 border-r-2 border-[#D4AF37] pointer-events-none"></div>

        <!-- Faint Background Star & Seal -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.06] z-0">
            <svg class="w-80 h-80" viewBox="0 0 200 200" fill="none" stroke="#D4AF37" stroke-width="1.2">
                <circle cx="100" cy="100" r="90" stroke-dasharray="3,3"/>
                <polygon points="100,15 125,60 175,60 135,95 150,145 100,115 50,145 65,95 25,60 75,60"/>
            </svg>
        </div>

        <!-- 1. Header Story -->
        <div class="relative z-10 pt-3 space-y-2">
            <div class="flex items-center justify-center gap-2.5">
                <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology" class="h-10 w-auto object-contain">
                <span class="font-cinzel text-sm font-bold tracking-widest text-[#E5C068]">RUHIOLOGY INSTITUTE</span>
            </div>
            <div>
                <span class="inline-block px-3 py-0.5 rounded-full bg-[#D4AF37]/20 border border-[#D4AF37]/50 text-[9px] font-bold uppercase tracking-widest text-[#E5C068] font-mono">
                    Official Certified Credential
                </span>
            </div>
        </div>

        <!-- 2. Main Content Story -->
        <div class="relative z-10 my-auto py-4 space-y-3">
            <p class="text-[10px] uppercase font-mono tracking-widest text-slate-400">Diberikan dengan Hormat Kepada:</p>
            
            <h2 class="font-cinzel font-bold text-2xl text-white tracking-wide leading-tight px-4 text-[#F7E7B4]">
                {{ $response->participant_name }}
            </h2>

            @if($response->participant_institution)
                <p class="text-xs text-slate-300 font-medium">
                    {{ $response->participant_institution }}
                </p>
            @endif

            <div class="w-16 h-0.5 bg-[#D4AF37] mx-auto my-2 opacity-70"></div>

            <div class="space-y-1">
                <p class="text-[10px] text-slate-300">Telah berhasil menyelesaikan & lulus agenda:</p>
                <h3 class="font-serif-title font-bold text-base text-[#E5C068] leading-snug px-3">
                    "{{ $form->certificate_event_name ?: $form->title }}"
                </h3>
            </div>

            <!-- Bobot JP Pill -->
            <div class="pt-1">
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-amber-500 text-slate-950 font-black text-xs shadow-md">
                    <span>⚡ Akumulasi Bobot:</span>
                    <span>{{ $form->calculated_total_jp }}</span>
                </span>
            </div>

            <p class="text-[10px] text-slate-400 pt-1">
                {{ $form->certificate_event_location ? explode(',', $form->certificate_event_location)[0] : 'Jambi' }} • {{ $form->certificate_event_date ?: now()->translatedFormat('d F Y') }}
            </p>
        </div>

        <!-- 3. Footer Story (TTD & QR) -->
        <div class="relative z-10 pb-2 border-t border-slate-700/80 pt-4 flex items-end justify-between px-3">
            <!-- QR Verification -->
            <div class="text-left flex items-center gap-2.5">
                <div class="p-1 bg-white rounded-lg shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('public.certificate.verify', $response->response_code)) }}" 
                         alt="QR" class="w-12 h-12">
                </div>
                <div>
                    <span class="text-[8px] font-mono text-emerald-400 uppercase tracking-wider block font-bold">✓ Terverifikasi</span>
                    <span class="text-[8px] font-mono text-slate-300 block">{{ $response->response_code }}</span>
                </div>
            </div>

            <!-- Founder Signer -->
            <div class="text-right">
                <span class="text-[9px] font-bold text-[#E5C068] block">Founder & Direktur</span>
                <div class="h-10 flex items-center justify-end my-0.5">
                    @if($form->certificate_signer1_signature)
                        <img src="{{ $form->certificate_signer1_signature }}" alt="TTD" class="h-9 object-contain">
                    @else
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('public.certificate.verify', $response->response_code)) }}" alt="QR" class="w-9 h-9 p-0.5 bg-white rounded">
                    @endif
                </div>
                <strong class="text-[10px] font-bold text-white block border-t border-slate-600 pt-0.5">
                    Prof. Dr. Iskandar Nazari
                </strong>
            </div>
        </div>

    </div>

</body>
</html>
