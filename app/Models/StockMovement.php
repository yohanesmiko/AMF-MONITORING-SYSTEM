<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi', 'tipe', 'referensi_id', 'material_id',
        'product_id', 'qty', 'satuan', 'keterangan', 'user_id',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}