<?php namespace App\Http\Controllers;

use App\Repositories\ReportInterface;
use App\Services\Reports\Manager;

class ReportsController extends BaseController {

    public function __construct(ReportInterface $report, Manager $manager) 
    {
        $this->reports = $report;
        $this->manager = $manager;
    }

    public function index() 
    {

        $reports = $this->reports->all();
        return view('reports.index', ['reports' => $reports]);

    }

    public function show($id) {

        $report = $this->reports->find($id);

        $results = $this->manager->driver($report->source)->run($report->sql);
        $depts = [];
        return view('reports.show', compact('report', 'results', 'depts'));

    }

}