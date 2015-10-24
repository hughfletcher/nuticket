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
			'status' => ['regex:/^(-?(open|closed|new)){1,3}$/'],
			'assigned_id' => ['regex:/^\d+(-\d+)*$/']
		]);
	}

	public function sort()
	{
		return ['id', 'last_action_at', 'title', 'user', 'priority', 'assigned'];
	}
}
