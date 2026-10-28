<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductionRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_run', 'production_product_id', 'shift', 'tanggal',
        'output_aktual', 'downtime_menit', 'cycle_time_aktual',
        'material_dipakai', 'target_output', 'efisiensi',
        'material_standar_total', 'material_aktual_total', 'variance_material',
        'catatan', 'operator_id', 'supervisor_id',
    ];

    protected $casts = [
        'output_aktual' => 'decimal:2',
        'downtime_menit' => 'decimal:2',
        'cycle_time_aktual' => 'decimal:2',
        'target_output' => 'decimal:2',
        'efisiensi' => 'decimal:2',
        'material_standar_total' => 'decimal:4',
        'material_aktual_total' => 'decimal:4',
        'variance_material' => 'decimal:4',
        'tanggal' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(ProductionProduct::class, 'production_product_id');
    }

    public function runMaterials()
    {
        return $this->hasMany(ProductionRunMaterial::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Hitung ulang semua metrik setelah input
     */
    public function recalculate()
    {
        $product = $this->product;
        
        // 1. Target output
        $waktuEfektifDetik = ($product->waktu_shift_menit - $this->downtime_menit) * 60;
        $this->target_output = floor($waktuEfektifDetik / $product->cycle_time_standar);
        
        // 2. Efisiensi
        if ($this->target_output > 0) {
            $this->efisiensi = ($this->output_aktual / $this->target_output) * 100;
        }
        
        // 3. Cycle time aktual
        if ($this->output_aktual > 0) {
            $this->cycle_time_aktual = $waktuEfektifDetik / $this->output_aktual;
        }
        
        // 4. Material standar total
        $this->material_standar_total = $this->output_aktual * 
            $product->materials()->sum('qty_per_unit');
        
        $this->save();
    }
}