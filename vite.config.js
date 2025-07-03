import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/client.css",
                "resources/css/trang-chu.css",
                "resources/css/dat-ve.css",
                "resources/css/ghe-ngoi.css",
                "resources/js/client.js",
                "resources/js/trang-chu.js",
                "resources/js/dat-ve.js",
                "resources/js/dat-ve-client.js",
                "resources/js/ghe-ngoi.js",
                "resources/js/page.js",
                "resources/js/login.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
