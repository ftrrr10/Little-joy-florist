import React from 'react';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head } from '@inertiajs/react';

export default function Contact() {
    return (
        <PublicLayout>
            <Head title="Hubungi Kami - Little Joy Jakarta" />
            <div className="max-w-4xl mx-auto px-4 py-16">
                <h2 className="font-serif text-3xl font-bold text-primary text-center mb-8">Hubungi Florist Kami</h2>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div className="bg-white border border-brandOutline-soft/30 p-8 rounded-2xl shadow-sm space-y-4">
                        <h4 className="font-serif text-lg font-bold text-primary">Informasi Layanan</h4>
                        <p className="text-sm text-brandText-muted leading-relaxed">
                            Butuh bantuan memilih bunga atau ingin memesan kustomisasi khusus? Hubungi tim layanan pelanggan kami yang ramah.
                        </p>
                        <div className="pt-2 space-y-2 text-sm text-brandText-muted">
                            <p><strong>WhatsApp:</strong> +62 812-3456-7890</p>
                            <p><strong>Email:</strong> support@littlejoyjakarta.com</p>
                        </div>
                    </div>
                    <div className="bg-white border border-brandOutline-soft/30 p-8 rounded-2xl shadow-sm space-y-4">
                        <h4 className="font-serif text-lg font-bold text-primary">Jam Operasional</h4>
                        <p className="text-sm text-brandText-muted leading-relaxed">
                            Kami melayani pengiriman rangkaian bunga segar setiap hari ke seluruh wilayah DKI Jakarta dan sekitarnya.
                        </p>
                        <div className="pt-2 space-y-2 text-sm text-brandText-muted">
                            <p><strong>Senin - Minggu:</strong> 08:00 - 20:00 WIB</p>
                            <p><strong>Pengiriman:</strong> Mulai pukul 09:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
