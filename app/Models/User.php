<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Student records use NIS as their non-incrementing primary key.
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['nis', 'nama', 'rombel'];

    /**
     * Use the student's NIS as the authentication identifier.
     */
    public function getAuthIdentifierName(): string
    {
        return 'nis';
    }

    /**
     * Get the aspirations submitted by this student.
     */
    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class, 'nis', 'nis');
    }
}
