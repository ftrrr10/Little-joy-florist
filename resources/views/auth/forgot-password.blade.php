@extends('layouts.guest')

@section('title', 'Lupa Kata Sandi - Little Joy Jakarta')

@section('content')
<div class="mb-6 text-sm text-brandText-muted leading-relaxed">
    Lupa kata sandi Anda? Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan reset kata sandi melalui email untuk membuat yang baru.
</div>

@if (session('status'))
    <div class="mb-4 text-sm font-medium text-success bg-green-50 border border-green-200 p-3 rounded-lg">
        {{ session('status') }}
    </div>
@endif

<form action="{{ route('password.email') }}" method="POST" class="space-y-5">
    @csrf

    <x-input
        label="Alamat Email"
        id="email"
        type="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="nama@email.com"
        autofocus
        required
    />

    <div class="flex items-center justify-between pt-2">
        <a
            href="{{ route('login') }}"
            class="text-xs font-bold text-primary hover:text-primary-dark hover:underline transition-all"
        >
            Kembali ke Login
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 px-4 text-xs"
        >
            Kirim Tautan Reset
        </button>
    </div>
</form>
@endsection
