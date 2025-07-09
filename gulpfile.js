import gulp from 'gulp';
import sass from 'gulp-sass';
import * as dartSass from 'sass';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'gulp-autoprefixer';
import cleanCSS from 'gulp-clean-css';
import browserSync from 'browser-sync';

const sassCompiler = sass(dartSass);
const bs = browserSync.create();

// Paths
const paths = {
  sass: './sass/**/*.scss',
  sassMain: './sass/style.scss',
  css: './',
  php: './**/*.php',
  js: './js/**/*.js'
};

// Compile SASS to CSS with sourcemaps
function styles() {
  return gulp.src(paths.sassMain)
    .pipe(sourcemaps.init())
    .pipe(sassCompiler().on('error', sassCompiler.logError))
    .pipe(autoprefixer())
    .pipe(cleanCSS())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.css))
    .pipe(bs.stream());
}

// Watch files
function watch() {
  bs.init({
    proxy: 'localhost:8888/SnowTrail', // Adjust this to your local WordPress URL
    notify: false,
    open: false
  });

  gulp.watch(paths.sass, styles);
  gulp.watch(paths.php).on('change', bs.reload);
  gulp.watch(paths.js).on('change', bs.reload);
}

// Build for production (without sourcemaps)
function build() {
  return gulp.src(paths.sassMain)
    .pipe(sassCompiler().on('error', sassCompiler.logError))
    .pipe(autoprefixer())
    .pipe(cleanCSS())
    .pipe(gulp.dest(paths.css));
}

// Export tasks
export { styles, watch, build };
export default watch; 