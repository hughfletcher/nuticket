<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class TicketIndexRequest extends QueryRequest
{

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array
	*/
	public function rules()
	{
		return array_merge(parent::rules(), [
			'status' => ['array', 'in:open,new,closed,resolved'],
			'assigned_id' => ['array', 'exists:users,id,is_staff,1'],
			'dept_id' => ['array', 'exists:depts,id'],
			'priority' => ['array', 'between:1,5'],
			'created_at' => []
		]);
	}

	public function sort()
	{
		return ['id', 'last_action_at', 'title', 'user', 'priority', 'assigned', 'created_at'];
	}
}
