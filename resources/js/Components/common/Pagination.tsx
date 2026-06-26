import React from 'react';
import { Link } from '@inertiajs/react';

interface LinkItem {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginationProps {
    links: LinkItem[];
    className?: string;
}

export default function Pagination({ links, className = '' }: PaginationProps) {
    // Hide pagination if there is only 1 page (usually 3 links: Prev, Page 1, Next)
    if (links.length <= 3) return null;

    return (
        <nav className={`flex flex-wrap items-center justify-center gap-1.5 ${className}`} aria-label="Navigasi Halaman">
            {links.map((link, index) => {
                // Localize Laravel default labels
                let cleanLabel = link.label;
                if (cleanLabel.includes('Previous')) {
                    cleanLabel = 'Sebelumnya';
                } else if (cleanLabel.includes('Next')) {
                    cleanLabel = 'Berikutnya';
                }

                // If link URL is null, render as disabled text
                if (link.url === null) {
                    return (
                        <span
                            key={index}
                            className="px-3.5 py-2 text-xs font-bold text-brandText-muted/40 bg-brandSurface-low border border-brandOutline-soft/20 rounded-lg cursor-not-allowed select-none"
                            dangerouslySetInnerHTML={{ __html: cleanLabel }}
                        />
                    );
                }

                // Render active/inactive page link
                return (
                    <Link
                        key={index}
                        href={link.url}
                        className={`px-3.5 py-2 text-xs font-bold border rounded-lg transition-all duration-200 ${
                            link.active
                                ? 'bg-primary border-primary text-white shadow-sm'
                                : 'border-brandOutline-soft/20 bg-white text-brandText-muted hover:bg-brandSurface-low hover:text-primary hover:border-primary'
                        }`}
                        dangerouslySetInnerHTML={{ __html: cleanLabel }}
                    />
                );
            })}
        </nav>
    );
}
