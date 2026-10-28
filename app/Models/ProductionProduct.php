<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk', 'nama_produk', 'kategori', 'cycle_time_standar',
        'target_per_shift', 'waktu_shift_menit', 'deskripsi',
    ];

    protected $casts = [
        'cycle_time_standar' => 'decimal:2',
        'target_per_shift' => 'decimal:2',
        'waktu_shift_menit' => 'decimal:2',
    ];

    public function materials()
    {
        return $this->hasMany(ProductionProductMaterial::class);
    }

    public function productionRuns()
    {
        return $this->hasMany(ProductionRun::class);
    }

    /**
     * Hitung target output per shift berdasarkan cycle time
     */
    public function hitungTargetOutput($downtimeMenit = 0)
    {
        $waktuEfektifDetik = ($this->waktu_shift_menit - $downtimeMenit) * 60;
        return floor($waktuEfektifDetik / $this->cycle_time_standar);
    }

    /**
     * Hitung material standar yang dibutuhkan untuk qty tertentu
     */
    public function hitungMaterialStandar($qtyOutput)
    {
        $result = [];
        foreach ($this->materials as $material) {
            $result[] = [
                'material_id' => $material->material_id,
                'material' => $material->material,
                'qty_per_unit' => $material->qty_per_unit,
                'total_standar' => $qtyOutput * $material->qty_per_unit,
                'satuan' => $material->satuan,
            ];
        }
        return $result;
    }
}