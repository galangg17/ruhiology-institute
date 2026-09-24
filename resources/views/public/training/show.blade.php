@extends('layouts.app')

@section('content')
<div class="bg-navy-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3">
        <span class="px-3 py-1 bg-amber-500/20 text-amber-400 text-xs font-bold rounded-full border border-amber-500/30 uppercase tracking-wider inline-block">
            {{ $training->category }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-bold font-serif text-white">{{ $training->title }}</h1>
        <p class="text-xs text-slate-300">Trainer Utama: <strong class="text-white">{{ $training->trainer }}</strong></p>
    </div>
</div>

<div class="py-12 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Detail Column -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h2 class="text-xl font-bold font-serif text-slate-900">Deskripsi Program Pelatihan</h2>
                    <div class="prose prose-sm text-slate-700 leading-relaxed text-xs">
                        {!! nl2br(e($training->description)) !!}
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 text-xs">
                    <h3 class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Informasi Fasilitas & Pelaksanaan</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div><strong>⏳ Durasi:</strong> {{ $training->duration }}</div>
                        <div><strong>📍 Lokasi:</strong> {{ $training->location }}</div>
                        <div><strong>💻 Mode:</strong> {{ $training->is_online ? 'Online (Zoom)' : 'Tatap Muka (Offline)' }}</div>
                        <div><strong>👥 Quota Peserta:</strong> {{ $training->quota }} Orang / Batch</div>
                    </div>
                </div>
            </div>

            <!-- Right Registration Sidebar Form -->
            <div class="lg:col-span-5">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-lg space-y-5 sticky top-24">
                    <div class="border-b border-slate-100 pb-3">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Biaya Pendaftaran</span>
                        <span class="text-2xl font-bold text-amber-700">Rp {{ number_format($training->price, 0, ',', '.') }}</span>
                    </div>

                    <h3 class="font-bold font-serif text-slate-900 text-sm">Form Pendaftaran Peserta</h3>

                    <form action="{{ route('training.register', $training->slug) }}" method="POST" class="space-y-4 text-xs">
                        @csrf

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Pilih Angkatan / Batch JADWAL</label>
                            <select name="batch_id" required class="w-full p-2.5 rounded border border-slate-300 text-xs">
                                @foreach($training->batches as $b)
                                    <option value="{{ $b->id }}">{{ $b->batch_name }} ({{ $b->start_date->format('d M Y') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Peserta</label>
                            <input type="text" name="participant_name" value="{{ old('participant_name', auth()->user()?->name) }}" required class="w-full p-2.5 rounded border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Email Aktif</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required class="w-full p-2.5 rounded border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required class="w-full p-2.5 rounded border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Institusi / Universitas (Opsional)</label>
                            <input type="text" name="institution" value="{{ old('institution') }}" class="w-full p-2.5 rounded border border-slate-300 text-xs">
                        </div>

                        <button type="submit" class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded text-xs shadow transition">
                            Konfirmasi & Kirim Pendaftaran →
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
