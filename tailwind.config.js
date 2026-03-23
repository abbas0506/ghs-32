/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            listStyleType: {
                roman: "lower-roman",
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
