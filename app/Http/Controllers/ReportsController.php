<?php namespace App\Http\Controllers;

use App\Repositories\ReportInterface;
use View, DB;

class ReportsController extends BaseController {

    public function __construct(ReportInterface $report) {
        $this->reports = $report;
    }

    public function index() {

        $reports = $this->reports->all();
        return View::make('reports.index', ['reports' => $reports]);

        // $name = 'App\Reports\\' . ucfirst(camel_case($report) . 'Report');

        // // try {
        //     return $this->report->make($name);
        // // } catch (Exception $e) {
        //     // return App::abort(404);
        // // }
    }

    public function show($id) {

        $report = $this->reports->find($id);
        // dd($report['sql']);
        $results = DB::select($report['sql']);
        $depts = [];
        return View::make('reports.show', compact('report', 'results', 'depts'));

    }

}