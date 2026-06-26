import React, { forwardRef } from 'react';

interface InputProps extends React.InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    error?: string;
    helperText?: string;
}

const Input = forwardRef<HTMLInputElement, InputProps>(
    ({ label, error, helperText, className = '', type = 'text', id, ...props }, ref) => {
        const inputId = id || `input-${Math.random().toString(36).substr(2, 9)}`;

        return (
            <div className="flex flex-col gap-1 w-full font-sans">
                {/* Label */}
                {label && (
                    <label
                        htmlFor={inputId}
                        className="text-xs font-semibold text-brandText-muted tracking-wide"
                    >
                        {label}
                    </label>
                )}

                {/* Input Element */}
                <div className="relative">
                    <input
                        ref={ref}
                        type={type}
                        id={inputId}
                        className={`w-full px-3.5 py-2 text-sm bg-brandSurface border rounded-lg transition-all duration-200 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-muted/40 focus:border-primary disabled:bg-brandSurface-low disabled:text-brandText-muted/50 ${
                            error
                                ? 'border-danger focus:border-danger focus:ring-danger/20'
                                : 'border-brandOutline-soft focus:border-primary'
                        } ${className}`}
                        {...props}
                    />
                </div>

                {/* Error Message */}
                {error && (
                    <span className="text-xs font-medium text-danger transition-all duration-200">
                        {error}
                    </span>
                )}

                {/* Helper Text (Optional, if no error) */}
                {!error && helperText && (
                    <span className="text-xs text-brandText-muted/70">
                        {helperText}
                    </span>
                )}
            </div>
        );
    }
);

Input.displayName = 'Input';

export default Input;
