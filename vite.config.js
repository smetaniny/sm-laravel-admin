import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const laravelConfig = {
    input: 'sites/packeg/resources/js/app.js',
    publicDirectory: 'sites/packeg/public',
    buildDirectory: 'sites/packeg/public/build',
    hotFile: 'sites/packeg/public/hot',
    ssr: 'sites/packeg/resources/js/ssr.js',
    ssrOutputDirectory: 'sites/packeg/bootstrap/ssr',
    refresh: {
        paths: ['sites/packeg/resources/views'],
        config: {
            overlay: true, // Пример параметра overlay
            // Другие параметры по необходимости
        },
    },
    detectTls: true,
    transformOnServe: (code, url) => code,
};

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            publicDirectory: 'sm-laravel-admin/public',
            refresh: true,
        }),
    ],
});
