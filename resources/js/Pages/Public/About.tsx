import React from 'react';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head } from '@inertiajs/react';

export default function About() {
    return (
        <PublicLayout>
            <Head title="Tentang Kami - Little Joy Jakarta" />
            <div className="max-w-4xl mx-auto px-4 py-16">
                <h2 className="font-serif text-3xl font-bold text-primary text-center mb-8">Kisah Little Joy Jakarta</h2>
                <div className="bg-white border border-brandOutline-soft/30 p-10 rounded-2xl shadow-sm space-y-6 text-sm leading-relaxed text-brandText-muted">
                    <p>
                        Didirikan di Jakarta Selatan, <strong>Little Joy Jakarta</strong> bermula dari kecintaan kami terhadap keindahan alami bunga segar. Kami percaya bahwa setiap tangkai bunga memiliki bahasa tersendiri yang mampu menyampaikan emosi yang mendalam.
                    </p>
                    <p>
                        Kami berkomitmen untuk hanya menggunakan bunga berkualitas terbaik yang dipilih langsung dari petani lokal maupun importir terpercaya. Setiap pesanan dirangkai secara higienis, presisi, dan berseni tinggi oleh tim florist berpengalaman untuk memastikan kepuasan Anda.
                    </p>
                </div>
            </div>
        </PublicLayout>
    );
}
