<?php namespace App\Http\Requests;

class TicketUpdateRequest extends FormRequest
{
    public static $rules = [
        'user_id' => ['exists:users,id'],
        'priority' => ['between:1,5'],
        'title' => ['min:10'],
        'body' => ['min:10'],
        'reason' => ['min:5']
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
        return static::$rules;
    }
}
