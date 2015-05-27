<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class FormTicketCreateRequest extends Request {

	protected $redirectRoute = 'tickets.create';

	protected $rules = [
    	'user_id' => ['required', 'exists:users,id'],
        'priority' => ['required', 'between:1,5'],
        'title' => ['required', 'min:10'],
        'body' => ['required', 'min:10'],
		'time_spent' => ['numeric'],
        'reply_body' => ['min:3'],
        'comment_body' => ['min:3'],
        'status' => ['in:open,closed,resolved'],
        'dept_id' => ['required', 'exists:depts,id'],
        'staff_id' => ['exists:staff,id'],
    ];

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
		if ($this->input('reply_body') != '') {
			$this->rules['reply_body'] = $this->rules['reply_body'] + ['required_with:time_spent,status'];
		}

		if ($this->input('comment_body') != '') {
			$this->rules['comment_body'] = $this->rules['comment_body'] + ['required_with:time_spent,status'];
		}

		return $this->rules;
	}

}
