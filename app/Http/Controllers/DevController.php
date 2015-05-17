<?php namespace App\Http\Controllers;

use View, File;

class DevController extends BaseController {

    public function index() {

        $todo = File::get(base_path() . '/tickets.todo');

        return View::make('blank', ['title' => 'NuTicket Development', 'content' => nl2br($todo), 'depts' => []]);
    }

}