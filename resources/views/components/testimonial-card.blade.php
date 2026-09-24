@props([
    'tekadList' => [
        [
            'quote' => 'Saya bertekad memperkuat ketenangan batin, menyelaraskan integritas niat dalam memimpin, dan menjadikan kesadaran ruh sebagai kompas kehidupan sehari-hari.',
            'name' => 'Ahmad Faisal, S.T.',
            'role' => 'Peserta Assessment RQ (Kategori: Sangat Baik)',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop'
        ],
        [
            'quote' => 'Assessment ini menyadarkan saya bahwa ketenangan sejati berakar pada purifikasi mental (Tazkiyah). Saya bertekad melatih resiliensi spiritual setiap hari.',
            'name' => 'Dr. Rina Wati',
            'role' => 'Akademisi & Peserta Assessment RQ',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop'
        ],
        [
            'quote' => 'Melalui pemetaan Ruhiologi, saya berkomitmen meluruskan niat dalam setiap karya dan pelayanan sosial demi keberkahan bersama.',
            'name' => 'M. Rizky Ramadhan',
            'role' => 'Praktisi Pendidikan & Peserta RQ',
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200&auto=format&fit=crop'
        ]
    ]
])

<div 
    x-data="{ 
        active: 0, 
        items: {{ json_encode($tekadList) }},
        timer: null,
        init() {
            this.timer = setInterval(() => {
                this.active = (this.active + 1) % this.items.length;
            }, 6000);
        }
    }" 
    class="p-8 sm:p-10 space-y-4 flex flex-col justify-between h-full"
>
    <div>
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-mono font-bold text-xs uppercase tracking-widest text-[#C9A24D] flex items-center gap-1.5">
                <span>✦</span> <span>Tekad Peserta Assessment</span>
            </h4>
            <span class="text-[10px] font-mono bg-[#C9A24D]/10 text-[#C9A24D] px-2 py-0.5 rounded-full border border-[#C9A24D]/30">Refleksi RQ</span>
        </div>

        <!-- Carousel Slide Item -->
        <template x-for="(item, index) in items" :key="index">
            <div 
                x-show="active === index" 
                x-transition:enter="transition ease-out duration-500 transform"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="space-y-4"
            >
                <div class="flex items-start gap-3.5">
                    <div class="w-11 h-11 rounded-full bg-slate-800 overflow-hidden border-2 border-[#C9A24D]/50 shrink-0 shadow-md">
                        <img :src="item.avatar" :alt="item.name" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-2.5">
                        <p class="text-xs sm:text-sm font-serif italic text-slate-100 leading-relaxed font-normal">
                            "<span x-text="item.quote"></span>"
                        </p>
                        <div>
                            <div class="font-bold text-white text-xs sm:text-sm" x-text="item.name"></div>
                            <div class="text-[11px] text-[#C9A24D]/90 font-medium" x-text="item.role"></div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Small pagination dots -->
    <div class="flex items-center gap-2 pt-3 border-t border-slate-800/80">
        <template x-for="(item, index) in items" :key="index">
            <button 
                @click="active = index" 
                :class="active === index ? 'w-6 bg-[#C9A24D]' : 'w-2 bg-slate-700 hover:bg-slate-500'"
                class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                :title="'Slide ' + (index + 1)"
            ></button>
        </template>
        <span class="text-[10px] font-mono text-slate-500 ml-auto" x-text="(active + 1) + '/' + items.length"></span>
    </div>
</div>
