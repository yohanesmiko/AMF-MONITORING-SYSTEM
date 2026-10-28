<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk', 'nama_produk', 'stok', 'satuan',
        'harga_jual', 'lokasi', 'status',
    ];

    protected $casts = [
        'stok' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    public function recipe()
    {
        return $this->belongsTo(ProductRecipe::class, 'nama_produk', 'nama_produk');
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'product_id');
    }
}