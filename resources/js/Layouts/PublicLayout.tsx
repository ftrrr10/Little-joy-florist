import React, { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import PublicNavbar from '@/Components/navigation/PublicNavbar';
import Footer from '@/Components/navigation/Footer';
import Toast from '@/Components/common/Toast';

interface PublicLayoutProps {
    children: React.ReactNode;
}

export default function PublicLayout({ children }: PublicLayoutProps) {
    const { flash } = usePage<PageProps>().props;
    const [toastMsg, setToastMsg] = useState<string | null>(null);
    const [toastVariant, setToastVariant] = useState<'success' | 'danger' | 'warning' | 'info'>('success');

    // Sync flash messages with Toast component
    useEffect(() => {
        if (flash.success) {
            setToastMsg(flash.success);
            setToastVariant('success');
        } else if (flash.error) {
            setToastMsg(flash.error);
            setToastVariant('danger');
        } else if (flash.warning) {
            setToastMsg(flash.warning);
            setToastVariant('warning');
        } else if (flash.info) {
            setToastMsg(flash.info);
            setToastVariant('info');
        }
    }, [flash]);

    return (
        <div className="flex flex-col min-h-screen bg-brandBackground text-brandText">
            {/* Navigation Header */}
            <PublicNavbar />

            {/* Floating Toasts */}
            {toastMsg && (
                <Toast
                    variant={toastVariant}
                    message={toastMsg}
                    onClose={() => setToastMsg(null)}
                />
            )}

            {/* Main Viewport Content */}
            <main className="flex-grow flex flex-col">
                {children}
            </main>

            {/* Footer */}
            <Footer />
        </div>
    );
}
