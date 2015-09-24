<?php namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Contracts\Repositories\UserInterface;
use App\Http\Requests\UserQueryRequest;
use App\Http\Requests\UserStoreRequest;

class UsersController extends Controller {

	public function __construct(UserInterface $user) {

		$this->user = $user;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(UserQueryRequest $request)
	{

		return response()->json($this->user->all(explode(',', $request->get('fields')), $request->all()));
		

	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(UserStoreRequest $request)
	{
		if (!$request->exists('display_name') && ($request->has('first_name') || $request->has('last_name'))) 
		{
			$request->merge(['display_name' => $request->input('first_name') . ' ' . $request->input('last_name')]);
		}
		
		return $this->user->create($request->all());
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// dd($this->user->find($id));
		$result = $this->user->find($id);
			
		return response()->json($result);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$this->user->update($id, Request::all());
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
