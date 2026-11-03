<!-- DARK NAVY FOOTER COMPONENT -->
@php $isEn = app()->getLocale() === 'en'; @endphp
<footer class="bg-[#08141E] text-slate-300 pt-16 pb-12 border-t border-slate-800 relative overflow-hidden">
    <!-- Top Imperial Gold Accent Glow Line -->
    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-[#C9A24D]/70 to-transparent"></div>
    <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-24 bg-[#C9A24D]/10 blur-3xl pointer-events-none rounded-full"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-12">
            
            <!-- Col 1: Logo & Tagline -->
            <div class="md:col-span-4 space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology Institute Logo" class="h-10 w-auto object-contain brightness-200">
                    <div class="flex flex-col">
                        <span class="font-black tracking-wider text-white text-base leading-tight font-serif uppercase">
                            RUHIOLOGY
                        </span>
                        <span class="text-[9px] font-bold text-[#C9A24D] tracking-widest uppercase">
                            INSTITUTE
                        </span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    {{ $isEn ? 'Forming the Complete Human · Center for Spiritual Intelligence & Ruhiology Quotient' : 'Membentuk Manusia Paripurna · Pusat Kajian Kecerdasan Ruhiologi' }}
                </p>
                <div class="pt-2 flex items-center gap-2 text-[10px] text-slate-400 font-mono tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="text-slate-300">RQI-15 & WHO-5 Certified Framework</span>
                </div>
            </div>

            <!-- Col 2 & 3: Navigasi Links -->
            <div class="md:col-span-5 grid grid-cols-2 gap-6 text-xs">
                <div>
                    <h4 class="font-bold text-white mb-3 text-xs tracking-wider uppercase flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#C9A24D]"></span>
                        <span>{{ $isEn ? 'Navigation' : 'Navigasi' }}</span>
                    </h4>
                    <ul class="space-y-2 text-slate-400 font-medium">
                        <li><a href="{{ url('/#hero') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> {{ $isEn ? 'Home' : 'Beranda' }}</a></li>
                        <li><a href="{{ url('/#tentang-ruhiologi') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> {{ $isEn ? 'About Ruhiology' : 'Tentang Ruhiologi' }}</a></li>
                        <li><a href="{{ url('/#founder') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> {{ $isEn ? 'Founder Profile' : 'Profil Founder' }}</a></li>
                        <li><a href="{{ route('assessment.index') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> RQ Assessment</a></li>
                        <li><a href="{{ route('training.index') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> Training Center</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-xs tracking-wider uppercase flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#C9A24D]"></span>
                        <span>{{ $isEn ? 'Services & Resources' : 'Layanan & Sumber Daya' }}</span>
                    </h4>
                    <ul class="space-y-2 text-slate-400 font-medium">
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> {{ $isEn ? 'Books Catalog' : 'Katalog Buku' }}</a></li>
                        <li><a href="{{ route('articles.index') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> {{ $isEn ? 'Articles & News' : 'Artikel & Berita' }}</a></li>
                        <li><a href="{{ url('/#inspirasi') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> {{ $isEn ? 'Inspirations' : 'Inspirasi' }}</a></li>
                        <li><a href="{{ route('consultation.index') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> {{ $isEn ? 'Consultation' : 'Konsultasi' }}</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition flex items-center gap-1.5"><span class="text-slate-600 text-[10px]">›</span> {{ $isEn ? 'Sign In / Dashboard' : 'Login / Dashboard' }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Col 4: Kontak & Sosial Media -->
            <div class="md:col-span-3 space-y-4 text-xs">
                <h4 class="font-bold text-white text-xs tracking-wider uppercase flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#C9A24D]"></span>
                    <span>{{ $isEn ? 'Contact & Office' : 'Kontak & Sekretariat' }}</span>
                </h4>
                <ul class="space-y-2.5 text-slate-400 font-medium">
                    <li class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-center justify-center shrink-0 text-[#C9A24D]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        <span>+62 812 3456 7890</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-center justify-center shrink-0 text-[#C9A24D]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <span>info@ruhiologyinstitute.com</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-center justify-center shrink-0 text-[#C9A24D] mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <span>{{ $isEn ? 'Jl. Pendidikan No. 123, Jambi, Indonesia (Global Access)' : 'Jl. Pendidikan No. 123, Jambi, Indonesia' }}</span>
                    </li>
                </ul>

                <div class="pt-3">
                    <h4 class="font-bold text-white mb-2 text-xs uppercase tracking-wider">{{ $isEn ? 'Follow Our Channels' : 'Ikuti Kami' }}</h4>
                    <div class="flex items-center gap-2">
                        <!-- Instagram -->
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram" class="w-8 h-8 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-center justify-center text-slate-300 hover:text-white hover:bg-pink-600/20 hover:border-pink-500/50 transition-all">
                            <svg class="w-4 h-4 fill-currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <!-- Facebook -->
                        <a href="https://facebook.com" target="_blank" aria-label="Facebook" class="w-8 h-8 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-center justify-center text-slate-300 hover:text-white hover:bg-blue-600/20 hover:border-blue-500/50 transition-all">
                            <svg class="w-4 h-4 fill-currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <!-- YouTube -->
                        <a href="https://youtube.com" target="_blank" aria-label="YouTube" class="w-8 h-8 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-center justify-center text-slate-300 hover:text-white hover:bg-red-600/20 hover:border-red-500/50 transition-all">
                            <svg class="w-4 h-4 fill-currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        <!-- TikTok -->
                        <a href="https://tiktok.com" target="_blank" aria-label="TikTok" class="w-8 h-8 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-center justify-center text-slate-300 hover:text-white hover:bg-teal-600/20 hover:border-teal-500/50 transition-all">
                            <svg class="w-4 h-4 fill-currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                        <!-- WhatsApp -->
                        <a href="https://wa.me/6281234567890" target="_blank" aria-label="WhatsApp" class="w-8 h-8 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-center justify-center text-slate-300 hover:text-white hover:bg-emerald-600/20 hover:border-emerald-500/50 transition-all">
                            <svg class="w-4 h-4 fill-currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Line -->
        <div class="pt-8 border-t border-slate-800/80 flex flex-col md:flex-row justify-between items-center text-[11px] text-slate-500 font-medium gap-4">
            <p>&copy; 2026 Ruhiology Institute. {{ $isEn ? 'All rights reserved.' : 'Hak cipta dilindungi undang-undang.' }}</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-slate-300 transition">{{ $isEn ? 'Privacy Policy' : 'Kebijakan Privasi' }}</a>
                <a href="#" class="hover:text-slate-300 transition">{{ $isEn ? 'Terms of Service' : 'Syarat & Ketentuan' }}</a>
            </div>
        </div>
    </div>
</footer>
