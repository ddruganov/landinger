const gulp = require("gulp");
const del = require("del");

const sass = require("gulp-sass")(require("sass"));
const csso = require("gulp-csso");
const rename = require("gulp-rename");

const ts = require("gulp-typescript");
const babel = require("gulp-babel");
const uglify = require("gulp-uglify");

const baseOutputFolder = "dist";
const cssOutputFolder = baseOutputFolder + "/css";
const jsOutputFolder = baseOutputFolder + "/js";

const cleanWorkload = (cb) => {
  del.sync([baseOutputFolder]);
  cb();
};

/* WORKLOADS */
const scssWorkload = async () => {
  gulp.src("src/scss/index.scss").pipe(sass()).pipe(csso()).pipe(rename("main.css")).pipe(gulp.dest(cssOutputFolder));
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
    .pipe(uglify())
    .pipe(gulp.dest(jsOutputFolder));
};

/* DEFAULT */
const directCompilation = gulp.parallel(scssWorkload, tsWorkload);
gulp.task("default", gulp.series(cleanWorkload, directCompilation));

/* WATCH */
gulp.task("watch", () => {
  gulp.watch("./src/scss/**/*.scss", scssWorkload);
  gulp.watch("./src/ts/**/*.ts", tsWorkload);
});
