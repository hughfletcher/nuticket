<?php namespace App\Http\Controllers;

// use Config;
use App\Repositories\ConfigInterface;
use App\Config;
use App;
use Modules\Adldap\Services\LdapConnector;

class DashController extends BaseController {

	public function __construct(ConfigInterface $config) 
	{
		$this->config = $config;
	}

	/**
	 * Display a listing of the resource.
	 * GET /app\s\dash
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return redirect()->route('tickets.index');
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