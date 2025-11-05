<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.app.finance');
    }

    public function financeDetail()
    {
        return view('pages.app.finance.detail');
    }
}
