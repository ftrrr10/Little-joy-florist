import React from 'react';

interface EmptyStateProps {
    title: string;
    message?: string;
    icon?: React.ReactNode;
    children?: React.ReactNode;
}

export default function EmptyState({
    title,
    message,
    icon,
    children,
}: EmptyStateProps) {
    return (
        <div className="flex flex-col items-center justify-center text-center p-12 bg-white border border-brandOutline-soft/25 rounded-2xl shadow-sm font-sans max-w-lg mx-auto">
            {/* Soft Illustration Icon */}
            {icon ? (
                <div className="text-primary/40 mb-5">{icon}</div>
            ) : (
                <div className="text-primary/40 mb-5">
                    <svg className="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.25" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            )}

            {/* Title & Description */}
            <h3 className="font-serif text-lg font-bold text-primary mb-1.5">
                {title}
            </h3>
            {message && (
                <p className="text-xs text-brandText-muted leading-relaxed max-w-sm">
                    {message}
                </p>
            )}

            {/* Optional Action Elements */}
            {children && <div className="mt-6">{children}</div>}
        </div>
    );
}
