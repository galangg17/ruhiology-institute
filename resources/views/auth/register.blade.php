@extends('layouts.app')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xl space-y-6">
            
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-xl bg-amber-600 text-white font-serif font-bold text-2xl flex items-center justify-center mx-auto shadow">RI</div>
                <h1 class="text-2xl font-bold font-serif text-slate-900">Daftar Akun Peserta</h1>
                <p class="text-xs text-slate-500">Buat akun untuk mengakses asesmen dan pelatihan</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-3 rounded-lg border border-slate-300">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Aktif</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-3 rounded-lg border border-slate-300">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full p-3 rounded-lg border border-slate-300">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full p-3 rounded-lg border border-slate-300">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full p-3 rounded-lg border border-slate-300">
                </div>

                <button type="submit" class="w-full py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-sm shadow transition">
                    Daftar Akun Peserta →
                </button>
            </form>

            <div class="text-center text-xs text-slate-500 pt-4 border-t border-slate-100">
                Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-amber-700 hover:underline">Masuk Saja</a>
            </div>

        </div>
    </div>
</div>
@endsection
