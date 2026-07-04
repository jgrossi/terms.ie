import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        // App is served over the machine's LAN IP (php) while Vite runs on :5173.
        // Module scripts are fetched cross-origin, so the dev server must send CORS
        // headers — recent Vite restricts the allowlist to localhost by default,
        // which silently blocks the LAN-IP origin and stops app.js from executing.
        host: '0.0.0.0',
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
