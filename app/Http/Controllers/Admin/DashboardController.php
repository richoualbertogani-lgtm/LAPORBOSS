<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;

/**
 * Dashboard admin: ringkasan jumlah data.
 */
class DashboardController extends Controller
{
    /**
     * Tampilkan ringkasan aspirasi per status dan jumlah kategori.
     */
    public function index()
    {
        return view('admin.dashboard', [
            'totalAspirasi' => Aspirasi::count(),
            'perStatus' => Aspirasi::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'totalKategori' => Kategori::count(),
            'terbaru' => Aspirasi::with('kategori')->latest('id_aspirasi')->limit(5)->get(),
        ]);
    }
}
