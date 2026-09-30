<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Isi data awal: akun admin dan kategori aspirasi.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email_admin' => 'admin@laporboss.test'],
            ['password_admin' => Hash::make('admin123')]
        );

        foreach ([
            ['nama_kategori' => 'Fasilitas', 'deskripsi' => 'Aspirasi mengenai sarana dan prasarana sekolah.'],
            ['nama_kategori' => 'Pembelajaran', 'deskripsi' => 'Aspirasi mengenai kegiatan dan proses pembelajaran.'],
            ['nama_kategori' => 'Kegiatan Siswa', 'deskripsi' => 'Aspirasi mengenai kegiatan organisasi dan siswa.'],
            ['nama_kategori' => 'Lingkungan', 'deskripsi' => 'Aspirasi mengenai kebersihan dan lingkungan sekolah.'],
        ] as $item) {
            Kategori::firstOrCreate(['nama_kategori' => $item['nama_kategori']], $item);
        }
    }
}
