import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/filament.css"],
            refresh: [...refreshPaths, "app/Http/Livewire/**"],
            buildDirectory: '/filament-build',
        }),
    ],
    css: {
        postcss: {
            plugins: [
                require("tailwindcss/nesting"),
                require("tailwindcss")("./tailwind.filament.config.js"),
                require("autoprefixer"),
            ],
        },
    },
});
