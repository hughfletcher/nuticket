<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    private $defaultThemeBasePath = 'public/themes/adminlte/';
    private $defaultThemeAssetPath = 'public/themes/adminlte/assets/';
    private $bowerPath = 'vendor/bower_components/';

    public function styles ($env = 'local')
    {

    	$this->taskConcat([
    		$this->b('AdminLTE/dist/css/AdminLTE.css'),
            'vendor/bower_components/AdminLTE/dist/css/skins/skin-blue.css',
	        'vendor/bower_components/select2/select2.css',
	        'vendor/bower_components/select2-bootstrap-css/select2-bootstrap.css',
	        'vendor/bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css',
	        'public/themes/adminlte/assets/css/styles.css'
    		])
        	->to($this->t('css/all.css'))
        	->run();
    }

    public function copy ()
    {
    	$this->taskFileSystemStack()
    	    ->copy('vendor/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css', 'public/themes/adminlte/css/bootstrap.min.css')
		    ->copy('vendor/bower_components/fontawesome/css/font-awesome.min.css', 'public/themes/adminlte/css/font-awesome.min.css')
		    ->copy('vendor/bower_components/select2/select2-spinner.gif', 'public/themes/adminlte/css/select2-spinner.gif')
		    ->copy('vendor/bower_components/select2/select2x2.png', 'public/themes/adminlte/css/select2x2.png')
		    ->copy('vendor/bower_components/select2/select2.png', 'public/themes/adminlte/css/select2.png')
		    ->copy('vendor/bower_components/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js', 'public/themes/adminlte/js/jquery.min.js')
		    ->copy('vendor/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js', 'public/themes/adminlte/js/bootstrap.min.js')
		    ->run();

        $this->taskCopyDir(
            [
                'vendor/bower_components/AdminLTE/bootstrap/fonts' => 'public/themes/adminlte/fonts',
                'vendor/bower_components/fontawesome/fonts' => 'public/themes/adminlte/fonts'
            ]
        )->run();
    }

    public function scripts()
    {
        $this->taskFileSystemStack()
            ->mkdir($this->t('js'))
            ->run();

    	$this->taskConcat([
            $this->a('js/app.js'),
            $this->a('js/modals/*'),
            $this->a('js/controllers/*')
	    	])
            ->to($this->t('js/app.js'))
	        ->run();

    	$this->taskConcat([
            $this->b('select2/select2.js'),
            $this->b('moment/moment.js'),
            $this->b('bootstrap-daterangepicker/daterangepicker.js'),
            'vendor/bower_components/AdminLTE/dist/js/app.js'
	    	])
            ->to($this->t('js/libs.js'))
	        ->run();
	}

    public function production()
    {
        $this->copy();
        $this->styles();
        $this->scripts();

        $this->taskMinify($this->t('css/all.css'))->to($this->t('css/all.css'))->run();
        $this->taskMinify($this->t('js/app.js'))->to($this->t('js/app.js'))->run();
        $this->taskMinify($this->t('js/libs.js'))->to($this->t('js/libs.js'))->run();
    }

	public function watch()
	{
        $this->taskExec('browser-sync')
            ->arg('start')
            ->option('proxy', 'localhost')
            ->option('files', 'public/themes/js/*.js, public/themes/adminlte/assets/css/*.css, public/themes/adminlte/views/*.php')
            ->option('port', 3000)
            ->background()->run();
            
		$this->taskWatch()
		 	->monitor('public/themes/adminlte/assets/js', function() {
		     $this->taskExec('robo scripts')->run();
		})->run();


	}

    public function testing()
    {

        $this->taskWatch()->monitor('tests', function ($event) {
            $this->taskPHPUnit()
                ->files([$event->getResource()])
                ->run();
        })->run();

    }

    public function test($suite = 'Unit', $opts = ['coverage|c' => false])
    {
        $task = $this->taskPHPUnit()->option('testsuite', ucfirst($suite));

        if ($opts['coverage']) {
            $task = $task->option('coverage-html', 'public/cc/' . strtolower($suite));
        }

        $task->run();
    }

    public function FunctionName($value='')
    {
        # code...
    }

    private function b($path = '')
    {
        return $this->bowerPath . $path;
    }

    private function a($path = '')
    {
        return $this->defaultThemeAssetPath . $path;
    }

    private function t($path = '')
    {
        return $this->defaultThemeBasePath . $path;
    }
}
