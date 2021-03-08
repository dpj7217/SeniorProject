const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/owlcarousel/owl.theme.default.scss', 'public/css')
    .sass('resources/sass/owlcarousel/owl.carousel.scss', 'public/css')
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/owlcarousel/owl.carousel.js', 'public/js');
    

