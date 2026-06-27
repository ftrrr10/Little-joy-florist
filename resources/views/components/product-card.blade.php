@props(['product'])

@php
    $isOutOfStock = $product->stock <= 0;
    $isLowStock = !$isOutOfStock && $product->stock <= 5;
@endphp

<div class="group flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 font-sans border border-brandOutline-soft/10">
    {{-- Product Image Wrapper - 4:5 Aspect Ratio --}}
    <a href="{{ route('catalogue.show', $product->slug) }}" class="relative aspect-[4/5] block overflow-hidden bg-brandSurface-low rounded-xl m-2">
        @if($product->image_path)
            <img
                src="/storage/{{ $product->image_path }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                loading="lazy"
            />
        @else
            {{-- Elegant Botanical CSS Placeholder if no image exists --}}
            <div class="w-full h-full flex flex-col items-center justify-center bg-primary-soft/15 text-primary p-6 select-none">
                <svg class="h-10 w-10 text-primary/45 group-hover:scale-110 transition-transform duration-300 ease-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
                <span class="text-[9px] font-bold tracking-[0.2em] uppercase text-primary/60 mt-3">
                    Little Joy Jakarta
                </span>
            </div>
        @endif

        {{-- Out of Stock Overlay --}}
        @if($isOutOfStock)
            <div class="absolute inset-0 bg-black/45 backdrop-blur-[1px] flex items-center justify-center">
                <span class="px-4 py-2 bg-white/95 text-danger font-bold text-xs uppercase tracking-widest rounded-lg shadow-md">
                    Habis Terjual
                </span>
            </div>
        @endif
    </a>

    {{-- Product Body Details --}}
    <div class="px-4 pb-5 pt-2 flex-grow flex flex-col gap-1.5">
        <div class="flex items-center justify-between gap-2">
            {{-- Category Tag --}}
            <span class="text-[9px] font-bold text-primary tracking-[0.1em] uppercase">
                {{ $product->category->name ?? 'Flower Arrangement' }}
            </span>
            
            {{-- Stock status pill --}}
            @if($isOutOfStock)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-danger border border-red-100">
                    <span class="h-1 w-1 rounded-full bg-danger animate-pulse"></span>
                    Stok Habis
                </span>
            @elseif($isLowStock)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-50 text-warning border border-yellow-100">
                    <span class="h-1 w-1 rounded-full bg-warning"></span>
                    Stok Terbatas ({{ $product->stock }})
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-success border border-green-100">
                    <span class="h-1 w-1 rounded-full bg-success"></span>
                    Tersedia ({{ $product->stock }})
                </span>
            @endif
        </div>

        {{-- Product Title --}}
        <h3 class="font-serif text-base font-bold text-brandText group-hover:text-primary transition-colors leading-snug line-clamp-1">
            <a href="{{ route('catalogue.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Product Price & Action --}}
        <div class="mt-auto pt-2.5 flex items-center justify-between border-t border-gray-50">
            <span class="text-base font-bold text-primary font-serif">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>
            
            {{-- Detail Link text icon --}}
            <a
                href="{{ route('catalogue.show', $product->slug) }}"
                class="text-xs font-bold text-secondary hover:text-secondary-dark group-hover:translate-x-0.5 transition-transform duration-200 flex items-center gap-0.5"
            >
                Lihat
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>
