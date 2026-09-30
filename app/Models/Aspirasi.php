<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Satu aspirasi yang dikirim siswa beserta status penanganannya.
 */
class Aspirasi extends Model
{
    // Daftar status berurutan dan label yang tampil di layar.
    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DIBACA = 'dibaca';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_SELESAI = 'selesai';

    public const STATUS_LABEL = [
        self::STATUS_DIAJUKAN => 'Menunggu',
        self::STATUS_DIBACA => 'Dibaca',
        self::STATUS_DIPROSES => 'Diproses',
        self::STATUS_SELESAI => 'Selesai',
    ];

    protected $table = 'tb_aspirasi';
    protected $primaryKey = 'id_aspirasi';

    // Kolom updated_at memakai nama legacy "update_at".
    public const UPDATED_AT = 'update_at';

    protected $fillable = [
        'kode_tiket',
        'nis',
        'id_kategori',
        'judul',
        'isi_aspirasi',
        'lampiran',
        'status',
        'tanggal',
        'dibaca_at',
        'diproses_at',
        'selesai_at',
        'balasan_admin_id',
    ];

    /**
     * Ubah kolom waktu menjadi objek Carbon.
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
            'dibaca_at' => 'datetime',
            'diproses_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
    }

    // ------------------------------------------------------------------
    // Relasi
    // ------------------------------------------------------------------

    /** Siswa pengirim aspirasi. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nis', 'nis');
    }

    /** Kategori aspirasi. */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /** Admin yang terakhir menangani aspirasi. */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'balasan_admin_id', 'id_admin');
    }

    /** Riwayat perubahan status, urut dari yang terbaru. */
    public function riwayat(): HasMany
    {
        return $this->hasMany(Riwayat::class, 'aspirasi_id', 'id_aspirasi')->latest('id_riwayat');
    }

    // ------------------------------------------------------------------
    // Pembuatan aspirasi
    // ------------------------------------------------------------------

    /**
     * Buat kode tiket unik, contoh: ASP-7K3M9XQ2.
     * Huruf/angka yang mirip (0, O, 1, I) dihindari agar mudah dibaca.
     */
    public static function buatKodeTiket(): string
    {
        do {
            $acak = '';
            $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            for ($i = 0; $i < 8; $i++) {
                $acak .= $karakter[random_int(0, strlen($karakter) - 1)];
            }
            $kode = 'ASP-'.$acak;
        } while (static::where('kode_tiket', $kode)->exists());

        return $kode;
    }

    /**
     * Catat satu baris riwayat untuk aspirasi ini.
     */
    public function catatRiwayat(string $status, string $keterangan, ?int $adminId = null): Riwayat
    {
        return $this->riwayat()->create([
            'status' => $status,
            'keterangan' => $keterangan,
            'id_admin' => $adminId,
        ]);
    }

    // ------------------------------------------------------------------
    // Penanganan oleh admin
    // ------------------------------------------------------------------

    /**
     * Tandai aspirasi sebagai "dibaca" saat admin pertama kali membukanya.
     * Tidak berbuat apa-apa jika aspirasi sudah dibaca sebelumnya.
     */
    public function tandaiDibaca(int $adminId): void
    {
        if ($this->status !== self::STATUS_DIAJUKAN) {
            return;
        }

        DB::transaction(function () use ($adminId) {
            $this->update([
                'status' => self::STATUS_DIBACA,
                'dibaca_at' => now(),
                'balasan_admin_id' => $adminId,
            ]);

            $this->catatRiwayat(self::STATUS_DIBACA, 'Aspirasi telah dibaca oleh admin.', $adminId);
        });
    }

    /**
     * Ubah status ke "diproses" atau "selesai" dan simpan waktunya.
     * Status hanya boleh maju, tidak boleh mundur.
     */
    public function ubahStatus(string $statusBaru, string $keterangan, int $adminId): bool
    {
        if ($this->urutanStatus($statusBaru) <= $this->urutanStatus($this->status)) {
            return false;
        }

        DB::transaction(function () use ($statusBaru, $keterangan, $adminId) {
            $sekarang = now();
            $data = ['status' => $statusBaru, 'balasan_admin_id' => $adminId];

            // Lengkapi tahap yang terlewat agar linimasa selalu berurutan.
            if (! $this->dibaca_at) {
                $data['dibaca_at'] = $sekarang;
            }
            if (! $this->diproses_at) {
                $data['diproses_at'] = $sekarang;
            }
            if ($statusBaru === self::STATUS_SELESAI) {
                $data['selesai_at'] = $sekarang;
            }

            $this->update($data);
            $this->catatRiwayat($statusBaru, $keterangan, $adminId);
        });

        return true;
    }

    /**
     * Nomor urut status (0 = diajukan ... 3 = selesai).
     */
    public function urutanStatus(string $status): int
    {
        return (int) array_search($status, array_keys(self::STATUS_LABEL), true);
    }

    // ------------------------------------------------------------------
    // Tampilan
    // ------------------------------------------------------------------

    /** Label status yang ramah dibaca, contoh: "Diproses". */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABEL[$this->status] ?? Str::headline($this->status);
    }

    /**
     * URL foto lampiran, atau null jika tidak ada lampiran.
     * Memakai asset() agar mengikuti alamat yang sedang dibuka (Laragon/artisan serve),
     * bukan nilai APP_URL. Membutuhkan `php artisan storage:link`.
     */
    public function lampiranUrl(): ?string
    {
        return $this->lampiran ? asset('storage/'.$this->lampiran) : null;
    }

    /**
     * Susunan linimasa: setiap tahap beserta waktu terjadinya (null = belum).
     *
     * @return array<int, array{status: string, label: string, waktu: mixed}>
     */
    public function linimasa(): array
    {
        return [
            ['status' => self::STATUS_DIAJUKAN, 'label' => 'Diajukan', 'waktu' => $this->tanggal],
            ['status' => self::STATUS_DIBACA, 'label' => 'Dibaca admin', 'waktu' => $this->dibaca_at],
            ['status' => self::STATUS_DIPROSES, 'label' => 'Sedang diproses', 'waktu' => $this->diproses_at],
            ['status' => self::STATUS_SELESAI, 'label' => 'Selesai dikerjakan', 'waktu' => $this->selesai_at],
        ];
    }
}
