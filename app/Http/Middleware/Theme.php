<?php namespace App\Http\Middleware;

use Illuminate\View\Factory;
use App\Services\Menu;
use Closure;

class Theme {

	public function __construct(Factory $view, Menu $menu)
	{
		$this->view = $view;
		$this->menu= $menu;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// theme
		$this->view->addLocation(public_path() . '/themes/default');
		include(public_path() . '/themes/default/helpers.php');

		// menu
		$this->menu->make('nav');

		// composers
		$this->loadComposers();

		return $next($request);
	}

	private function loadComposers()
	{
		$this->view->composers(array(
		    'App\\Http\\Composers\\TicketsAssignedCountComposer' => ['tickets.index'],
		    'App\\Http\\Composers\\TicketsClosedCountComposer' => ['tickets.index'],
		    'App\\Http\\Composers\\TicketsOpenCountComposer' => ['tickets.index'],
		    'App\\Http\\Composers\\TicketPrioritiesComposer' => ['tickets.create'],
		    'App\\Http\\Composers\\DeptComposer' => ['tickets.index', 'tickets.show', 'tickets.create', 'reports.index', 'tickets.edit', 'user.timelog', 'me.time.index', 'me.time.edit', 'me.time.delete'],
		    'App\\Http\\Composers\\StaffComposer' => ['tickets.index', 'tickets.create', 'tickets.show']
		));
	}

}
