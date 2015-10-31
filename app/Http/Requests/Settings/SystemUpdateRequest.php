<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Contracts\Auth\Access\Gate;

class SystemUpdateRequest extends Request
{
    private $rules = [
        'system_title' => 'required',
        'system_pagesize' => ['required', 'numeric'],
        'system_format_date' => ['required'],
        'system_format_dateday' => ['required']
    ];

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->gate->allows('manage_settings_system');
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
