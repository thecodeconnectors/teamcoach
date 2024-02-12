import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/web/css/app.css',
                //'resources/assets/web/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
