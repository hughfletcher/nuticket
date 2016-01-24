<?php namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Contracts\Repositories\OrgInterface;

/**
 * Class OrganizationRepository
 * @package App\Repositories
 */
class OrgRepository extends Repository implements OrgInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return 'App\Org';
    }
}