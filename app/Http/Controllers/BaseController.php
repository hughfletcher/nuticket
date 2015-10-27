<?php  namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller {

    use AuthorizesRequests;
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = $this->app['view']->make($this->layout);
		}
	}

}
