<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page with the current student and available categories.
     */
    public function index(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $user = $userId ? User::find($userId) : null;
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('home', compact('user', 'kategori'));
    }
}
