<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function home()
    {
//        return 'this is home page';
        return view('static_pages/home');
    }

    public function about()
    {
//        return 'this is about page';
        return view('static_pages/about');
    }

    public function help()
    {
//        return 'this is help page';
        return view('static_pages/help');
    }
}
