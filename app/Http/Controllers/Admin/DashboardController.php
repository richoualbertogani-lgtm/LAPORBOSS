<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display summary counts for the admin dashboard.
     */
    public function index(Request $request)
    {
        return view('admin.dashboard', [
            'adminId' => $request->session()->get('admin_id'),
            'totalUsers' => User::count(),
            'totalKategori' => Kategori::count(),
        ]);
    }
}
