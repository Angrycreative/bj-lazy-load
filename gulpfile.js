const fs = require('fs');
const flatten = require('flat');
const package = JSON.parse(fs.readFileSync('./package.json'));
const gulp = require('gulp');
const replace = require('gulp-replace');
const wpPot = require('gulp-wp-pot');
const zip = require('gulp-zip');
const uglify = require('gulp-uglify');
const rename = require("gulp-rename");
const del = require('del');

/**
 * Delete all files and folders in the tmp folder
 */
function cleanTemp() {
    return del(['tmp/**']);
}

/**
 * Copy files to tmp directory and substitute variables from package.json
 */
function copyFiles() {
    const packageVars = flatten(package);
    return gulp
        .src(['src/**/**', '!**/.git*', '!**/composer.json', '!**/.eslintrc.js'], { base: 'src/' })
        .pipe(replace(
            /%\{\{([^{}\s]+)\}\}/g,
            function (match, packageVar) {
                return packageVars[packageVar] || '';
            }
        ))
        .pipe(gulp.dest('tmp/'));
}

/**
 * Generate POT file for translations
 */
function generatePot() {
    return gulp.src('tmp/**/**.php')
        .pipe(wpPot({
            domain: package.wordpress.translation.domain,
            package: `${package.wordpress.title} ${package.version} `,
        }))
        .pipe(gulp.dest(`tmp/lang/${package.wordpress.translation.domain}.pot`));
}

/**
 * Minify JavaScript libraries
 */
function minifyJs() {
    return gulp.src('tmp/js/**.js')
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('tmp/js/'));
}

/**
 * Generate ZIP archive for plugin distribution
 */
function generateZip() {
    return gulp.src('tmp/**/**')
        .pipe(zip( package.name + '.zip' ))
        .pipe(gulp.dest('dist'));
}

const zipTask = gulp.series(
    cleanTemp,
    copyFiles,
    gulp.parallel(minifyJs, generatePot),
    generateZip
);
zipTask.description = 'create WordPress plugin zip archive';

exports.default = zipTask;
