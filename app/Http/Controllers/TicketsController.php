<?php namespace App\Http\Controllers;

use App\Repositories\TicketInterface;
use App\Repositories\TicketActionInterface;
use App\Http\Requests\QueryTicketRequest;
use App\Http\Requests\FormTicketCreateRequest;
use App\Http\Requests\FormTicketUpdateRequest;
use View, Request, Auth, Redirect, Theme;

class TicketsController extends BaseController {

	public function __construct(TicketInterface $ticket, TicketActionInterface $action) {

		$this->tickets = $ticket;
		$this->action = $action;
	}

	/**
	 * Display a listing of the resource.
	 * GET /app\s\dash
	 *
	 * @return Response
	 */
	public function index(QueryTicketRequest $request)
	{
		$tickets = $this->tickets->paginateByRequest($request->get('per_page', config('system.page_size')));
		
		return View::make('tickets.index', compact('tickets'));
	}

	public function show($id) {
		$ticket = $this->tickets->find($id);
		return View::make('tickets.show', compact('ticket'));
	}

	public function create() {
		return View::make('tickets.create');
	}

	public function store(FormTicketCreateRequest $request) 
	{
		$ticket = $this->tickets->create(array_add($request->except('hours'), 'auth_id', Auth::user()->id));

		$hours = $request->get('hours');

		if ($request->has('reply_body')) 
		{ 
			$this->action->create([
				'ticket_id' => $ticket->id,
				'user_id' => Auth::user()->id,
				'type' => in_array($request->get('status'), ['closed', 'resolved']) ? $request->get('status') : 'reply',
				'body' => $request->get('reply_body'),
				'hours' => $hours,
				'time_at' => $request->get('time_at'),
				'status' => $request->has('status') ? $request->get('status') : 'open'
			]);
			$hours = 0;
		}

		if ($request->has('comment_body')) 
		{ 
			$this->action->create([
				'ticket_id' => $ticket->id,
				'user_id' => Auth::user()->id,
				'type' => 'comment',
				'body' => $request->get('comment_body'),
				'hours' => $hours,
				'time_at' => $request->get('time_at'),
				'status' => $request->has('status') ? $request->get('status') : 'open'
			]);
		}

		return Redirect::route('tickets.show', [$ticket['id']])
			->with('message', 'Ticket #' . $ticket['id'] . ' sucessfully created.');

	}

	public function edit($id) {
		$ticket = $this->tickets->find($id);
		return View::make('tickets.edit', compact('ticket'));
	}

	public function update(FormTicketUpdateRequest $request, $id) {

		$attrs = $request->all();

		//get old ticket/sction data so we can see if anything is changing
		$old_ticket = $this->tickets->find($id);
		$old_create = $this->action->findTicketCreate($id);

		//lets see what's exactly changing
		$changed = [];
		
		if ($attrs['user_id'] != $old_ticket['user_id']) {
			$changed[] = 'user_id';
		}	

		if ($attrs['priority'] != $old_ticket['priority']) {
			$changed[] = 'priority';
		}	

		if ($attrs['title'] != $old_create['title']) {
			$changed[] = 'title';
		}

		if ($attrs['body'] != $old_create['body']) {
			$changed[] = 'body';
		}

		$old_ticket = array_merge($old_ticket->toArray(), array_only($old_create, ['title', 'body']));

		//no changes, user is wasting our time
		if (empty($changed)) {
	
			return Redirect::route('tickets.edit', [$id])
				->with('message', 'There were no changes made to this ticket.')
				->withInput();
		}

		//update ticket/create action
		$this->tickets->update($id, array_only($attrs, ['user_id', 'priority']));
		$this->action->update($old_create['id'], array_only($attrs, ['body', 'title']));

		//lets create a edit action
		$body = '';
		foreach ($changed as $change) {
			$body .= $change . ' changed from ' . $old_ticket[$change] . ' to ' . $attrs[$change] . "\n";
		}

		$new_action = $this->action->create([
			'ticket_id' => $id,
			'user_id' => Auth::user()->id,
			'type' => 'edit',
			'body' => $body . "\n" . $attrs['reason']
		]);

		return Redirect::route('tickets.show', [$id, '#action-' . $new_action['id']]);
	}

}