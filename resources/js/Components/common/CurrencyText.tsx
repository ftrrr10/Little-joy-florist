import React from 'react';

interface CurrencyTextProps {
    value: number | string;
    className?: string;
}

export default function CurrencyText({ value, className = '' }: CurrencyTextProps) {
    const numericValue = typeof value === 'string' ? parseFloat(value) : value;

    // Format value as Indonesian Rupiah (IDR)
    const formatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(isNaN(numericValue) ? 0 : numericValue);

    return <span className={className}>{formatted}</span>;
}
