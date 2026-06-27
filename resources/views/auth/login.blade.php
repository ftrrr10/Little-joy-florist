@extends('layouts.guest')

@section('title', 'Masuk ke Akun - Little Joy Jakarta')

@section('content')
{{-- Header Text --}}
<div class="text-center mb-8">
    <h2 class="font-serif text-2xl font-bold text-primary tracking-wide">
        Selamat Datang Kembali
    </h2>
    <p class="text-xs text-brandText-muted mt-2 leading-relaxed">
        Silakan masuk ke akun Anda untuk mengelola pesanan atau memulai pemesanan bunga baru.
    </p>
</div>

{{-- Status Alert Banner --}}
@if (session('status'))
    <div class="mb-6 p-3.5 bg-green-50 border border-green-200 text-success text-xs font-semibold rounded-lg">
        {{ session('status') }}
    </div>
@endif

{{-- Login Form --}}
<form action="{{ route('login') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Email Field --}}
    <x-input
        label="Alamat Email"
        id="email"
        type="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="nama@email.com"
        autocomplete="username"
        autofocus
        required
    />

    {{-- Password Field --}}
    <x-input
        label="Kata Sandi"
        id="password"
        type="password"
        name="password"
        placeholder="••••••••"
        autocomplete="current-password"
        required
    />

    {{-- Remember Me & Forgot Password --}}
    <div class="flex items-center justify-between pt-1">
        <label class="inline-flex items-center cursor-pointer">
            <input
                type="checkbox"
                name="remember"
                class="rounded border-brandOutline-soft text-primary shadow-sm focus:ring-primary-soft/40 focus:ring-2 focus:border-primary h-4 w-4 transition-colors"
            />
            <span class="ms-2 text-xs font-semibold text-brandText-muted hover:text-brandText transition-colors">
                Ingat Saya
            </span>
        </label>

        @if (Route::has('password.request'))
            <a
                href="{{ route('password.request') }}"
                class="text-xs font-bold text-primary hover:text-primary-dark hover:underline transition-all"
            >
                Lupa Kata Sandi?
            </a>
        @endif
    </div>

    {{-- Action Buttons --}}
    <div class="pt-2">
        <button
            type="submit"
            class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 text-sm"
        >
            Masuk Sekarang
        </button>
    </div>
</form>

{{-- Register Shortcut Footer --}}
<div class="mt-8 pt-6 border-t border-brandSurface-low text-center">
    <p class="text-xs text-brandText-muted">
        Belum memiliki akun?{' '}
        <a
            href="{{ route('register') }}"
            class="font-bold text-primary hover:text-primary-dark hover:underline transition-all"
        >
            Daftar Akun Baru
        </a>
    </p>
</div>
@endsection
