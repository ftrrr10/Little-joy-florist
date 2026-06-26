import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import AppLogo from '../common/AppLogo';
import Button from '../common/Button';

export default function PublicNavbar() {
    const { auth } = usePage<PageProps>().props;
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [isProfileDropdownOpen, setIsProfileDropdownOpen] = useState(false);

    // Mock cart count - will connect to database cart in Phase 3
    const cartCount = 0;

    const navLinks = [
        { label: 'Beranda', href: route('home') },
        { label: 'Koleksi', href: route('catalogue.index') },
        { label: 'Tentang Kami', href: route('about') },
    ];

    return (
        <nav className="sticky top-0 z-40 w-full bg-white/90 backdrop-blur-md border-b border-brandSurface-high/30 shadow-sm font-sans">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between h-16">
                    {/* Left: Brand Logo & Links */}
                    <div className="flex items-center gap-8">
                        <Link href={route('home')} className="flex-shrink-0 flex items-center">
                            <AppLogo variant="dark" className="h-8 w-auto" />
                        </Link>
                        
                        <div className="hidden md:flex md:items-center md:space-x-6">
                            {navLinks.map((link) => (
                                <Link
                                    key={link.label}
                                    href={link.href}
                                    className={`text-xs font-bold uppercase tracking-wider transition-all duration-200 hover:text-primary ${
                                        usePage().url === link.href || (link.href !== '/' && usePage().url.startsWith(link.href))
                                            ? 'text-primary border-b-2 border-primary pb-1'
                                            : 'text-brandText-muted'
                                    }`}
                                >
                                    {link.label}
                                </Link>
                            ))}
                        </div>
                    </div>

                    {/* Right Side: Search, Cart, Profile */}
                    <div className="hidden md:flex md:items-center md:gap-4">
                        {/* Search Input */}
                        <div className="relative w-40 lg:w-48">
                            <input
                                type="text"
                                placeholder="Cari bunga..."
                                className="w-full pl-8 pr-3 py-1.5 text-xs border border-brandOutline rounded-full bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            />
                            <svg className="absolute left-2.5 top-2.5 w-3.5 h-3.5 text-brandText-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        {/* Cart Icon */}
                        <Link
                            href={auth.user ? route('cart.index') : route('login')}
                            className="p-1.5 text-brandText-muted hover:text-primary transition-colors rounded-full hover:bg-brandSurface-low relative"
                            aria-label="Keranjang Belanja"
                        >
                            <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                />
                            </svg>
                            {cartCount > 0 && (
                                <span className="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-secondary rounded-full">
                                    {cartCount}
                                </span>
                            )}
                        </Link>

                        {/* Divider */}
                        <span className="h-5 w-px bg-brandSurface-high" aria-hidden="true" />

                        {/* Auth / Profile Area */}
                        {auth.user ? (
                            <div className="relative">
                                <button
                                    type="button"
                                    onClick={() => setIsProfileDropdownOpen(!isProfileDropdownOpen)}
                                    className="flex items-center gap-1 p-0.5 rounded-full hover:ring-2 hover:ring-primary/40 focus:outline-none transition-all"
                                >
                                    <div className="h-8 w-8 rounded-full bg-primary-soft text-primary-dark font-bold text-xs flex items-center justify-center ring-2 ring-primary/70 p-0.5">
                                        {auth.user.name.charAt(0).toUpperCase()}
                                    </div>
                                </button>

                                {/* Profile Dropdown Menu */}
                                {isProfileDropdownOpen && (
                                    <>
                                        <div
                                            className="fixed inset-0 z-10"
                                            onClick={() => setIsProfileDropdownOpen(false)}
                                        />
                                        <div className="absolute right-0 mt-2 w-48 bg-white border border-brandOutline-soft/35 rounded-xl shadow-xl py-1.5 z-20 animate-in fade-in slide-in-from-top-2 duration-150">
                                            <div className="px-4 py-2 border-b border-brandSurface-low">
                                                <p className="text-xs font-semibold text-brandText-muted">Logged in as</p>
                                                <p className="text-sm font-bold text-brandText truncate">{auth.user.name}</p>
                                            </div>

                                            {/* Role-specific dashboard access */}
                                            {(auth.user.role === 'admin' || auth.user.role === 'operator') && (
                                                <Link
                                                    href={route(auth.user.role === 'admin' ? 'admin.dashboard' : 'operator.dashboard')}
                                                    className="block px-4 py-2 text-sm text-primary font-semibold hover:bg-brandSurface-low transition-colors"
                                                    onClick={() => setIsProfileDropdownOpen(false)}
                                                >
                                                    Dashboard Kerja
                                                </Link>
                                            )}

                                            <Link
                                                href={route('customer.profile')}
                                                className="block px-4 py-2 text-sm text-brandText-muted hover:bg-brandSurface-low hover:text-brandText transition-colors"
                                                onClick={() => setIsProfileDropdownOpen(false)}
                                            >
                                                Profil Saya
                                            </Link>
                                            <Link
                                                href={route('customer.orders.index')}
                                                className="block px-4 py-2 text-sm text-brandText-muted hover:bg-brandSurface-low hover:text-brandText transition-colors"
                                                onClick={() => setIsProfileDropdownOpen(false)}
                                            >
                                                Riwayat Pesanan
                                            </Link>

                                            <div className="border-t border-brandSurface-low my-1" />

                                            <Link
                                                href={route('logout')}
                                                method="post"
                                                as="button"
                                                className="w-full text-left block px-4 py-2 text-sm text-danger hover:bg-red-50 transition-colors animate-none"
                                                onClick={() => setIsProfileDropdownOpen(false)}
                                            >
                                                Keluar
                                            </Link>
                                        </div>
                                    </>
                                )}
                            </div>
                        ) : (
                            <div className="flex items-center gap-2">
                                <Link href={route('login')}>
                                    <Button variant="ghost" size="sm" className="text-xs py-1.5 px-3">
                                        Masuk
                                    </Button>
                                </Link>
                                <Link href={route('register')}>
                                    <Button variant="primary" size="sm" className="text-xs py-1.5 px-3 rounded-xl shadow-sm">
                                        Daftar
                                    </Button>
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Mobile menu button */}
                    <div className="flex items-center md:hidden">
                        <button
                            type="button"
                            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                            className="inline-flex items-center justify-center p-2 rounded-lg text-brandText-muted hover:text-primary hover:bg-brandSurface-low focus:outline-none transition-colors"
                            aria-label="Buka Menu"
                        >
                            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                {isMobileMenuOpen ? (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                ) : (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                )}
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {/* Mobile Menu Drawer */}
            {isMobileMenuOpen && (
                <div className="md:hidden border-t border-brandSurface-high/50 bg-white animate-in slide-in-from-top duration-200">
                    <div className="px-2 pt-2 pb-3 space-y-1">
                        {navLinks.map((link) => (
                            <Link
                                key={link.label}
                                href={link.href}
                                className={`block px-3 py-2 rounded-lg text-base font-medium ${
                                    usePage().url === link.href
                                        ? 'bg-primary-soft/30 text-primary font-semibold'
                                        : 'text-brandText-muted hover:bg-brandSurface-low hover:text-primary'
                                }`}
                                onClick={() => setIsMobileMenuOpen(false)}
                            >
                                {link.label}
                            </Link>
                        ))}
                    </div>
                    <div className="pt-4 pb-4 border-t border-brandSurface-low">
                        {auth.user ? (
                            <div className="px-4 space-y-2">
                                <div className="flex items-center gap-3">
                                    <div className="h-9 w-9 rounded-full bg-primary-soft text-primary-dark font-bold text-sm flex items-center justify-center">
                                        {auth.user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <p className="text-sm font-bold text-brandText">{auth.user.name}</p>
                                        <p className="text-xs text-brandText-muted">{auth.user.email}</p>
                                    </div>
                                </div>
                                <div className="mt-3 space-y-1">
                                    {(auth.user.role === 'admin' || auth.user.role === 'operator') && (
                                        <Link
                                            href={route(auth.user.role === 'admin' ? 'admin.dashboard' : 'operator.dashboard')}
                                            className="block py-2 text-base font-medium text-primary"
                                            onClick={() => setIsMobileMenuOpen(false)}
                                        >
                                            Dashboard Kerja
                                        </Link>
                                    )}
                                    <Link
                                        href={route('customer.profile')}
                                        className="block py-2 text-base font-medium text-brandText-muted hover:text-primary"
                                        onClick={() => setIsMobileMenuOpen(false)}
                                    >
                                        Profil Saya
                                    </Link>
                                    <Link
                                        href={route('customer.orders.index')}
                                        className="block py-2 text-base font-medium text-brandText-muted hover:text-primary"
                                        onClick={() => setIsMobileMenuOpen(false)}
                                    >
                                        Riwayat Pesanan
                                    </Link>
                                    <Link
                                        href={route('logout')}
                                        method="post"
                                        as="button"
                                        className="w-full text-left block py-2 text-base font-medium text-danger"
                                        onClick={() => setIsMobileMenuOpen(false)}
                                    >
                                        Keluar
                                    </Link>
                                </div>
                            </div>
                        ) : (
                            <div className="px-4 flex flex-col gap-2">
                                <Link href={route('login')} className="w-full" onClick={() => setIsMobileMenuOpen(false)}>
                                    <Button variant="outline" className="w-full">
                                        Masuk
                                    </Button>
                                </Link>
                                <Link href={route('register')} className="w-full" onClick={() => setIsMobileMenuOpen(false)}>
                                    <Button variant="primary" className="w-full">
                                        Daftar
                                    </Button>
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}
