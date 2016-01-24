<?php namespace App\Repositories;

use App\Contracts\Repositories\UserInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use App\Events\UserCreatedEvent;

class UserRepository extends Repository implements UserInterface, RepositoryInterface {

	public function model() {
        return 'App\User';
    }

}