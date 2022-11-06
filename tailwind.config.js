module.exports = {
    content: [
        "./resources/views/!(filament|vendor)/**/*.blade.php"
    ],
    darkMode: "class",
    theme: {
        extend: {},
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
