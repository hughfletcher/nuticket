<?php namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Contracts\Repositories\UserInterface;
use App\Http\Requests\UserQueryRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Events\UsersGetAllEvent;
use App\Repositories\Criteria\Users\WhereNameLike;

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
		$local = $this->user->pushCriteria(new WhereNameLike($request->get('q')))
			->all(explode(',', $request->get('fields')), $request->all());
		if ($request->get('noevent')) {
			return response()->json($local);
		}

		$event_data = event(new UsersGetAllEvent($local, $request->all()));

		if (empty($event_data)) {
			return response()->json($local);
		}

		$results = [];
    	foreach ($event_data as $data) {

    		foreach ($data as $row) {
    			$results[] = $row;
    		}
    		
    	}

		return response()->json($results);


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
	public function update(UserUpdateRequest $request, $id)
	{
		$this->user->update($request->except('_method'), $id);

		return response()->json($this->user->find($id));
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
