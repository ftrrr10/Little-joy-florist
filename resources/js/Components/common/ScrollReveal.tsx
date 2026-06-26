import React, { useEffect, useRef, useState } from 'react';

interface ScrollRevealProps {
    children: React.ReactNode;
    className?: string;
    delay?: number; // delay in milliseconds
    duration?: number; // duration in milliseconds
    distance?: string; // Tailwind translate/scale initial state, e.g. "translate-y-12"
}

export default function ScrollReveal({
    children,
    className = '',
    delay = 0,
    duration = 1000,
    distance = 'translate-y-12',
}: ScrollRevealProps) {
    const [isVisible, setIsVisible] = useState(false);
    const elementRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        // Respect prefers-reduced-motion media query
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            setIsVisible(true);
            return;
        }

        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    setIsVisible(true);
                    if (elementRef.current) {
                        observer.unobserve(elementRef.current);
                    }
                }
            },
            {
                threshold: 0.1, // Trigger when 10% of the element is visible
                rootMargin: '0px 0px -80px 0px', // Trigger slightly inside the viewport for better UX
            }
        );

        const currentElement = elementRef.current;
        if (currentElement) {
            observer.observe(currentElement);
        }

        return () => {
            if (currentElement) {
                observer.unobserve(currentElement);
            }
        };
    }, []);

    const style: React.CSSProperties = {
        transitionDuration: `${duration}ms`,
        transitionDelay: `${delay}ms`,
    };

    return (
        <div
            ref={elementRef}
            style={style}
            className={`transition-all ease-out ${
                isVisible 
                    ? 'opacity-100 translate-x-0 translate-y-0 scale-100' 
                    : `opacity-0 ${distance}`
            } ${className}`}
        >
            {children}
        </div>
    );
}
