<?php

namespace Tests\Feature;

use App\Models\Aspirasi;
use App\Models\Admin;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Menguji alur siswa (guest) mengirim aspirasi dan admin menanganinya.
 */
class AspirasiFlowTest extends TestCase
{
    use RefreshDatabase;

    /** Data form yang valid untuk mengirim aspirasi. */
    private function dataAspirasi(array $override = []): array
    {
        $kategori = Kategori::firstOrCreate(['nama_kategori' => 'Fasilitas']);

        return array_merge([
            'nis' => '12511334',
            'nama' => 'Richou Alberto Gani',
            'rombel' => 'PPLG XI-3',
            'id_kategori' => $kategori->id_kategori,
            'judul' => 'Keran air toilet lantai 2 bocor',
            'isi_aspirasi' => 'Keran air di toilet putri lantai 2 terus menetes sejak Senin.',
        ], $override);
    }

    /** Buat admin dan kembalikan id-nya. */
    private function buatAdmin(): int
    {
        return Admin::create([
            'email_admin' => 'admin@laporboss.test',
            'password_admin' => Hash::make('admin123'),
        ])->id_admin;
    }

    public function test_halaman_awal_tanpa_tombol_daftar(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Mulai Sampaikan Aspirasi')
            ->assertSee(route('aspirasi.create'))
            ->assertSee('Masuk')
            ->assertDontSee('Daftar');
    }

    public function test_form_aspirasi_dapat_dibuka_tanpa_login(): void
    {
        $this->get(route('aspirasi.create'))->assertOk()->assertSee('Kirim Aspirasi');
    }

    public function test_guest_dapat_mengirim_aspirasi_dengan_lampiran(): void
    {
        Storage::fake('public');

        $response = $this->post(route('aspirasi.store'), $this->dataAspirasi([
            'lampiran' => UploadedFile::fake()->image('keran.jpg'),
        ]));

        $aspirasi = Aspirasi::firstOrFail();
        $response->assertRedirect(route('aspirasi.lacak', $aspirasi->kode_tiket));

        $this->assertSame('diajukan', $aspirasi->status);
        $this->assertNotNull($aspirasi->tanggal);
        Storage::disk('public')->assertExists($aspirasi->lampiran);
        $this->assertDatabaseHas('users', ['nis' => 12511334, 'nama' => 'Richou Alberto Gani']);
        $this->assertDatabaseHas('tb_riwayat', ['aspirasi_id' => $aspirasi->id_aspirasi, 'status' => 'diajukan', 'id_admin' => null]);
    }

    public function test_pengiriman_gagal_bila_data_tidak_lengkap(): void
    {
        $this->post(route('aspirasi.store'), [])
            ->assertSessionHasErrors(['nis', 'nama', 'rombel', 'id_kategori', 'judul', 'isi_aspirasi']);
    }

    public function test_siswa_dapat_melacak_aspirasi_dengan_kode_tiket(): void
    {
        $this->post(route('aspirasi.store'), $this->dataAspirasi());
        $aspirasi = Aspirasi::firstOrFail();

        $this->get(route('aspirasi.lacak', $aspirasi->kode_tiket))
            ->assertOk()
            ->assertSee('Keran air toilet lantai 2 bocor')
            ->assertSee('Belum');

        // Form mengirim kode lewat query string (?kode=...), lalu dialihkan ke URL rapi.
        $this->get(route('aspirasi.lacak').'?kode='.$aspirasi->kode_tiket)
            ->assertRedirect(route('aspirasi.lacak', $aspirasi->kode_tiket));

        $this->get(route('aspirasi.lacak', 'ASP-TIDAKADA'))->assertRedirect(route('aspirasi.lacak'));
    }

    public function test_admin_membuka_detail_menandai_dibaca_lalu_memproses_dan_menyelesaikan(): void
    {
        $adminId = $this->buatAdmin();
        $this->post(route('aspirasi.store'), $this->dataAspirasi());
        $aspirasi = Aspirasi::firstOrFail();
        $sesi = ['admin_id' => $adminId];

        // Membuka detail -> status "dibaca" dan waktu baca tercatat.
        $this->withSession($sesi)->get(route('admin.aspirasi.show', $aspirasi))->assertOk();
        $aspirasi->refresh();
        $this->assertSame('dibaca', $aspirasi->status);
        $this->assertNotNull($aspirasi->dibaca_at);
        $this->assertNull($aspirasi->diproses_at);

        // Diproses -> waktu proses tercatat.
        $this->withSession($sesi)->patch(route('admin.aspirasi.status', $aspirasi), [
            'status' => 'diproses',
            'keterangan' => 'Petugas sarana sedang memperbaiki.',
        ])->assertSessionHas('success');
        $aspirasi->refresh();
        $this->assertSame('diproses', $aspirasi->status);
        $this->assertNotNull($aspirasi->diproses_at);
        $this->assertNull($aspirasi->selesai_at);

        // Selesai -> waktu selesai tercatat.
        $this->withSession($sesi)->patch(route('admin.aspirasi.status', $aspirasi), [
            'status' => 'selesai',
            'keterangan' => 'Keran sudah diganti.',
        ])->assertSessionHas('success');
        $aspirasi->refresh();
        $this->assertSame('selesai', $aspirasi->status);
        $this->assertNotNull($aspirasi->selesai_at);

        // Riwayat: diajukan, dibaca, diproses, selesai.
        $this->assertSame(4, $aspirasi->riwayat()->count());

        // Siswa melihat tanggapan admin pada halaman lacak.
        $this->get(route('aspirasi.lacak', $aspirasi->kode_tiket))
            ->assertSee('Keran sudah diganti.')
            ->assertDontSee('Belum</small>', false);
    }

    public function test_status_tidak_boleh_mundur(): void
    {
        $adminId = $this->buatAdmin();
        $this->post(route('aspirasi.store'), $this->dataAspirasi());
        $aspirasi = Aspirasi::firstOrFail();
        $sesi = ['admin_id' => $adminId];

        $this->withSession($sesi)->patch(route('admin.aspirasi.status', $aspirasi), [
            'status' => 'selesai',
            'keterangan' => 'Selesai.',
        ]);

        $this->withSession($sesi)->patch(route('admin.aspirasi.status', $aspirasi), [
            'status' => 'diproses',
            'keterangan' => 'Coba mundur.',
        ])->assertSessionHas('error');

        $this->assertSame('selesai', $aspirasi->fresh()->status);
    }

    public function test_guest_tidak_dapat_membuka_halaman_admin_aspirasi(): void
    {
        $this->get(route('admin.aspirasi.index'))->assertRedirect(route('admin.login'));
    }
}
