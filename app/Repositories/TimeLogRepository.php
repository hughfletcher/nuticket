<?php namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Contracts\Repositories\TimeLogInterface;

/**
 * Class TimeLogRepository
 * @package App\Repositories
 */
class TimeLogRepository extends Repository implements TimeLogInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return 'App\TimeLog';
    }
}
