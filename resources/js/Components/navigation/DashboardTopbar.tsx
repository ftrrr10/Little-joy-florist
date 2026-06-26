import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Search, Bell, Settings, LogOut, User as UserIcon } from 'lucide-react';

interface DashboardTopbarProps {
    title?: string;
}

export default function DashboardTopbar({ title }: DashboardTopbarProps) {
    const { auth } = usePage<PageProps>().props;
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);

    if (!auth.user) return null;

    const getFormattedRole = (role: string) => {
        if (role === 'admin') return 'STORE MANAGER';
        if (role === 'operator') return 'FLORIST OPERATOR';
        return role.toUpperCase();
    };

    return (
        <header className="h-16 bg-white border-b border-brandSurface-high/30 flex items-center justify-between px-8 sticky top-0 z-30 font-sans shadow-sm">
            {/* Left Side: Page Title */}
            <div className="flex items-center gap-4">
                <h1 className="font-serif text-lg font-bold text-primary tracking-wide">
                    {title || 'Dashboard'}
                </h1>
            </div>

            {/* Middle: Search Bar from Mockup */}
            <div className="relative w-72 max-w-xs md:max-w-sm hidden sm:block">
                <input
                    type="text"
                    placeholder="Search orders, flowers..."
                    className="w-full pl-9 pr-4 py-1.5 text-xs border border-brandOutline rounded-xl bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                />
                <Search className="absolute left-3 top-2.5 w-3.5 h-3.5 text-brandText-muted" />
            </div>

            {/* Right Side: Notification Bell, Settings Cog, Profile Avatar Card */}
            <div className="flex items-center gap-4">
                {/* Home shortcut */}
                <Link
                    href={route('home')}
                    className="p-2 text-brandText-muted hover:text-primary transition-colors rounded-lg hover:bg-brandSurface-low"
                    title="Lihat Website Publik"
                >
                    <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </Link>

                {/* Notification Bell */}
                <button className="p-1.5 text-brandText-muted hover:text-primary transition-colors rounded-full relative focus:outline-none">
                    <Bell className="h-5 w-5" />
                    <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                {/* Settings Cog */}
                <button className="p-1.5 text-brandText-muted hover:text-primary transition-colors rounded-full focus:outline-none">
                    <Settings className="h-5 w-5" />
                </button>

                {/* Divider */}
                <span className="h-5 w-px bg-brandSurface-high" aria-hidden="true" />

                {/* User Dropdown Profile Card */}
                <div className="relative">
                    <button
                        type="button"
                        onClick={() => setIsDropdownOpen(!isDropdownOpen)}
                        className="flex items-center gap-2.5 p-1 rounded-xl hover:bg-brandSurface-low transition-all focus:outline-none"
                    >
                        {/* Styled User Profile Card from Mockup */}
                        <div className="text-right hidden md:block">
                            <p className="text-xs font-bold text-brandText">{auth.user.name}</p>
                            <p className="text-[9px] font-bold text-brandText-muted tracking-wider">
                                {getFormattedRole(auth.user.role)}
                            </p>
                        </div>
                        <div className="h-8 w-8 rounded-full bg-primary-soft text-primary-dark font-bold text-xs flex items-center justify-center ring-2 ring-[#10B981] p-0.5">
                            {auth.user.name.charAt(0).toUpperCase()}
                        </div>
                    </button>

                    {/* Profile Dropdown Items */}
                    {isDropdownOpen && (
                        <>
                            {/* Backdrop to dismiss */}
                            <div
                                className="fixed inset-0 z-10"
                                onClick={() => setIsDropdownOpen(false)}
                            />
                            <div className="absolute right-0 mt-2 w-48 bg-white border border-brandOutline-soft/35 rounded-xl shadow-xl py-1.5 z-20 animate-in fade-in slide-in-from-top-2 duration-150">
                                <div className="px-4 py-2 border-b border-brandSurface-low">
                                    <p className="text-[10px] font-semibold text-brandText-muted">Logged in as</p>
                                    <p className="text-xs font-bold text-brandText truncate">{auth.user.email}</p>
                                </div>

                                <Link
                                    href={route('customer.profile')}
                                    className="px-4 py-2 text-sm text-[#064E3B] hover:bg-brandSurface-low transition-colors flex items-center gap-2"
                                    onClick={() => setIsDropdownOpen(false)}
                                >
                                    <UserIcon className="w-4 h-4" />
                                    <span>Profil Saya</span>
                                </Link>

                                <div className="border-t border-brandSurface-low my-1" />

                                <Link
                                    href={route('logout')}
                                    method="post"
                                    as="button"
                                    className="w-full text-left px-4 py-2 text-sm text-danger hover:bg-red-50 transition-colors flex items-center gap-2"
                                    onClick={() => setIsDropdownOpen(false)}
                                >
                                    <LogOut className="w-4 h-4" />
                                    <span>Keluar</span>
                                </Link>
                            </div>
                        </>
                    )}
                </div>
            </div>
        </header>
    );
}
