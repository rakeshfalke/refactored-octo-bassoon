var project 		= 'globant_referral', // Project name, used for build zip.
  url 			    = 'referral-portal.dev', // Local Development URL for BrowserSync. Change as-needed.
  node 			    = './node_modules/'; // Not truly using this yet, more or less playing right now. TO-DO Place in Dev branch

// Load plugins
var gulp     = require('gulp'),
  browserSync  = require('browser-sync'), // Asynchronous browser loading on .scss file changes
  reload       = browserSync.reload,
  autoprefixer = require('gulp-autoprefixer'), // Autoprefixing magic
  minifycss    = require('gulp-uglifycss'),
  filter       = require('gulp-filter'),
  uglify       = require('gulp-uglify'),
  // imagemin     = require('gulp-imagemin'),
  // newer        = require('gulp-newer'),
  rename       = require('gulp-rename'),
  concat       = require('gulp-concat'),
  notify       = require('gulp-notify'),
  runSequence  = require('gulp-run-sequence'),
  sass         = require('gulp-sass'),
  // plugins      = require('gulp-load-plugins')({ camelize: true }),
  ignore       = require('gulp-ignore'), // Helps with ignoring files and directories in our run tasks
  // rimraf       = require('gulp-rimraf'), // Helps with removing files and directories in our run tasks
  // zip          = require('gulp-zip'), // Using to zip up our packaged theme into a tasty zip file that can be installed in WordPress!
  plumber      = require('gulp-plumber'), // Helps prevent stream crashing on errors
  cache        = require('gulp-cache'),
  sourcemaps   = require('gulp-sourcemaps');

gulp.task('browser-sync', function() {
  var files = [
    '**/*.{twig,js,scss}',
    '**/*.{png,jpg,gif}'
  ];
  browserSync.init(files, {

    proxy: url,
    open: true,
    injectChanges: true

  });
});


gulp.task('styles', function () {
  gulp.src('./sass/**/*.scss')
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(sass({
      errLogToConsole: true,

      //outputStyle: 'compressed',
      outputStyle: 'compact',
      // outputStyle: 'nested',
      // outputStyle: 'expanded',
      precision: 10
    }))
    .pipe(sourcemaps.write({includeContent: false}))
    .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(autoprefixer('last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(sourcemaps.write('.'))
    .pipe(plumber.stop())
    .pipe(gulp.dest('./css/'))
    .pipe(filter('**/*.css')) // Filtering stream to only css files
    .pipe(reload({stream:true})) // Inject Styles when style file is created
    .pipe(rename({ suffix: '.min' }))
    .pipe(minifycss({
      maxLineLen: 80
    }))
    .pipe(gulp.dest('./'))
    .pipe(reload({stream:true})) // Inject Styles when min style file is created
    .pipe(notify({ message: 'Styles task complete', onLast: true }))
});

// Watch Task
gulp.task('default', ['browser-sync'], function () {
  gulp.watch('./sass/**/*.scss', ['styles']);
});
