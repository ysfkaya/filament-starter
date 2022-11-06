import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/filament-app.css"],
            refresh: [...refreshPaths, "app/Http/Livewire/**"],
            buildDirectory: '/filament-build',
        }),
    ],
    css: {
        postcss: {
            plugins: [
                require("tailwindcss/nesting"),
                require("tailwindcss")("./filament.tailwind.config.js"),
                require("autoprefixer"),
            ],
        },
    },
});
