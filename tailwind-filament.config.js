const colors = require("tailwindcss/colors");

module.exports = {
    content: ["./vendor/filament/**/*.blade.php"],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                primary: {
                    50: "#7C7C74",
                    100: "#72726A",
                    200: "#5C5C56",
                    300: "#474742",
                    400: "#32322F",
                    500: "#1D1D1B",
                    600: "#000000",
                    700: "#000000",
                    800: "#000000",
                    900: "#000000",
                },
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
