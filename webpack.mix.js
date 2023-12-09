const mix = require('laravel-mix');
const path = require("path");

mix.js('resources/js/app.jsx', 'public')
    .react()
    .sourceMaps()
    .extract()
    .setPublicPath('public')
    .copy('resources/fonts/', 'public/fonts')
    .alias({'@': path.join(__dirname, 'resources/js/')})
    .sass('resources/scss/app.scss', 'public/css')
    .webpackConfig({
        output: {uniqueName: 'laravel/smetaniny/sm-laravel-admin'},
    })
    .version()
