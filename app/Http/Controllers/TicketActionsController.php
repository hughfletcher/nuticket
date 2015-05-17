<?php namespace App\Http\Controllers;

use App\Repositories\TicketActionInterface;
use App\Validators\TicketActionValidator;
use Illuminate\Foundation\Application;

class TicketActionsController extends BaseController {

	public function __construct(Application $app, TicketActionInterface $action, TicketActionValidator $validator) {

		$this->app = $app;
		$this->action = $action;
		$this->validator = $validator;
	}

	public function store($type) {

		$actionValidator = $this->validator->make($this->app['request']->all())->addContext($type);

		if ($actionValidator->fails()) {

		  	return $this->app['redirect']->route('tickets.show', [$this->app['request']->input('ticket_id'), '#action'])
		  		->withErrors($actionValidator)
		  		->withInput()
		  		->with('type', $type);
			
		} else {

			$attrs = array_merge($actionValidator->getAttributes(), ['type' => $type]);

			if (isset($attrs[$type . '_status'])) {
	            $attrs['status'] = $attrs[$type . '_status'];
	        }

			if (isset($attrs[$type . '_time'])) {
	            $attrs['time_spent'] = $attrs[$type . '_time'];
	        }

			$action = $this->action->createAndUpdateTicket($attrs);

			return $this->app['redirect']->route('tickets.show', [$action->ticket_id, '#action-' . $action->id]);
		}
	}


}