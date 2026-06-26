import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import Button from '@/Components/common/Button';
import CurrencyText from '@/Components/common/CurrencyText';
import EmptyState from '@/Components/common/EmptyState';
import Alert from '@/Components/common/Alert';
import QuantitySelector from '@/Components/products/QuantitySelector';
import { CartItem, PageProps } from '@/types';
import { Trash2, ArrowLeft, ShoppingBag, CreditCard } from 'lucide-react';

interface CartPageProps extends Record<string, unknown> {
    cart: {
        id: number;
        user_id: number;
        created_at: string;
        updated_at: string;
    } | null;
    items: CartItem[];
    subtotal: number;
    deliveryFee: number;
    total: number;
}

export default function Cart({
    cart,
    items = [],
    subtotal = 0,
    deliveryFee = 0,
    total = 0,
    flash,
}: PageProps<CartPageProps>) {
    const [processingId, setProcessingId] = useState<number | null>(null);
    const [isClearing, setIsClearing] = useState(false);

    const handleQuantityChange = (itemId: number, newQty: number) => {
        setProcessingId(itemId);
        router.put(
            route('cart.update', itemId),
            { quantity: newQty },
            {
                preserveScroll: true,
                onFinish: () => setProcessingId(null),
            }
        );
    };

    const handleRemoveItem = (itemId: number) => {
        if (confirm('Apakah Anda yakin ingin menghapus barang ini dari keranjang?')) {
            setProcessingId(itemId);
            router.delete(route('cart.destroy', itemId), {
                preserveScroll: true,
                onFinish: () => setProcessingId(null),
            });
        }
    };

    const handleClearCart = () => {
        if (confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja Anda?')) {
            setIsClearing(true);
            router.delete(route('cart.clear'), {
                preserveScroll: true,
                onFinish: () => setIsClearing(false),
            });
        }
    };

    const hasItems = items.length > 0;

    return (
        <PublicLayout>
            <Head title="Keranjang Belanja | Little Joy Jakarta" />

            <div className="bg-cream-light/30 min-h-screen py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-8">
                        <Link
                            href={route('catalogue.index')}
                            className="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors mb-3 group"
                        >
                            <ArrowLeft className="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" />
                            Kembali ke Katalog
                        </Link>
                        <h1 className="font-serif text-3xl sm:text-4xl font-bold text-primary tracking-tight">
                            Keranjang Belanja
                        </h1>
                        <p className="text-sm text-brandText-muted mt-1">
                            Kelola bunga pilihan Anda sebelum melanjutkan ke pembayaran.
                        </p>
                    </div>

                    {/* Flash messages specifically for cart errors (e.g., stock limits) */}
                    {flash?.error && (
                        <div className="mb-6">
                            <Alert variant="danger" message={flash.error} />
                        </div>
                    )}
                    {flash?.success && (
                        <div className="mb-6">
                            <Alert variant="success" message={flash.success} />
                        </div>
                    )}

                    {!hasItems ? (
                        <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-12 shadow-sm max-w-2xl mx-auto text-center">
                            <EmptyState
                                title="Keranjang Belanja Kosong"
                                message="Anda belum menambahkan rangkaian bunga apa pun ke keranjang belanja Anda."
                            />
                            <div className="mt-8 flex justify-center">
                                <Link href={route('catalogue.index')}>
                                    <Button variant="primary" className="flex items-center space-x-2">
                                        <ShoppingBag className="w-4 h-4 mr-1.5" />
                                        Mulai Belanja
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                            {/* Items List */}
                            <div className="lg:col-span-2 space-y-4">
                                <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm overflow-hidden">
                                    <div className="px-6 py-4 border-b border-brandOutline-soft/30 bg-cream/10 flex justify-between items-center">
                                        <h3 className="font-serif text-lg font-semibold text-primary">
                                            Daftar Rangkaian Bunga ({items.length})
                                        </h3>
                                        <button
                                            type="button"
                                            onClick={handleClearCart}
                                            disabled={isClearing}
                                            className="text-xs text-red-600 hover:text-red-800 font-semibold transition-colors disabled:opacity-50"
                                        >
                                            {isClearing ? 'Mengosongkan...' : 'Kosongkan Keranjang'}
                                        </button>
                                    </div>

                                    <div className="divide-y divide-brandOutline-soft/20">
                                        {items.map((item) => {
                                            const product = item.product;
                                            if (!product) return null;

                                            const isItemProcessing = processingId === item.id;

                                            return (
                                                <div
                                                    key={item.id}
                                                    className={`p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition-opacity ${
                                                        isItemProcessing ? 'opacity-50 pointer-events-none' : ''
                                                    }`}
                                                >
                                                    {/* Product Details */}
                                                    <div className="flex items-center space-x-4 flex-1">
                                                        <div className="w-20 h-20 rounded-xl overflow-hidden border border-brandOutline-soft bg-cream-light/20 flex-shrink-0 flex items-center justify-center">
                                                            {product.image_path ? (
                                                                <img
                                                                    src={`/storage/${product.image_path}`}
                                                                    alt={product.name}
                                                                    className="w-full h-full object-cover"
                                                                />
                                                            ) : (
                                                                <span className="text-2xl text-primary/30 font-serif">
                                                                    ❀
                                                                </span>
                                                            )}
                                                        </div>

                                                        <div>
                                                            <span className="text-[10px] uppercase tracking-wider text-gold font-bold">
                                                                {product.category?.name || 'Bunga'}
                                                            </span>
                                                            <h4 className="font-serif text-base font-bold text-primary leading-tight mt-0.5">
                                                                {product.name}
                                                            </h4>
                                                            <p className="text-xs text-brandText-muted mt-1">
                                                                Harga Satuan:{' '}
                                                                <span className="font-semibold text-brandText">
                                                                    <CurrencyText value={Number(item.unit_price)} />
                                                                </span>
                                                            </p>
                                                            {product.stock <= 5 && (
                                                                <p className="text-[10px] font-semibold text-gold mt-1">
                                                                    Sisa Stok: {product.stock}
                                                                </p>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Controls & Subtotal */}
                                                    <div className="flex items-center justify-between sm:justify-end gap-6 sm:gap-10">
                                                        {/* Quantity */}
                                                        <div>
                                                            <QuantitySelector
                                                                value={item.quantity}
                                                                onChange={(newQty) =>
                                                                    handleQuantityChange(item.id, newQty)
                                                                }
                                                                max={product.stock}
                                                                disabled={isItemProcessing}
                                                            />
                                                        </div>

                                                        {/* Item Subtotal */}
                                                        <div className="text-right min-w-[100px]">
                                                            <p className="text-xs text-brandText-muted">Subtotal</p>
                                                            <p className="font-serif text-sm font-bold text-primary mt-0.5">
                                                                <CurrencyText value={Number(item.subtotal)} />
                                                            </p>
                                                        </div>

                                                        {/* Delete Button */}
                                                        <button
                                                            type="button"
                                                            onClick={() => handleRemoveItem(item.id)}
                                                            disabled={isItemProcessing}
                                                            className="p-2 text-brandText-muted/50 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all focus:outline-none"
                                                            title="Hapus barang"
                                                        >
                                                            <Trash2 className="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>

                            {/* Summary Card */}
                            <div className="space-y-6">
                                <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                                    <h3 className="font-serif text-lg font-bold text-primary mb-4 pb-3 border-b border-brandOutline-soft/30">
                                        Ringkasan Belanja
                                    </h3>

                                    <div className="space-y-3 text-sm">
                                        <div className="flex justify-between text-brandText-muted">
                                            <span>Subtotal</span>
                                            <span className="font-semibold text-brandText">
                                                <CurrencyText value={subtotal} />
                                            </span>
                                        </div>
                                        <div className="flex justify-between text-brandText-muted">
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

                                    {/* Checkout Buttons */}
                                    <div className="mt-6 space-y-3">
                                        {/* In Phase 3, we don't have a real checkout yet, but the route exists as a placeholder or we can link to it. */}
                                        <Link href={route('checkout.index')} className="block w-full">
                                            <Button variant="primary" className="w-full justify-center py-2.5 flex items-center space-x-2">
                                                <CreditCard className="w-4 h-4 mr-2" />
                                                Lanjutkan ke Checkout
                                            </Button>
                                        </Link>

                                        <Link href={route('catalogue.index')} className="block w-full text-center">
                                            <span className="inline-block text-xs font-semibold text-primary hover:text-primary-dark transition-colors py-2">
                                                Tambah Produk Lain
                                            </span>
                                        </Link>
                                    </div>
                                </div>

                                {/* Premium Service Info Banner */}
                                <div className="bg-cream/10 border border-brandOutline-soft/20 rounded-2xl p-5 text-xs text-brandText-muted/80 space-y-3">
                                    <h4 className="font-bold text-primary flex items-center">
                                        <span className="text-base mr-1.5">✿</span> Layanan Little Joy Jakarta
                                    </h4>
                                    <ul className="space-y-2 list-disc list-inside">
                                        <li>Pengiriman terjamin segar dan tepat waktu.</li>
                                        <li>Setiap pesanan menyertakan kartu ucapan gratis.</li>
                                        <li>Pembayaran dilakukan secara transfer bank manual dengan verifikasi cepat.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </PublicLayout>
    );
}
