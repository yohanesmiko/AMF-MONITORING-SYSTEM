<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_recipe_id', 'material_id', 'qty_dibutuhkan', 'satuan',
    ];

    protected $casts = [
        'qty_dibutuhkan' => 'decimal:2',
    ];

    public function recipe()
    {
        return $this->belongsTo(ProductRecipe::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}