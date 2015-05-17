<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueryRequest extends FormRequest {

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
			'sort' => 'required_with:order|in:' . implode(',', $this->sort()), 
	        'order' => 'required_with:sort|in:asc,desc', 
	        'per_page' => 'numeric'
		];
	}


	protected function sort() 
	{
		return [];
	}

}
