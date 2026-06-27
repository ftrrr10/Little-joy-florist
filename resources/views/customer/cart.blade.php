@extends('layouts.public')

@section('title', 'Keranjang Belanja | Little Joy Jakarta')

@section('content')
<div class="bg-cream-light/30 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 reveal">
            <a
                href="{{ route('catalogue.index') }}"
                class="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors mb-3 group"
            >
                <svg class="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Katalog
            </a>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-primary tracking-tight">
                Keranjang Belanja
            </h1>
            <p class="text-sm text-brandText-muted mt-1">
                Kelola bunga pilihan Anda sebelum melanjutkan ke pembayaran.
            </p>
        </div>

        @if(empty($items) || count($items) === 0)
            <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-12 shadow-sm max-w-2xl mx-auto text-center reveal" data-delay="100">
                <div class="w-16 h-16 bg-primary-soft/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="font-serif text-lg font-bold text-primary mb-2">Keranjang Belanja Kosong</h3>
                <p class="text-xs text-brandText-muted leading-relaxed">
                    Anda belum menambahkan rangkaian bunga apa pun ke keranjang belanja Anda.
                </p>
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('catalogue.index') }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm px-4 py-2 text-sm gap-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                {{-- Items List --}}
                <div class="lg:col-span-2 space-y-4 reveal" data-delay="100">
                    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-brandOutline-soft/30 bg-cream/10 flex justify-between items-center">
                            <h3 class="font-serif text-lg font-semibold text-primary">
                                Daftar Rangkaian Bunga ({{ count($items) }})
                            </h3>
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja Anda?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="text-xs text-red-600 hover:text-red-800 font-semibold transition-colors"
                                >
                                    Kosongkan Keranjang
                                </button>
                            </form>
                        </div>

                        <div class="divide-y divide-brandOutline-soft/20">
                            @foreach($items as $item)
                                @php
                                    $product = $item->product;
                                @endphp
                                @if($product)
                                    <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                        {{-- Product Details --}}
                                        <div class="flex items-center space-x-4 flex-1">
                                            <div class="w-20 h-20 rounded-xl overflow-hidden border border-brandOutline-soft bg-cream-light/20 flex-shrink-0 flex items-center justify-center">
                                                @if($product->image_path)
                                                    <img
                                                        src="/storage/{{ $product->image_path }}"
                                                        alt="{{ $product->name }}"
                                                        class="w-full h-full object-cover"
                                                    />
                                                @else
                                                    <span class="text-2xl text-primary/30 font-serif">
                                                        ❀
                                                    </span>
                                                @endif
                                            </div>

                                            <div>
                                                <span class="text-[10px] uppercase tracking-wider text-primary font-bold">
                                                    {{ $product->category->name ?? 'Bunga' }}
                                                </span>
                                                <h4 class="font-serif text-base font-bold text-primary leading-tight mt-0.5">
                                                    {{ $product->name }}
                                                </h4>
                                                <p class="text-xs text-brandText-muted mt-1">
                                                    Harga Satuan:
                                                    <span class="font-semibold text-brandText">
                                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                                    </span>
                                                </p>
                                                @if($product->stock <= 5)
                                                    <p class="text-[10px] font-semibold text-warning mt-1">
                                                        Sisa Stok: {{ $product->stock }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Controls & Subtotal --}}
                                        <div class="flex items-center justify-between sm:justify-end gap-6 sm:gap-10">
                                            {{-- Quantity --}}
                                            <div>
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <div x-data="{ quantity: {{ $item->quantity }}, max: {{ $product->stock }} }" class="flex items-center border border-brandOutline-soft/75 rounded-lg bg-white overflow-hidden shadow-sm">
                                                        <button
                                                            type="submit"
                                                            @click="if(quantity > 1) { quantity--; $nextTick(() => $el.form.submit()) }"
                                                            :disabled="quantity <= 1"
                                                            class="px-2 py-1 text-brandText-muted hover:bg-brandSurface-low transition-colors text-xs font-bold disabled:opacity-50"
                                                        >
                                                            &minus;
                                                        </button>
                                                        <span class="px-2.5 py-1 text-xs font-bold text-brandText min-w-[30px] text-center" x-text="quantity"></span>
                                                        <button
                                                            type="submit"
                                                            @click="if(quantity < max) { quantity++; $nextTick(() => $el.form.submit()) }"
                                                            :disabled="quantity >= max"
                                                            class="px-2 py-1 text-brandText-muted hover:bg-brandSurface-low transition-colors text-xs font-bold disabled:opacity-50"
                                                        >
                                                            &#43;
                                                        </button>
                                                        <input type="hidden" name="quantity" :value="quantity">
                                                    </div>
                                                </form>
                                            </div>

                                            {{-- Item Subtotal --}}
                                            <div class="text-right min-w-[100px]">
                                                <p class="text-xs text-brandText-muted">Subtotal</p>
                                                <p class="font-serif text-sm font-bold text-primary mt-0.5">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            {{-- Delete Button --}}
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini dari keranjang?')">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="p-2 text-brandText-muted/50 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all focus:outline-none"
                                                    title="Hapus barang"
                                                >
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Summary Card --}}
                <div class="space-y-6 reveal" data-delay="200">
                    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                        <h3 class="font-serif text-lg font-bold text-primary mb-4 pb-3 border-b border-brandOutline-soft/30">
                            Ringkasan Belanja
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-brandText-muted">
                                <span>Subtotal</span>
                                <span class="font-semibold text-brandText">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-brandText-muted">
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

                        {{-- Checkout Buttons --}}
                        <div class="mt-6 space-y-3">
                            <a href="{{ route('checkout.index') }}" class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2.5 text-sm gap-2">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Lanjutkan ke Checkout
                            </a>

                            <a href="{{ route('catalogue.index') }}" class="block w-full text-center text-xs font-semibold text-primary hover:text-primary-dark transition-colors py-2">
                                Tambah Produk Lain
                            </a>
                        </div>
                    </div>

                    {{-- Premium Service Info Banner --}}
                    <div class="bg-cream/10 border border-brandOutline-soft/20 rounded-2xl p-5 text-xs text-brandText-muted/80 space-y-3">
                        <h4 class="font-bold text-primary flex items-center">
                            <span class="text-base mr-1.5">✿</span> Layanan Little Joy Jakarta
                        </h4>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>Pengiriman terjamin segar dan tepat waktu.</li>
                            <li>Setiap pesanan menyertakan kartu ucapan gratis.</li>
                            <li>Pembayaran dilakukan secara transfer bank manual dengan verifikasi cepat.</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
