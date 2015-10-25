<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Events\TicketCreateRequestValidateEvent;

class TicketStoreRequest extends FormRequest
{

	protected $rules = [
		'user_id' => ['required_without_all:display_name,email', 'exists:users,id'],
		'display_name' => ['required_without:user_id'],
		'email' => ['required_without:user_id', 'email'],
        'priority' => ['required', 'between:1,5'],
        'title' => ['required', 'min:10'],
        'body' => ['required', 'min:10'],
		'hours' => ['numeric'],
		'time_at' => ['required_with:hours', 'date_format:m/d/Y'],
        'reply_body' => ['min:3'],
        'comment_body' => ['min:3'],
        'status' => ['in:open,closed,resolved'],
        'dept_id' => ['required', 'exists:depts,id'],
        'assigned_id' => ['exists:users,id,is_staff,1'],
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
        $this->redirect = 'tickets/create' . ($this->has('user_id') ? '?user_id=' . $this->get('user_id') : null);

		if ($this->input('reply_body') != '') {
			$this->rules['reply_body'] = $this->rules['reply_body'] + ['required_with:hours,status,date'];
		}

		if ($this->input('comment_body') != '') {
			$this->rules['comment_body'] = $this->rules['comment_body'] + ['required_with:hours,status,date'];
		}

		return $this->rules;
	}
}
