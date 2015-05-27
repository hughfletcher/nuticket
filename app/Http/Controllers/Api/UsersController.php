<?php namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Repositories\UserInterface;
use Request, Response, App, Str;

class UsersController extends Controller {

	public function __construct(UserInterface $user) {

		$this->user = $user;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		// $validator = $this->queryValidator->make(Request::query())
		// 	->addContext('user')
		//     ->bindReplacement('sort', ['fields' => 'id,username,first_name,middle_name,last_name,display_name,email,created_at,updated_at']);

		// if ($validator->fails()) {

		// 	App::abort(404);
		// }

		$this->user
				// ->sort(Request::get('sort', 'id'), Request::get('order', 'desc'))
				// ->whereCreated($dates[0], $dates[1])
				->whereSearch(Request::has('q') ? explode('-', str_slug(Request::get('q'))) : [])
				->fields(array_filter(explode(',', Request::get('fields'))));

		// $errors = $validator->errors();

		// if (Request::has('list')) {
			
		// 	$display = explode(',', Request::get('list'));

			return Response::json($this->user->get());
		// }

		// $tickets = $this->tickets->paginate(Request::get('per_page'));
		
		// return View::make('tickets.list', compact('tickets', 'errors'));

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

		$result = $this->user
			->where('id', [$id])
			->fields(array_filter(explode(',', Request::get('fields'))))
			->get();
			
		return Response::json($result[0]);
	}


	/**
	 * Show the form for editing the specified resource.
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
