@extends('layouts.public')

@section('title', 'Pemesanan Berhasil | Little Joy Jakarta')

@section('content')
<div class="bg-cream-light/30 min-h-screen py-16 flex items-center justify-center font-sans">
    <div class="max-w-xl w-full mx-auto px-4">
        <div class="bg-white border border-brandOutline-soft/30 rounded-3xl p-8 sm:p-10 shadow-md text-center reveal" x-data="{ copyText(text) { navigator.clipboard.writeText(text); alert('Disalin ke papan klip!'); } }">
            {{-- Success Icon --}}
            <div class="mx-auto w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-6 text-green-600">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            {{-- Title --}}
            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-primary mb-2">
                Pemesanan Berhasil!
            </h2>
            <p class="text-sm text-brandText-muted leading-relaxed mb-6">
                Terima kasih atas kepercayaan Anda. Pesanan Anda telah tercatat di sistem kami dengan detail berikut:
            </p>

            {{-- Order Details Card --}}
            <div class="bg-cream/10 border border-brandOutline-soft/20 rounded-2xl p-5 mb-8 text-left space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-brandText-muted uppercase tracking-wider">
                        Nomor Pesanan
                    </span>
                    <div class="flex items-center space-x-2">
                        <span class="font-mono text-sm font-bold text-primary">
                            {{ $orderNumber }}
                        </span>
                        <button
                            type="button"
                            @click="copyText('{{ $orderNumber }}')"
                            class="p-1 text-brandText-muted hover:text-primary transition-colors focus:outline-none"
                            title="Salin nomor pesanan"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-brandOutline-soft/10">
                    <span class="text-xs font-bold text-brandText-muted uppercase tracking-wider">
                        Total Pembayaran
                    </span>
                    <span class="font-serif text-lg font-bold text-primary">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Bank Transfer Instructions --}}
            <div class="text-left mb-8 space-y-4">
                <h4 class="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2">
                    Instruksi Pembayaran Transfer Bank
                </h4>
                <p class="text-xs text-brandText-muted leading-relaxed">
                    Silakan lakukan transfer bank manual dengan nominal yang tepat ke salah satu rekening resmi **Little Joy Jakarta** berikut:
                </p>

                <div class="space-y-3">
                    {{-- Bank BCA --}}
                    <div class="bg-white border border-brandOutline-soft/30 rounded-xl p-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-bold text-primary">BANK BCA</p>
                            <p class="font-mono text-sm font-semibold text-brandText mt-0.5">123-456-7890</p>
                            <p class="text-[10px] text-brandText-muted">a/n Little Joy Jakarta</p>
                        </div>
                        <button
                            type="button"
                            @click="copyText('1234567890')"
                            class="text-xs font-semibold text-primary hover:text-primary-dark focus:outline-none"
                        >
                            Salin Rekening
                        </button>
                    </div>

                    {{-- Bank Mandiri --}}
                    <div class="bg-white border border-brandOutline-soft/30 rounded-xl p-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-bold text-primary">BANK MANDIRI</p>
                            <p class="font-mono text-sm font-semibold text-brandText mt-0.5">098-765-4321</p>
                            <p class="text-[10px] text-brandText-muted">a/n Little Joy Jakarta</p>
                        </div>
                        <button
                            type="button"
                            @click="copyText('0987654321')"
                            class="text-xs font-semibold text-primary hover:text-primary-dark focus:outline-none"
                        >
                            Salin Rekening
                        </button>
                    </div>
                </div>

                <ul class="text-[10px] text-brandText-muted/80 list-disc list-inside space-y-1.5 leading-relaxed pt-2">
                    <li>Pastikan nominal transfer sesuai dengan total pembayaran di atas.</li>
                    <li>Simpan foto atau tangkapan layar bukti transfer Anda.</li>
                    <li>Unggah bukti transfer untuk memulai proses verifikasi pesanan oleh florist kami.</li>
                </ul>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3">
                <a href="{{ route('customer.payments.create', $orderNumber) }}" class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-3 text-sm gap-2 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Unggah Bukti Pembayaran
                </a>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('customer.orders.show', $orderNumber) }}" class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border border-brandOutline-soft/30 bg-transparent text-brandText hover:bg-brandSurface-low focus:ring-brandSurface-high py-2.5 text-xs">
                        Detail Pesanan
                    </a>
                    <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border border-brandOutline-soft/30 bg-transparent text-brandText hover:bg-brandSurface-low focus:ring-brandSurface-high py-2.5 text-xs gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Kembali Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
