import React from 'react';
import { Minus, Plus } from 'lucide-react';

interface QuantitySelectorProps {
    value: number;
    onChange: (value: number) => void;
    max: number;
    disabled?: boolean;
}

export default function QuantitySelector({
    value,
    onChange,
    max,
    disabled = false,
}: QuantitySelectorProps) {
    const handleDecrement = () => {
        if (value > 1 && !disabled) {
            onChange(value - 1);
        }
    };

    const handleIncrement = () => {
        if (value < max && !disabled) {
            onChange(value + 1);
        }
    };

    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (disabled) return;
        const val = parseInt(e.target.value, 10);
        if (isNaN(val)) return;
        
        if (val < 1) {
            onChange(1);
        } else if (val > max) {
            onChange(max);
        } else {
            onChange(val);
        }
    };

    return (
        <div className="flex items-center border border-brandOutline rounded-lg bg-cream/20 w-fit overflow-hidden shadow-sm">
            <button
                type="button"
                onClick={handleDecrement}
                disabled={value <= 1 || disabled}
                className="p-2 text-brandText-muted hover:text-primary disabled:text-brandText-muted/30 disabled:bg-transparent transition-colors focus:outline-none"
                aria-label="Kurangi jumlah"
            >
                <Minus className="w-4 h-4" />
            </button>
            
            <input
                type="text"
                value={value}
                onChange={handleInputChange}
                disabled={disabled}
                className="w-10 text-center border-none bg-transparent text-sm font-semibold text-brandText focus:ring-0 p-0 focus:outline-none"
                aria-label="Jumlah barang"
            />
            
            <button
                type="button"
                onClick={handleIncrement}
                disabled={value >= max || disabled}
                className="p-2 text-brandText-muted hover:text-primary disabled:text-brandText-muted/30 disabled:bg-transparent transition-colors focus:outline-none"
                aria-label="Tambah jumlah"
            >
                <Plus className="w-4 h-4" />
            </button>
        </div>
    );
}
