const elixir = require('laravel-elixir');
var path = require("path");
require('laravel-elixir-vue-2');
require('laravel-elixir-vueify');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {

    elixir.webpack.mergeConfig({
        module: {
            loaders:[
                {
                    test: /\.js$/,
                    loader: 'buble-loader'
                },
                {
                    test: /\.scss$/,
                    loader: 'style-loader!css-loader!sass-loader'
                },{
                    test: /\.css$/,
                    loader:'style-loader!css-loader!'
                },
                {
                    test: /\.(png|jpg|gif|svg|ttf|woff|eot|woff2)$/,
                    loader: 'file-loader'
                },
                {
                    test: /\.vue$/,
                    loader: 'vue-loader'
                }
            ]
        },
        resolve: {
            extensions: ['', '.js', '.vue', '.json', '.css'],
            alias: {
                vue: 'vue/dist/vue.js',
                'vue$': 'vue/dist/vue.esm.js'
            }
        }
    });

    mix.browserify('./resources/assets/js/app.js', './public/js/app.js');
    mix.webpack('./resources/assets/js/vendor-js.js', './public/js/vendor-js.js');
    mix.webpack('./resources/assets/js/vendor-css.js', './public/js/vendor-css.js');

});
