<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    private $defaultThemeBasePath = 'public/themes/default/';
    private $defaultThemeAssetPath = 'public/themes/default/assets/';
    private $bowerPath = 'vendor/bower_components/';

    public function styles ($env = 'local')
    {

    	$this->taskConcat([
    		$this->b('AdminLTE/css/AdminLTE.css'),
	        'vendor/bower_components/select2/select2.css',
	        'vendor/bower_components/select2-bootstrap-css/select2-bootstrap.css',
	        'vendor/bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css',
	        $this->a('css/styles.css')
    		])
        	->to($this->t('css/all.css'))
        	->run();
    }

    public function copy ()
    {
    	$this->taskFileSystemStack()
    	    ->copy($this->b('bootstrap/dist/css/bootstrap.min.css'), $this->t('css/bootstrap.min.css'))
		    ->copy('vendor/bower_components/font-awesome/css/font-awesome.min.css', 'public/themes/default/css/font-awesome.min.css')
		    ->copy('vendor/bower_components/select2/select2-spinner.gif', 'public/themes/default/css/select2-spinner.gif')
		    ->copy('vendor/bower_components/select2/select2x2.png', 'public/themes/default/css/select2x2.png')
		    ->copy('vendor/bower_components/select2/select2.png', 'public/themes/default/css/select2.png')
		    ->copy('vendor/bower_components/jquery/dist/jquery.min.js', 'public/themes/default/js/jquery.min.js')
		    ->copy('vendor/bower_components/bootstrap/dist/js/bootstrap.min.js', 'public/themes/default/js/bootstrap.min.js')
		    ->run();

        $this->taskCopyDir(
            [
                'vendor/bower_components/bootstrap/dist/fonts' => 'public/themes/default/fonts',
                'vendor/bower_components/font-awesome/fonts' => 'public/themes/default/fonts'
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
            $this->b('AdminLTE/js/app.js')
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
		$this->taskWatch()
		 	->monitor('public/themes/default/assets/js', function() {
		     $this->taskExec('robo scripts')->run();
		})->run();
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
