import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    safelist: ["bg-blue", "bg-yellow"],

    darkMode: "class",

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "blue-tec": "#1B396A",
                "custom-green": "#00ff00",
            
            },
        },
    },

    plugins: [
        forms,
        typography,
        function ({ addUtilities }) {
            addUtilities({
                '.neon-light': {
                    fontSize: '80px',
                    color: '#fff',
                    textAlign: 'center',
                    textShadow: '0 0 5px #fff, 0 0 10px #fff, 0 0 15px #0073e6, 0 0 20px #0073e6, 0 0 25px #0073e6, 0 0 30px #0073e6, 0 0 35px #0073e6',
                },
            }, ['responsive', 'hover'])
        }
    ],
};
