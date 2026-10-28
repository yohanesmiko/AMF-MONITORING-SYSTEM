<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WipItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_wip', 'material_id', 'qty_input', 'qty_output', 'qty_proses',
        'mesin', 'operator', 'tanggal_mulai', 'tanggal_selesai',
        'status', 'keterangan', 'user_id',
    ];

    protected $casts = [
        'qty_input' => 'decimal:2',
        'qty_output' => 'decimal:2',
        'qty_proses' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
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