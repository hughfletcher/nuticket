<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class FormActionCreateRequest extends Request 
{

	protected $rules = [
		'ticket_id' => ['required', 'numeric', 'exists:tickets,id'],
		'body' => ['required', 'min:3'],
		'status' => ['required_if:type,reply', 'in:closed,open,resolved'], 
        'time_spent' => ['numeric'],
        'transfer_id' => ['required_if:type,transfer', 'numeric', 'exists:depts,id'],
        'assigned_id' => ['required_if:type,assign', 'numeric'],
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

		return $this->rules;
	}

	public function response(array $errors)
	{
		return $this->redirector->route('tickets.show', [$this->get('ticket_id'), '#' . $this->get('type')])
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
	}

}
