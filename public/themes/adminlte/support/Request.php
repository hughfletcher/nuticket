<?php

namespace Themes\Adminlte\Support;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Auth\Access\Gate;

class Request extends FormRequest
{
    private $rules = [
        'theme_adminlte_logo' => ['required'],
        'theme_adminlte_logomini' => ['required']
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
        return $this->gate->allows('manage_settings_themes');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $attrs = $this->all();

        $attrs['theme_adminlte_logomini'] = htmlspecialchars($attrs['theme_adminlte_logomini']);
        $attrs['theme_adminlte_logo'] = htmlspecialchars($attrs['theme_adminlte_logo']);
        $attrs['theme_adminlte_logomini'] = str_replace("&lt;b&gt;", "<b>", $attrs['theme_adminlte_logomini']);
        $attrs['theme_adminlte_logomini'] = str_replace("&lt;/b&gt;", "</b>", $attrs['theme_adminlte_logomini']);
        $attrs['theme_adminlte_logo'] = str_replace("&lt;b&gt;", "<b>", $attrs['theme_adminlte_logo']);
        $attrs['theme_adminlte_logo'] = str_replace("&lt;/b&gt;", "</b>", $attrs['theme_adminlte_logo']);

        $this->replace($attrs);


        return $this->rules;
    }
}
