var gulp = require('gulp');
var builder = require('lightweb-builder').default;

gulp.task('css', function () {
    (new builder)
        .withMinify()
        //.withGzip()
        .withSourceMaps()
        .then.scss(function(compiler) {
            compiler
                .file('resources/stylesheets/layout.scss')
                .autoPrefix();
        })
        .build('./public/assets/app.css')
});

gulp.task('js', function () {
    (new builder)
        .withMinify()
        //.withGzip()
        .withCommonJs()
        .withPolyfill()
        .withSourceMaps()
        .then.js(require.resolve('knockout/build/output/knockout-latest'))
        .then.js('resources/javascripts/vendor/highlight-9.4.0.js')
        .then.es7(function (compiler) {
            compiler
                .path('resources/javascripts/ko/')
                .namespace('Ko');
        })
        .then.es7(function (compiler) {
            compiler
                .plugin('syntax-flow')
                .plugin('transform-flow-strip-types')
                .path('resources/javascripts/app/')
                .namespace('/');
        })
        .build('./public/assets/app.js');
});

gulp.task('default', ['css', 'js'], function () {
    //
});