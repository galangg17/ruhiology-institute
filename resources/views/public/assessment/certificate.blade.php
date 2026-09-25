<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Resmi RQI - {{ $submission->participant->name }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1a28;
            color: #0B2A43;
            margin: 0;
            padding: 0;
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
            }
            .cert-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                max-width: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 md:p-8">

    <!-- Top Action Navigation Bar (Hidden when printing) -->
    <div class="no-print max-w-[1100px] w-full mb-6 flex flex-wrap justify-between items-center bg-slate-900/90 text-white p-4 rounded-2xl backdrop-blur border border-amber-500/30 shadow-2xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('assessment.result', $submission->submission_code) }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-amber-300 font-semibold rounded-xl text-xs transition border border-amber-500/20 flex items-center gap-2">
                <span>←</span>
                <span>Kembali ke Halaman Hasil</span>
            </a>
            <span class="text-xs text-slate-400 border-l border-slate-700 pl-3 hidden sm:inline">
                Sertifikat Digital Resmi Ruhiology Quotient (RQI)
            </span>
        </div>
        <div class="flex items-center gap-3 mt-2 sm:mt-0">
            <button onclick="window.print()" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-extrabold rounded-xl text-xs shadow-lg shadow-amber-500/20 transition cursor-pointer flex items-center gap-2">
                <span>🖨️</span>
                <span>Cetak / Simpan PDF (A4 Landscape)</span>
            </button>
        </div>
    </div>

    <!-- Official Printable Certificate Container (A4 Landscape aspect ~ 297mm x 210mm) -->
    <div class="cert-container w-full max-w-[1050px] aspect-[1.414/1] bg-[#F8F6F0] relative p-8 md:p-12 border-[12px] border-[#0B2A43] rounded-3xl shadow-2xl overflow-hidden flex flex-col justify-between">
        
        <!-- Outer Gold Frame Overlay -->
        <div class="absolute inset-3 border-2 border-[#C9A24D] rounded-2xl pointer-events-none"></div>
        <div class="absolute inset-4 border border-[#C9A24D]/40 rounded-xl pointer-events-none"></div>
        
        <!-- Corner Ornaments -->
        <div class="absolute top-6 left-6 text-[#C9A24D] text-2xl font-serif leading-none select-none">❖</div>
        <div class="absolute top-6 right-6 text-[#C9A24D] text-2xl font-serif leading-none select-none">❖</div>
        <div class="absolute bottom-6 left-6 text-[#C9A24D] text-2xl font-serif leading-none select-none">❖</div>
        <div class="absolute bottom-6 right-6 text-[#C9A24D] text-2xl font-serif leading-none select-none">❖</div>

        <!-- Watermark Background Logo -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
            <img src="{{ asset('images/settings/ruhiology-logo.png') }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400';" class="w-96 h-96 object-contain" alt="Ruhiology Watermark">
        </div>

        <!-- Certificate Header -->
        <div class="relative z-10 text-center space-y-2">
            <div class="flex items-center justify-center gap-3 mb-1">
                <img src="{{ asset('images/settings/ruhiology-logo.png') }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=RQ&background=0B2A43&color=C9A24D';" class="h-12 w-12 object-contain" alt="Logo">
                <div class="text-left">
                    <span class="block font-serif-gold text-lg font-bold tracking-widest text-[#0B2A43] leading-none">
                        {{ $settings['institute_name'] ?? 'RUHIOLOGY INSTITUTE' }}
                    </span>
                    <span class="block text-[10px] tracking-widest uppercase text-amber-700 font-bold mt-0.5">
                        {{ $settings['tagline'] ?? 'Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.' }}
                    </span>
                </div>
            </div>

            <div class="pt-2">
                <h1 class="font-serif-gold text-2xl md:text-3xl font-extrabold text-[#0B2A43] tracking-wide uppercase">
                    {{ $settings['certificate_title'] ?? 'SERTIFIKAT HASIL ASESMEN RQI' }}
                </h1>
                <p class="text-xs font-serif italic text-amber-800 tracking-wider mt-0.5">
                    {{ $settings['certificate_subtitle'] ?? 'Ruhiology Quotient Assessment Certificate' }}
                </p>
                <div class="inline-block mt-2 px-4 py-1 bg-amber-100/80 border border-amber-300 text-amber-900 rounded-full text-[10px] font-mono font-bold tracking-wider">
                    NO: CERT/RQI/{{ date('Y') }}/{{ strtoupper($submission->submission_code) }}
                </div>
            </div>
        </div>

        <!-- Certificate Body -->
        <div class="relative z-10 text-center my-4 space-y-3">
            <p class="text-xs text-slate-600 max-w-2xl mx-auto leading-relaxed">
                {{ $settings['certificate_body_text'] ?? 'Diberikan kepada peserta di bawah ini atas partisipasi dan pencapaian evaluasi potensi diri dalam Asesmen Ruhiology Quotient (RQI).' }}
            </p>

            <div class="py-2">
                <h2 class="font-serif-classic text-2xl md:text-3xl font-extrabold text-[#0B2A43] tracking-wide border-b-2 border-amber-400/60 inline-block px-8 pb-1">
                    {{ $submission->participant->name }}
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Kategori: <strong class="text-slate-700">{{ $submission->participant->category }}</strong>
                    @if($submission->participant->regency)
                        • {{ $submission->participant->regency->name }}
                    @endif
                </p>
            </div>

            <!-- Score Summary Box -->
            <div class="max-w-xl mx-auto bg-white/90 p-4 rounded-2xl border border-amber-200 shadow-sm flex items-center justify-around">
                <div class="text-center px-4">
                    <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">Skor Total RQI</span>
                    <span class="font-serif-gold text-3xl font-black text-[#0B2A43]">
                        {{ number_format($submission->result->total_score ?? 0, 1) }}
                    </span>
                    <span class="block text-[10px] font-semibold text-slate-500">Skala 100</span>
                </div>
                <div class="h-10 w-px bg-amber-200"></div>
                <div class="text-center px-4">
                    <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">Tingkat Potensi</span>
                    <span class="font-serif-classic text-lg font-bold text-amber-800">
                        {{ $submission->result->level_category ?? 'Tinggi / Paripurna' }}
                    </span>
                    <span class="block text-[10px] font-semibold text-slate-500">Kualifikasi Standar</span>
                </div>
                <div class="h-10 w-px bg-amber-200"></div>
                <div class="text-center px-4">
                    <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">Tanggal Asesmen</span>
                    <span class="font-bold text-xs text-slate-800">
                        {{ $submission->created_at ? $submission->created_at->translatedFormat('d F Y') : date('d F Y') }}
                    </span>
                    <span class="block text-[10px] font-semibold text-slate-500">Verifikasi Resmi</span>
                </div>
            </div>
        </div>

        <!-- Certificate Footer & Signature -->
        <div class="relative z-10 flex justify-between items-end pt-4 border-t border-amber-200/80">
            <!-- QR / Security Code -->
            <div class="text-left space-y-1">
                <div class="p-1.5 bg-white border border-slate-200 rounded-lg inline-block shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode(route('assessment.result', $submission->submission_code)) }}" alt="QR Code" class="w-14 h-14 object-contain">
                </div>
                <span class="block text-[9px] font-mono text-slate-400">Scan untuk Verifikasi Keaslian</span>
            </div>

            <!-- Institute Stamp / Badge -->
            <div class="text-center">
                <div class="w-20 h-20 rounded-full border-2 border-dashed border-amber-500/60 p-1 flex items-center justify-center mx-auto opacity-80">
                    <div class="w-full h-full rounded-full bg-amber-50 flex flex-col items-center justify-center text-[9px] font-serif font-bold text-amber-900 border border-amber-300">
                        <span>RUHIOLOGY</span>
                        <span class="text-[7px] text-amber-700">VERIFIED</span>
                        <span>INSTITUTE</span>
                    </div>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="text-center space-y-1 min-w-[200px]">
                <p class="text-[10px] text-slate-500 font-medium">Kota Jambi, {{ date('d F Y') }}</p>
                
                <div class="h-14 flex items-center justify-center">
                    @if(!empty($settings['founder_signature']))
                        <img src="{{ $settings['founder_signature'] }}" alt="Tanda Tangan Founder" class="max-h-14 max-w-[180px] object-contain">
                    @else
                        <!-- Elegant Default Signature Stylized Placeholder -->
                        <div class="font-serif italic text-lg text-slate-400 tracking-widest border-b border-dashed border-slate-300 px-4">
                            Prof. Iskandar Nazari
                        </div>
                    @endif
                </div>

                <div class="border-t border-slate-400 pt-1">
                    <h3 class="font-serif-classic text-xs font-bold text-[#0B2A43]">
                        {{ $settings['founder_name'] ?? 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.' }}
                    </h3>
                    <p class="text-[10px] text-amber-800 font-medium">
                        {{ $settings['founder_title'] ?? 'Founder Ruhiology Institute' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
