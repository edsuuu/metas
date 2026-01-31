import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.tsx',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            colors: {
                "primary": "#13ec92",
                "secondary": "#ff6b6b",
                "accent": "#ffd93d",
                "background-light": "#f6f8f7",
                "background-dark": "#10221a",
            },
            fontFamily: {
                sans: ['Manrope', 'sans-serif', ...defaultTheme.fontFamily.sans],
                display: ["Manrope", "sans-serif"],
            },
            borderRadius: {
                "DEFAULT": "1rem",
                "lg": "2rem",
                "xl": "3rem",
                "full": "9999px"
            },
        },
    },

    plugins: [
        forms,
        containerQueries
    ],
};
