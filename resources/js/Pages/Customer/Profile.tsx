import React from 'react';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

export default function Profile() {
    const { auth } = usePage<PageProps>().props;

    return (
        <PublicLayout>
            <Head title="Profil Saya" />
            <div className="max-w-3xl mx-auto px-4 py-12">
                <h2 className="font-serif text-2xl font-bold text-primary mb-6">Profil Pelanggan</h2>
                <div className="bg-white border border-brandOutline-soft/30 p-8 rounded-2xl shadow-sm space-y-4 font-sans">
                    <div className="grid grid-cols-3 border-b border-brandSurface-low pb-3">
                        <span className="text-xs font-bold text-brandText-muted">Nama Lengkap</span>
                        <span className="col-span-2 text-sm font-semibold text-brandText">{auth.user?.name}</span>
                    </div>
                    <div className="grid grid-cols-3 border-b border-brandSurface-low pb-3">
                        <span className="text-xs font-bold text-brandText-muted">Alamat Email</span>
                        <span className="col-span-2 text-sm font-semibold text-brandText">{auth.user?.email}</span>
                    </div>
                    <div className="grid grid-cols-3 border-b border-brandSurface-low pb-3">
                        <span className="text-xs font-bold text-brandText-muted">Nomor Telepon</span>
                        <span className="col-span-2 text-sm font-semibold text-brandText">{auth.user?.phone || '-'}</span>
                    </div>
                    <div className="grid grid-cols-3">
                        <span className="text-xs font-bold text-brandText-muted">Alamat Utama</span>
                        <span className="col-span-2 text-sm font-semibold text-brandText whitespace-pre-wrap">{auth.user?.address || 'Belum diisi'}</span>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
