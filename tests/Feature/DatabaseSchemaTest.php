<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_tables_match_the_aspirasi_schema(): void
    {
        $this->assertSame(['nis', 'nama', 'rombel'], Schema::getColumnListing('users'));
        $this->assertSame(
            ['id_admin', 'email_admin', 'password_admin', 'created_at', 'update_at'],
            Schema::getColumnListing('admin')
        );
        $this->assertSame(['id_kategori', 'nama_kategori', 'deskripsi'], Schema::getColumnListing('kategori'));
        $this->assertSame(
            ['id_aspirasi', 'nis', 'id_kategori', 'judul', 'isi_aspirasi', 'status', 'tanggal', 'balasan_admin_id', 'created_at', 'update_at'],
            Schema::getColumnListing('tb_aspirasi')
        );
        $this->assertSame(
            ['id_riwayat', 'aspirasi_id', 'status', 'keterangan', 'created_at', 'update_at', 'id_admin'],
            Schema::getColumnListing('tb_riwayat')
        );
    }
}
