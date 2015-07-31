<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeLog extends Model {

	use SoftDeletes;

	protected $table = 'time_log';

	protected $dates = ['time_at', 'deleted_at'];

	protected $fillable = [
        'user_id', 
        'hours',
        'type',
        'message',
        'ticket_action_id',
        'time_at'
    ];

    public function action() {
        return $this->belongsTo('App\TicketAction', 'ticket_action_id', 'id');
    }
}
 