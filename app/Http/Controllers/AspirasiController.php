<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Fitur siswa (guest): membuat aspirasi dan melacak statusnya. Tanpa login.
 */
class AspirasiController extends Controller
{
    /**
     * Tampilkan form "Buat Aspirasi".
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('aspirasi.create', compact('kategori'));
    }

    /**
     * Validasi dan simpan aspirasi baru, lalu arahkan siswa ke halaman lacak.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => ['required', 'integer', 'min:1', 'max:4294967295'],
            'nama' => ['required', 'string', 'max:255'],
            'rombel' => ['required', 'string', 'max:50'],
            'id_kategori' => ['required', 'integer', 'exists:kategori,id_kategori'],
            'judul' => ['required', 'string', 'max:255'],
            'isi_aspirasi' => ['required', 'string', 'min:10', 'max:5000'],
            'lampiran' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [], [
            'nis' => 'NIS',
            'nama' => 'Nama',
            'rombel' => 'Rombel',
            'id_kategori' => 'Kategori',
            'judul' => 'Judul aspirasi',
            'isi_aspirasi' => 'Isi aspirasi',
            'lampiran' => 'Lampiran visual',
        ]);

        // Simpan foto (jika ada) pada disk "public" di folder aspirasi/.
        $pathLampiran = $request->hasFile('lampiran')
            ? $request->file('lampiran')->store('aspirasi', 'public')
            : null;

        $aspirasi = DB::transaction(function () use ($data, $pathLampiran) {
            // Catat / perbarui identitas siswa berdasarkan NIS.
            User::updateOrCreate(
                ['nis' => $data['nis']],
                ['nama' => $data['nama'], 'rombel' => $data['rombel']]
            );

            $aspirasi = Aspirasi::create([
                'kode_tiket' => Aspirasi::buatKodeTiket(),
                'nis' => $data['nis'],
                'id_kategori' => $data['id_kategori'],
                'judul' => $data['judul'],
                'isi_aspirasi' => $data['isi_aspirasi'],
                'lampiran' => $pathLampiran,
                'status' => Aspirasi::STATUS_DIAJUKAN,
                'tanggal' => now(),
            ]);

            // Riwayat pertama: aspirasi diajukan (belum ada admin yang terlibat).
            $aspirasi->catatRiwayat(Aspirasi::STATUS_DIAJUKAN, 'Aspirasi berhasil diajukan.');

            return $aspirasi;
        });

        return redirect()
            ->route('aspirasi.lacak', $aspirasi->kode_tiket)
            ->with('success', 'Aspirasi berhasil dikirim. Simpan kode tiket Anda untuk memantau statusnya.');
    }

    /**
     * Halaman "Lacak Aspirasi": form kode tiket, atau hasil jika kode diberikan.
     */
    public function lacak(Request $request, ?string $kode = null)
    {
        // Form mengirim kode lewat query string, ubah menjadi URL yang rapi.
        if (! $kode && $request->filled('kode')) {
            return redirect()->route('aspirasi.lacak', strtoupper(trim($request->query('kode'))));
        }

        $aspirasi = null;

        if ($kode) {
            $aspirasi = Aspirasi::with(['kategori', 'riwayat.admin'])
                ->where('kode_tiket', strtoupper($kode))
                ->first();

            if (! $aspirasi) {
                return redirect()
                    ->route('aspirasi.lacak')
                    ->with('error', 'Kode tiket tidak ditemukan. Periksa kembali kode Anda.');
            }
        }

        return view('aspirasi.lacak', compact('aspirasi'));
    }
}
