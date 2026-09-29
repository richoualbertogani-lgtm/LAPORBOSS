<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Riwayat extends Model
{
    protected $table = 'tb_riwayat';
    protected $primaryKey = 'id_riwayat';
    public const UPDATED_AT = 'update_at';

    protected $fillable = ['aspirasi_id', 'status', 'keterangan', 'id_admin'];

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class, 'aspirasi_id', 'id_aspirasi');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
