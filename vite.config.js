import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

import vue from '@vitejs/plugin-vue'; // Import the Vue plugin
export default defineConfig({
       server: {
        host: '0.0.0.0', // Permite conexiones externas
        port: 5188,
        strictPort: true,
        hmr: {
            host: '192.168.1.4',
            protocol: 'wss'
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
     
           vue(), // Add the Vue plugin here
    ],
});
