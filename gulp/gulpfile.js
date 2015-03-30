var gulp = require ('gulp'),
    livereload = require('gulp-livereload');

gulp.task ('default', function () {
  livereload.listen ();

  ['./root/index.html', './root/style.css', './root/main.js'].forEach (function (t) {
    gulp.watch (t).on ('change', function () {
      gulp.run ('reload');
    });
  });
});

gulp.task ('reload', function () {
  livereload.changed ();
  console.info ('\nReLoad Browser!\n');
});
