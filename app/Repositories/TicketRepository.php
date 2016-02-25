<?php namespace App\Repositories;

use App\Contracts\Repositories\TicketInterface;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Http\Requests\TicketIndexRequest;
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
		$ticket = parent::create(array_only($data, ['assigned_id', 'priority', 'dept_id', 'user_id', 'org_id']));

		$action = $this->createTicketAction([
			'user_id' => $data['auth_id'],
			'type' => 'create',
			'title' => $data['title'],
			'body' => $data['body']
		]);

		$ticket->actions()->save($action);

		return $ticket;
	}

    public function job_create($array)
    {
        return parent::create($array);
    }

    public function paginateByRequest(TicketIndexRequest $request)
    {
    	$this->model = $this->model->with('user', 'assigned');

    	$this->pushCriteria(new Criteria\Request\RequestOrderBy($request))
            ->pushCriteria(new Criteria\Request\RequestWhereCreatedAtBetween($request))
            ->pushCriteria(new Criteria\Tickets\RequestWhereInStatus($request))
            ->pushCriteria(new Criteria\Tickets\RequestWhereInPriority($request))
            ->pushCriteria(new Criteria\Request\RequestWhereInDeptId($request))
            ->pushCriteria(new Criteria\Request\RequestWhereInOrgId($request))
            ->pushCriteria(new Criteria\Tickets\RequestWhereInAssignedId($request))
            ->pushCriteria(new Criteria\Tickets\RequestSearch($request));

    	return parent::paginate($request->get('per_page', config('system.page_size')));
    }
}
