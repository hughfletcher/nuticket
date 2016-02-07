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
            'settings_notify_newticket_status' => ['required', 'boolean'],
            'settings_notify_newticket_admin' => ['required', 'boolean'],
            'settings_notify_newticket_mgr' => ['required', 'boolean'],
            'settings_notify_newticket_dept' => ['required', 'boolean'],
            'settings_notify_newticket_org' => ['required', 'boolean'],
            'settings_notify_reply_status' => ['required', 'boolean'],
            'settings_notify_reply_last' => ['required', 'boolean'],
            'settings_notify_reply_assigned' => ['required', 'boolean'],
            'settings_notify_reply_mgr' => ['required', 'boolean'],
            'settings_notify_reply_org' => ['required', 'boolean'],
            'settings_notify_internal_status' => ['required', 'boolean'],
            'settings_notify_internal_last' => ['required', 'boolean'],
            'settings_notify_internal_assigned' => ['required', 'boolean'],
            'settings_notify_internal_mgr' => ['required', 'boolean'],
            'settings_notify_assign_status' => ['required', 'boolean'],
            'settings_notify_assign_assigned' => ['required', 'boolean'],
            'settings_notify_assign_mgr' => ['required', 'boolean'],
            'settings_notify_assign_dept' => ['required', 'boolean'],
            'settings_notify_transfer_status' => ['required', 'boolean'],
            'settings_notify_transfer_assigned' => ['required', 'boolean'],
            'settings_notify_transfer_mgr' => ['required', 'boolean'],
            'settings_notify_transfer_dept' => ['required', 'boolean'],
            'settings_notify_system_status' => ['required', 'boolean'],
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
        return $this->rules[$this->segment(2)];
    }
}
