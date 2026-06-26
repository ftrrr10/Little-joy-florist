import React from 'react';
import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import Button from '@/Components/common/Button';
import CurrencyText from '@/Components/common/CurrencyText';
import { CheckCircle, Copy, ArrowRight, Home, CreditCard } from 'lucide-react';

interface SuccessProps {
    orderNumber: string;
    total: number;
}

export default function CheckoutSuccess({ orderNumber, total }: SuccessProps) {
    const handleCopy = (text: string) => {
        navigator.clipboard.writeText(text);
        alert('Disalin ke papan klip!');
    };

    return (
        <PublicLayout>
            <Head title="Pemesanan Berhasil | Little Joy Jakarta" />

            <div className="bg-cream-light/30 min-h-screen py-16 flex items-center justify-center font-sans">
                <div className="max-w-xl w-full mx-auto px-4">
                    <div className="bg-white border border-brandOutline-soft/30 rounded-3xl p-8 sm:p-10 shadow-md text-center">
                        {/* Success Icon */}
                        <div className="mx-auto w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-6 text-green-600">
                            <CheckCircle className="w-10 h-10" />
                        </div>

                        {/* Title */}
                        <h2 className="font-serif text-2xl sm:text-3xl font-bold text-primary mb-2">
                            Pemesanan Berhasil!
                        </h2>
                        <p className="text-sm text-brandText-muted leading-relaxed mb-6">
                            Terima kasih atas kepercayaan Anda. Pesanan Anda telah tercatat di sistem kami dengan detail berikut:
                        </p>

                        {/* Order Details Card */}
                        <div className="bg-cream/10 border border-brandOutline-soft/20 rounded-2xl p-5 mb-8 text-left space-y-4">
                            <div className="flex justify-between items-center">
                                <span className="text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                    Nomor Pesanan
                                </span>
                                <div className="flex items-center space-x-2">
                                    <span className="font-mono text-sm font-bold text-primary">
                                        {orderNumber}
                                    </span>
                                    <button
                                        type="button"
                                        onClick={() => handleCopy(orderNumber)}
                                        className="p-1 text-brandText-muted hover:text-primary transition-colors focus:outline-none"
                                        title="Salin nomor pesanan"
                                    >
                                        <Copy className="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </div>

                            <div className="flex justify-between items-center pt-3 border-t border-brandOutline-soft/10">
                                <span className="text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                    Total Pembayaran
                                </span>
                                <span className="font-serif text-lg font-bold text-primary">
                                    <CurrencyText value={total} />
                                </span>
                            </div>
                        </div>

                        {/* Bank Transfer Instructions */}
                        <div className="text-left mb-8 space-y-4">
                            <h4 className="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2">
                                Instruksi Pembayaran Transfer Bank
                            </h4>
                            <p className="text-xs text-brandText-muted leading-relaxed">
                                Silakan lakukan transfer bank manual dengan nominal yang tepat ke salah satu rekening resmi **Little Joy Jakarta** berikut:
                            </p>

                            <div className="space-y-3">
                                {/* Bank BCA */}
                                <div className="bg-white border border-brandOutline-soft/30 rounded-xl p-4 flex justify-between items-center">
                                    <div>
                                        <p className="text-xs font-bold text-primary">BANK BCA</p>
                                        <p className="font-mono text-sm font-semibold text-brandText mt-0.5">123-456-7890</p>
                                        <p className="text-[10px] text-brandText-muted">a/n Little Joy Jakarta</p>
                                    </div>
                                    <button
                                        type="button"
                                        onClick={() => handleCopy('1234567890')}
                                        className="text-xs font-semibold text-primary hover:text-primary-dark flex items-center gap-1 focus:outline-none"
                                    >
                                        Salin Rekening
                                    </button>
                                </div>

                                {/* Bank Mandiri */}
                                <div className="bg-white border border-brandOutline-soft/30 rounded-xl p-4 flex justify-between items-center">
                                    <div>
                                        <p className="text-xs font-bold text-primary">BANK MANDIRI</p>
                                        <p className="font-mono text-sm font-semibold text-brandText mt-0.5">098-765-4321</p>
                                        <p className="text-[10px] text-brandText-muted">a/n Little Joy Jakarta</p>
                                    </div>
                                    <button
                                        type="button"
                                        onClick={() => handleCopy('0987654321')}
                                        className="text-xs font-semibold text-primary hover:text-primary-dark flex items-center gap-1 focus:outline-none"
                                    >
                                        Salin Rekening
                                    </button>
                                </div>
                            </div>

                            <ul className="text-[10px] text-brandText-muted/80 list-disc list-inside space-y-1.5 leading-relaxed pt-2">
                                <li>Pastikan nominal transfer sesuai dengan total pembayaran di atas.</li>
                                <li>Simpan foto atau tangkapan layar bukti transfer Anda.</li>
                                <li>Unggah bukti transfer untuk memulai proses verifikasi pesanan oleh florist kami.</li>
                            </ul>
                        </div>

                        {/* Action Buttons */}
                        <div className="space-y-3">
                            <Link href={route('customer.payments.create', orderNumber)} className="block w-full">
                                <Button variant="primary" className="w-full justify-center py-3 flex items-center space-x-2 shadow-md">
                                    <CreditCard className="w-4 h-4 mr-2" />
                                    Unggah Bukti Pembayaran
                                </Button>
                            </Link>

                            <div className="grid grid-cols-2 gap-3">
                                <Link href={route('customer.orders.show', orderNumber)} className="block">
                                    <Button variant="outline" className="w-full justify-center py-2.5 text-xs">
                                        Detail Pesanan
                                    </Button>
                                </Link>
                                <Link href={route('home')} className="block">
                                    <Button variant="outline" className="w-full justify-center py-2.5 text-xs flex items-center justify-center gap-1">
                                        <Home className="w-3.5 h-3.5" />
                                        Kembali Beranda
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
