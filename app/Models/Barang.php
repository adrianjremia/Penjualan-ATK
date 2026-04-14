<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    // Nama tabel di database
    protected $table = 'barang';

    // Primary key
    protected $primaryKey = 'id_barang';

    // Primary key auto increment
    public $incrementing = true;

    // Tipe primary key
    protected $keyType = 'int';

    // Aktifkan created_at & updated_at
    public $timestamps = true;

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'nama_barang',
        'kategori',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan'
    ];
}