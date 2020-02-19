// npm install --save-dev gulp gulp-postcss gulp-sourcemaps autoprefixer postcss-cssnext postcss-nested postcss-mixins postcss-import csswring rucksack-css css-mqpacker 
// npm install --global gulp-cli
// npm install --g postcss  

// Gulp *
var gulp = require('gulp')
// Uso de PostCSS *
var postcss = require('gulp-postcss')
// Reutilizar estilos de CSS *
var mixins = require('postcss-mixins')
// Importar archivos de CSS dentro de uno solo *
var atImport = require ('postcss-import')
// Extienden la sintaxis de CSS, la posibilidad de anidar clases *
var cssnested = require('postcss-nested')
// Para juntar media queries similares en una sola *
var mqpacker = require('css-mqpacker')
// Crear tamaños responsivos para las fuentes *
var rucksack = require('rucksack-css')
// Minificar CSS *
var csswring = require('csswring')
// Utilizar hoy la sintaxis CSS del mañana *
var cssnext = require('postcss-cssnext')


// Tarea para procesar el CSS
gulp.task('css', function () {
  var processors = [
    mixins(),
    atImport(),
    cssnested,
    rucksack(),
    cssnext({browsers:'last 5 versions'}),
    mqpacker,
    csswring()
  ]
  return gulp.src(['./src/css/*.css','./src/css/*.min.css'])
    .pipe(postcss(processors))
    .pipe(gulp.dest('./assets/css'))
})

// Tarea para procesar JS
gulp.task('js', function() {
  return gulp.src('./src/js/**/*.js') 
    .pipe(gulp.dest('./assets/js'))
});


// Tarea para vigilar los cambios
gulp.task('watch', function () {
  gulp.watch('./src/css/**/*.css', ['css'])
  gulp.watch('./src/js/**/*.js', ['js'])
})

gulp.task('default', ['css','js','watch'])