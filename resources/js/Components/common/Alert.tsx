import React, { useState } from 'react';

interface AlertProps {
    variant?: 'success' | 'danger' | 'warning' | 'info';
    message: string;
    onClose?: () => void;
    className?: string;
}

export default function Alert({
    variant = 'info',
    message,
    onClose,
    className = '',
}: AlertProps) {
    const [isVisible, setIsVisible] = useState(true);

    if (!isVisible || !message) return null;

    // Define color styles based on variant
    const styles = {
        success: {
            container: 'bg-green-50 border-green-200 text-success',
            icon: (
                <svg className="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            ),
        },
        danger: {
            container: 'bg-red-50 border-red-200 text-danger',
            icon: (
                <svg className="h-5 w-5 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            ),
        },
        warning: {
            container: 'bg-yellow-50 border-yellow-200 text-warning',
            icon: (
                <svg className="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            ),
        },
        info: {
            container: 'bg-sky-50 border-sky-200 text-info',
            icon: (
                <svg className="h-5 w-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            ),
        },
    };

    const currentStyle = styles[variant] || styles.info;

    const handleClose = () => {
        setIsVisible(false);
        if (onClose) onClose();
    };

    return (
        <div
            className={`flex items-start gap-3 p-4 border rounded-lg shadow-sm font-sans text-sm ${currentStyle.container} ${className}`}
            role="alert"
        >
            {/* Alert Icon */}
            <div className="flex-shrink-0 mt-0.5">{currentStyle.icon}</div>

            {/* Message */}
            <div className="flex-grow font-medium leading-relaxed">{message}</div>

            {/* Dismiss Button */}
            {onClose && (
                <button
                    type="button"
                    onClick={handleClose}
                    className="flex-shrink-0 -mr-1 -mt-1 p-1 rounded-md hover:bg-black/5 transition-colors focus:outline-none"
                    aria-label="Tutup"
                >
                    <svg className="h-4 w-4 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            )}
        </div>
    );
}
