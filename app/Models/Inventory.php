<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'stok',
        'satuan',
        'harga_beli',
        'harga_jual',
        'lokasi',
        'deskripsi',
        'gambar',
        'status',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];
    
    public function transactions()
{
    return $this->hasMany(Transaction::class);
}
}