import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                // sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
                display: ["Plus Jakarta Sans", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#022448",
                "primary-container": "#1e3a5f",
                "on-primary-container": "#8aa4cf",
                secondary: "#fd761a", // البرتقالي الحيوي
                "secondary-dark": "#9d4300",
                background: "#faf9fc",
                surface: "#ffffff",
                "surface-container": "#f4f3f7",
                "on-surface": "#1a1c1e",
                "on-surface-variant": "#43474e",
                outline: "#e3e2e6",
                error: "#ba1a1a",
            },
        },
    },

    plugins: [forms],
};
