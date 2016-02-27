<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Contracts\Auth\Access\Gate;

class SettingsUpdateRequest extends Request
{
    private $rules = [
        'system' => [
            'settings_title' => 'required',
            'settings_time_enabled' => ['required', 'boolean'],
            'settings_time_edit' => ['required', 'boolean'],
            'settings_default_tz' => ['required', 'timezone'],
            'settings_default_dept' => ['required', 'integer', 'exists:depts,id'],
            'settings_default_pagesize' => ['required', 'integer'],
            'settings_default_priority' => ['required', 'integer'],
            'settings_default_org' => ['required', 'integer', 'exists:orgs,id'],
            'settings_format_date' => ['required'],
            'settings_format_dateday' => ['required'],
            'settings_format_datetime' => ['required'],
        ],
        'emails' => [
            'settings_mail_default' => ['required', 'integer', 'exists:emails,id'],
            'settings_mail_admin' => ['required', 'email'],
            'settings_mail_fetching' => ['required', 'boolean'],
            'settings_mail_acceptunknown' => ['required', 'boolean'],
            'settings_mail_defaultmta' => ['required', 'integer', 'exists:emails,id']
        ],
        'notifications' => [
            'settings_autorespond_bymail' => ['required', 'boolean']
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
        return $this->gate->allows('manage_settings_' . $this->segment(2));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (method_exists($this, $this->segment(2) . 'Rules')) {
            return array_merge($this->rules[$this->segment(2)], $this->{$this->segment(2) . 'Rules'}());
        }
        return $this->rules[$this->segment(2)];
    }

    protected function notificationsRules() 
    {
        $rules = [];
        foreach (config('settings.notify') as $key => $array) {
            foreach ($array as $item => $value) {
                $rules['settings_notify_' . $key . '_' . $item] = ['required', 'boolean'];
            }
            
        }
        return $rules;
    }
}
