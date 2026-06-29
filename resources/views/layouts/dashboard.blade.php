@extends('layouts.app')

@section('body')
@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
    $currentRoute = request()->route()->getName();
@endphp

<div class="min-h-screen flex bg-brandBackground">
    {{-- Sidebar --}}
    <aside class="print:hidden w-64 bg-[#022C22] text-white flex flex-col h-screen sticky top-0 border-r border-[#022C22] select-none font-sans z-20">
        {{-- Sidebar Header --}}
        <div class="h-16 flex items-center px-6 border-b border-white/10">
            <x-app-logo variant="light" class="h-7 w-auto" />
        </div>

        {{-- Navigation Menus --}}
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-7">
            {{-- Operasional Section --}}
            <div class="space-y-2">
                <h5 class="text-[10px] font-bold tracking-[0.2em] text-white/40 uppercase px-4">
                    Operasional
                </h5>
                <div class="space-y-1">
                    {{-- Dashboard Link --}}
                    @php
                        $dashRoute = $isAdmin ? 'admin.dashboard' : 'operator.dashboard';
                        $isDashActive = $currentRoute === $dashRoute;
                    @endphp
                    <a
                        href="{{ route($dashRoute) }}"
                        class="relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 {{ $isDashActive ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                    >
                        @if($isDashActive)
                            <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
                        @endif
                        <span class="transition-colors {{ $isDashActive ? 'text-[#10B981]' : 'text-white/40' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                            </svg>
                        </span>
                        <span>Dashboard</span>
                    </a>

                    {{-- Orders Link --}}
                    @php
                        $isOrdersActive = str_starts_with($currentRoute, 'operator.orders') || str_starts_with($currentRoute, 'admin.orders');
                    @endphp
                    <a
                        href="{{ route('operator.orders.index') }}"
                        class="relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 {{ $isOrdersActive ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                    >
                        @if($isOrdersActive)
                            <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
                        @endif
                        <span class="transition-colors {{ $isOrdersActive ? 'text-[#10B981]' : 'text-white/40' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </span>
                        <span>Pesanan</span>
                    </a>

                    {{-- Stock Link --}}
                    @php
                        $isStockActive = str_starts_with($currentRoute, 'operator.stock');
                    @endphp
                    <a
                        href="{{ route('operator.stock.index') }}"
                        class="relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 {{ $isStockActive ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                    >
                        @if($isStockActive)
                            <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
                        @endif
                        <span class="transition-colors {{ $isStockActive ? 'text-[#10B981]' : 'text-white/40' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </span>
                        <span>Produk & Stok</span>
                    </a>
                </div>
            </div>

            {{-- Administrator Section --}}
            @if($isAdmin)
                <div class="space-y-2">
                    <h5 class="text-[10px] font-bold tracking-[0.2em] text-white/40 uppercase px-4">
                        Administrasi
                    </h5>
                    <div class="space-y-1">
                        {{-- Categories Link --}}
                        @php
                            $isCatActive = str_starts_with($currentRoute, 'admin.categories');
                        @endphp
                        <a
                            href="{{ route('admin.categories.index') }}"
                            class="relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 {{ $isCatActive ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                        >
                            @if($isCatActive)
                                <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
                            @endif
                            <span class="transition-colors {{ $isCatActive ? 'text-[#10B981]' : 'text-white/40' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </span>
                            <span>Kategori</span>
                        </a>

                        {{-- Products Link --}}
                        @php
                            $isProductsActive = str_starts_with($currentRoute, 'admin.products');
                        @endphp
                        <a
                            href="{{ route('admin.products.index') }}"
                            class="relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 {{ $isProductsActive ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                        >
                            @if($isProductsActive)
                                <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
                            @endif
                            <span class="transition-colors {{ $isProductsActive ? 'text-[#10B981]' : 'text-white/40' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </span>
                            <span>Daftar Produk</span>
                        </a>

                        {{-- Operators Link --}}
                        @php
                            $isOpActive = str_starts_with($currentRoute, 'admin.operators');
                        @endphp
                        <a
                            href="{{ route('admin.operators.index') }}"
                            class="relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 {{ $isOpActive ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                        >
                            @if($isOpActive)
                                <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
                            @endif
                            <span class="transition-colors {{ $isOpActive ? 'text-[#10B981]' : 'text-white/40' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <span>Data Operator</span>
                        </a>

                        {{-- Customers Link --}}
                        @php
                            $isCustActive = str_starts_with($currentRoute, 'admin.customers');
                        @endphp
                        <a
                            href="{{ route('admin.customers.index') }}"
                            class="relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 {{ $isCustActive ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                        >
                            @if($isCustActive)
                                <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
                            @endif
                            <span class="transition-colors {{ $isCustActive ? 'text-[#10B981]' : 'text-white/40' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <span>Data Pelanggan</span>
                        </a>

                        {{-- Reports Link --}}
                        @php
                            $isRepActive = str_starts_with($currentRoute, 'admin.reports');
                        @endphp
                        <a
                            href="{{ route('admin.reports.index') }}"
                            class="relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 {{ $isRepActive ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                        >
                            @if($isRepActive)
                                <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
                            @endif
                            <span class="transition-colors {{ $isRepActive ? 'text-[#10B981]' : 'text-white/40' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </span>
                            <span>Laporan Keuangan</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Action Button --}}
        <div class="px-4 pb-4">
            <a
                href="{{ $isAdmin ? route('admin.products.create') : route('catalogue.index') }}"
                class="w-full py-2.5 bg-[#F7F4EB] hover:bg-[#EFECE2] text-[#022C22] text-xs font-bold uppercase tracking-wider rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm font-semibold"
            >
                <svg class="w-4 h-4 text-[#022C22]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                {{ $isAdmin ? 'Tambah Produk' : 'Transaksi Baru' }}
            </a>
        </div>

    </aside>

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto print:h-auto print:overflow-visible">
        {{-- Topbar --}}
        <header class="print:hidden h-16 bg-white border-b border-brandSurface-high/30 flex items-center justify-between px-8 sticky top-0 z-10 font-sans shadow-sm">
            {{-- Left Side: Page Title --}}
            <div class="flex items-center gap-4">
                <h1 class="font-serif text-lg font-bold text-primary tracking-wide">
                    @yield('title')
                </h1>
            </div>

            {{-- Middle: Search Bar (Pure visual match without React) --}}
            <div class="relative w-72 max-w-xs md:max-w-sm hidden sm:block">
                <input
                    type="text"
                    placeholder="Cari transaksi, produk..."
                    class="w-full pl-9 pr-4 py-1.5 text-xs border border-brandOutline rounded-xl bg-cream-light/5 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
                />
                <svg class="absolute left-3 top-2.5 w-3.5 h-3.5 text-brandText-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            {{-- Right Side: User Menu dropdown & Home shortcut --}}
            <div class="flex items-center gap-4">
                {{-- Home shortcut --}}
                <a
                    href="{{ route('home') }}"
                    class="p-2 text-brandText-muted hover:text-primary transition-colors rounded-lg hover:bg-brandSurface-low"
                    title="Lihat Website Publik"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>

                {{-- Note: we deleted Bell and Settings placeholder icons as requested by user --}}

                {{-- Divider --}}
                <span class="h-5 w-px bg-brandSurface-high" aria-hidden="true"></span>

                {{-- User Dropdown Profile Card with Alpine --}}
                <div class="relative" x-data="{ open: false }">
                    <button
                        type="button"
                        @click="open = !open"
                        class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-brandSurface-low transition-all focus:outline-none"
                    >
                        <div class="text-right hidden md:block">
                            <p class="text-xs font-bold text-brandText">{{ $user->name ?? '' }}</p>
                            <p class="text-[9px] font-bold text-brandText-muted tracking-wider uppercase">
                                {{ $isAdmin ? 'STORE MANAGER' : 'FLORIST OPERATOR' }}
                            </p>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-primary-soft text-primary-dark font-bold text-xs flex items-center justify-center ring-2 ring-[#10B981] p-0.5">
                            {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                        </div>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div 
                        x-show="open"
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        style="display: none;"
                        class="absolute right-0 mt-2 w-48 bg-white border border-brandOutline-soft/35 rounded-xl shadow-xl py-1.5 z-30 font-sans"
                    >
                        <div class="px-4 py-2 border-b border-brandSurface-low">
                            <p class="text-[10px] font-semibold text-brandText-muted">Masuk sebagai</p>
                            <p class="text-xs font-bold text-brandText truncate">{{ $user->email ?? '' }}</p>
                        </div>

                        <a
                            href="{{ route('profile.edit') }}"
                            class="px-4 py-2 text-xs text-[#064E3B] hover:bg-brandSurface-low transition-colors flex items-center gap-2 font-semibold"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profil Saya</span>
                        </a>

                        <div class="border-t border-brandSurface-low my-1"></div>

                        <a
                            href="#"
                            onclick="event.preventDefault(); document.getElementById('dashboard-logout-form').submit();"
                            class="px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2 font-semibold"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Body Content --}}
        <main class="flex-1 p-8 print:p-0">
            @yield('content')
        </main>
    </div>
</div>

{{-- Global dashboard logout form --}}
<form id="dashboard-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
@endsection
