<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->session()->get('user_id') ? User::find($request->session()->get('user_id')) : null;
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('home', compact('user', 'kategori'));
    }
}
