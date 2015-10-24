<?php namespace App\Repositories;

use App\Contracts\Repositories\TicketInterface;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\TicketAction;
use Carbon\Carbon;

class TicketRepository extends Repository implements TicketInterface {

	public function model() {
        return 'App\Ticket';
    }

    /**
	 * Create a ticket and/or a reply/comment action
	 *
	 * @param  array $attrs [user_id, auth_id, dept_id, title, body, [priority, staff_id, status]]
	 * @return array
	 */
	public function create(array $data)
	{
		// $array = array_add($data, 'last_action_at', Carbon::now());

		//create ticket
		$ticket = parent::create(array_only($data, ['status', 'assigned_id', 'priority', 'dept_id', 'user_id']));

		$action = $this->createTicketAction([
			'user_id' => $data['auth_id'],
			'type' => 'create',
			'title' => $data['title'],
			'body' => $data['body']
		]);

		$ticket->actions()->save($action);

		return $ticket;
	}

    public function paginateByRequest($perPage = 1, $columns = ['*'])
    {
    	$this->model = $this->model->select(
    			'tickets.*',
    			'ticket_actions.title as title',
    			'users.display_name as user',
    			'su.display_name as assigned'
    		)
			->join('users', 'users.id', '=', 'tickets.user_id')
			->join('users as su', 'su.id', '=', 'tickets.assigned_id')
			->join('ticket_actions','ticket_actions.ticket_id', '=', 'tickets.id')
			->where('ticket_actions.type', 'create');

    	$this->pushCriteria(new Criteria\RequestSort)
    		->pushCriteria(new Criteria\RequestCreatedAtRange)
    		->pushCriteria(new Criteria\RequestSearchTickets)
    		->pushCriteria(new Criteria\Request('status'))
    		->pushCriteria(new Criteria\Request('priority'))
    		->pushCriteria(new Criteria\Request('dept_id'))
    		->pushCriteria(new Criteria\Request('assigned_id'));

    	return parent::paginate($perPage);
    }

    protected function createTicketAction(array $data)
    {
    	return new TicketAction($data);
    }



	// public function buildUpdateByReply()
	// {
	// 	return [

	// 	];
	// }

	/**
	 * Update ticket by a comment ticket action.
	 *
	 * @param  array $action App\TicketAction::toArray()
	 * @return App\Ticket
	 */
	// public function updateByComment(TicketAction $action)
	// {
	// 	$ticket = parent::update([
	// 			'last_action_at' => $action->created_at,
	// 			'hours' => 'hours + ' . $action->hours
	// 		], $action->ticket_id);

	// 	return $ticket;
	// }
}
