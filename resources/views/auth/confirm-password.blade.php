@extends('layouts.guest')

@section('title', 'Konfirmasi Kata Sandi - Little Joy Jakarta')

@section('content')
<div class="mb-6 text-sm text-brandText-muted leading-relaxed">
    Ini adalah area aman aplikasi. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.
</div>

<form action="{{ route('password.confirm') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Password Field --}}
    <x-input
        label="Kata Sandi"
        id="password"
        type="password"
        name="password"
        placeholder="••••••••"
        autocomplete="current-password"
        autofocus
        required
    />

    <div class="pt-2">
        <button
            type="submit"
            class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 text-sm"
        >
            Konfirmasi
        </button>
    </div>
</form>
@endsection
