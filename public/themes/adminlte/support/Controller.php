<?php

namespace Themes\AdminLte\Support;

use App\Http\Controllers\Controller as LaravelController;
use App\Jobs\UpdateConfigJob;

class Controller extends LaravelController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('settings.adminlte');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->dispatch(new UpdateConfigJob($request->except('_token', '_method'), true));

        return redirect()->route('theme.adminlte.edit')
            ->with('message', trans('validation.were_sucessfully_updated', ['name' => trans('settings.system_settings')]));

    }
}
