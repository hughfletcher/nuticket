<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class LogsIndexRequest extends QueryRequest
{

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array
	*/
	public function rules()
	{
		return array_merge(parent::rules(), [
			//
		]);
	}

	public function sort()
	{
		return ['date', 'level', 'message'];
	}
}
