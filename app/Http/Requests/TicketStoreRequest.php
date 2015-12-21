<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;
use App\Events\TicketCreateRequestValidateEvent;
use App\Policies\TicketPolicy;

class TicketStoreRequest extends Request
{
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
	public function rules(TicketPolicy $policy)
	{
        $this->redirect = 'tickets/create' . ($this->has('user_id') ? '?user_id=' . $this->get('user_id') : null);

        $rules = $policy->storeRules();

		if ($this->input('reply') != '') {
			$rules['reply'] = $rules['reply'] + ['required_with:hours,status,date'];
		}

		if ($this->input('comment') != '') {
			$rules['comment'] = $rules['comment'] + ['required_with:hours,status,date'];
		}

		return $rules;
	}
}
