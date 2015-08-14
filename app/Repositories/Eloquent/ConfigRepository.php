<?php namespace App\Repositories\Eloquent;

use App\Repositories\ConfigInterface;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

class ConfigRepository extends Repository implements ConfigInterface {

	public function model() {
        return 'App\Config';
    }

    public function store($key, $value)
    {
    	$item = $this->model->firstOrCreate([
    		'key' => $key,
    		'enviroment' => app()->environment()
    	]);

    	$item->value = $value;
    	$item->save();

    	app('config')->set($key, $value);
    }

}