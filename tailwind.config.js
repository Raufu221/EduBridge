import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', 'serif'],
            },
            colors: {
                terracotta: {
                    DEFAULT: 'hsl(14, 70%, 52%)',
                    light: 'hsl(14, 60%, 94%)',
                },
                sage: {
                    DEFAULT: 'hsl(150, 20%, 45%)',
                    light: 'hsl(150, 20%, 92%)',
                },
                gold: {
                    DEFAULT: 'hsl(38, 85%, 55%)',
                    light: 'hsl(38, 70%, 92%)',
                },
                charcoal: 'hsl(20, 20%, 14%)',
                cream: 'hsl(30, 25%, 97%)',
                'warm-white': 'hsl(30, 20%, 99%)',
                border: 'hsl(30, 15%, 90%)',
                muted: {
                    DEFAULT: 'hsl(30, 10%, 60%)',
                    foreground: 'hsl(30, 10%, 45%)',
                }
            }
        },
    },

    plugins: [forms],
};
