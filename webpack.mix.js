let mix = require('laravel-mix');

require('./nova.mix');

mix.setPublicPath('dist')
    .js('resources/js/better-lens.js', 'js')
    .vue({ version: 3 })
    .nova('meta-common/better-lens');
