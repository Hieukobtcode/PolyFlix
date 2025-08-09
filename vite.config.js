import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/dat-ve.css",
                "resources/js/dat-ve-client.js",
                "resources/css/trang-chu.css",
                "resources/js/trang-chu.js",
                "resources/css/ghe-ngoi.css",
                "resources/js/ghe-ngoi.js",
                "resources/css/client.css",
                "resources/js/client.js",
                "resources/js/chat.js",
                "resources/js/login.js",
                "resources/js/dat-ve.js",
                "resources/js/thanh-toan.js",
                "resources/css/thanh-toan.css",
            ],
            refresh: true,
        }),
    ],
});
