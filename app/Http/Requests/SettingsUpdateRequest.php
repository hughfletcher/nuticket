<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Contracts\Auth\Access\Gate;

class SettingsUpdateRequest extends Request
{
    private $rules = [
        'system' => [
            'system_title' => 'required',
            'system_pagesize' => ['required', 'numeric'],
            'system_format_date' => ['required'],
            'system_format_dateday' => ['required']
        ],
        'emails' => [
            'mail_default' => ['required', 'exists:emails,id'],
            'mail_admin' => ['required', 'email'],
            'mail_fetching' => ['in:true'],
            'mail_replyseperator' => [],
            'mail_keeppriority' => ['in:true'],
            'mail_acceptunknown' => ['in:true'],
            'mail_defaultmta' => ['required', 'exists:emails,id']
        ]
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
        return $this->rules[$this->segment(2)];
    }
}
