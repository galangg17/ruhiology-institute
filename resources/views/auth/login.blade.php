@extends('layouts.app')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xl space-y-6">
            
            <div class="text-center space-y-2">
                <img src="{{ asset('images/ruhiology-logo.png') }}" alt="Ruhiology Institute Logo" class="h-20 w-auto object-contain mx-auto drop-shadow-md">
                <h1 class="text-2xl font-bold font-serif text-slate-900">Masuk Akun Ruhiology</h1>
                <p class="text-xs text-slate-500">Akses portal peserta dan dashboard administratif</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@ruhiologyinstitute.com" class="w-full p-3 rounded-lg border border-slate-300">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full p-3 rounded-lg border border-slate-300">
                </div>

                <div class="flex justify-between items-center text-[11px]">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded text-amber-600">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-sm shadow transition">
                    Masuk Ke Sistem →
                </button>
            </form>

            <div class="text-center text-xs text-slate-500 pt-4 border-t border-slate-100">
                Belum memiliki akun peserta? <a href="{{ route('register') }}" class="font-bold text-amber-700 hover:underline">Daftar Akun Baru</a>
            </div>

            <!-- Demo Credentials Hint Box -->
            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 text-[11px] text-amber-900 space-y-1">
                <strong class="block text-amber-950 font-bold uppercase">Kredensial Demo Super Admin:</strong>
                <p>Email: <code class="font-mono bg-white px-1 rounded">admin@ruhiologyinstitute.com</code></p>
                <p>Password: <code class="font-mono bg-white px-1 rounded">password123</code></p>
            </div>

        </div>
    </div>
</div>
@endsection
