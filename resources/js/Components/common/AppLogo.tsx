import React from 'react';

interface AppLogoProps {
    className?: string;
    showText?: boolean;
    variant?: 'dark' | 'light' | 'gold';
}

export default function AppLogo({ className = 'h-9 w-auto', showText = true, variant = 'dark' }: AppLogoProps) {
    // Define colors based on variant
    const getColors = () => {
        switch (variant) {
            case 'light':
                return {
                    leaf: '#B0F0D6',
                    flower: '#FFFFFF',
                    text: 'text-white',
                    subtext: 'text-primary-soft',
                };
            case 'gold':
                return {
                    leaf: '#FFE088',
                    flower: '#FFE088',
                    text: 'text-tertiary-soft',
                    subtext: 'text-tertiary-soft/80',
                };
            case 'dark':
            default:
                return {
                    leaf: '#064E3B',
                    flower: '#8A486F',
                    text: 'text-primary',
                    subtext: 'text-brandText-muted',
                };
        }
    };

    const colors = getColors();

    return (
        <div className="flex items-center gap-3 select-none">
            {/* Elegant Botanical Emblem */}
            <svg
                className={className}
                viewBox="0 0 32 32"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                {/* Outer leaf curve (representing growth and nature) */}
                <path
                    d="M16 2C23.732 2 30 8.268 30 16C30 23.732 23.732 30 16 30C12.134 30 8.634 28.433 6.072 25.871C6.024 25.823 5.977 25.774 5.931 25.724C3.513 23.109 2 19.61 2 15.75C2 8.156 8.268 2 16 2Z"
                    stroke={colors.leaf}
                    strokeWidth="1.5"
                    strokeLinecap="round"
                />
                {/* Internal flower bud curve (delicate plum color or light) */}
                <path
                    d="M16 8C11.582 8 8 11.582 8 16C8 19.314 10.015 22.157 13 23.364V16C13 14.343 14.343 13 16 13C17.657 13 19 14.343 19 16V23.364C21.985 22.157 24 19.314 24 16C24 11.582 20.418 8 16 8Z"
                    fill={colors.flower}
                    fillOpacity="0.85"
                />
                {/* Center pistil / gold accent */}
                <circle cx="16" cy="16" r="2.5" fill="#FFE088" />
                {/* Secondary abstract leaf line */}
                <path
                    d="M16 2V8"
                    stroke={colors.leaf}
                    strokeWidth="1.5"
                    strokeLinecap="round"
                />
            </svg>

            {/* Brand Typography */}
            {showText && (
                <div className="flex flex-col leading-none">
                    <span className={`font-serif text-lg font-bold tracking-wide ${colors.text}`}>
                        Little Joy
                    </span>
                    <span className={`font-sans text-[9px] font-semibold tracking-[0.2em] uppercase mt-0.5 ${colors.subtext}`}>
                        Jakarta
                    </span>
                </div>
            )}
        </div>
    );
}
