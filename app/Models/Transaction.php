<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'jenis',
        'inventory_id',
        'jumlah',
        'harga',
        'supplier_id',
        'keterangan',
        'tujuan',
        'tanggal',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'decimal:2',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}