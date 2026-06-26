import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import AppLogo from '../common/AppLogo';
import { Plus } from 'lucide-react';

interface SidebarLinkProps {
    href: string;
    icon: React.ReactNode;
    label: string;
    active: boolean;
}

function SidebarLink({ href, icon, label, active }: SidebarLinkProps) {
    return (
        <Link
            href={href}
            className={`relative flex items-center gap-3 pl-5 pr-4 py-3.5 text-xs uppercase tracking-wider font-bold rounded-xl transition-all duration-200 ${
                active
                    ? 'bg-white/10 text-white font-bold'
                    : 'text-white/60 hover:bg-white/5 hover:text-white'
            }`}
        >
            {active && (
                <span className="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#10B981] rounded-full"></span>
            )}
            <span className={`transition-colors ${active ? 'text-[#10B981]' : 'text-white/40'}`}>{icon}</span>
            <span>{label}</span>
        </Link>
    );
}

export default function DashboardSidebar() {
    const { auth } = usePage<PageProps>().props;
    const currentUrl = usePage().url;

    if (!auth.user) return null;

    const isAdmin = auth.user.role === 'admin';

    // Menu structure based on roles
    const commonMenu = [
        {
            label: 'Dashboard',
            href: route(isAdmin ? 'admin.dashboard' : 'operator.dashboard'),
            routeName: isAdmin ? 'admin.dashboard' : 'operator.dashboard',
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
            ),
        },
        {
            label: 'Pesanan',
            href: route('operator.orders.index'),
            routeName: 'operator.orders.*',
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            ),
        },
        {
            label: 'Produk & Stok',
            href: route('operator.stock.index'),
            routeName: 'operator.stock.*',
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            ),
        },
    ];

    const adminMenu = [
        {
            label: 'Kategori',
            href: route('admin.categories.index'),
            routeName: 'admin.categories.*',
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            ),
        },
        {
            label: 'Daftar Produk',
            href: route('admin.products.index'),
            routeName: 'admin.products.*',
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            ),
        },
        {
            label: 'Data Operator',
            href: route('admin.operators.index'),
            routeName: 'admin.operators.*',
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            ),
        },
        {
            label: 'Data Pelanggan',
            href: route('admin.customers.index'),
            routeName: 'admin.customers.*',
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            ),
        },
        {
            label: 'Laporan Keuangan',
            href: route('admin.reports.index'),
            routeName: 'admin.reports.*',
            icon: (
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            ),
        },
    ];

    return (
        <aside className="w-64 bg-[#022C22] text-white flex flex-col h-screen sticky top-0 border-r border-[#022C22] select-none font-sans">
            {/* Sidebar Header */}
            <div className="h-16 flex items-center px-6 border-b border-white/10">
                <AppLogo variant="light" className="h-7 w-auto" />
            </div>

            {/* Navigation Menus */}
            <div className="flex-1 overflow-y-auto px-4 py-6 space-y-7">
                {/* Common Section */}
                <div className="space-y-2">
                    <h5 className="text-[10px] font-bold tracking-[0.2em] text-white/40 uppercase px-4">
                        Operasional
                    </h5>
                    <div className="space-y-1">
                        {commonMenu.map((item) => (
                            <SidebarLink
                                key={item.label}
                                href={item.href}
                                icon={item.icon}
                                label={item.label}
                                active={route().current(item.routeName)}
                            />
                        ))}
                    </div>
                </div>

                {/* Administrator Section */}
                {isAdmin && (
                    <div className="space-y-2">
                        <h5 className="text-[10px] font-bold tracking-[0.2em] text-white/40 uppercase px-4">
                            Administrasi
                        </h5>
                        <div className="space-y-1">
                            {adminMenu.map((item) => (
                                <SidebarLink
                                    key={item.label}
                                    href={item.href}
                                    icon={item.icon}
                                    label={item.label}
                                    active={route().current(item.routeName)}
                                />
                            ))}
                        </div>
                    </div>
                )}
            </div>

            {/* Action Button from Mockup */}
            <div className="px-4 pb-4">
                <Link
                    href={isAdmin ? route('admin.products.create') : route('catalogue.index')}
                    className="w-full py-2.5 bg-[#F7F4EB] hover:bg-[#EFECE2] text-[#022C22] text-xs font-bold uppercase tracking-wider rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm"
                >
                    <Plus className="w-4 h-4 text-[#022C22]" />
                    {isAdmin ? 'Tambah Produk' : 'Transaksi Baru'}
                </Link>
            </div>

            {/* User Footer Summary */}
            <div className="p-4 border-t border-white/10 bg-black/20">
                <div className="flex items-center gap-3">
                    <div className="h-9 w-9 rounded-full bg-white/10 text-white font-bold text-xs flex items-center justify-center ring-2 ring-white/20 p-0.5">
                        {auth.user.name.charAt(0).toUpperCase()}
                    </div>
                    <div className="flex-grow min-w-0">
                        <p className="text-xs font-bold truncate text-white">{auth.user.name}</p>
                        <span className="inline-block px-2 py-0.5 mt-0.5 text-[9px] font-bold tracking-wide uppercase bg-white/15 text-white rounded-md leading-none">
                            {auth.user.role === 'admin' ? 'Store Manager' : 'Florist Operator'}
                        </span>
                    </div>
                </div>
            </div>
        </aside>
    );
}
