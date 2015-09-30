<?php namespace App\Repositories\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Http\Request;

class RequestSearchTickets extends Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
    	$query = app('request')->has('q') ? explode('-', str_slug(app('request')->get('q'))) : [];

        return $model->WhereIn('tickets.id', function($q) use ($query) {
		    $q = $q->select('ta.ticket_id')
			    ->from('ticket_actions as ta')
			    ->join('tickets as t', 'ta.ticket_id', '=', 't.id')
				->join('users as u', 't.user_id', '=', 'u.id');

			foreach ($query as $term) {

				$q = $q->where(function($qu) use ($term) {

					$qu->orWhere('ta.title', 'LIKE', '%' . $term . '%')
						->orWhere('ta.body', 'LIKE', '%' . $term . '%')
						->orWhere('u.display_name', 'LIKE', '%' . $term . '%')
						->orWhere('u.username', 'LIKE', '%' . $term . '%');

				});

			}


		});
    }
}