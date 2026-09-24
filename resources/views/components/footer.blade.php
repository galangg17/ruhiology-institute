<!-- DARK NAVY FOOTER COMPONENT -->
<footer class="bg-[#08141E] text-slate-300 pt-16 pb-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-12">
            
            <!-- Col 1: Logo & Tagline -->
            <div class="md:col-span-4 space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology Institute Logo" class="h-10 w-auto object-contain brightness-200">
                    <div class="flex flex-col">
                        <span class="font-black tracking-wider text-white text-base leading-tight font-serif uppercase">
                            RUHIOLOGY
                        </span>
                        <span class="text-[9px] font-bold text-slate-400 tracking-widest uppercase">
                            INSTITUTE
                        </span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 font-medium">
                    Membentuk Manusia Paripurna
                </p>
            </div>

            <!-- Col 2 & 3: Navigasi Links -->
            <div class="md:col-span-5 grid grid-cols-2 gap-6 text-xs">
                <div>
                    <h4 class="font-bold text-white mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-slate-400 font-medium">
                        <li><a href="{{ url('/#hero') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ url('/#tentang-ruhiologi') }}" class="hover:text-white transition">Tentang Ruhiologi</a></li>
                        <li><a href="{{ url('/#tentang-ruhiologi') }}" class="hover:text-white transition">Tentang Institute</a></li>
                        <li><a href="{{ url('/#assessment') }}" class="hover:text-white transition">RQ Assessment</a></li>
                        <li><a href="{{ url('/#training') }}" class="hover:text-white transition">Training Center</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3">&nbsp;</h4>
                    <ul class="space-y-2 text-slate-400 font-medium">
                        <li><a href="{{ url('/#katalog') }}" class="hover:text-white transition">Katalog</a></li>
                        <li><a href="{{ url('/#artikel') }}" class="hover:text-white transition">Artikel & Berita</a></li>
                        <li><a href="{{ url('/#inspirasi') }}" class="hover:text-white transition">Inspirasi</a></li>
                        <li><a href="{{ url('/#inspirasi') }}" class="hover:text-white transition">Konsultasi</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login / Dashboard</a></li>
                    </ul>
                </div>
            </div>

            <!-- Col 4: Kontak & Sosial Media -->
            <div class="md:col-span-3 space-y-4 text-xs">
                <h4 class="font-bold text-white">Kontak</h4>
                <ul class="space-y-2.5 text-slate-400 font-medium">
                    <li class="flex items-center gap-2">
                        <span>📞</span> <span>+62 812 3456 7890</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>✉️</span> <span>info@ruhiologyinstitute.com</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span>📍</span> <span>Jl. Pendidikan No. 123, Jambi, Indonesia</span>
                    </li>
                </ul>

                <div class="pt-2">
                    <h4 class="font-bold text-white mb-2">Ikuti Kami</h4>
                    <div class="flex items-center gap-3 text-slate-400 text-sm">
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram" class="hover:text-white transition">📷</a>
                        <a href="https://facebook.com" target="_blank" aria-label="Facebook" class="hover:text-white transition">📘</a>
                        <a href="https://youtube.com" target="_blank" aria-label="YouTube" class="hover:text-white transition">▶️</a>
                        <a href="https://tiktok.com" target="_blank" aria-label="TikTok" class="hover:text-white transition">🎵</a>
                        <a href="https://wa.me/6281234567890" target="_blank" aria-label="WhatsApp" class="hover:text-white transition">💬</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Line -->
        <div class="pt-8 border-t border-slate-800/80 flex flex-col md:flex-row justify-between items-center text-[11px] text-slate-500 font-medium gap-4">
            <p>&copy; 2026 Ruhiology Institute. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-slate-300">Privacy Policy</a>
                <a href="#" class="hover:text-slate-300">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
