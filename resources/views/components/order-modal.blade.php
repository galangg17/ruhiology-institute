<!-- FORM PEMESANAN LANGSUNG BUKU (DIRECT WA ORDER MODAL POPUP) -->
<div x-data="{
        isOpen: false,
        productId: '1',
        productTitle: '',
        productPrice: '',
        productStock: 50,
        quantity: 1,
        customerName: '{{ addslashes(auth()->user()?->name ?? "Super Admin Ruhiology") }}',
        customerEmail: '{{ addslashes(auth()->user()?->email ?? "admin@ruhiologyinstitute.com") }}',
        customerPhone: '{{ addslashes(auth()->user()?->phone ?? "081274110001") }}',
        shippingAddress: '',
        notes: '',
        waNumber: '{{ addslashes(\App\Models\Setting::get("whatsapp_number", "6281234567890")) }}',

        openModal(detail) {
            if (detail) {
                if (detail.id) this.productId = detail.id;
                if (detail.title) this.productTitle = detail.title;
                if (detail.price) this.productPrice = detail.price;
                if (detail.stock) this.productStock = detail.stock;
                if (detail.quantity) this.quantity = detail.quantity;
                if (detail.name) this.customerName = detail.name;
                if (detail.email) this.customerEmail = detail.email;
                if (detail.phone) this.customerPhone = detail.phone;
            }
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
        },
        handleSendWA() {
            if (!this.customerName.trim() || !this.shippingAddress.trim() || !this.customerPhone.trim()) {
                alert('Harap isi Nama Pemesan, Nomor HP/WhatsApp, dan Alamat Pengiriman.');
                return;
            }

            let text = `Halo Admin Ruhiology Institute,\nSaya ingin memesan buku berikut:\n\n` +
                       `📚 *Judul Buku*: ${this.productTitle || 'Buku Ruhiologi'}\n` +
                       `📦 *Jumlah*: ${this.quantity} eksemplar\n` +
                       (this.productPrice ? `💰 *Harga*: ${this.productPrice}\n` : '') +
                       `\n📋 *Data Pemesan*:\n` +
                       `• *Nama*: ${this.customerName.trim()}\n` +
                       `• *Email*: ${this.customerEmail.trim()}\n` +
                       `• *No. HP/WA*: ${this.customerPhone.trim()}\n` +
                       `• *Alamat Pengiriman*: ${this.shippingAddress.trim()}\n`;

            if (this.notes && this.notes.trim()) {
                text += `• *Catatan*: ${this.notes.trim()}\n`;
            }

            text += `\n(Pemesanan melalui website resmi Ruhiology Institute)`;

            let cleanNumber = this.waNumber.replace(/[^0-9]/g, '');
            if (cleanNumber.startsWith('0')) {
                cleanNumber = '62' + cleanNumber.substring(1);
            }

            const url = `https://wa.me/${cleanNumber}?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
            this.closeModal();
        }
    }"
    x-init="
        window.addEventListener('open-order-modal', (e) => openModal(e.detail));
        window.addEventListener('open-wa-modal', (e) => openModal(e.detail));
        if (new URLSearchParams(window.location.search).get('order') === '1') { isOpen = true; }
    "
    @open-order-modal.window="openModal($event.detail)"
    @open-wa-modal.window="openModal($event.detail)"
    x-show="isOpen"
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/65 backdrop-blur-sm animate-fadeIn">

    <!-- Modal Window -->
    <div @click.away="closeModal()" class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100 max-h-[92vh] overflow-y-auto" @click.stop>
        
        <!-- Close Button Top Right -->
        <button @click="closeModal()" type="button" aria-label="Tutup Form" class="absolute top-5 right-5 p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Header -->
        <div class="mb-6 pb-3 border-b border-slate-100">
            <h3 class="text-xl sm:text-2xl font-bold font-serif text-slate-900 flex items-center gap-2">
                <span>🛒</span>
                <span>Form Pemesanan Langsung Buku</span>
            </h3>
            <template x-if="productTitle">
                <p class="text-xs font-semibold text-[#0B2A43] mt-1" x-text="'Buku: ' + productTitle + (productPrice ? ' (' + productPrice + ')' : '')"></p>
            </template>
        </div>

        <form @submit.prevent="handleSendWA()" class="space-y-4 text-xs font-sans">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Jumlah Eksemplar</label>
                    <input type="number" x-model="quantity" min="1" :max="productStock" required class="w-full p-3 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-[#0B2A43] outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Nama Pemesan</label>
                    <input type="text" x-model="customerName" required placeholder="Masukkan nama pemesan..." class="w-full p-3 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-[#0B2A43] outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Email Pemesan</label>
                    <input type="email" x-model="customerEmail" required placeholder="admin@ruhiologyinstitute.com" class="w-full p-3 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-[#0B2A43] outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                    <input type="text" x-model="customerPhone" required placeholder="081274110001" class="w-full p-3 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-[#0B2A43] outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Alamat Pengiriman Lengkap</label>
                <textarea x-model="shippingAddress" rows="3" required placeholder="Jalan, Nomor Rumah, RT/RW, Kecamatan, Kota/Kabupaten, Kode Pos" class="w-full p-3 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-[#0B2A43] outline-none"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                <input type="text" x-model="notes" placeholder="Contoh: Tolong beri tanda tangan ucapan penulis" class="w-full p-3 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-[#0B2A43] outline-none">
            </div>

            <div class="pt-3 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 py-3.5 px-6 bg-[#D97706] hover:bg-[#B45309] text-white font-extrabold rounded-xl text-xs shadow-md transition-all flex items-center justify-center space-x-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Buat Pesanan</span>
                </button>
                <button type="button" @click="closeModal()" class="py-3.5 px-6 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-colors cursor-pointer">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
