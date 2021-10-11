const gulp = require("gulp");
const del = require("del");

const sass = require("gulp-sass")(require("sass"));
const csso = require("gulp-csso");
const rename = require("gulp-rename");

const ts = require("gulp-typescript");
const babel = require("gulp-babel");
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");

const outputFolder = "dist";

const cleanWorkload = (cb) => {
  del.sync([outputFolder]);
  cb();
};

/* WORKLOADS */
const scssWorkload = async () => {
  gulp.src("src/scss/index.scss").pipe(sass()).pipe(csso()).pipe(gulp.dest(outputFolder));
};
const tsWorkload = async () => {
  gulp
    .src("src/ts/**/*.ts")
    .pipe(
      ts({
        module: "es6",
        target: "esnext",
        lib: ["DOM", "DOM.Iterable", "ESNext"],
      })
    )
    // .pipe(babel({ presets: ["@babel/env"] }))
    .pipe(concat("knob.js"))
    .pipe(uglify())
    .pipe(gulp.dest(outputFolder));
};

/* DEFAULT */
const directCompilation = gulp.parallel(scssWorkload, tsWorkload);
gulp.task("default", gulp.series(cleanWorkload, directCompilation));

/* WATCH */
gulp.task("watch", () => {
  gulp.watch("./src/scss/**/*.scss", scssWorkload);
  gulp.watch("./src/ts/**/*.ts", tsWorkload);
});
