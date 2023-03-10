const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/helpers.js',
    ],


    theme: {
        extend: {
            fontFamily: {
                sans: ['Lato', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require('@headlessui/tailwindcss'),
        require('@tailwindcss/forms')
    ],
};
