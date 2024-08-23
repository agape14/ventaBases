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

 mix.js('resources/js/app.js', 'public/js')
 .postCss('resources/css/app.css', 'public/css', [
     require('tailwindcss'),
 ]);

// Copy CSS files from node_modules to public/css
mix.copy('node_modules/@fortawesome/fontawesome-free/css/all.min.css', 'public/css/fontawesome.css')
.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
.copy('node_modules/owl.carousel/dist/assets/owl.carousel.min.css', 'public/css/owl.carousel.min.css')
.copy('node_modules/owl.carousel/dist/assets/owl.theme.default.min.css', 'public/css/owl.theme.default.min.css');

// Copy JavaScript files from node_modules to public/js
mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js')
.copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'public/js/bootstrap.bundle.min.js')
.copy('node_modules/owl.carousel/dist/owl.carousel.min.js', 'public/js/owl.carousel.min.js')
.copy('node_modules/waypoints/lib/jquery.waypoints.min.js', 'public/js/jquery.waypoints.min.js');

if (mix.inProduction()) {
 mix.version();
}
