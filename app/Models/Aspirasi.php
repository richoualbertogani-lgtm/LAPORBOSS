<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aspirasi extends Model
{
    protected $table = 'tb_aspirasi';
    protected $primaryKey = 'id_aspirasi';
    public const UPDATED_AT = 'update_at';

    protected $fillable = [
        'nis',
        'id_kategori',
        'judul',
        'isi_aspirasi',
        'status',
        'tanggal',
        'balasan_admin_id',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nis', 'nis');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'balasan_admin_id', 'id_admin');
    }

    public function riwayat(): HasMany
    {
        return $this->hasMany(Riwayat::class, 'aspirasi_id', 'id_aspirasi');
    }
}
