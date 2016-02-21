<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Stevebauman\LogReader\LogReader;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\LogsIndexRequest;
use App\Http\Requests\QueryRequest;

class LogsController extends Controller
{

    public function __construct(LogReader $log)
    {
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LogsIndexRequest $request)
    {
        $current = $request->has('date') ? $request->get('date') : Carbon::now('utc')->toDateString();
        $reader = $this->log->date(strtotime($current));

        if ($request->has('level')) {
            $reader = $reader->level($request->get('level'));
        }

        if ($request->has('sort') && $request->has('order')) {
            $reader = $reader->orderBy($request->get('sort') , $request->get('order'));
        }

        $reader = $reader->paginate(25);
        $data = collect();

        foreach ($reader as $entry) {
            $raw = explode(': ', $entry->header, 2)[1];
            $line = explode(' {', $raw, 2);

            $row = [
                'date' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->date),
                'level' => $entry->level,
                'message' => $line[0],
                'context' => isset($line[1]) ? '{' . $line[1] : null,
                'id' => $entry->id
            ];
            $data->push($row);
        }

        $logs = new LengthAwarePaginator($data, $reader->total(), 25);
        $logs->setPath('logs');
        return view('system.log', compact('logs', 'current'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(QueryRequest $request, $id)
    {
        $entry = $this->log->date(strtotime($request->get('date')))->find($id);
        $raw = explode(': ', $entry->header, 2)[1];
        $line = explode(' {', $raw, 2);
        $log = [
            'date' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->date),
            'level' => $entry->level,
            'message' => $line[0],
            'context' => isset($line[1]) ? '{' . $line[1] : null,
            'id' => $entry->id,
            'stack' =>$entry->stack
        ];
        return view('system.entry', compact('log'));
    }

}
