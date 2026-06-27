<footer class="bg-brandSurface-low border-t border-brandSurface-high/50 font-sans mt-auto">
    <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8">
            {{-- Brand Info --}}
            <div class="flex flex-col gap-4">
                <x-app-logo variant="dark" />
                <p class="text-sm text-brandText-muted leading-relaxed max-w-sm">
                    Menghadirkan keindahan rangkaian bunga segar premium untuk melengkapi momen kebahagiaan dan kehangatan Anda di Jakarta.
                </p>
            </div>

            {{-- Quick Links --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-xs font-bold text-primary tracking-[0.15em] uppercase">
                    Navigasi Cepat
                </h4>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('catalogue.index') }}" class="text-sm text-brandText-muted hover:text-primary transition-colors">
                            Katalog Bunga
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="text-sm text-brandText-muted hover:text-primary transition-colors">
                            Tentang Kami
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-sm text-brandText-muted hover:text-primary transition-colors">
                            Hubungi Kami
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Contact & Hours --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-xs font-bold text-primary tracking-[0.15em] uppercase">
                    Informasi Toko
                </h4>
                <div class="space-y-3 text-sm text-brandText-muted">
                    <p class="flex items-start gap-2.5">
                        <svg class="h-5 w-5 text-primary flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Ruko Little Joy, Jl. Kemang Raya No. 12, Jakarta Selatan</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">
                            +62 812-3456-7890 (WhatsApp)
                        </a>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Setiap Hari: 08.00 - 20.00 WIB</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-brandSurface-high/50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs text-brandText-muted/70">
                &copy; {{ date('Y') }} Little Joy Jakarta. Semua Hak Cipta Dilindungi.
            </p>
            <p class="text-xs text-brandText-muted/50 font-medium">
                Designed for Little Joy Management System.
            </p>
        </div>
    </div>
</footer>
