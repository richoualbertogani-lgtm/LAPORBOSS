<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriCrudTest extends TestCase
{
    use RefreshDatabase;

    private function asAdmin(): static
    {
        return $this->withSession(['admin_id' => 1]);
    }

    public function test_index_create_and_edit_pages_render(): void
    {
        $kategori = Kategori::create(['nama_kategori' => 'Fasilitas', 'deskripsi' => 'Sarana']);

        $this->asAdmin()->get(route('admin.kategori.index'))->assertOk()->assertSee('Fasilitas');
        $this->asAdmin()->get(route('admin.kategori.create'))->assertOk();
        $this->asAdmin()->get(route('admin.kategori.edit', $kategori))->assertOk();
    }

    public function test_admin_can_create_update_and_delete_kategori(): void
    {
        $this->asAdmin()->post(route('admin.kategori.store'), [
            'nama_kategori' => 'Pembelajaran',
            'deskripsi' => 'Proses belajar',
        ])->assertRedirect(route('admin.kategori.index'));

        $kategori = Kategori::where('nama_kategori', 'Pembelajaran')->firstOrFail();

        $this->asAdmin()->put(route('admin.kategori.update', $kategori), [
            'nama_kategori' => 'Pembelajaran Baru',
            'deskripsi' => null,
        ])->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseHas('kategori', ['id_kategori' => $kategori->id_kategori, 'nama_kategori' => 'Pembelajaran Baru']);

        $this->asAdmin()->delete(route('admin.kategori.destroy', $kategori))
            ->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseMissing('kategori', ['id_kategori' => $kategori->id_kategori]);
    }

    public function test_admin_cannot_delete_kategori_used_by_an_aspirasi(): void
    {
        $kategori = Kategori::create(['nama_kategori' => 'Fasilitas']);
        $user = User::create(['nis' => 123456789, 'nama' => 'Siswa', 'rombel' => 'XI RPL 1']);

        $kategori->aspirasi()->create([
            'kode_tiket' => 'ASP-TEST123',
            'nis' => $user->nis,
            'judul' => 'Perbaikan fasilitas',
            'isi_aspirasi' => 'Mohon diperbaiki.',
            'status' => 'diajukan',
            'tanggal' => now(),
        ]);

        $this->asAdmin()
            ->delete(route('admin.kategori.destroy', $kategori))
            ->assertRedirect(route('admin.kategori.index'))
            ->assertSessionHas('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh aspirasi.');

        $this->assertDatabaseHas('kategori', ['id_kategori' => $kategori->id_kategori]);
    }

    public function test_nama_kategori_must_be_unique(): void
    {
        Kategori::create(['nama_kategori' => 'Fasilitas']);

        $this->asAdmin()->post(route('admin.kategori.store'), ['nama_kategori' => 'Fasilitas'])
            ->assertSessionHasErrors('nama_kategori');
    }

    public function test_kategori_can_keep_its_own_name_on_update(): void
    {
        $kategori = Kategori::create(['nama_kategori' => 'Fasilitas']);

        $this->asAdmin()->put(route('admin.kategori.update', $kategori), [
            'nama_kategori' => 'Fasilitas',
            'deskripsi' => 'Diubah',
        ])->assertSessionHasNoErrors();
    }
}
