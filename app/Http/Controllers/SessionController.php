<?php namespace App\Http\Controllers;

use \Auth, \Input, \Redirect;

use Gigabill\Repositories\Interfaces;
use \View;

class SessionController extends BaseController {

    public function create() {
        return View::make('session.create');
    }

    public function store() {
       
        if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')), Input::get('remember'))) {
            return Redirect::intended('/');
        } else {
            return Redirect::route('session.create')
                ->with('message', 'Your username/password combination was incorrect')
                ->withInput();
        }
    }

    // end session
    public function index() {
        Auth::logout();
        return Redirect::route('session.create');
    }

}