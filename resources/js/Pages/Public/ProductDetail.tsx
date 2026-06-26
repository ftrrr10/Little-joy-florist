import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { Product, PageProps } from '@/types';
import PublicLayout from '@/Layouts/PublicLayout';
import CurrencyText from '@/Components/common/CurrencyText';
import Button from '@/Components/common/Button';
import StockIndicator from '@/Components/products/StockIndicator';
import ProductCard from '@/Components/products/ProductCard';

interface DetailProps extends PageProps {
    product: Product;
    relatedProducts: Product[];
}

export default function ProductDetail({ product, relatedProducts }: DetailProps) {
    const { auth } = usePage<PageProps>().props;
    const [quantity, setQuantity] = useState(1);
    const [isAdding, setIsAdding] = useState(false);

    const isOutOfStock = product.stock <= 0;

    const handleQuantityChange = (type: 'increase' | 'decrease') => {
        if (type === 'increase') {
            if (quantity < product.stock) {
                setQuantity(quantity + 1);
            }
        } else {
            if (quantity > 1) {
                setQuantity(quantity - 1);
            }
        }
    };

    const handleAddToCart = () => {
        if (!auth.user) {
            // Redirect to login if guest
            router.get(route('login'));
            return;
        }

        setIsAdding(true);
        
        router.post(
            route('cart.store'),
            {
                product_id: product.id,
                quantity: quantity,
            },
            {
                onFinish: () => setIsAdding(false),
            }
        );
    };

    return (
        <PublicLayout>
            <Head title={`${product.name} - Little Joy Jakarta`} />

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 font-sans">
                {/* Back to Catalogue Button */}
                <div className="mb-8">
                    <Link
                        href={route('catalogue.index')}
                        className="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary-dark transition-colors"
                    >
                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Katalog
                    </Link>
                </div>

                {/* Main Product Info grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-12 items-start mb-20">
                    {/* Left Column: Product Image Container */}
                    <div className="bg-white border border-brandOutline-soft/25 rounded-3xl overflow-hidden shadow-sm aspect-[4/3] relative">
                        {product.image_path ? (
                            <img
                                src={`/storage/${product.image_path}`}
                                alt={product.name}
                                className="w-full h-full object-cover"
                            />
                        ) : (
                            <div className="w-full h-full flex flex-col items-center justify-center bg-primary-soft/15 text-primary p-12 select-none">
                                <svg className="h-16 w-16 text-primary/45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.25" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                                </svg>
                                <span className="text-xs font-bold tracking-[0.2em] uppercase text-primary/60 mt-4">
                                    Little Joy Jakarta
                                </span>
                            </div>
                        )}

                        {isOutOfStock && (
                            <div className="absolute inset-0 bg-black/40 backdrop-blur-[1.5px] flex items-center justify-center">
                                <span className="px-6 py-3 bg-white/95 text-danger font-bold text-sm uppercase tracking-widest rounded-xl shadow-lg">
                                    Habis Terjual
                                </span>
                            </div>
                        )}
                    </div>

                    {/* Right Column: Product Core Details */}
                    <div className="space-y-6">
                        <div className="flex items-center gap-3">
                            <span className="inline-block px-3 py-1 bg-primary-soft/25 text-primary text-xs font-bold tracking-wide uppercase rounded-lg">
                                {product.category?.name || 'Flower Arrangement'}
                            </span>
                            <StockIndicator stock={product.stock} />
                        </div>

                        {/* Product Title */}
                        <h2 className="font-serif text-3xl sm:text-4xl font-bold text-primary leading-tight tracking-wide">
                            {product.name}
                        </h2>

                        {/* Price */}
                        <div className="py-3 border-y border-brandSurface-high/50 flex items-center">
                            <CurrencyText
                                value={product.price}
                                className="text-2xl sm:text-3xl font-bold text-brandText"
                            />
                        </div>

                        {/* Description */}
                        <div className="space-y-3">
                            <h4 className="text-xs font-bold text-primary uppercase tracking-wider">
                                Deskripsi Rangkaian
                            </h4>
                            <p className="text-sm text-brandText-muted leading-relaxed whitespace-pre-wrap">
                                {product.description}
                            </p>
                        </div>

                        {/* Order Controls (Only if in stock) */}
                        {!isOutOfStock ? (
                            <div className="space-y-4 pt-4 border-t border-brandSurface-low">
                                <div className="flex items-center gap-6 select-none">
                                    <span className="text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Jumlah:
                                    </span>
                                    
                                    {/* Qty Counter Buttons */}
                                    <div className="flex items-center border border-brandOutline-soft/75 rounded-lg bg-white overflow-hidden shadow-sm">
                                        <button
                                            type="button"
                                            onClick={() => handleQuantityChange('decrease')}
                                            className="px-3.5 py-1.5 text-brandText-muted hover:bg-brandSurface-low active:bg-brandSurface-high transition-colors text-sm font-bold focus:outline-none"
                                            disabled={quantity <= 1}
                                        >
                                            &minus;
                                        </button>
                                        <span className="px-4 py-1.5 text-sm font-bold text-brandText min-w-[40px] text-center border-x border-brandSurface-high">
                                            {quantity}
                                        </span>
                                        <button
                                            type="button"
                                            onClick={() => handleQuantityChange('increase')}
                                            className="px-3.5 py-1.5 text-brandText-muted hover:bg-brandSurface-low active:bg-brandSurface-high transition-colors text-sm font-bold focus:outline-none"
                                            disabled={quantity >= product.stock}
                                        >
                                            &#43;
                                        </button>
                                    </div>
                                </div>

                                <div className="pt-2 flex flex-col sm:flex-row gap-3">
                                    <Button
                                        onClick={handleAddToCart}
                                        variant="primary"
                                        size="lg"
                                        className="flex-grow justify-center shadow-md"
                                        isLoading={isAdding}
                                    >
                                        Masukkan Ke Keranjang
                                    </Button>
                                </div>
                            </div>
                        ) : (
                            <div className="pt-4 border-t border-brandSurface-low">
                                <Button
                                    variant="outline"
                                    size="lg"
                                    className="w-full justify-center border-red-200 text-danger cursor-not-allowed select-none"
                                    disabled
                                >
                                    Stok Habis
                                </Button>
                            </div>
                        )}

                        {/* Extra Banners / Info cards */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-brandSurface-low">
                            <div className="p-3 bg-brandSurface-low/30 border border-brandSurface-high rounded-xl text-xs text-brandText-muted space-y-1">
                                <p className="font-bold text-primary">Informasi Pengiriman</p>
                                <p className="leading-relaxed">Pengiriman terjadwal setiap hari pukul 09:00 - 20:00 WIB ke seluruh wilayah DKI Jakarta.</p>
                            </div>
                            <div className="p-3 bg-brandSurface-low/30 border border-brandSurface-high rounded-xl text-xs text-brandText-muted space-y-1">
                                <p className="font-bold text-primary">Pembayaran Aman</p>
                                <p className="leading-relaxed">Mendukung transfer bank manual (BCA/Mandiri). Verifikasi cepat oleh operator kami.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Related Products Section */}
                {relatedProducts.length > 0 && (
                    <div className="pt-16 border-t border-brandSurface-high/65">
                        <div className="mb-8 flex justify-between items-center">
                            <h3 className="font-serif text-2xl font-bold text-primary tracking-wide">
                                Rekomendasi Rangkaian Lainnya
                            </h3>
                            <Link
                                href={route('catalogue.index', { category: String(product.category_id) })}
                                className="text-xs font-bold text-primary hover:underline"
                            >
                                Lihat Semua
                            </Link>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            {relatedProducts.map((related) => (
                                <ProductCard key={related.id} product={related} />
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}
