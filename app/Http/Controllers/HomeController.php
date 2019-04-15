<?php

namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $next_week = now()->addWeek();
        $next_month = now()->addMonth();
        $urls = Auth::user()->userUrls()->orderByDesc('created_at')->get();
        return view('home', compact(['next_week', 'next_month', 'urls']));
    }
}
