var elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    gutil = require('gulp-util'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    cssnano = require('cssnano'),
    fonts = require('postcss-font-magician'),
    sass = require('gulp-sass'),
    media = require('gulp-group-css-media-queries'),
    run = require("run-sequence"),
    sourcemaps = require('gulp-sourcemaps');


//require('laravel-elixir-vue-2');

gulp.task('postcss', function() {
    const processor = ([
        autoprefixer({browsers: ['last 10 version']}),
        cssnano(),
        fonts()
    ]);
    return gulp.src('./resources/assets/sass/*.sass')
        .pipe(sass().on('error', gutil.log))
        //.pipe(sourcemaps.init())
        .pipe(postcss(processor))
        .pipe(media())
        //.pipe(sourcemaps.write())
        .pipe(gulp.dest('./public/css'));
});
gulp.task("mmm", function(fn) {
    run(
        "postcss",
        "version",
        fn
    );
});

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(function(mix) {
    console.log('Compiling in', elixir.config.production ? 'PRODUCTION' : 'DEVELOPMENT', 'mode.' );
    // Customer area
    //mix.sass('app.sass');
    mix.task('postcss');
    mix.scripts([
        'vendor/jquery-3.2.1.min.js',
        'vendor/jquery.carousel.js',
        'vendor/jquery.mask.min.js',
        'vendor/nouislider.min.js',
        'vendor/hammer.min.js',
        'vendor/jquery.fancybox.min.js',
        'vendor/jquery.horizontalScroll.js',
        'vendor/jquery.ajax.autocomplete.min.js'
    ], 'public/js/vendor.js');

    mix.scripts([
        'libs/loader.js'
    ], 'public/js/lib.js');

    mix.scripts([
         'filters.js',
        'map.js',
        'app.js'
    ], 'public/js/app.js');
    mix.version([
        'assets/admin/css/admin.css',
        'assets/admin/js/admin.js',
        'css/app.css',
        'js/vendor.js',
        'js/lib.js',
        'js/app.js'
    ]);

});