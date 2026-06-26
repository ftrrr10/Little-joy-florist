import React from 'react';
import { Product } from '@/types';
import ProductCard from './ProductCard';
import EmptyState from '../common/EmptyState';

interface ProductGridProps {
    products: Product[];
}

export default function ProductGrid({ products }: ProductGridProps) {
    if (products.length === 0) {
        return (
            <div className="py-12">
                <EmptyState
                    title="Rangkaian Bunga Tidak Ditemukan"
                    message="Maaf, kami tidak menemukan rangkaian bunga yang cocok dengan kata kunci pencarian atau kategori filter Anda. Silakan coba filter lainnya."
                    icon={
                        <svg className="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.25" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    }
                />
            </div>
        );
    }

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 animate-in fade-in duration-300">
            {products.map((product) => (
                <ProductCard key={product.id} product={product} />
            ))}
        </div>
    );
}
