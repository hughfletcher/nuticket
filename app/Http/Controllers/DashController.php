<?php namespace App\Http\Controllers;

use App, View, Redirect;

class DashController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /app\s\dash
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return Redirect::route('tickets.index');
		$memory = App::make('orchestra.memory')->make();
		$memory->set('site.allow_pw_reset', false);
		$memory->forget('site.client_registration');
		$memory->put('site.user_registration', false);
		$memory->set('site.date_time_format', 'm/d/Y g:i a');
		// dd(\Auth::user());
		// return View::make('tickets.dash');
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /app\s\dash/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /app\s\dash
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /app\s\dash/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /app\s\dash/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /app\s\dash/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /app\s\dash/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}