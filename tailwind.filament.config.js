/** @type {import('tailwindcss').Config} */
const colors = require("tailwindcss/colors");

module.exports = {
    content: [
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.gray,
                success: colors.green,
                warning: colors.yellow,
                indigo: colors.indigo,
                cyan: colors.cyan,
                sky: colors.sky,
                orange: colors.orange,
            },
        },
    },
    safelist: [
        "text-orange-700",
        "dark:text-orange-500",
        "text-sky-700",
        "dark:text-sky-500",
        "text-indigo-700",
        "dark:text-indigo-500",
        "text-cyan-700",
        "dark:text-cyan-500",
        "bg-orange-500/10",
        "bg-sky-500/10",
        "bg-indigo-500/10",
        "bg-cyan-500/10",
    ],
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
