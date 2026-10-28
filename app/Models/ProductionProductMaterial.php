<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionProductMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_product_id', 'material_id', 'qty_per_unit', 'satuan',
    ];

    protected $casts = [
        'qty_per_unit' => 'decimal:4',
    ];

    public function product()
    {
        return $this->belongsTo(ProductionProduct::class, 'production_product_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}