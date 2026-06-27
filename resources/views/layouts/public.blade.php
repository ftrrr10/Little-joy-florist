@extends('layouts.app')

@section('body')
    @include('layouts.navigation-public')

    {{-- Main Viewport Content --}}
    <main class="flex-grow flex flex-col">
        @yield('content')
    </main>

    @include('layouts.footer')

    {{-- Global Register Modal for Guests --}}
    @guest
        <div
            x-data="{ 
                isOpen: false,
                redirectUrl: ''
            }"
            @open-register-modal.window="isOpen = true; redirectUrl = window.location.href"
            x-show="isOpen"
            class="fixed inset-0 z-50 overflow-y-auto font-sans"
            style="display: none;"
            role="dialog"
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div 
                x-show="isOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm"
                @click="isOpen = false"
            ></div>

            {{-- Modal Content Container --}}
            <div class="flex min-h-screen items-center justify-center p-4">
                <div 
                    x-show="isOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                    class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-brandOutline-soft/30 transition-all text-left"
                >
                    {{-- Close Button --}}
                    <button 
                        @click="isOpen = false" 
                        class="absolute right-4 top-4 p-1 text-brandText-muted hover:text-brandText transition-colors rounded-full hover:bg-brandSurface-low"
                        aria-label="Tutup"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    {{-- Header --}}
                    <div class="text-center mb-6">
                        <h3 class="font-serif text-2xl font-bold text-primary tracking-wide">
                            Daftar Akun Baru
                        </h3>
                        <p class="text-xs text-brandText-muted mt-2 leading-relaxed">
                            Silakan daftarkan akun baru untuk melanjutkan pembelian rangkaian bunga premium.
                        </p>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('register') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="redirect_to" :value="redirectUrl">

                        {{-- Name --}}
                        <div>
                            <label for="modal-name" class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input 
                                type="text" 
                                name="name" 
                                id="modal-name" 
                                required 
                                placeholder="Nama lengkap Anda"
                                class="w-full px-4 py-2.5 text-sm border border-brandOutline rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all bg-cream-light/5 text-brandText"
                            >
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="modal-email" class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5">Alamat Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                id="modal-email" 
                                required 
                                placeholder="nama@email.com"
                                class="w-full px-4 py-2.5 text-sm border border-brandOutline rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all bg-cream-light/5 text-brandText"
                            >
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="modal-phone" class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5">Nomor WhatsApp</label>
                            <input 
                                type="tel" 
                                name="phone" 
                                id="modal-phone" 
                                required 
                                placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-2.5 text-sm border border-brandOutline rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all bg-cream-light/5 text-brandText"
                            >
                            <p class="mt-1 text-[10px] text-brandText-muted leading-snug">Digunakan untuk konfirmasi pesanan via WhatsApp.</p>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="modal-password" class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5">Kata Sandi</label>
                            <input 
                                type="password" 
                                name="password" 
                                id="modal-password" 
                                required 
                                placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-2.5 text-sm border border-brandOutline rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all bg-cream-light/5 text-brandText"
                            >
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="modal-password_confirmation" class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="modal-password_confirmation" 
                                required 
                                placeholder="Ulangi kata sandi Anda"
                                class="w-full px-4 py-2.5 text-sm border border-brandOutline rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all bg-cream-light/5 text-brandText"
                            >
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button 
                                type="submit" 
                                class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-md py-3 text-sm"
                            >
                                Daftar Akun Baru
                            </button>
                        </div>
                    </form>

                    {{-- Footer --}}
                    <div class="mt-6 pt-4 border-t border-brandSurface-low text-center">
                        <p class="text-xs text-brandText-muted">
                            Sudah memiliki akun? 
                            <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary-dark hover:underline transition-all">
                                Masuk Sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endguest
@endsection
