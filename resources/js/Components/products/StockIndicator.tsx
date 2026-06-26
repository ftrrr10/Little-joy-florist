import React from 'react';

interface StockIndicatorProps {
    stock: number;
    className?: string;
}

export default function StockIndicator({ stock, className = '' }: StockIndicatorProps) {
    if (stock <= 0) {
        return (
            <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-danger border border-red-150 ${className}`}>
                <span className="h-1.5 w-1.5 rounded-full bg-danger animate-pulse" />
                Stok Habis
            </span>
        );
    }

    if (stock <= 5) {
        return (
            <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-warning border border-yellow-150 ${className}`}>
                <span className="h-1.5 w-1.5 rounded-full bg-warning" />
                Stok Terbatas ({stock})
            </span>
        );
    }

    return (
        <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-success border border-green-150 ${className}`}>
            <span className="h-1.5 w-1.5 rounded-full bg-success" />
            Tersedia ({stock})
        </span>
    );
}
