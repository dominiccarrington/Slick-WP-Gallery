"use strict";

const gulp = require('gulp');
const sass = require('gulp-sass');
const minify = require('gulp-minify');
const cleanCss = require('gulp-clean-css');
const svgmin = require('gulp-svgmin');
const autoprefixer = require('gulp-autoprefixer');

sass.compiler = require('node-sass');

gulp.task('sass', () => {
    return gulp.src("./assets/scss/**/*.scss")
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(cleanCss())
        .pipe(gulp.dest('./assets/css'));
});

gulp.task('js', () => {
    return gulp.src(['./assets/js/**/*.js', '!./assets/js/**/*.min.js'])
        .pipe(minify({
            ext: {
                src: '.js',
                min: '.min.js'
            },
            ignoreFiles: ['.min.js']
        }))
        .pipe(gulp.dest((file) => file.base));
});

gulp.task('svg', () => {
    return gulp.src('./assets/svg/**/*.svg')
        .pipe(svgmin({
            plugins: [{
                cleanupIDs: false
            }]
        }))
        .pipe(gulp.dest((file) => file.base));
});

gulp.task('watch', () => {
    gulp.watch('./assets/scss/**/*.scss', gulp.series(['sass']));
    gulp.watch('./assets/js/**/*.js', gulp.series(['js']));
    gulp.watch('./assets/svg/**/*.svg', gulp.series(['svg']));
});

gulp.task('default', gulp.parallel(['sass', 'js', 'svg']));

