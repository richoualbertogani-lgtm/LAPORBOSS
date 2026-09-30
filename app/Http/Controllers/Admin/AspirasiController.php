<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Pengelolaan aspirasi oleh admin: daftar, detail, dan perubahan status.
 */
class AspirasiController extends Controller
{
    /**
     * Daftar aspirasi dengan filter status (opsional).
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $aspirasi = Aspirasi::with(['kategori', 'user'])
            ->when(
                array_key_exists($status, Aspirasi::STATUS_LABEL),
                fn ($query) => $query->where('status', $status)
            )
            ->latest('id_aspirasi')
            ->paginate(10)
            ->withQueryString();

        return view('admin.aspirasi.index', compact('aspirasi', 'status'));
    }

    /**
     * Detail aspirasi. Membuka detail otomatis menandai aspirasi "dibaca".
     */
    public function show(Request $request, Aspirasi $aspirasi)
    {
        $aspirasi->tandaiDibaca((int) $request->session()->get('admin_id'));
        $aspirasi->load(['kategori', 'user', 'riwayat.admin']);

        return view('admin.aspirasi.show', compact('aspirasi'));
    }

    /**
     * Ubah status menjadi "diproses" atau "selesai" beserta tanggapan admin.
     */
    public function updateStatus(Request $request, Aspirasi $aspirasi)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([Aspirasi::STATUS_DIPROSES, Aspirasi::STATUS_SELESAI])],
            'keterangan' => ['required', 'string', 'max:1000'],
        ], [], [
            'status' => 'Status',
            'keterangan' => 'Tanggapan',
        ]);

        $berhasil = $aspirasi->ubahStatus(
            $data['status'],
            $data['keterangan'],
            (int) $request->session()->get('admin_id')
        );

        if (! $berhasil) {
            return back()->with('error', 'Status tidak dapat diubah mundur atau ke status yang sama.');
        }

        return back()->with('success', 'Status aspirasi berhasil diperbarui.');
    }
}
