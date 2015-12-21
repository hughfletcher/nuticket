<?php namespace App;

use Str;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Observers\TicketObserver;
use Carbon\Carbon;

class Ticket extends Eloquent {

	protected $fillable = [
        'last_action_at',
        'user_id',
        'priority',
        'assigned_id',
        'status',
        'dept_id',
        'hours'
    ];

    protected $dates = ['last_action_at', 'closed_at'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public function user() {
        return $this->belongsTo('App\User');
    }

	public function assigned() {
        return $this->belongsTo('App\User', 'assigned_id', 'id');
    }

	public function dept() {
        return $this->belongsTo('App\Dept', 'dept_id', 'id');
    }

    public function actions() {
        return $this->hasMany('App\TicketAction')->orderBy('created_at', 'asc');
    }

    public function getTitleAttribute($value)
    {
        return $this->actions()->where('type', 'create')->get()->first()->title;
    }


}
