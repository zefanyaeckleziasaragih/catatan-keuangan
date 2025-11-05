<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Halaman utama transaksi keuangan
     */
    public function index()
    {
        return view('pages.app.finance.index');
    }

    /**
     * Halaman statistik keuangan
     */
    public function statistics()
    {
        return view('pages.app.finance.statistics');
    }
}