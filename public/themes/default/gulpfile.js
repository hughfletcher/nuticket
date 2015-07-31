var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.styles([
        'bower_components/AdminLTE/css/AdminLTE.css',
        'bower_components/select2/select2.css',
        'bower_components/select2-bootstrap-css/select2-bootstrap.css',
        'bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css',
        'assets/css/styles.css'
    ], 'css/all.css', '../default')
    .copy('bower_components/bootstrap/dist/css/bootstrap.min.css', 'css/bootstrap.min.css')
    .copy('bower_components/bootstrap/fonts', 'fonts')
    .copy('bower_components/font-awesome/css/font-awesome.min.css', 'css/font-awesome.min.css')
    .copy('bower_components/font-awesome/fonts', 'fonts')
    .copy('bower_components/select2/select2-spinner.gif', 'css/select2-spinner.gif')
    .copy('bower_components/select2/select2x2.png', 'css/select2x2.png')
    .copy('bower_components/select2/select2.png', 'css/select2.png')
    .copy('bower_components/jquery/dist/jquery.min.js', 'js/jquery.min.js')
    .copy('bower_components/bootstrap/dist/js/bootstrap.min.js', 'js/bootstrap.min.js')
    .scripts([
    	'bower_components/select2/select2.js',
    	'bower_components/moment/moment.js',
    	'bower_components/bootstrap-daterangepicker/daterangepicker.js',
    	'bower_components/AdminLTE/js/app.js'
    ], 'js/libs.js', '../default');
});
