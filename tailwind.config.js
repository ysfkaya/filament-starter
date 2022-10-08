module.exports = {
    content: [
        // INFO: Remove the "vendor" path if you want to scan the Tailwind classes in vendor files
        "./resources/views/!(filament|vendor)/**/*.blade.php",
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
