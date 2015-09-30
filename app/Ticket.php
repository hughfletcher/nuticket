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
        'staff_id', 
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

    public static function boot()
    {
        parent::boot();

        self::creating(function ($ticket) {
            $ticket->last_action_at = $ticket->created_at;
            return $ticket;
        });
    }

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

    public static function getOpenCount($id = false) {

    	$ticket = self::wherein('status', ['open', 'new']);

    	$ticket = $id ? $ticket->where('user_id', $id) : $ticket;

    	return $ticket->count();
    }
    
    public static function getClosedCount($id = false) {

    	$ticket = self::where('status', 'closed');

    	$ticket = $id ? $ticket->where('user_id', $id) : $ticket;

    	return $ticket->count();
    }

    public static function getAssignedCount($staff_id) {

    	return self::wherein('status', ['open', 'new'])->where('assigned_id', $staff_id)->count();
    }

    public static function checkUserTicket($ticket_id, $user_id) {
    	return self::where('id', $ticket_id)->where('user_id', $user_id)->count();
    }

}
