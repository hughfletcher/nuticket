<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class FormTicketUpdateRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
	        'user_id' => ['required', 'exists:users,id'],
	        'priority' => ['required', 'between:1,5'],
	        'title' => ['required', 'min:10'],
	        'body' => ['required', 'min:10'],
	        'reason' => ['required', 'min:5']
        ];
	}

}
