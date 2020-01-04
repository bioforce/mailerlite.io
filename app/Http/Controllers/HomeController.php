<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show Subscribers admin page
     */
    public function subscribers()
    {
        return view('admin.subscriber.list');
    }

    /**
     * Show Additional fields admin page
     */
    public function fields()
    {
        return view('admin.field.list');
    }
}
