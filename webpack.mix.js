let mix = require('laravel-mix');

mix
    .react('assets/js/index.js', './dist/js/blocks.js')
    .sass('assets/sass/styles.sass', './dist/css/style.css')
    .sass('assets/sass/editorStyles.sass', './dist/css/editor.css')
    .sourceMaps();
