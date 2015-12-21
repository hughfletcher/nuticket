<?php namespace App\Http\Requests;

use App\Policies\TicketActionPolicy;

class ActionCreateRequest extends FormRequest
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
    public function rules(TicketActionPolicy $policy)
    {

        return $policy->createRules();
    }

    public function response(array $errors)
    {
        return $this->redirector->route('tickets.show', [$this->get('ticket_id'), '#' . $this->get('type')])
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
