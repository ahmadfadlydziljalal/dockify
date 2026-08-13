const gulp = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const cleanCSS = require("gulp-clean-css");
const uglify = require("gulp-uglify");
const concat = require("gulp-concat");
const rename = require("gulp-rename");
const sourcemaps = require("gulp-sourcemaps");
const browserSync = require("browser-sync").create();

const paths = {
  scss: "./themes/assets/css/**/*.scss",
  colorModeJs: "./themes/assets/js/color-mode.js",
  appJs: "./themes/assets/js/main.js",
  php: "./views/**/*.php", // pantau file PHP
  distCss: "./themes/dist/css",
  distJs: "./themes/dist/js",
  bootstrapJs: "./vendor/twbs/bootstrap/dist/js/bootstrap.bundle.js"
};

// Compile SCSS → CSS → Minify
function styles() {
  return gulp
    .src(paths.scss)
    .pipe(sourcemaps.init())
    .pipe(sass().on("error", sass.logError))
    .pipe(cleanCSS())
    .pipe(rename({ suffix: ".min" }))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest(paths.distCss))
    .pipe(browserSync.stream()); // inject CSS tanpa reload full
}

// Bundle & Minify JS (Bootstrap + custom scripts)
function scripts() {
  return gulp
    .src([
      paths.bootstrapJs, // bootstrap dulu
      paths.colorModeJs, // lalu color mode
      paths.appJs        // lalu main entry
    ])
    .pipe(sourcemaps.init())
    .pipe(concat("main.js"))
    .pipe(uglify())
    .pipe(rename({ suffix: ".min" }))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest(paths.distJs))
    .pipe(browserSync.stream()); // reload setelah JS update
}

// BrowserSync init
function serve() {
  browserSync.init({
    proxy: "http://php", // service PHP container
    port: 3000, // port browser-sync
    open: false, // auto buka browser
    notify: false
  });

  gulp.watch(paths.scss, styles);
  gulp.watch([paths.colorModeJs, paths.appJs], scripts);
  gulp.watch(paths.php).on("change", browserSync.reload);
}

const build = gulp.series(gulp.parallel(styles, scripts));

exports.styles = styles;
exports.scripts = scripts;
exports.serve = gulp.series(build, serve);
exports.default = build;
