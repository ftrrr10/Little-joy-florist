import React, { PropsWithChildren } from 'react';
import { Link } from '@inertiajs/react';
import AppLogo from '@/Components/common/AppLogo';

export default function GuestLayout({ children }: PropsWithChildren) {
    return (
        <div className="flex min-h-screen flex-col items-center justify-center bg-brandBackground px-4 py-12 font-sans select-none antialiased">
            {/* Logo Brand Mark */}
            <div className="mb-8 transform hover:scale-105 transition-transform duration-200">
                <Link href="/">
                    <AppLogo variant="dark" className="h-14 w-auto" />
                </Link>
            </div>

            {/* Premium Form Card */}
            <div className="w-full sm:max-w-md bg-white border border-brandOutline-soft/30 rounded-2xl shadow-xl shadow-primary/5 px-8 py-10 transition-all duration-200">
                {children}
            </div>
        </div>
    );
}
