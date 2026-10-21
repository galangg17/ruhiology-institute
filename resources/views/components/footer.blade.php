<!-- DARK NAVY FOOTER COMPONENT -->
@php $isEn = app()->getLocale() === 'en'; @endphp
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
                    {{ $isEn ? 'Forming the Complete Human · Center for Spiritual Intelligence & Ruhiology Quotient' : 'Membentuk Manusia Paripurna · Pusat Kajian Kecerdasan Ruhiologi' }}
                </p>
            </div>

            <!-- Col 2 & 3: Navigasi Links -->
            <div class="md:col-span-5 grid grid-cols-2 gap-6 text-xs">
                <div>
                    <h4 class="font-bold text-white mb-3">{{ $isEn ? 'Navigation' : 'Navigasi' }}</h4>
                    <ul class="space-y-2 text-slate-400 font-medium">
                        <li><a href="{{ url('/#hero') }}" class="hover:text-white transition">{{ $isEn ? 'Home' : 'Beranda' }}</a></li>
                        <li><a href="{{ url('/#tentang-ruhiologi') }}" class="hover:text-white transition">{{ $isEn ? 'About Ruhiology' : 'Tentang Ruhiologi' }}</a></li>
                        <li><a href="{{ url('/#founder') }}" class="hover:text-white transition">{{ $isEn ? 'Founder Profile' : 'Profil Founder' }}</a></li>
                        <li><a href="{{ route('assessment.index') }}" class="hover:text-white transition">RQ Assessment</a></li>
                        <li><a href="{{ route('training.index') }}" class="hover:text-white transition">Training Center</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3">{{ $isEn ? 'Services & Resources' : 'Layanan & Sumber Daya' }}</h4>
                    <ul class="space-y-2 text-slate-400 font-medium">
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition">{{ $isEn ? 'Books Catalog' : 'Katalog Buku' }}</a></li>
                        <li><a href="{{ route('articles.index') }}" class="hover:text-white transition">{{ $isEn ? 'Articles & News' : 'Artikel & Berita' }}</a></li>
                        <li><a href="{{ url('/#inspirasi') }}" class="hover:text-white transition">{{ $isEn ? 'Inspirations' : 'Inspirasi' }}</a></li>
                        <li><a href="{{ route('consultation.index') }}" class="hover:text-white transition">{{ $isEn ? 'Consultation' : 'Konsultasi' }}</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">{{ $isEn ? 'Sign In / Dashboard' : 'Login / Dashboard' }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Col 4: Kontak & Sosial Media -->
            <div class="md:col-span-3 space-y-4 text-xs">
                <h4 class="font-bold text-white">{{ $isEn ? 'Contact & Office' : 'Kontak & Sekretariat' }}</h4>
                <ul class="space-y-2.5 text-slate-400 font-medium">
                    <li class="flex items-center gap-2">
                        <span>📞</span> <span>+62 812 3456 7890</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>✉️</span> <span>info@ruhiologyinstitute.com</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span>📍</span> <span>{{ $isEn ? 'Jl. Pendidikan No. 123, Jambi, Indonesia (Global Access)' : 'Jl. Pendidikan No. 123, Jambi, Indonesia' }}</span>
                    </li>
                </ul>

                <div class="pt-2">
                    <h4 class="font-bold text-white mb-2">{{ $isEn ? 'Follow Our Channels' : 'Ikuti Kami' }}</h4>
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
            <p>&copy; 2026 Ruhiology Institute. {{ $isEn ? 'All rights reserved.' : 'Hak cipta dilindungi undang-undang.' }}</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-slate-300">{{ $isEn ? 'Privacy Policy' : 'Kebijakan Privasi' }}</a>
                <a href="#" class="hover:text-slate-300">{{ $isEn ? 'Terms of Service' : 'Syarat & Ketentuan' }}</a>
            </div>
        </div>
    </div>
</footer>
