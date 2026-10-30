<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_out', 'tanggal', 'jam_out', 'shift', 'nama_operator',
        'nama_produk', 'nama_mesin', 'material_id', 'jenis_material',
        'warna', 'qty_out', 'keterangan', 'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'qty_out' => 'decimal:2',
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