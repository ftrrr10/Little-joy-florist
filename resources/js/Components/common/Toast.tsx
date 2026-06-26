import React, { useEffect, useState } from 'react';

interface ToastProps {
    variant?: 'success' | 'danger' | 'warning' | 'info';
    message: string;
    duration?: number; // in ms
    onClose?: () => void;
}

export default function Toast({
    variant = 'success',
    message,
    duration = 4000,
    onClose,
}: ToastProps) {
    const [isVisible, setIsVisible] = useState(false);
    const [shouldRender, setShouldRender] = useState(false);

    useEffect(() => {
        if (message) {
            setShouldRender(true);
            // Trigger entering slide transition in next frame
            const enterTimeout = setTimeout(() => setIsVisible(true), 50);

            // Trigger leaving slide transition before unmounting
            const leaveTimeout = setTimeout(() => {
                setIsVisible(false);
                // Wait for exit transition to finish (300ms) before unmounting
                const unmountTimeout = setTimeout(() => {
                    setShouldRender(false);
                    if (onClose) onClose();
                }, 300);
                return () => clearTimeout(unmountTimeout);
            }, duration);

            return () => {
                clearTimeout(enterTimeout);
                clearTimeout(leaveTimeout);
            };
        }
    }, [message, duration, onClose]);

    if (!shouldRender || !message) return null;

    // Styling configurations
    const styles = {
        success: {
            bg: 'bg-white border-green-200 shadow-green-100/50',
            text: 'text-brandText',
            accent: 'bg-green-600',
            icon: (
                <div className="flex items-center justify-center h-8 w-8 rounded-full bg-green-50 text-green-600">
                    <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            ),
        },
        danger: {
            bg: 'bg-white border-red-200 shadow-red-100/50',
            text: 'text-brandText',
            accent: 'bg-danger',
            icon: (
                <div className="flex items-center justify-center h-8 w-8 rounded-full bg-red-50 text-danger">
                    <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            ),
        },
        warning: {
            bg: 'bg-white border-yellow-200 shadow-yellow-100/50',
            text: 'text-brandText',
            accent: 'bg-warning',
            icon: (
                <div className="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-50 text-yellow-600">
                    <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 9v2m0 4h.01" />
                    </svg>
                </div>
            ),
        },
        info: {
            bg: 'bg-white border-sky-200 shadow-sky-100/50',
            text: 'text-brandText',
            accent: 'bg-info',
            icon: (
                <div className="flex items-center justify-center h-8 w-8 rounded-full bg-sky-50 text-sky-600">
                    <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M13 16h-1v-4h-1m1-4h.01" />
                    </svg>
                </div>
            ),
        },
    };

    const currentStyle = styles[variant] || styles.info;

    return (
        <div
            className={`fixed top-4 right-4 z-50 flex items-center gap-3.5 max-w-sm w-[340px] p-4 bg-white border rounded-xl shadow-xl transition-all duration-300 transform font-sans ${
                isVisible ? 'translate-y-0 opacity-100' : '-translate-y-4 opacity-0 pointer-events-none'
            } ${currentStyle.bg}`}
            role="status"
        >
            {/* Left accent color strip */}
            <div className={`absolute left-0 top-0 bottom-0 w-1.5 rounded-l-xl ${currentStyle.accent}`} />

            {/* Icon */}
            <div className="flex-shrink-0 ml-1">{currentStyle.icon}</div>

            {/* Message Body */}
            <div className="flex-grow">
                <p className={`text-xs font-semibold leading-relaxed tracking-wide ${currentStyle.text}`}>
                    {message}
                </p>
            </div>

            {/* Manual Dismiss Button */}
            <button
                type="button"
                onClick={() => setIsVisible(false)}
                className="flex-shrink-0 p-1 text-brandText-muted/40 hover:text-brandText-muted rounded-lg hover:bg-brandSurface-low transition-all focus:outline-none"
                aria-label="Tutup"
            >
                <svg className="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    );
}
