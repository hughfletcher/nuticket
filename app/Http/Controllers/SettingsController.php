<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SystemUpdateRequest;
use App\Jobs\UpdateConfigJob;

class SettingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($type)
    {
        return view('settings.' . $type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(SystemUpdateRequest $request, $type)
    {
        $this->dispatch(new UpdateConfigJob($request->except('_token', '_method'), true));

        return redirect()->route('settings.edit', [$type])
            ->with('message', trans('validation.were_sucessfully_updated', ['name' => trans('settings.system_settings')]));

    }
}
