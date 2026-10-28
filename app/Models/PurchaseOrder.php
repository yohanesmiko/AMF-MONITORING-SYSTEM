<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_po', 'material_id', 'qty', 'harga_satuan', 'total_harga',
        'supplier', 'tanggal_po', 'tanggal_jatuh_tempo', 'status',
        'keterangan', 'user_id',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'tanggal_po' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}