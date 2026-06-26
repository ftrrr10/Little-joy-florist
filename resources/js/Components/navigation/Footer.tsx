import React from 'react';
import { Link } from '@inertiajs/react';
import AppLogo from '../common/AppLogo';

export default function Footer() {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="bg-brandSurface-low border-t border-brandSurface-high/50 font-sans mt-auto">
            <div className="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8">
                    {/* Brand Info */}
                    <div className="flex flex-col gap-4">
                        <AppLogo variant="dark" />
                        <p className="text-sm text-brandText-muted leading-relaxed max-w-sm">
                            Menghadirkan keindahan rangkaian bunga segar premium untuk melengkapi momen kebahagiaan dan kehangatan Anda di Jakarta.
                        </p>
                    </div>

                    {/* Quick Links */}
                    <div className="flex flex-col gap-4">
                        <h4 className="text-xs font-bold text-primary tracking-[0.15em] uppercase">
                            Navigasi Cepat
                        </h4>
                        <ul className="space-y-2.5">
                            <li>
                                <Link href={route('catalogue.index')} className="text-sm text-brandText-muted hover:text-primary transition-colors">
                                    Katalog Bunga
                                </Link>
                            </li>
                            <li>
                                <Link href={route('about')} className="text-sm text-brandText-muted hover:text-primary transition-colors">
                                    Tentang Kami
                                </Link>
                            </li>
                            <li>
                                <Link href={route('contact')} className="text-sm text-brandText-muted hover:text-primary transition-colors">
                                    Hubungi Kami
                                </Link>
                            </li>
                        </ul>
                    </div>

                    {/* Contact & Hours */}
                    <div className="flex flex-col gap-4">
                        <h4 className="text-xs font-bold text-primary tracking-[0.15em] uppercase">
                            Informasi Toko
                        </h4>
                        <div className="space-y-3 text-sm text-brandText-muted">
                            <p className="flex items-start gap-2.5">
                                <svg className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Ruko Little Joy, Jl. Kemang Raya No. 12, Jakarta Selatan</span>
                            </p>
                            <p className="flex items-center gap-2.5">
                                <svg className="h-5 w-5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" className="hover:text-primary transition-colors">
                                    +62 812-3456-7890 (WhatsApp)
                                </a>
                            </p>
                            <p className="flex items-center gap-2.5">
                                <svg className="h-5 w-5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Setiap Hari: 08.00 - 20.00 WIB</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div className="mt-12 pt-8 border-t border-brandSurface-high/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p className="text-xs text-brandText-muted/70">
                        &copy; {currentYear} Little Joy Jakarta. Semua Hak Cipta Dilindungi.
                    </p>
                    <p className="text-xs text-brandText-muted/50 font-medium">
                        Designed for Little Joy Management System.
                    </p>
                </div>
            </div>
        </footer>
    );
}
