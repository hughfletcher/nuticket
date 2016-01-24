<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'id' => ['required', 'exists:users,id'],
            'username' => ['unique:users,username,' . $this->id],
            'email' => ['email'],
            'timezone' => ['timezone'],
            'locale' => ['alpha_dash'],
            'org_id' => ['exists:orgs,id']
        ];
    }
}
