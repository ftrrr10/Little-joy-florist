@extends('layouts.public')

@section('title', 'Katalog Rangkaian Bunga Segar - Little Joy Jakarta')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 font-sans">
    {{-- Hero Catalog Header --}}
    <div class="text-center max-w-3xl mx-auto mb-16 reveal">
        <span class="text-[10px] font-bold tracking-[0.3em] text-primary uppercase mb-3 block">
            Katalog Florist
        </span>
        <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-primary leading-tight">
            Temukan Rangkaian Bunga Terbaik
        </h2>
        <p class="text-sm sm:text-base text-brandText-muted mt-4 leading-relaxed">
            Mulai dari buket mawar klasik hingga standing flower megah, setiap ciptaan kami dirangkai menggunakan bunga potong segar berkualitas terbaik untuk menghiasi momen berharga Anda.
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        {{-- 1. Desktop Filter Sidebar --}}
        <aside class="hidden lg:block w-64 flex-shrink-0 bg-white border border-brandOutline-soft/25 p-6 rounded-2xl shadow-sm sticky top-24 reveal">
            <div class="space-y-6">
                {{-- Search Filter --}}
                <div>
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-3">
                        Cari Bunga
                    </h4>
                    <form action="{{ route('catalogue.index') }}" method="GET" class="flex gap-2">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('availability'))
                            <input type="hidden" name="availability" value="{{ request('availability') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <input
                            type="text"
                            name="search"
                            placeholder="Cari nama bunga..."
                            value="{{ request('search') }}"
                            class="w-full pl-3 pr-3 py-1.5 text-xs border border-brandOutline rounded-lg bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                        />
                        <button type="submit" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm text-xs py-1.5 px-3">
                            Cari
                        </button>
                    </form>
                </div>

                <hr class="border-brandSurface-low" />

                {{-- Category Filter --}}
                <div>
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-3">
                        Kategori
                    </h4>
                    <div class="flex flex-col gap-1">
                        <a
                            href="{{ route('catalogue.index', array_merge(request()->query(), ['category' => null, 'page' => null])) }}"
                            class="text-left text-xs py-2 px-3 rounded-lg font-medium transition-all {{ !request('category') ? 'bg-primary-soft/35 text-primary font-bold' : 'text-brandText-muted hover:bg-brandSurface-low hover:text-primary' }}"
                        >
                            Semua Rangkaian
                        </a>
                        @foreach($categories as $cat)
                            <a
                                href="{{ route('catalogue.index', array_merge(request()->query(), ['category' => $cat->id, 'page' => null])) }}"
                                class="text-left text-xs py-2 px-3 rounded-lg font-medium transition-all {{ request('category') == $cat->id ? 'bg-primary-soft/35 text-primary font-bold' : 'text-brandText-muted hover:bg-brandSurface-low hover:text-primary' }}"
                            >
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <hr class="border-brandSurface-low" />

                {{-- Availability Filter --}}
                <div>
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-3">
                        Ketersediaan
                    </h4>
                    <a
                        href="{{ route('catalogue.index', array_merge(request()->query(), ['availability' => request('availability') === 'instock' ? null : 'instock', 'page' => null])) }}"
                        class="inline-flex items-center cursor-pointer"
                    >
                        <span class="inline-flex items-center justify-center h-4 w-4 rounded border {{ request('availability') === 'instock' ? 'bg-primary border-primary text-white' : 'border-brandOutline-soft bg-white' }} transition-colors">
                            @if(request('availability') === 'instock')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @endif
                        </span>
                        <span class="ms-2.5 text-xs font-semibold text-brandText-muted hover:text-brandText transition-colors select-none">
                            Hanya Stok Tersedia
                        </span>
                    </a>
                </div>

                {{-- Clear Filters Button --}}
                @php
                    $activeFiltersCount = count(array_filter(request()->only(['search', 'category', 'availability'])));
                @endphp
                @if($activeFiltersCount > 0)
                    <div class="pt-2">
                        <a
                            href="{{ route('catalogue.index') }}"
                            class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border-2 border-red-200 bg-transparent text-danger hover:bg-red-50 hover:border-red-300 w-full text-center text-xs justify-center py-1.5 px-3"
                        >
                            Hapus Semua Filter ({{ $activeFiltersCount }})
                        </a>
                    </div>
                @endif
            </div>
        </aside>

        {{-- 2. Mobile Filter Header Bar & Drawer --}}
        <div x-data="{ isMobileFilterOpen: false }" class="w-full lg:hidden space-y-4">
            <div class="flex flex-wrap gap-3 items-center justify-between bg-white border border-brandOutline-soft/25 p-4 rounded-xl mb-2">
                <button
                    type="button"
                    @click="isMobileFilterOpen = !isMobileFilterOpen"
                    class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border-2 border-brandOutline-soft bg-transparent text-primary hover:bg-brandSurface-low hover:border-primary focus:ring-primary-soft text-xs py-1.5 px-3 flex items-center gap-2"
                >
                    <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter {{ $activeFiltersCount > 0 ? "({$activeFiltersCount})" : '' }}
                </button>
                
                <p class="text-xs text-brandText-muted font-medium">
                    Menampilkan {{ $products->total() > 0 ? $products->firstItem().'-'.$products->lastItem().' dari '.$products->total() : '0' }} Rangkaian
                </p>
            </div>

            {{-- Mobile Filter Drawer Content --}}
            <div
                x-show="isMobileFilterOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="bg-white border border-brandOutline-soft/25 p-5 rounded-xl space-y-4 shadow-md"
                style="display: none;"
            >
                {{-- Mobile Search --}}
                <form action="{{ route('catalogue.index') }}" method="GET" class="flex gap-2">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('availability'))
                        <input type="hidden" name="availability" value="{{ request('availability') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari nama bunga..."
                        value="{{ request('search') }}"
                        class="w-full pl-3 pr-3 py-1.5 text-xs border border-brandOutline rounded-lg bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                    />
                    <button type="submit" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm text-xs py-1.5 px-3">
                        Cari
                    </button>
                </form>

                {{-- Mobile Category Select --}}
                <div>
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-2">Kategori</h4>
                    <div class="flex flex-wrap gap-1.5">
                        <a
                            href="{{ route('catalogue.index', array_merge(request()->query(), ['category' => null, 'page' => null])) }}"
                            class="text-xs py-1.5 px-3 rounded-full transition-all {{ !request('category') ? 'bg-primary text-white font-bold' : 'bg-brandSurface-low text-brandText-muted hover:bg-brandSurface-high' }}"
                        >
                            Semua
                        </a>
                        @foreach($categories as $cat)
                            <a
                                href="{{ route('catalogue.index', array_merge(request()->query(), ['category' => $cat->id, 'page' => null])) }}"
                                class="text-xs py-1.5 px-3 rounded-full transition-all {{ request('category') == $cat->id ? 'bg-primary text-white font-bold' : 'bg-brandSurface-low text-brandText-muted hover:bg-brandSurface-high' }}"
                            >
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile Availability Check & Clear --}}
                <div class="flex items-center justify-between pt-1">
                    <a
                        href="{{ route('catalogue.index', array_merge(request()->query(), ['availability' => request('availability') === 'instock' ? null : 'instock', 'page' => null])) }}"
                        class="inline-flex items-center cursor-pointer"
                    >
                        <span class="inline-flex items-center justify-center h-4 w-4 rounded border {{ request('availability') === 'instock' ? 'bg-primary border-primary text-white' : 'border-brandOutline-soft bg-white' }} transition-colors">
                            @if(request('availability') === 'instock')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @endif
                        </span>
                        <span class="ms-2 text-xs font-semibold text-brandText-muted">Hanya Stok Tersedia</span>
                    </a>

                    @if($activeFiltersCount > 0)
                        <a
                            href="{{ route('catalogue.index') }}"
                            class="text-xs font-bold text-danger hover:underline"
                        >
                            Hapus Semua Filter
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. Catalog Products Area --}}
        <div class="flex-grow w-full space-y-8 reveal" data-delay="100">
            {{-- Toolbar: Sorting & Count --}}
            <div class="hidden lg:flex items-center justify-between border-b border-brandSurface-high/60 pb-4">
                <p class="text-xs text-brandText-muted font-bold tracking-wide">
                    Menampilkan {{ $products->total() > 0 ? $products->firstItem().'-'.$products->lastItem().' dari '.$products->total() : '0' }} Rangkaian Bunga Segar
                </p>

                <div class="flex items-center gap-2 select-none">
                    <span class="text-xs font-bold text-brandText-muted">Urutkan:</span>
                    <div x-data="{ sort: '{{ request('sort', 'latest') }}' }" class="relative">
                        <select
                            x-model="sort"
                            @change="window.location.href = '{{ route('catalogue.index', array_merge(request()->query(), ['sort' => '_SORT_'])) }}'.replace('_SORT_', sort)"
                            class="text-xs font-semibold text-brandText border border-brandOutline-soft/70 rounded-lg bg-white py-1 pl-2.5 pr-8 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary cursor-pointer transition-all"
                        >
                            <option value="latest">Terbaru</option>
                            <option value="price_asc">Harga: Rendah ke Tinggi</option>
                            <option value="price_desc">Harga: Tinggi ke Rendah</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Mobile Sorting bar (only visible on mobile) --}}
            <div class="lg:hidden flex items-center justify-between border-b border-brandSurface-high/60 pb-3 px-1">
                <span class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">
                    Urutan Katalog
                </span>
                <div x-data="{ sort: '{{ request('sort', 'latest') }}' }">
                    <select
                        x-model="sort"
                        @change="window.location.href = '{{ route('catalogue.index', array_merge(request()->query(), ['sort' => '_SORT_'])) }}'.replace('_SORT_', sort)"
                        class="text-xs font-bold text-primary border-none bg-transparent py-0.5 pl-1 pr-6 focus:outline-none focus:ring-0 cursor-pointer"
                    >
                        <option value="latest">Terbaru</option>
                        <option value="price_asc">Harga Terendah</option>
                        <option value="price_desc">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            {{-- Product Cards Grid --}}
            @if($products->isEmpty())
                <div class="py-12">
                    <div class="text-center py-12 bg-white border border-brandOutline-soft/15 rounded-2xl p-8 max-w-md mx-auto">
                        <div class="w-16 h-16 bg-primary-soft/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="font-serif text-lg font-bold text-primary mb-2">Rangkaian Bunga Tidak Ditemukan</h3>
                        <p class="text-xs text-brandText-muted leading-relaxed">
                            Maaf, kami tidak menemukan rangkaian bunga yang cocok dengan kata kunci pencarian atau kategori filter Anda. Silakan coba filter lainnya.
                        </p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            @endif

            {{-- Pagination Links --}}
            <div class="pt-8 border-t border-brandSurface-high/55">
                <x-pagination :paginator="$products" />
            </div>
        </div>
    </div>
</div>
@endsection
