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
            <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5 cursor-pointer border border-slate-700">
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Print</span>
            </button>

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
                    NOMOR: {{ $response->certificate_number }}
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
                    "{{ $form->certificate_event_name ?: $form->title }}"
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
        <div class="relative z-10 pt-4 border-t border-slate-200/90 px-8 flex items-end justify-between text-xs">
            
            <!-- Signer 1 (Direktur Ruhiology) -->
            <div class="text-center w-56">
                <p class="text-[10px] text-slate-400 font-semibold uppercase">Penyelenggara,</p>
                <div class="h-16 flex items-center justify-center my-1">
                    @if($form->certificate_signer1_signature)
                        <img src="{{ $form->certificate_signer1_signature }}" alt="TTD 1" class="h-14 object-contain">
                    @else
                        <!-- Official Digital Seal Stamp Placeholder -->
                        <div class="w-16 h-16 rounded-full border-2 border-dashed border-[#D4AF37] flex items-center justify-center text-[9px] font-bold text-[#D4AF37] uppercase text-center p-1">
                            RUHIOLOGY SEAL
                        </div>
                    @endif
                </div>
                <strong class="block font-bold text-slate-900 border-b border-slate-300 pb-0.5 text-xs">{{ $form->certificate_signer1_name ?: 'Prof. Dr. Iskandar Nazari, M.Pd' }}</strong>
                <span class="text-[10px] text-slate-500 block mt-0.5">{{ $form->certificate_signer1_role ?: 'Direktur Ruhiology Institute' }}</span>
            </div>

            <!-- Center: QR Code Verification -->
            <div class="text-center px-4 flex flex-col items-center">
                <div class="p-1.5 bg-white border border-slate-200 rounded-xl shadow-xs">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('public.certificate.verify', $response->response_code)) }}" 
                         alt="QR Verifikasi" class="w-16 h-16">
                </div>
                <span class="text-[8px] font-mono text-slate-400 mt-1 uppercase tracking-wider">Scan untuk Keaslian Digital</span>
                <span class="text-[8px] font-mono font-bold text-slate-600">{{ $response->response_code }}</span>
            </div>

            <!-- Signer 2 (Mitra / Kepala Sekolah) -->
            <div class="text-center w-56">
                <p class="text-[10px] text-slate-400 font-semibold uppercase">
                    {{ $form->certificate_signer2_name ? 'Mengetahui / Pimpinan Mitra,' : 'Penanggung Jawab Akademik,' }}
                </p>
                <div class="h-16 flex items-center justify-center my-1">
                    @if($form->certificate_signer2_signature)
                        <img src="{{ $form->certificate_signer2_signature }}" alt="TTD 2" class="h-14 object-contain">
                    @else
                        <div class="w-16 h-16 rounded-full border border-slate-200 flex items-center justify-center text-[9px] text-slate-400 uppercase">
                            STEMPEL
                        </div>
                    @endif
                </div>
                <strong class="block font-bold text-slate-900 border-b border-slate-300 pb-0.5 text-xs">
                    {{ $form->certificate_signer2_name ?: 'Drs. H. Ahmad Fauzi, M.Si' }}
                </strong>
                <span class="text-[10px] text-slate-500 block mt-0.5">
                    {{ $form->certificate_signer2_role ?: 'Ketua Tim Penjamin Mutu Pelatihan' }}
                </span>
            </div>

        </div>

    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 2: LEMBAR STRUKTUR MATERI & JP     -->
    <!-- ========================================== -->
    <div id="certPage2" class="a4-landscape shadow-2xl p-10 flex flex-col justify-between border-[8px] border-[#0B2A43] relative">
        
        <!-- Double Border Accent (Gold) -->
        <div class="absolute inset-2 border-[2px] border-[#D4AF37] pointer-events-none"></div>
        <div class="absolute inset-3 border-[1px] border-[#D4AF37]/40 pointer-events-none"></div>

        <!-- 1. Header Halaman Belakang -->
        <div class="relative z-10 border-b border-slate-200 pb-3 text-center">
            <h2 class="font-cinzel text-xl font-bold tracking-widest text-[#0B2A43]">
                STRUKTUR PROGRAM & MATRIKS JAM PELAJARAN (JP)
            </h2>
            <p class="font-serif-title font-semibold text-sm text-[#8C703E] mt-1">
                "{{ $form->certificate_event_name ?: $form->title }}"
            </p>
            <div class="flex items-center justify-center gap-4 text-[10px] text-slate-500 mt-2 font-mono">
                <span>Nama Peserta: <strong class="text-slate-800">{{ $response->participant_name }}</strong></span>
                <span>•</span>
                <span>No. Sertifikat: <strong class="text-slate-800">{{ $response->certificate_number }}</strong></span>
            </div>
        </div>

        <!-- 2. Curriculum Table -->
        <div class="relative z-10 my-auto py-4 px-6">
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
                            <th class="py-2.5 px-4 w-12 text-center border-r border-slate-700">No</th>
                            <th class="py-2.5 px-6 border-r border-slate-700">Pokok Bahasan / Materi Pelatihan</th>
                            <th class="py-2.5 px-6 w-36 text-center">Alokasi Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 font-medium text-slate-800">
                        @foreach($curriculum as $index => $item)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/70' }}">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-500 border-r border-slate-200">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-2.5 px-6 border-r border-slate-200 font-semibold text-slate-800">
                                    {{ $item['title'] }}
                                </td>
                                <td class="py-2.5 px-6 text-center font-bold text-[#8C703E]">
                                    {{ $item['jp'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-amber-50/80 border-t-2 border-slate-300 text-xs font-bold text-[#0B2A43]">
                            <td colspan="2" class="py-3 px-6 text-right uppercase tracking-wider border-r border-slate-300">
                                Total Alokasi Jam Pelajaran (JP)
                            </td>
                            <td class="py-3 px-6 text-center text-sm font-black text-[#8C703E]">
                                {{ $form->calculated_total_jp }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <p class="text-[10px] text-slate-400 mt-2 italic text-center">
                * 1 (satu) Jam Pelajaran (JP) setara dengan 45 menit pembelajaran aktif/praktik terbimbing.
            </p>
        </div>

        <!-- 3. Signature & Validation Footer Halaman 2 -->
        <div class="relative z-10 pt-4 border-t border-slate-200/90 px-8 flex items-end justify-between text-xs">
            <div class="text-[10px] text-slate-500 max-w-sm leading-relaxed">
                <p><strong>Catatan Legalitas:</strong></p>
                <p>Dokumen ini merupakan lampiran resmi dan tak terpisahkan dari lembar sertifikat utama dengan nomor registrasi yang sama.</p>
            </div>

            <div class="text-center w-64">
                <p class="text-[10px] text-slate-500">
                    Ditetapkan di: {{ $form->certificate_event_location ?: 'Indonesia' }}<br>
                    Pada tanggal: {{ $form->certificate_event_date ?: now()->translatedFormat('d F Y') }}
                </p>
                <div class="h-14 flex items-center justify-center my-1">
                    @if($form->certificate_signer1_signature)
                        <img src="{{ $form->certificate_signer1_signature }}" alt="TTD Pengesahan" class="h-12 object-contain">
                    @else
                        <div class="w-14 h-14 rounded-full border-2 border-dashed border-[#D4AF37] flex items-center justify-center text-[8px] font-bold text-[#D4AF37] uppercase text-center p-1">
                            SEAL
                        </div>
                    @endif
                </div>
                <strong class="block font-bold text-slate-900 border-b border-slate-300 pb-0.5 text-xs">
                    {{ $form->certificate_signer1_name ?: 'Prof. Dr. Iskandar Nazari, M.Pd' }}
                </strong>
                <span class="text-[10px] text-slate-500 block mt-0.5">Penanggung Jawab Akademik</span>
            </div>
        </div>

    </div>

</body>
</html>
