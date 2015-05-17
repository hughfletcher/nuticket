<?php namespace App\Repositories\Eloquent;

use App\Repositories\TicketInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Ticket;
use App\TicketAction;
use Auth;

class TicketRepository extends BaseRepository implements TicketInterface {

	/**
	 * Create the TicketRepository instance.
	 * 
	 * @param App\Ticket
	 */
	public function __construct(Ticket $model, TicketAction $action) {

		$this->model = $model;
		$this->action = $action;

	}
	/**
	 * Create a ticket and/or a reply/comment action
	 * 		
	 * @param  string $attrs 
	 * @return array 
	 */
	public function create($attrs) {

		//if no comment/reply status remains new
		$attrs['status'] = $attrs['reply_body'] == '' && $attrs['comment_body'] == '' ? 'new' : $attrs['status'];

		//create ticket
		$ticket = $this->model->create($attrs);

		//create action type - create
		$action = ['ticket_id' => $ticket->id, 'user_id' => Auth::user()->id, 'type' => 'create', 'title' => $attrs['title'], 'body' => $attrs['body']];
		$this->action->create($action);

		return $ticket;
	}

	/**
	 * Find record by id.
	 * 
	 * @param  string $id
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function find($id) {
		return $this->model->find($id);
	}

	/**
	 * Create the select.
	 * 
	 * @return $this;
	 */
	public function select() {

		$this->model = $this->model->select('tickets.id as id', 'last_action_at', 'ticket_actions.title as subject', 'users.display_name as user', 'priority', 'su.display_name as staff')
			->join('users', 'users.id', '=', 'tickets.user_id')
			->join('staff', 'staff.id', '=', 'tickets.staff_id')
			->join('users as su', 'su.id', '=', 'staff.user_id')
			->join('ticket_actions','ticket_actions.ticket_id', '=', 'tickets.id')
			->where('ticket_actions.type', 'create');		

		return $this;

	}

	/**
	 * Get a paginator for the "select" statement.
	 *
	 * @param  int    $per_page
	 * @return \Illuminate\Pagination\Paginator
	 */
	public function paginate($per_page) {
		// die($this->select()->model->toSql());

		return $this->select()->model->paginate($per_page);
	}
	/**
	 * Filter the query by creation date range
	 * 
	 * @param  string $start
	 * @param  string $end
	 * @param  string $table
	 * @return parent
	 */
	public function whereCreated($start = null, $end = null, $table = null) {

		return parent::whereCreated($start, $end, 'tickets');
	}

	/**
	 * Search filter on query
	 * 
	 * @param  array $query
	 * @param  array $cols
	 * @return $this
	 */
	public function whereSearch(array $query = [], array $cols = []) {
		
		$this->model = $this->model->WhereIn('tickets.id', function($q) use ($query) {
		    $q = $q->select('ta.ticket_id')
			    ->from($this->action->getTable() . ' as ta')
			    ->join('tickets as t', 'ta.ticket_id', '=', 't.id')
				->join('users as u', 't.user_id', '=', 'u.id')
				->join('staff as s', 's.id', '=', 't.staff_id')
				->join('users as su','su.id', '=', 's.user_id');

			foreach ($query as $term) {

				$q = $q->where(function($qu) use ($term) {

					$qu->orWhere('ta.title', 'LIKE', '%' . $term . '%')
						->orWhere('ta.body', 'LIKE', '%' . $term . '%')
						->orWhere('u.display_name', 'LIKE', '%' . $term . '%')
						->orWhere('u.username', 'LIKE', '%' . $term . '%');

				});

			}


		});

		return $this;
	}

	/**
	 * Ticket status filter on the query.
	 * 
	 * @param  array $values
	 * @return $this
	 */
	public function whereStatus(array $values) {

		return parent::where('status', $values);
	}

	/**
	 * Ticket priority filter on query.
	 * 
	 * @param  array $values
	 * @return $this
	 */
	public function wherePriority(array $values) {

		return $this->where('priority', $values);
	} 

	/**
	 * Ticket staff filter on query.
	 * 
	 * @param  array $values
	 * @return $this
	 */
	public function whereStaff(array $values) {

		return $this->where('staff_id', $values);
	} 

	/**
	 * Ticket dept filter on query.
	 * 
	 * @param  array $values
	 * @return $this
	 */
	public function whereDept(array $values) {

		return $this->where('dept_id', $values);
	} 

	/**
	 * User filter on query.
	 * 	
	 * @param  string $id
	 * @return $this
	 */
	public function whereUser($id = '*') {

		if ($id == '*') {

			return $this;
			
		}

		$this->model = $this->model->where('tickets.user_id', $id);

		return $this;

	}

	/**
	 * Update ticket by a reply ticket action
	 * 
	 * @param  array App\TicketAction::toArray() + $action['status']
	 * @return array App\Ticket::toArray() + $['old_status']
	 */
	public function updateByReply(array $action) {

		$ticket = $this->model->find($action['ticket_id']);
		$ticket_array['old_status'] = $ticket->status;

		$ticket->last_action_at = $action['created_at'];
		$ticket->time_spent += $action['time_spent'];
		$ticket->status = $action['status'];

		if ($action['status'] == 'open') {
			$ticket->closed_at = null;
		} else {
			$ticket->closed_at = $action['created_at'];
		}

		$ticket->save();

		return array_merge($ticket->toArray(), $ticket_array);
	}

	/**
	 * Update ticket by a comment ticket action.
	 * 
	 * @param  array $action App\TicketAction::toArray()
	 * @return App\Ticket
	 */
	public function updateByComment(array $action) {

		$ticket = $this->model->find($action['ticket_id']);

		$ticket->last_action_at = $action['created_at'];
		$ticket->time_spent += $action['time_spent'];

		$ticket->save();

		return $ticket;
	}

	/**
	 * Update ticket by a transfer ticket action.
	 * 
	 * @param  array $action App\TicketAction::toArray()
	 * @return App\Ticket
	 */
	public function updateByTransfer(array $action) {

		$ticket = $this->model->find($action['ticket_id']);

		$ticket->last_action_at = $action['created_at'];
		$ticket->dept_id = $action['transfer_id'];

		$ticket->save();

		return $ticket;
	}

	/**
	 * Update ticket by a assign ticket action.
	 * 
	 * @param  array $action App\TicketAction::toArray()
	 * @return App\Ticket
	 */
	public function updateByAssign(array $action) {

		$ticket = $this->model->find($action['ticket_id']);

		$ticket->last_action_at = $action['created_at'];
		$ticket->staff_id = $action['assigned_id'];

		$ticket->save();

		return $ticket;
	}


}