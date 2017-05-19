var elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    gutil = require('gulp-util'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    cssnano = require('cssnano'),
    fonts = require('postcss-font-magician'),
    sass = require('gulp-sass'),
    media = require('gulp-group-css-media-queries'),
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
gulp.task('watch1', function() {
    gulp.watch('./resources/assets/sass/*.sass', ['postcss']);
})
gulp.task('watch2', function() {
    gulp.watch('./resources/assets/sass/*.sass');
})

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

    // Admin area
    //mix.webpack('admin/app.js', 'public/js/admin/app.js');
    mix.version([
        'js/admin/app.js'
    ]);

    // Customer area
    mix.task('postcss');
    mix.task('watch1');
    mix.scripts([
        'vendor/jquery-3.2.1.min.js',
        'vendor/jquery.carousel.js',
        'vendor/jquery.mask.min.js',
        'vendor/nouislider.min.js',
        'vendor/hammer.min.js',
        'vendor/jquery.fancybox.min.js'
    ], 'public/js/vendor.js');

    mix.scripts([
        'libs/loader.js'
        //'libs/inject_params.js',
        //'libs/cart.js',
        //'libs/defer.js',
        //'libs/thank.js'
    ], 'public/js/lib.js');

    mix.scripts([
         //'cart.js',
        // 'defer.js',
        // 'fastbuy.js',
        // 'subscribe.js',
         'filters.js',
        // 'home.filters.js',
        // 'reviews.js',
        // 'search.js',
        // 'size-selector.js',
        // 'contacts.js',
        'map.js',
        'app.js'
    ], 'public/js/app.js');

    mix.version([
        'css/app.css',
        'js/vendor.js',
        'js/lib.js',
        'js/app.js'
    ]);
});
