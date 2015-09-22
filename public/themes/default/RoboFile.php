<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    public function styles ($env = 'local') 
    {
    	$this->taskConcat([
    		'bower_components/AdminLTE/css/AdminLTE.css',
	        'bower_components/select2/select2.css',
	        'bower_components/select2-bootstrap-css/select2-bootstrap.css',
	        'bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css',
	        'assets/css/styles.css'
    		])
        	->to('css/all.css')
        	->run();
    }

    public function copy () 
    {
    	$this->taskFileSystemStack()
    	    ->copy('bower_components/bootstrap/dist/css/bootstrap.min.css', 'css/bootstrap.min.css')
		    ->copy('bower_components/bootstrap/fonts', 'fonts')
		    ->copy('bower_components/font-awesome/css/font-awesome.min.css', 'css/font-awesome.min.css')
		    ->copy('bower_components/font-awesome/fonts', 'fonts')
		    ->copy('bower_components/select2/select2-spinner.gif', 'css/select2-spinner.gif')
		    ->copy('bower_components/select2/select2x2.png', 'css/select2x2.png')
		    ->copy('bower_components/select2/select2.png', 'css/select2.png')
		    ->copy('bower_components/jquery/dist/jquery.min.js', 'js/jquery.min.js')
		    ->copy('bower_components/bootstrap/dist/js/bootstrap.min.js', 'js/bootstrap.min.js')
		    ->run();
    }

    public function scripts()
    {
    	$this->taskConcat([
    		'assets/js/app.js',
    		'assets/js/modals/userSelect.js',
			'assets/js/controllers/ticketsCreate.js',
	    	])
	    	->to('js/app.js')
	        ->run();

    	$this->taskConcat([
			'bower_components/select2/select2.js',
	    	'bower_components/moment/moment.js',
	    	'bower_components/bootstrap-daterangepicker/daterangepicker.js',
	    	'bower_components/AdminLTE/js/app.js'
	    	])
	    	->to('js/libs.js')
	        ->run();
	}

	public function watch()
	{
		$this->taskWatch()
		 	->monitor('assets/js', function() {
		     $this->taskExec('robo scripts')->run();
		})->run();
	}

}