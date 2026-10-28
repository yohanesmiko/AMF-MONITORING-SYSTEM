<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionRunMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_run_id', 'material_id', 'qty_standar', 'qty_aktual', 'variance',
    ];

    protected $casts = [
        'qty_standar' => 'decimal:4',
        'qty_aktual' => 'decimal:4',
        'variance' => 'decimal:4',
    ];

    public function run()
    {
        return $this->belongsTo(ProductionRun::class, 'production_run_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}