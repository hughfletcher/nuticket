<?php namespace App\Http\Controllers;

use File;
use \Michelf\Markdown;

class DevController extends BaseController {

    public function index() {

        $change = File::get(base_path() . '/CHANGELOG.md');

        return view('blank', ['content' => Markdown::defaultTransform($change), 'depts' => []]);
    }

}