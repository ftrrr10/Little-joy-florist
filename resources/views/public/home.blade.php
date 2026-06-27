@extends('layouts.public')

@section('title', 'Premium Florist Jakarta - Rangkaian Bunga Eksklusif | Little Joy')

@section('content')
@php
    $categoryImageMap = [
        'Hand Bouquet' => '/storage/products/hand-bouquet.png',
        'Bloom Box' => '/storage/products/bloom-box.png',
        'Flower Stand' => '/storage/products/flower-stand.png',
        'Vase Arrangement' => '/storage/products/vase-arrangement.png',
        'Orchid Plant' => '/storage/products/orchid-plant.png',
    ];
@endphp

<div class="space-y-24 pb-20 font-sans bg-brandBackground">
    {{-- 1. HERO SECTION --}}
    <section class="relative overflow-hidden pt-12 md:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {{-- Hero Text --}}
            <div class="reveal lg:col-span-7 space-y-8 z-10" data-delay="0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold tracking-[0.2em] text-primary uppercase bg-primary-soft/15 rounded-full">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    Premium Florist Jakarta
                </span>
                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-primary leading-[1.12] tracking-tight">
                    Rangkaian Bunga Segar <br />
                    <span class="text-secondary italic font-normal">Dirangkai dengan Jiwa</span> <br />
                    Untuk Momen Spesial Anda.
                </h1>
                <p class="text-brandText-muted text-sm sm:text-base max-w-xl leading-relaxed">
                    Hadirkan kebahagiaan sejati melalui keindahan botani terbaik. Setiap tangkai bunga di Little Joy Jakarta dipilih secara manual dan dirangkai oleh florist bersertifikat untuk melahirkan kemewahan visual yang tiada duanya.
                </p>
                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="{{ route('catalogue.index') }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-md hover:shadow-lg rounded-xl px-6 py-3 text-base gap-2.5">
                        Jelajahi Katalog
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="{{ route('about') }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border-2 border-brandOutline-soft bg-transparent text-primary hover:bg-brandSurface-low hover:border-primary focus:ring-primary-soft rounded-xl px-6 py-3 text-base gap-2.5">
                        Tentang Kami
                    </a>
                </div>
            </div>

            {{-- Hero Image --}}
            <div class="reveal lg:col-span-5 relative flex justify-center lg:justify-end" data-delay="200">
                <div class="absolute -inset-4 bg-primary-soft/10 rounded-3xl blur-3xl -z-10 pointer-events-none"></div>
                <div class="relative w-full max-w-[400px] aspect-[4/5] bg-white p-3 rounded-3xl border border-brandOutline-soft/20 shadow-xl rotate-2 hover:rotate-0 transition-all duration-500 ease-out">
                    <img 
                        src="/storage/products/vase-arrangement.png" 
                        alt="Table Vase Arrangement Little Joy" 
                        class="w-full h-full object-cover rounded-2xl"
                    />
                    <div class="absolute bottom-6 -left-6 bg-white border border-brandOutline-soft/30 px-5 py-3 rounded-2xl shadow-lg flex items-center gap-3">
                        <div class="p-2 bg-green-50 text-primary rounded-xl">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Desain Terlaris</p>
                            <p class="font-serif text-xs font-bold text-primary">Vase Table Arrangement</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. THE BOTANICAL PROMISE (BRAND VALUES) --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="bg-white p-8 md:p-12 rounded-3xl border border-brandOutline-soft/20 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            {{-- Value 1 --}}
            <div class="reveal space-y-3 p-4" data-delay="0">
                <div class="w-10 h-10 bg-primary-soft/20 text-primary rounded-2xl flex items-center justify-center mx-auto md:mx-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <h4 class="font-serif text-lg font-bold text-primary">100% Garansi Kesegaran</h4>
                <p class="text-xs text-brandText-muted leading-relaxed">
                    Kami mendatangkan bunga segar setiap pagi hari untuk memastikan buket Anda tetap harum, mekar sempurna, dan tahan lama.
                </p>
            </div>

            {{-- Value 2 --}}
            <div class="reveal space-y-3 p-4 border-y md:border-y-0 md:border-x border-gray-100" data-delay="150">
                <div class="w-10 h-10 bg-secondary-soft/30 text-secondary rounded-2xl flex items-center justify-center mx-auto md:mx-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h4 class="font-serif text-lg font-bold text-primary">Rangkaian Hasil Seni</h4>
                <p class="text-xs text-brandText-muted leading-relaxed">
                    Bunga Anda dirangkai khusus secara personal oleh florist ahli kami, melahirkan satu karya seni botani unik di setiap pesanan.
                </p>
            </div>

            {{-- Value 3 --}}
            <div class="reveal space-y-3 p-4" data-delay="300">
                <div class="w-10 h-10 bg-amber-50 text-warning rounded-2xl flex items-center justify-center mx-auto md:mx-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10M13 8h7a1 1 0 011 1v5m0 0h1a1 1 0 011 1v2a1 1 0 01-1 1h-1m-12 0a2 2 0 00-2 3h12a2 2 0 00-2-3" />
                    </svg>
                </div>
                <h4 class="font-serif text-lg font-bold text-primary">Pengiriman Hari Yang Sama</h4>
                <p class="text-xs text-brandText-muted leading-relaxed">
                    Butuh kejutan mendadak? Kami menyediakan opsi pengiriman cepat di hari yang sama untuk wilayah seluruh area Jakarta.
                </p>
            </div>
        </div>
    </section>

    {{-- 3. CATEGORY SHOWCASE --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
        <div class="reveal text-center max-w-2xl mx-auto space-y-2" data-delay="0">
            <span class="text-[10px] font-bold tracking-[0.2em] text-secondary uppercase">
                Koleksi Eksklusif
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-primary">
                Pilih Berdasarkan Kategori Bunga
            </h2>
            <p class="text-xs sm:text-sm text-brandText-muted">
                Temukan bentuk rangkaian terbaik yang dirancang khusus untuk mewakili setiap pesan emosi Anda.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($categories as $index => $category)
                @php
                    $bgImage = $categoryImageMap[$category->name] ?? '/storage/products/hand-bouquet.png';
                @endphp
                <div class="reveal flex flex-col h-full" data-delay="{{ $index * 100 }}">
                    <a 
                        href="{{ route('catalogue.index', ['category' => $category->id]) }}"
                        class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-brandOutline-soft/10 shadow-sm hover:shadow-md transition-all duration-300 h-full"
                    >
                        <div class="relative aspect-[4/5] overflow-hidden bg-gray-50">
                            <img 
                                src="{{ $bgImage }}" 
                                alt="{{ $category->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-serif text-sm font-bold text-primary group-hover:text-secondary transition-colors truncate">
                                    {{ $category->name }}
                                </h3>
                                <p class="text-[10px] text-brandText-muted leading-relaxed line-clamp-2 mt-1">
                                    {{ $category->description }}
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-secondary mt-3 border-b border-secondary/0 group-hover:border-secondary/100 w-fit transition-all">
                                Lihat 
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 4. FEATURED PRODUCTS (THE COLLECTION) --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8 space-y-10">
        <div class="reveal flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4" data-delay="0">
            <div class="space-y-1">
                <span class="text-[10px] font-bold tracking-[0.2em] text-primary uppercase">
                    Produk Unggulan
                </span>
                <h2 class="font-serif text-3xl font-bold text-primary">
                    Rangkaian Terlaris Minggu Ini
                </h2>
                <p class="text-xs text-brandText-muted">
                    Bunga-bunga pilihan terfavorit yang paling sering dipesan oleh pelanggan kami.
                </p>
            </div>
            <a 
                href="{{ route('catalogue.index') }}" 
                class="inline-flex items-center gap-1 text-xs font-bold text-secondary hover:text-secondary-dark group self-start sm:self-end"
            >
                Lihat Semua Produk 
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

        @if($featuredProducts->isEmpty())
            <div class="text-center py-12 text-brandText-muted">
                Belum ada produk bunga yang diunggulkan saat ini.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $index => $product)
                    <div class="reveal" data-delay="{{ $index * 100 }}">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- 5. BRAND STORY & CTA BANNER --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="reveal-scale w-full" data-delay="0">
            <div class="bg-primary text-white rounded-3xl p-8 md:p-16 text-center space-y-6 relative overflow-hidden shadow-lg">
                <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="max-w-2xl mx-auto space-y-6">
                    <svg class="w-10 h-10 text-primary-soft mx-auto animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <h3 class="font-serif text-3xl sm:text-4xl font-bold leading-tight">
                        Ingin Rangkaian Kustom Khusus Untuk Momen Anda?
                    </h3>
                    <p class="text-white/80 text-sm leading-relaxed">
                        Tim florist berpengalaman kami selalu siap melayani konsultasi desain bunga kustom, menyesuaikan dengan preferensi bunga favorit, palet warna, ukuran, hingga bujet spesifik Anda.
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('contact') }}">
                            <button class="px-8 py-3.5 bg-white hover:bg-gray-50 text-primary text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md">
                                Hubungi Florist Kami
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
