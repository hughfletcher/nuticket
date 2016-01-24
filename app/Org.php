<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
	protected $casts = [
    	'active' => 'boolean',
	];
	
    public function users() {
        return $this->hasMany('App\User');
    }
}
