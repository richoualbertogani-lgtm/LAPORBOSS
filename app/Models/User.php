<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Identitas siswa pengirim aspirasi.
 *
 * Model ini BUKAN akun login. Siswa hanya berstatus guest, datanya dicatat
 * otomatis ketika mengirim aspirasi (lihat AspirasiController::store).
 */
class User extends Model
{
    use HasFactory;

    // NIS dipakai sebagai primary key dan tidak auto-increment.
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['nis', 'nama', 'rombel'];

    /**
     * Aspirasi yang pernah dikirim oleh siswa ini.
     */
    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class, 'nis', 'nis');
    }
}
