import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': new URL('./resources/js', import.meta.url).pathname,
        },
    },
    server: {
        // App is served over the machine's LAN IP (php) while Vite runs on :5173.
        // Module scripts are fetched cross-origin, so the dev server must send CORS
        // headers — recent Vite restricts the allowlist to localhost by default,
        // which silently blocks the LAN-IP origin and stops the app from executing.
        host: '0.0.0.0',
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
