@extends('layouts.public')

@section('title', 'Checkout | Little Joy Jakarta')

@section('content')
@php
    $todayStr = date('Y-m-d');
@endphp

<div class="bg-cream-light/30 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumbs / Back --}}
        <div class="mb-8 reveal">
            <a
                href="{{ route('cart.index') }}"
                class="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors mb-3 group"
            >
                <svg class="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Keranjang
            </a>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-primary tracking-tight">
                Detail Pengiriman & Pemesanan
            </h1>
            <p class="text-sm text-brandText-muted mt-1">
                Lengkapi informasi di bawah ini untuk menyelesaikan pesanan Anda.
            </p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            @csrf

            {{-- Left Column: Checkout Form --}}
            <div class="lg:col-span-2 space-y-6 reveal" data-delay="100">
                {{-- Section 1: Penerima --}}
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                    <h3 class="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Penerima
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input
                                label="Nama Penerima"
                                id="recipient_name"
                                type="text"
                                name="recipient_name"
                                value="{{ old('recipient_name') }}"
                                placeholder="Nama Lengkap Penerima"
                                required
                            />
                        </div>

                        <div>
                            <x-input
                                label="No. Telepon Penerima"
                                id="recipient_phone"
                                type="tel"
                                name="recipient_phone"
                                value="{{ old('recipient_phone') }}"
                                placeholder="Contoh: 081234567890"
                                required
                            />
                        </div>
                    </div>
                </div>

                {{-- Section 2: Detail Pengiriman --}}
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                    <h3 class="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Alamat Pengiriman
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label htmlFor="delivery_address" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="delivery_address"
                                name="delivery_address"
                                rows="3"
                                class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('delivery_address') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Tulis alamat pengiriman lengkap termasuk kecamatan, kelurahan, kode pos, dan patokan jalan..."
                                required
                            >{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label htmlFor="delivery_date" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tanggal Pengiriman <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="delivery_date"
                                name="delivery_date"
                                min="{{ $todayStr }}"
                                value="{{ old('delivery_date') }}"
                                class="w-fit border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('delivery_date') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                required
                            />
                            @error('delivery_date')
                                <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 3: Pesan & Catatan --}}
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6" x-data="{ greetingMessage: '{{ old('greeting_message', '') }}' }">
                    <h3 class="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Kartu Ucapan & Catatan Tambahan (Opsional)
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label htmlFor="greeting_message" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                Pesan Kartu Ucapan
                            </label>
                            <textarea
                                id="greeting_message"
                                name="greeting_message"
                                rows="3"
                                x-model="greetingMessage"
                                maxlength="500"
                                class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('greeting_message') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Tulis ucapan gratis untuk disematkan pada rangkaian bunga (contoh: Happy Birthday, Happy Graduation, dll)..."
                            ></textarea>
                            <div class="flex justify-between items-center mt-1 text-[10px] text-brandText-muted">
                                <span>Maksimal 500 karakter.</span>
                                <span><span x-text="greetingMessage.length"></span>/500</span>
                            </div>
                            @error('greeting_message')
                                <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label htmlFor="customer_note" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Catatan Operasional untuk Florist
                            </label>
                            <textarea
                                id="customer_note"
                                name="customer_note"
                                rows="2"
                                class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('customer_note') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Contoh: Tolong gunakan pita warna merah muda, atau pastikan bunga mawar dikupas rapi..."
                            >{{ old('customer_note') }}</textarea>
                            @error('customer_note')
                                <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Order Summary --}}
            <div class="space-y-6 reveal" data-delay="200">
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6 sticky top-6">
                    <h3 class="font-serif text-lg font-bold text-primary mb-4 pb-3 border-b border-brandOutline-soft/30">
                        Ringkasan Pesanan
                    </h3>

                    {{-- Items Compact List --}}
                    <div class="space-y-3 mb-6 max-h-60 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            @php
                                $product = $item->product;
                            @endphp
                            @if($product)
                                <div class="flex justify-between items-start gap-4 text-xs">
                                    <div class="flex-1">
                                        <span class="font-semibold text-brandText">{{ $product->name }}</span>
                                        <span class="text-brandText-muted ml-1.5">x{{ $item->quantity }}</span>
                                    </div>
                                    <span class="font-bold text-primary flex-shrink-0">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Pricing Cost Breakdown --}}
                    <div class="space-y-3 text-sm pt-4 border-t border-brandOutline-soft/30">
                        <div class="flex justify-between text-brandText-muted text-xs">
                            <span>Subtotal</span>
                            <span class="font-semibold text-brandText">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-brandText-muted text-xs">
                            <span>Ongkos Kirim (Flat)</span>
                            <span class="font-semibold text-brandText">
                                Rp {{ number_format($deliveryFee, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="pt-3 border-t border-brandOutline-soft/30 flex justify-between items-center font-bold text-base text-primary">
                            <span>Total Pembayaran</span>
                            <span class="font-serif text-lg">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Submit button --}}
                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2.5 text-sm gap-2 shadow-md"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Konfirmasi & Buat Pesanan
                        </button>
                    </div>

                    <div class="mt-4 text-[10px] text-brandText-muted/80 text-center leading-relaxed">
                        Dengan menekan tombol di atas, Anda setuju untuk membuat pesanan. Anda akan diarahkan ke petunjuk pembayaran transfer manual.
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
