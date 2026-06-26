import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import Button from '@/Components/common/Button';
import CurrencyText from '@/Components/common/CurrencyText';
import Alert from '@/Components/common/Alert';
import { CartItem, PageProps } from '@/types';
import { ArrowLeft, MapPin, Calendar, MessageSquare, User, Phone, FileText, CreditCard } from 'lucide-react';

interface CheckoutProps extends Record<string, unknown> {
    items: CartItem[];
    subtotal: number;
    deliveryFee: number;
    total: number;
}

export default function Checkout({
    items = [],
    subtotal = 0,
    deliveryFee = 0,
    total = 0,
    flash,
}: PageProps<CheckoutProps>) {
    // Set minimum date to today
    const todayStr = new Date().toISOString().split('T')[0];

    const { data, setData, post, processing, errors } = useForm({
        recipient_name: '',
        recipient_phone: '',
        delivery_address: '',
        delivery_date: '',
        greeting_message: '',
        customer_note: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('checkout.store'));
    };

    return (
        <PublicLayout>
            <Head title="Checkout | Little Joy Jakarta" />

            <div className="bg-cream-light/30 min-h-screen py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Breadcrumbs / Back */}
                    <div className="mb-8">
                        <Link
                            href={route('cart.index')}
                            className="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors mb-3 group"
                        >
                            <ArrowLeft className="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" />
                            Kembali ke Keranjang
                        </Link>
                        <h1 className="font-serif text-3xl sm:text-4xl font-bold text-primary tracking-tight">
                            Detail Pengiriman & Pemesanan
                        </h1>
                        <p className="text-sm text-brandText-muted mt-1">
                            Lengkapi informasi di bawah ini untuk menyelesaikan pesanan Anda.
                        </p>
                    </div>

                    {flash?.error && (
                        <div className="mb-6 max-w-4xl">
                            <Alert variant="danger" message={flash.error} />
                        </div>
                    )}

                    <form onSubmit={handleSubmit} className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                        {/* Left Column: Checkout Form */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Section 1: Penerima */}
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                                <h3 className="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                                    <User className="w-5 h-5 mr-2 text-gold" />
                                    Informasi Penerima
                                </h3>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label htmlFor="recipient_name" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                            Nama Penerima <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="recipient_name"
                                            value={data.recipient_name}
                                            onChange={(e) => setData('recipient_name', e.target.value)}
                                            className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                errors.recipient_name ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                            }`}
                                            placeholder="Nama Lengkap Penerima"
                                            required
                                        />
                                        {errors.recipient_name && (
                                            <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.recipient_name}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label htmlFor="recipient_phone" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                            No. Telepon Penerima <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="tel"
                                            id="recipient_phone"
                                            value={data.recipient_phone}
                                            onChange={(e) => setData('recipient_phone', e.target.value)}
                                            className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                errors.recipient_phone ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                            }`}
                                            placeholder="Contoh: 081234567890"
                                            required
                                        />
                                        {errors.recipient_phone && (
                                            <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.recipient_phone}</p>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Section 2: Detail Pengiriman */}
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                                <h3 className="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                                    <MapPin className="w-5 h-5 mr-2 text-gold" />
                                    Alamat Pengiriman
                                </h3>

                                <div className="space-y-4">
                                    <div>
                                        <label htmlFor="delivery_address" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                            Alamat Lengkap <span className="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            id="delivery_address"
                                            rows={3}
                                            value={data.delivery_address}
                                            onChange={(e) => setData('delivery_address', e.target.value)}
                                            className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                errors.delivery_address ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                            }`}
                                            placeholder="Tulis alamat pengiriman lengkap termasuk kecamatan, kelurahan, kode pos, dan patokan jalan..."
                                            required
                                        />
                                        {errors.delivery_address && (
                                            <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.delivery_address}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label htmlFor="delivery_date" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2 flex items-center">
                                            <Calendar className="w-3.5 h-3.5 mr-1.5 text-primary" />
                                            Tanggal Pengiriman <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            id="delivery_date"
                                            min={todayStr}
                                            value={data.delivery_date}
                                            onChange={(e) => setData('delivery_date', e.target.value)}
                                            className={`w-fit border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                errors.delivery_date ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                            }`}
                                            required
                                        />
                                        {errors.delivery_date && (
                                            <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.delivery_date}</p>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Section 3: Pesan & Catatan */}
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                                <h3 className="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                                    <MessageSquare className="w-5 h-5 mr-2 text-gold" />
                                    Kartu Ucapan & Catatan Tambahan (Opsional)
                                </h3>

                                <div className="space-y-4">
                                    <div>
                                        <label htmlFor="greeting_message" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                            Pesan Kartu Ucapan
                                        </label>
                                        <textarea
                                            id="greeting_message"
                                            rows={3}
                                            value={data.greeting_message}
                                            onChange={(e) => setData('greeting_message', e.target.value)}
                                            className={`w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                errors.greeting_message ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''
                                            }`}
                                            placeholder="Tulis ucapan gratis untuk disematkan pada rangkaian bunga (contoh: Happy Birthday, Happy Graduation, dll)..."
                                        />
                                        <div className="flex justify-between items-center mt-1 text-[10px] text-brandText-muted">
                                            <span>Maksimal 500 karakter.</span>
                                            <span>{data.greeting_message.length}/500</span>
                                        </div>
                                        {errors.greeting_message && (
                                            <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.greeting_message}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label htmlFor="customer_note" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2 flex items-center">
                                            <FileText className="w-3.5 h-3.5 mr-1.5 text-primary" />
                                            Catatan Operasional untuk Florist
                                        </label>
                                        <textarea
                                            id="customer_note"
                                            rows={2}
                                            value={data.customer_note}
                                            onChange={(e) => setData('customer_note', e.target.value)}
                                            className={`w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                errors.customer_note ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''
                                            }`}
                                            placeholder="Contoh: Tolong gunakan pita warna merah muda, atau pastikan bunga mawar dikupas rapi..."
                                        />
                                        {errors.customer_note && (
                                            <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.customer_note}</p>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Right Column: Order Summary */}
                        <div className="space-y-6">
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6 sticky top-6">
                                <h3 className="font-serif text-lg font-bold text-primary mb-4 pb-3 border-b border-brandOutline-soft/30">
                                    Ringkasan Pesanan
                                </h3>

                                {/* Items Compact List */}
                                <div className="space-y-3 mb-6 max-h-60 overflow-y-auto pr-1">
                                    {items.map((item) => {
                                        const product = item.product;
                                        if (!product) return null;

                                        return (
                                            <div key={item.id} className="flex justify-between items-start gap-4 text-xs">
                                                <div className="flex-1">
                                                    <span className="font-semibold text-brandText">{product.name}</span>
                                                    <span className="text-brandText-muted ml-1.5">x{item.quantity}</span>
                                                </div>
                                                <span className="font-bold text-primary flex-shrink-0">
                                                    <CurrencyText value={Number(item.subtotal)} />
                                                </span>
                                            </div>
                                        );
                                    })}
                                </div>

                                {/* Pricing Cost Breakdown */}
                                <div className="space-y-3 text-sm pt-4 border-t border-brandOutline-soft/30">
                                    <div className="flex justify-between text-brandText-muted text-xs">
                                        <span>Subtotal</span>
                                        <span className="font-semibold text-brandText">
                                            <CurrencyText value={subtotal} />
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-brandText-muted text-xs">
                                        <span>Ongkos Kirim (Flat)</span>
                                        <span className="font-semibold text-brandText">
                                            <CurrencyText value={deliveryFee} />
                                        </span>
                                    </div>
                                    <div className="pt-3 border-t border-brandOutline-soft/30 flex justify-between items-center font-bold text-base text-primary">
                                        <span>Total Pembayaran</span>
                                        <span className="font-serif text-lg">
                                            <CurrencyText value={total} />
                                        </span>
                                    </div>
                                </div>

                                {/* Submit button */}
                                <div className="mt-6">
                                    <Button
                                        type="submit"
                                        variant="primary"
                                        className="w-full justify-center py-2.5 flex items-center space-x-2 shadow-md"
                                        isLoading={processing}
                                        disabled={processing}
                                    >
                                        <CreditCard className="w-4 h-4 mr-2" />
                                        Konfirmasi & Buat Pesanan
                                    </Button>
                                </div>

                                <div className="mt-4 text-[10px] text-brandText-muted/80 text-center leading-relaxed">
                                    Dengan menekan tombol di atas, Anda setuju untuk membuat pesanan. Anda akan diarahkan ke petunjuk pembayaran transfer manual.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </PublicLayout>
    );
}
