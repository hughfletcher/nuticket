<?php namespace App\Http\Controllers;

use App\Http\Requests\TimeCreateRequest;
use App\Http\Requests\TimeUpdateRequest;
use App\Http\Requests\TimeDestroyRequest;
use App\Http\Controllers\Controller;
use App\Repositories\TimeLogInterface;
use Auth;
use Carbon\Carbon;

use Illuminate\Http\Request;

class TimeController extends Controller {

	public function __construct(TimeLogInterface $time) {

		$this->time = $time;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$logs = $this->time->paginateByUser(Auth::user()->id, config('system.page_size'));
		return view('me.time.index', compact('logs'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TimeCreateRequest $request)
	{

		$request->merge(['time_at' => Carbon::createFromFormat('m/d/Y', $request->input('time_at'))]);

		$time = $this->time->create($request->all());
		
		return redirect($request->input('_redirect'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$entry = $this->time->find($id);
		return view('me.time.edit', compact('entry'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(TimeUpdateRequest $request, $id)
	{
		if ($request->has('time_at')) {
			$request->merge(['time_at' => Carbon::createFromFormat('m/d/Y', $request->input('time_at'))]);
		}

		$time = $this->time->update($request->only('time_at', 'hours', 'type', 'message'), $id);
		
		return redirect()->route('me.time.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(TimeDestroyRequest $request, $id)
	{
		$this->time->delete($id);
		return redirect($request->input('_redirect'));
	}

}
