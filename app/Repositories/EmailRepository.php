<?php namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Contracts\Repositories\EmailInterface;

/**
 * Class EmailRepository
 * @package App\Repositories
 */
class EmailRepository extends Repository implements EmailInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return 'App\Email';
    }
}
