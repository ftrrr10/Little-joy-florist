import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.tsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['"Libre Caslon Text"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                primary: {
                    DEFAULT: '#064E3B',
                    dark: '#003527',
                    soft: '#B0F0D6',
                    muted: '#95D3BA',
                },
                secondary: {
                    DEFAULT: '#8A486F',
                    soft: '#FFD8EA',
                },
                tertiary: {
                    DEFAULT: '#735C00',
                    soft: '#FFE088',
                },
                brandBackground: '#F9FAF8',
                brandSurface: {
                    DEFAULT: '#FFFFFF',
                    low: '#F3F4F2',
                    high: '#E7E8E6',
                },
                brandText: {
                    DEFAULT: '#191C1D',
                    muted: '#404944',
                },
                brandOutline: {
                    DEFAULT: '#707974',
                    soft: '#BFC9C3',
                },
                success: '#166534',
                warning: '#A16207',
                danger: '#BA1A1A',
                info: '#075985',
            },
        },
    },

    plugins: [forms],
};
