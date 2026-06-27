@extends('layouts.public')

@section('title', $product->name . ' - Little Joy Jakarta')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 font-sans">
    {{-- Back to Catalogue Button --}}
    <div class="mb-8 reveal">
        <a
            href="{{ route('catalogue.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary-dark transition-colors"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Katalog
        </a>
    </div>

    {{-- Main Product Info grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start mb-20">
        {{-- Left Column: Product Image Container --}}
        <div class="bg-white border border-brandOutline-soft/25 rounded-3xl overflow-hidden shadow-sm aspect-[4/3] relative reveal">
            @if($product->image_path)
                <img
                    src="/storage/{{ $product->image_path }}"
                    alt="{{ $product->name }}"
                    class="w-full h-full object-cover"
                />
            @else
                <div class="w-full h-full flex flex-col items-center justify-center bg-primary-soft/15 text-primary p-12 select-none">
                    <svg class="h-16 w-16 text-primary/45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                    <span class="text-xs font-bold tracking-[0.2em] uppercase text-primary/60 mt-4">
                        Little Joy Jakarta
                    </span>
                </div>
            @endif

            @if($product->stock <= 0)
                <div class="absolute inset-0 bg-black/40 backdrop-blur-[1.5px] flex items-center justify-center">
                    <span class="px-6 py-3 bg-white/95 text-danger font-bold text-sm uppercase tracking-widest rounded-xl shadow-lg">
                        Habis Terjual
                    </span>
                </div>
            @endif
        </div>

        {{-- Right Column: Product Core Details --}}
        <div class="space-y-6 reveal" data-delay="100">
            <div class="flex items-center gap-3">
                <span class="inline-block px-3 py-1 bg-primary-soft/25 text-primary text-xs font-bold tracking-wide uppercase rounded-lg">
                    {{ $product->category->name ?? 'Flower Arrangement' }}
                </span>
                
                @if($product->stock <= 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-danger border border-red-150">
                        <span class="h-1.5 w-1.5 rounded-full bg-danger animate-pulse"></span>
                        Stok Habis
                    </span>
                @elseif($product->stock <= 5)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-warning border border-yellow-150">
                        <span class="h-1.5 w-1.5 rounded-full bg-warning"></span>
                        Stok Terbatas ({{ $product->stock }})
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-success border border-green-150">
                        <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
                        Tersedia ({{ $product->stock }})
                    </span>
                @endif
            </div>

            {{-- Product Title --}}
            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-primary leading-tight tracking-wide">
                {{ $product->name }}
            </h2>

            {{-- Price --}}
            <div class="py-3 border-y border-brandSurface-high/50 flex items-center">
                <span class="text-2xl sm:text-3xl font-bold text-brandText">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            </div>

            {{-- Description --}}
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-primary uppercase tracking-wider">
                    Deskripsi Rangkaian
                </h4>
                <p class="text-sm text-brandText-muted leading-relaxed whitespace-pre-wrap">{{ $product->description }}</p>
            </div>

            {{-- Order Controls (Only if in stock) --}}
            @if($product->stock > 0)
                <div x-data="{ quantity: 1, max: {{ $product->stock }} }" class="space-y-4 pt-4 border-t border-brandSurface-low">
                    <div class="flex items-center gap-6 select-none">
                        <span class="text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Jumlah:
                        </span>
                        
                        {{-- Qty Counter Buttons --}}
                        <div class="flex items-center border border-brandOutline-soft/75 rounded-lg bg-white overflow-hidden shadow-sm">
                            <button
                                type="button"
                                @click="if (quantity > 1) quantity--"
                                :disabled="quantity <= 1"
                                class="px-3.5 py-1.5 text-brandText-muted hover:bg-brandSurface-low active:bg-brandSurface-high transition-colors text-sm font-bold focus:outline-none disabled:opacity-50"
                            >
                                &minus;
                            </button>
                            <span class="px-4 py-1.5 text-sm font-bold text-brandText min-w-[40px] text-center border-x border-brandSurface-high" x-text="quantity"></span>
                            <button
                                type="button"
                                @click="if (quantity < max) quantity++"
                                :disabled="quantity >= max"
                                class="px-3.5 py-1.5 text-brandText-muted hover:bg-brandSurface-low active:bg-brandSurface-high transition-colors text-sm font-bold focus:outline-none disabled:opacity-50"
                            >
                                &#43;
                            </button>
                        </div>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row gap-3">
                        @auth
                            <form action="{{ route('cart.store') }}" method="POST" class="w-full flex">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" :value="quantity">
                                
                                <button
                                    type="submit"
                                    class="flex-grow inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-md px-6 py-3 text-base gap-2.5"
                                >
                                    Masukkan Ke Keranjang
                                </button>
                            </form>
                        @else
                            <button
                                type="button"
                                @click="$dispatch('open-register-modal')"
                                class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-md px-6 py-3 text-base gap-2.5"
                            >
                                Masukkan Ke Keranjang
                            </button>
                        @endauth
                    </div>
                </div>
            @else
                <div class="pt-4 border-t border-brandSurface-low">
                    <button
                        class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border-2 border-red-200 bg-transparent text-danger cursor-not-allowed select-none py-3 text-base"
                        disabled
                    >
                        Stok Habis
                    </button>
                </div>
            @endif

            {{-- Extra Banners / Info cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-brandSurface-low">
                <div class="p-3 bg-brandSurface-low/30 border border-brandSurface-high rounded-xl text-xs text-brandText-muted space-y-1">
                    <p class="font-bold text-primary">Informasi Pengiriman</p>
                    <p class="leading-relaxed">Pengiriman terjadwal setiap hari pukul 09:00 - 20:00 WIB ke seluruh wilayah DKI Jakarta.</p>
                </div>
                <div class="p-3 bg-brandSurface-low/30 border border-brandSurface-high rounded-xl text-xs text-brandText-muted space-y-1">
                    <p class="font-bold text-primary">Pembayaran Aman</p>
                    <p class="leading-relaxed">Mendukung transfer bank manual (BCA/Mandiri). Verifikasi cepat oleh operator kami.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products Section --}}
    @if($relatedProducts->isNotEmpty())
        <div class="pt-16 border-t border-brandSurface-high/65 reveal" data-delay="200">
            <div class="mb-8 flex justify-between items-center">
                <h3 class="font-serif text-2xl font-bold text-primary tracking-wide">
                    Rekomendasi Rangkaian Lainnya
                </h3>
                <a
                    href="{{ route('catalogue.index', ['category' => $product->category_id]) }}"
                    class="text-xs font-bold text-primary hover:underline"
                >
                    Lihat Semua
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
