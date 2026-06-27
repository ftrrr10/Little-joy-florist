@extends('layouts.guest')

@section('title', 'Verifikasi Email - Little Joy Jakarta')

@section('content')
<div class="mb-6 text-sm text-brandText-muted leading-relaxed">
    Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerimanya, kami akan dengan senang hati mengirimkan tautan yang baru.
</div>

@if (session('status') == 'verification-link-sent')
    <div class="mb-6 text-sm font-medium text-success bg-green-50 border border-green-200 p-3 rounded-lg">
        Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.
    </div>
@endif

<div class="flex items-center justify-between mt-4">
    <form action="{{ route('verification.send') }}" method="POST">
        @csrf
        <button
            type="submit"
            class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 px-4 text-xs"
        >
            Kirim Ulang Email Verifikasi
        </button>
    </form>

    <a
        href="#"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="text-xs font-bold text-brandText-muted hover:text-brandText underline"
    >
        Keluar
    </a>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
@endsection
