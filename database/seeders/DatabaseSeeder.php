<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email_admin' => 'admin@laporboss.test'],
            ['password_admin' => Hash::make('admin123')]
        );

        foreach ([
            ['nama_kategori' => 'Fasilitas Sekolah', 'deskripsi' => 'Aspirasi mengenai sarana dan prasarana sekolah.'],
            ['nama_kategori' => 'Pembelajaran', 'deskripsi' => 'Aspirasi mengenai kegiatan dan proses pembelajaran.'],
            ['nama_kategori' => 'Kegiatan Siswa', 'deskripsi' => 'Aspirasi mengenai kegiatan organisasi dan siswa.'],
        ] as $item) {
            Kategori::firstOrCreate(['nama_kategori' => $item['nama_kategori']], $item);
        }

        User::firstOrCreate(
            ['nis' => '123456789'],
            ['nama' => 'Siswa Demo', 'rombel' => 'XI RPL 1']
        );
    }
}
