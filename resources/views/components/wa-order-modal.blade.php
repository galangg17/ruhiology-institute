<!-- WHATSAPP DIRECT ORDER MODAL COMPONENT (EXACT TK-PPEG RECONSTRUCTION) -->
<div x-data="{
        isOpen: false,
        productName: '',
        productPrice: '',
        productWeight: 'Softcover',
        quantity: 1,
        notes: '',
        waNumber: '6281234567890',
        openModal(detail) {
            this.productName = detail.title || 'Buku Ruhiologi';
            this.productPrice = detail.price || '';
            this.productWeight = detail.weight || 'Softcover';
            this.quantity = 1;
            this.notes = '';
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
        },
        handleSendWA() {
            let text = `Halo, saya tertarik dengan produk *${this.productName}*.\nSaya ingin memesan *${this.productName}*.\nJumlah: *${this.quantity} eksemplar*.`;
            if (this.productPrice) {
                text += `\nHarga: *${this.productPrice}*`;
            }
            if (this.notes.trim()) {
                text += `\nCatatan Tambahan: ${this.notes.trim()}`;
            }
            text += `\n\n(Dipesan melalui website resmi Ruhiology Institute)`;

            const cleanNumber = this.waNumber.replace(/[^0-9]/g, '');
            const url = `https://wa.me/${cleanNumber}?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
            this.closeModal();
        }
    }"
    @open-wa-modal.window="openModal($event.detail)"
    x-show="isOpen"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">

    <div @click.away="closeModal()" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative border border-slate-100" @click.stop>
        
        <!-- Close Button -->
        <button @click="closeModal()" type="button" aria-label="Tutup Modal" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Header Modal (TK-PPEG Style) -->
        <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <div>
                <h3 class="font-serif font-bold text-lg text-slate-900 leading-tight">Pemesanan Langsung</h3>
                <p class="text-xs text-slate-500">Inquiry via WhatsApp Resmi Ruhiology Institute</p>
            </div>
        </div>

        <div class="mt-4 space-y-4">
            
            <!-- Selected Product Item Box (TK-PPEG Style) -->
            <div class="bg-[#F8F6F0] p-3.5 rounded-xl border border-slate-200/80">
                <span class="text-[11px] font-semibold text-[#C9A24D] uppercase tracking-wider block">
                    Produk Dipilih
                </span>
                <div class="font-bold text-slate-900 text-sm mt-0.5" x-text="productName"></div>
                <div class="flex items-center justify-between text-xs text-slate-600 mt-1">
                    <span>Format: <span x-text="productWeight"></span></span>
                    <span class="font-semibold text-emerald-700" x-text="productPrice ? productPrice : 'Harga via Admin'"></span>
                </div>
            </div>

            <!-- Counter Jumlah -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Jumlah Pesanan
                </label>
                <div class="flex items-center space-x-3">
                    <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="w-9 h-9 rounded-lg border border-slate-300 font-bold text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors">-</button>
                    <span class="w-12 text-center font-bold text-sm text-slate-900" x-text="quantity"></span>
                    <button type="button" @click="quantity = quantity + 1" class="w-9 h-9 rounded-lg border border-slate-300 font-bold text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors">+</button>
                </div>
            </div>

            <!-- Catatan Tambahan (Opsional) -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Catatan Khusus (Opsional)
                </label>
                <textarea rows="2" x-model="notes" placeholder="Contoh: Pengiriman ke Jambi kota / sertakan nama pemesan..." class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#0B2A43] focus:border-transparent outline-none transition"></textarea>
            </div>

            <!-- Send WA Button (TK-PPEG Style) -->
            <div class="pt-2 space-y-2">
                <button type="button" @click="handleSendWA()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-xl text-xs flex items-center justify-center space-x-2 shadow-md transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Kirim Pesan WhatsApp Otomatis</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>

            <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                Pesanan Anda akan langsung terhubung ke WhatsApp pengurus resmi Ruhiology Institute.
            </p>

        </div>
    </div>
</div>
