<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Dept extends Eloquent {

	// protected $table = 'staff';

	protected $fillable = [];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public function members()
	{
		return $this->hasMany('App\User');
	}

}
