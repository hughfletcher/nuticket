<?php namespace App\Http\Controllers;

use App\Contracts\Repositories\TicketInterface;
use App\Http\Requests\ActionCreateRequest;
use Carbon\Carbon;

class TicketActionsController extends BaseController {

	public function __construct(TicketInterface $ticket)
	{
		$this->ticket = $ticket;
	}

	public function store(ActionCreateRequest $request)
	{
		$ticket = $this->ticket->find($request->get('ticket_id'));

		if ($request->get('type') == 'reply') {
			$request->merge(['time_at' => Carbon::createFromFormat(config('settings.format.date'), $request->get('time_at'))]);

			// set type to closed/resolved
			if (in_array($request->get('status'), ['closed', 'resolved'])) {
				$request->merge(['type' => $request->get('status')]);
			}

			//set type to open
			if(in_array($ticket->status, ['closed', 'resolved']) && $request->get('status') == 'open') {
				$request->merge(['type' => 'open']);
			}
		}

		$action = $this->dispatchFrom('App\Jobs\ActionCreateJob', $request);

		return redirect()->route('tickets.show', [$action->ticket_id, '#action-' . $action->id]);
	}


}
