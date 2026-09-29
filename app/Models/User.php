<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['nis', 'nama', 'rombel'];

    public function getAuthIdentifierName(): string
    {
        return 'nis';
    }

    public function aspirasi()
    {
        return $this->hasMany(Aspirasi::class, 'nis', 'nis');
    }
}
