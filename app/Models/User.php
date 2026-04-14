<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nama tabel
    protected $table = 'users';

    // Primary key
    protected $primaryKey = 'id_user';

    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang boleh diisi
    protected $fillable = [
        'username',
        'password',
        'role'
    ];

    // Kolom yang disembunyikan
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relasi ke transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_user', 'id_user');
    }

    // Helper role (opsional tapi rapi)
    public function isAdmin()
    {
        return $this->role === 0;
    }

    public function isPemilik()
    {
        return $this->role === 1;
    }
}