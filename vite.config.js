import { defineConfig } from 'vite';
import laravel from 'laravel-vite';

export default defineConfig({
    build: {
        manifest: true,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
