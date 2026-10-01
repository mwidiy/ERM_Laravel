<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    // Tambahin 1 baris ini bro:
    protected $fillable = [
        'nama_barang', 'stok', 'harga'
    ];
}
