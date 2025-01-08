import nodeSass from "node-sass";
import gulp from "gulp";
const { src, dest, watch, series, parallel } = gulp;
import sourcemaps from "gulp-sourcemaps";
import gulpSass from "gulp-sass";
import autoPrefixer from "gulp-autoprefixer";

import * as sass from "sass";
const scss = gulpSass(sass);
scss.compiler = nodeSass;

function css() {
    var common = src('scss/common*.scss')
            .pipe(scss())
            .pipe(autoPrefixer())
            .pipe(dest('application/views/css'))
                .pipe(dest('dashboard/views/css'));
        
    var frontend = src('scss/frontend*.scss')
            .pipe(scss())
            .pipe(autoPrefixer())
                .pipe(dest('application/views/css'));
        
    var dashboard = src('scss/dashboard*.scss')
            .pipe(scss())
            .pipe(autoPrefixer())
                .pipe(dest('dashboard/views/css'));
        
   var manager = src('scss/manager*.scss')
            .pipe(scss())
            .pipe(autoPrefixer())
                .pipe(dest('manager/views/css'));
        
    var course = src('scss/course-personal*.scss')
            .pipe(scss())
            .pipe(autoPrefixer())
                .pipe(dest('dashboard/views/css'));

    var course = src('scss/quiz*.scss')
            .pipe(scss())
            .pipe(autoPrefixer())
                .pipe(dest('dashboard/views/css'));
        
    var forum = src('scss/forum*.scss')
            .pipe(scss())
            .pipe(autoPrefixer())
            .pipe(dest('application/views/css'));
    return (common, frontend, dashboard, manager, course, forum);
}

// Watch files
function watchFiles() {
    watch(["scss"], css);
}

export { watchFiles as watch };
export default series(css);
