const mix = require('laravel-mix');
const webpack = require("webpack");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    plugins: [
        new webpack.ProvidePlugin({
            $               : 'jquery',
            jQuery          : 'jquery',
            'window.jQuery' : 'jquery',
            Popper          : ['popper.js', 'default'],
            Alert           : 'exports-loader?Alert!bootstrap/js/dist/alert',
            Button          : 'exports-loader?Button!bootstrap/js/dist/button',
            Carousel        : 'exports-loader?Carousel!bootstrap/js/dist/carousel',
            Collapse        : 'exports-loader?Collapse!bootstrap/js/dist/collapse',
            Dropdown        : 'exports-loader?Dropdown!bootstrap/js/dist/dropdown',
            Modal           : 'exports-loader?Modal!bootstrap/js/dist/modal',
            Popover         : 'exports-loader?Popover!bootstrap/js/dist/popover',
            Scrollspy       : 'exports-loader?Scrollspy!bootstrap/js/dist/scrollspy',
            Tab             : 'exports-loader?Tab!bootstrap/js/dist/tab',
            Tooltip         : "exports-loader?Tooltip!bootstrap/js/dist/tooltip",
            Util            : 'exports-loader?Util!bootstrap/js/dist/util',
        }),
    ],
});

mix
    .js('resources/js/app.js', 'public/js')
    .css('resources/css/app.css', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .extract(['bootstrap'])
    .vue();

if (mix.inProduction()) {
    mix.version();
}
