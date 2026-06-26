import React, { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import DashboardSidebar from '@/Components/navigation/DashboardSidebar';
import DashboardTopbar from '@/Components/navigation/DashboardTopbar';
import Toast from '@/Components/common/Toast';

interface DashboardLayoutProps {
    title?: string;
    children: React.ReactNode;
}

export default function DashboardLayout({ title, children }: DashboardLayoutProps) {
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
        <div className="flex min-h-screen bg-brandBackground text-brandText">
            {/* Sidebar Navigation */}
            <DashboardSidebar />

            {/* Floating Toasts */}
            {toastMsg && (
                <Toast
                    variant={toastVariant}
                    message={toastMsg}
                    onClose={() => setToastMsg(null)}
                />
            )}

            {/* Main Workspace Area */}
            <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
                {/* Header Topbar */}
                <DashboardTopbar title={title} />

                {/* Scrollable Workspace Viewport */}
                <main className="flex-1 overflow-y-auto p-8">
                    <div className="max-w-7xl mx-auto">
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
