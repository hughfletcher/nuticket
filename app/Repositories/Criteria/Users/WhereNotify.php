<?php namespace App\Repositories\Criteria\Users;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Database\Eloquent\Builder;
use App\Ticket;

/**
 * Class WhereIsAdmin
 *
 * @package App\Repositories\Criteria
 */
class WhereNotify extends Criteria {

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        
        return $model->where(function ($query) {

            $type = (in_array($this->ticket->events->first()->type, ['open', 'resolved', 'closed']) ? 'reply' : $this->ticket->events->first()->type);

            if ($this->ticket->events->first()->source == 'mail' && config('settings.autorespond.bymail')) {
                $query = $query->orWhere('id', $this->ticket->events->first()->user_id);
            }

            $notify = config('settings.notify.' . $type);

            foreach ($notify as $key => $bool) {

                if ($bool) {

                    $method = 'notify' . ucfirst($key);
                    $query = $this->$method($query, $type);

                }
                
            }

            $query = $this->checkQuery($query);
            
        });

    }

    private function notifyAdmin(Builder $query, $type)
    {
        return $query->orWhere('is_admin', true);
    }

    private function notifyMgr(Builder $query, $type)
    {
        return $query->orWhere('id', $this->ticket->dept->mgr_id);
    }

    private function notifyDept(Builder $query, $type)
    {
        return $query->orWhereIn('id', $this->ticket->dept->members->lists('id'));
    }

    private function notifyOwner(Builder $query, $type)
    {
        return $query->orWhere('id', $this->ticket->user_id);
    }

    private function notifyAssigned(Builder $query, $type)
    {
        return $query->orWhere('id', $this->ticket->assigned_id);
    }

    private function notifyOrg(Builder $query, $type)
    {
        return $query;
    }

    private function checkQuery(Builder $query)
    {
        //nobody gets anything
        if (count($query->getQuery()->wheres) < 1) {
            return $query ->where('id','<', 1);
        }
    }

    private function notifyLast(Builder $query, $type)
    {

        $current = $this->ticket->events->first()->id;

        foreach ($this->ticket->actions as $action) {
            if ($action->id == $current) {
                break;
            }
            $last = $action;
        }

        return $query->orWhere('id', $last->user_id);
    }
}