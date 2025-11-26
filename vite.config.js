import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts','resources/css/filament/admin/theme.css'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        svelte({
            compilerOptions: (filename) => ({
                runes: !filename.includes('node_modules'),
            }),
            onwarn: (warning, handler) => {
                if (warning.code === 'legacy_export_invalid' && warning.filename?.includes('node_modules')) {
                    return;
                }
                handler(warning);
            },
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
});
