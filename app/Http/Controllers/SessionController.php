<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends BaseController {

    public function create() {
        return view('session.create');
    }

    public function store(Request $request) {
       
        if (auth()->attempt(array('username' => $request->get('username'), 'password' => $request->get('password')), $request->get('remember'))) {
            return redirect()->intended('/');
        } else {
            return redirect()->route('session.create')
                ->with('message', ['type' => 'danger', 'body' => 'Your username/password combination was incorrect'])
                ->withInput();
        }
    }

    // end session
    public function index() {
        auth()->logout();
        return redirect()->route('session.create');
    }

}