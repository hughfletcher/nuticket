<?php namespace App\Http\Requests;

use Auth;

class TimeCreateRequest extends FormRequest {	

	protected $rules = [
    	'user_id' => ['required', 'exists:users,id'],
        'type' => ['required', 'in:vacation,holiday,sick,other'],
        'message' => ['required', 'min:8'],
		'hours' => ['required', 'numeric'],
		'time_at' => ['required', 'date_format:m/d/Y']
    ];
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return $this->input('user_id') == Auth::user()->id;
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

}
