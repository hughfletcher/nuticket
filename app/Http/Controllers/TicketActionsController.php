<?php namespace App\Http\Controllers;

use App\Contracts\Repositories\TicketActionInterface;
use App\Http\Requests\FormActionCreateRequest;
use Illuminate\Foundation\Application;
use App\Events\ActionCreatedEvent;
use Auth;

class TicketActionsController extends BaseController {

	public function __construct(TicketActionInterface $action)
	{
		$this->action = $action;
	}

	public function store(FormActionCreateRequest $request)
	{

		$action = $this->action->create(array_add($request->all(), 'user_id', Auth::user()->id));

        event(new ActionCreatedEvent(collect([$action])));

		return redirect()->route('tickets.show', [$action->ticket_id, '#action-' . $action->id]);
	}


}
