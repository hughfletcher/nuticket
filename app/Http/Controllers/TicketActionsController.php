<?php namespace App\Http\Controllers;

use App\Contracts\Repositories\TicketActionInterface;
use App\Http\Requests\ActionCreateRequest;
use Carbon\Carbon;

class TicketActionsController extends BaseController {

	public function __construct(TicketActionInterface $action)
	{
		$this->action = $action;
	}

	public function store(ActionCreateRequest $request)
	{
		if ($request->get('type') == 'reply') {
			$request->merge(['time_at' => Carbon::createFromFormat(config('settings.format.date'), $request->get('time_at'))]);

			if (in_array($request->get('status'), ['closed', 'resolved', 'open'])) {
				$request->merge(['type' => $request->get('status')]);
			}
		}

		$action = $this->dispatchFrom('App\Jobs\ActionCreateJob', $request);

		return redirect()->route('tickets.show', [$action->ticket_id, '#action-' . $action->id]);
	}


}
