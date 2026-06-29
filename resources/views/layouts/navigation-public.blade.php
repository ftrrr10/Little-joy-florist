<nav x-data="{ isMobileMenuOpen: false, isProfileDropdownOpen: false }" class="sticky top-0 z-40 w-full bg-white/90 backdrop-blur-md border-b border-brandSurface-high/30 shadow-sm font-sans">
    <div class="w-full px-6 lg:px-12">
        <div class="flex justify-between h-16">
            {{-- Left: Brand Logo & Links --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                    <x-app-logo variant="dark" className="h-8 w-auto" />
                </a>
                
                <div class="hidden lg:flex lg:items-center lg:space-x-8">
                    @php
                        $navLinks = [
                            ['label' => 'Beranda', 'route' => 'home'],
                            ['label' => 'Koleksi', 'route' => 'catalogue.index'],
                            ['label' => 'Tentang Kami', 'route' => 'about'],
                        ];
                    @endphp

                    @foreach($navLinks as $link)
                        @php
                            $isActive = request()->routeIs($link['route']) || ($link['route'] !== 'home' && str_starts_with(request()->url(), route($link['route'])));
                        @endphp
                        <a
                            href="{{ route($link['route']) }}"
                            class="text-xs font-bold uppercase tracking-wider transition-all duration-200 hover:text-primary {{ $isActive ? 'text-primary border-b-2 border-primary pb-1' : 'text-brandText-muted' }}"
                        >
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right Side: Search, Cart, Profile --}}
            <div class="hidden lg:flex lg:items-center lg:gap-5">
                {{-- Search Input --}}
                <form action="{{ route('catalogue.index') }}" method="GET" class="relative w-40 lg:w-48">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari bunga..."
                        class="w-full pl-8 pr-3 py-1.5 text-xs border border-brandOutline rounded-full bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                    />
                    <button type="submit" class="absolute left-2.5 top-2.5 w-3.5 h-3.5 text-brandText-muted focus:outline-none">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>

                {{-- Cart Icon --}}
                <a
                    href="{{ auth()->check() ? route('cart.index') : route('login') }}"
                    class="p-1.5 text-brandText-muted hover:text-primary transition-colors rounded-full hover:bg-brandSurface-low relative"
                    aria-label="Keranjang Belanja"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                        />
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-secondary rounded-full">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- Divider --}}
                <span class="h-5 w-px bg-brandSurface-high" aria-hidden="true"></span>

                {{-- Auth / Profile Area --}}
                @auth
                    <div class="relative">
                        <button
                            type="button"
                            @click="isProfileDropdownOpen = !isProfileDropdownOpen"
                            @click.away="isProfileDropdownOpen = false"
                            class="flex items-center gap-1 p-0.5 rounded-full hover:ring-2 hover:ring-primary/40 focus:outline-none transition-all"
                        >
                            <div class="h-8 w-8 rounded-full bg-primary-soft text-primary-dark font-bold text-xs flex items-center justify-center ring-2 ring-primary/70 p-0.5">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </button>

                        {{-- Profile Dropdown Menu --}}
                        <div
                            x-show="isProfileDropdownOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white border border-brandOutline-soft/35 rounded-xl shadow-xl py-1.5 z-20"
                            style="display: none;"
                        >
                            <div class="px-4 py-2 border-b border-brandSurface-low">
                                <p class="text-xs font-semibold text-brandText-muted">Masuk sebagai</p>
                                <p class="text-sm font-bold text-brandText truncate">{{ auth()->user()->name }}</p>
                            </div>

                            {{-- Role-specific dashboard access --}}
                            @if(auth()->user()->isAdmin() || auth()->user()->isOperator())
                                <a
                                    href="{{ route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'operator.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-primary font-semibold hover:bg-brandSurface-low transition-colors"
                                >
                                    Dashboard Kerja
                                </a>
                            @endif

                            <a
                                href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-brandText-muted hover:bg-brandSurface-low hover:text-brandText transition-colors"
                            >
                                Profil Saya
                            </a>
                            <a
                                href="{{ route('customer.orders.index') }}"
                                class="block px-4 py-2 text-sm text-brandText-muted hover:bg-brandSurface-low hover:text-brandText transition-colors"
                            >
                                Riwayat Pesanan
                            </a>

                            <div class="border-t border-brandSurface-low my-1" />

                            <a
                                href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="w-full text-left block px-4 py-2 text-sm text-danger hover:bg-red-50 transition-colors"
                            >
                                Keluar
                            </a>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center font-sans font-bold text-xs text-brandText hover:text-primary transition-all duration-200 py-1.5 px-3">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center font-sans font-bold text-xs bg-primary text-white hover:bg-primary-dark transition-all duration-200 shadow-sm py-1.5 px-4 rounded-full">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Mobile menu button --}}
            <div class="flex items-center lg:hidden">
                <button
                    type="button"
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-brandText-muted hover:text-primary hover:bg-brandSurface-low focus:outline-none transition-colors"
                    aria-label="Buka Menu"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Drawer --}}
    <div
        x-show="isMobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden border-t border-brandSurface-high/50 bg-white"
        style="display: none;"
    >
        <div class="px-2 pt-2 pb-3 space-y-1">
            @foreach($navLinks as $link)
                @php
                    $isActive = request()->routeIs($link['route']) || ($link['route'] !== 'home' && str_starts_with(request()->url(), route($link['route'])));
                @endphp
                <a
                    href="{{ route($link['route']) }}"
                    class="block px-3 py-2 rounded-lg text-base font-medium {{ $isActive ? 'bg-primary-soft/30 text-primary font-semibold' : 'text-brandText-muted hover:bg-brandSurface-low hover:text-primary' }}"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
        <div class="pt-4 pb-4 border-t border-brandSurface-low">
            @auth
                <div class="px-4 space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-primary-soft text-primary-dark font-bold text-sm flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-brandText">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-brandText-muted">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        @if(auth()->user()->isAdmin() || auth()->user()->isOperator())
                            <a
                                href="{{ route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'operator.dashboard') }}"
                                class="block py-2 text-base font-medium text-primary"
                            >
                                Dashboard Kerja
                            </a>
                        @endif
                        <a
                            href="{{ route('profile.edit') }}"
                            class="block py-2 text-base font-medium text-brandText-muted hover:text-primary"
                        >
                            Profil Saya
                        </a>
                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="block py-2 text-base font-medium text-brandText-muted hover:text-primary"
                        >
                            Riwayat Pesanan
                        </a>
                        <a
                            href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block py-2 text-base font-medium text-danger"
                        >
                            Keluar
                        </a>
                    </div>
                </div>
            @else
                <div class="px-4 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center font-sans font-bold border border-brandOutline bg-transparent text-brandText hover:bg-brandSurface-low transition-all duration-200 py-2.5 text-xs rounded-full">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center font-sans font-bold bg-primary text-white hover:bg-primary-dark transition-all duration-200 shadow-sm py-2.5 text-xs rounded-full">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

@auth
    {{-- Global Logout Form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endauth
