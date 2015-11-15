<?php namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Contracts\Repositories\TicketActionInterface;

/**
 * Class TicketActionRepository
 * @package App\Repositories
 */
class TicketActionRepository extends Repository implements TicketActionInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return 'App\TicketAction';
    }
}
