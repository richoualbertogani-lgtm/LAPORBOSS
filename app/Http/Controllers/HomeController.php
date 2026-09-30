<?php

namespace App\Http\Controllers;

/**
 * Halaman awal (landing page) yang dapat dibuka siapa saja tanpa login.
 */
class HomeController extends Controller
{
    /**
     * Tampilkan halaman awal LaporBoss.
     */
    public function index()
    {
        return view('home');
    }
}
