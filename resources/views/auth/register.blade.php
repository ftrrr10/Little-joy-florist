@extends('layouts.guest')

@section('title', 'Daftar Akun Baru - Little Joy Jakarta')

@section('content')
{{-- Header Text --}}
<div class="text-center mb-8">
    <h2 class="font-serif text-2xl font-bold text-primary tracking-wide">
        Daftar Akun Baru
    </h2>
    <p class="text-xs text-brandText-muted mt-2 leading-relaxed">
        Buat akun Little Joy Anda untuk mulai memilih bunga segar premium dan melacak status pesanan dengan mudah.
    </p>
</div>

{{-- Registration Form --}}
<form action="{{ route('register') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Name Field --}}
    <x-input
        label="Nama Lengkap"
        id="name"
        type="text"
        name="name"
        value="{{ old('name') }}"
        placeholder="Nama lengkap Anda"
        autocomplete="name"
        autofocus
        required
    />

    {{-- Email Field --}}
    <x-input
        label="Alamat Email"
        id="email"
        type="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="nama@email.com"
        autocomplete="username"
        required
    />

    {{-- WhatsApp / Phone Field --}}
    <x-input
        label="Nomor WhatsApp / Telepon"
        id="phone"
        type="tel"
        name="phone"
        value="{{ old('phone') }}"
        placeholder="Contoh: 081234567890"
        autocomplete="tel"
        required
        helperText="Digunakan untuk konfirmasi pesanan via WhatsApp."
    />

    {{-- Password Field --}}
    <x-input
        label="Kata Sandi"
        id="password"
        type="password"
        name="password"
        placeholder="Minimal 8 karakter"
        autocomplete="new-password"
        required
    />

    {{-- Confirm Password Field --}}
    <x-input
        label="Konfirmasi Kata Sandi"
        id="password_confirmation"
        type="password"
        name="password_confirmation"
        placeholder="Ulangi kata sandi Anda"
        autocomplete="new-password"
        required
    />

    {{-- Action Button --}}
    <div class="pt-2">
        <button
            type="submit"
            class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 text-sm"
        >
            Daftar Akun Baru
        </button>
    </div>
</form>

{{-- Login Shortcut Footer --}}
<div class="mt-8 pt-6 border-t border-brandSurface-low text-center">
    <p class="text-xs text-brandText-muted">
        Sudah memiliki akun?
        <a
            href="{{ route('login') }}"
            class="font-bold text-primary hover:text-primary-dark hover:underline transition-all"
        >
            Masuk Sekarang
        </a>
    </p>
</div>
@endsection
