<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = ['email_admin', 'password_admin'];
    protected $hidden = ['password_admin', 'remember_token'];
    public const UPDATED_AT = 'update_at';

    public function getAuthPassword(): string
    {
        return $this->password_admin;
    }

    public function getAuthPasswordName(): string
    {
        return 'password_admin';
    }

    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class, 'balasan_admin_id', 'id_admin');
    }

    public function riwayat(): HasMany
    {
        return $this->hasMany(Riwayat::class, 'id_admin', 'id_admin');
    }
}
