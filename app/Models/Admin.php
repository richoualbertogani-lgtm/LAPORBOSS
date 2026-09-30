<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use Notifiable;

    // These legacy column names are part of the existing administrator schema.
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = ['email_admin', 'password_admin'];
    protected $hidden = ['password_admin', 'remember_token'];
    public const UPDATED_AT = 'update_at';

    /**
     * Return the password stored in the legacy administrator column.
     */
    public function getAuthPassword(): string
    {
        return $this->password_admin;
    }

    /**
     * Identify the legacy column used to store the administrator password.
     */
    public function getAuthPasswordName(): string
    {
        return 'password_admin';
    }

    /**
     * Get aspirations answered by this administrator.
     */
    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class, 'balasan_admin_id', 'id_admin');
    }

    /**
     * Get the handling history entries recorded by this administrator.
     */
    public function riwayat(): HasMany
    {
        return $this->hasMany(Riwayat::class, 'id_admin', 'id_admin');
    }
}
