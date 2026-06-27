@extends('layouts.guest')

@section('title', 'Reset Kata Sandi - Little Joy Jakarta')

@section('content')
{{-- Header Text --}}
<div class="text-center mb-6">
    <h2 class="font-serif text-2xl font-bold text-primary tracking-wide">
        Atur Ulang Kata Sandi
    </h2>
    <p class="text-xs text-brandText-muted mt-2 leading-relaxed">
        Silakan masukkan kata sandi baru Anda di bawah ini.
    </p>
</div>

<form action="{{ route('password.store') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Password Reset Token --}}
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    {{-- Email Field --}}
    <x-input
        label="Alamat Email"
        id="email"
        type="email"
        name="email"
        value="{{ old('email', $request->email) }}"
        autocomplete="username"
        required
    />

    {{-- Password Field --}}
    <x-input
        label="Kata Sandi Baru"
        id="password"
        type="password"
        name="password"
        placeholder="Minimal 8 karakter"
        autocomplete="new-password"
        autofocus
        required
    />

    {{-- Confirm Password Field --}}
    <x-input
        label="Konfirmasi Kata Sandi"
        id="password_confirmation"
        type="password"
        name="password_confirmation"
        placeholder="Ulangi kata sandi baru"
        autocomplete="new-password"
        required
    />

    <div class="pt-2">
        <button
            type="submit"
            class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 text-sm"
        >
            Atur Ulang Kata Sandi
        </button>
    </div>
</form>
@endsection
