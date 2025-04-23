import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.js",
        "./app/View/Components/**/*.php",
    ],

    theme: {
        extend: {
            colors: {
                'body': { light: '#FDFDFC', dark: '#0a0a0a' },
                'text': { DEFAULT: '#1b1b18', secondary: '#706f6c', light: '#EDEDEC', 'secondary-dark': '#A1A09A' },
                'panel': { light: '#ffffff', dark: '#161615' },
                'panel-accent': { light: '#f5f5f5', dark: '#1D0002' },
                'border': {
                    light: '#e3e3e0', dark: '#3E3E3A',
                    'auth-link': 'rgba(25, 20, 0, 0.21)', 'auth-link-hover': 'rgba(25, 21, 1, 0.29)',
                    'auth-link-dark': '#3E3E3A', 'auth-link-dark-hover': '#62605b',
                    'inset-light': 'rgba(26, 26, 0, 0.16)', 'inset-dark': 'rgba(255, 250, 237, 0.18)',
                },
                'icon-dot': { light: '#dbdbd7', dark: '#3E3E3A' },
                'placeholder-icon-bg': { light: '#dbeafe', dark: '#1e3a8a' },
                'placeholder-icon-text': { light: '#2563eb', dark: '#93c5fd' },
            },
            fontFamily: {
                sans: ['"Instrument Sans"', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
