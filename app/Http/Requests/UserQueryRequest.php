<?php

namespace App\Http\Requests;

class UserQueryRequest extends QueryRequest
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
        return array_merge(parent::rules(), [
            // 'fields' => 'in:display_name,id'
        ]);;
    }
}
