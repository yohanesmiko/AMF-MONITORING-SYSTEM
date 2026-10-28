<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk', 'nama_produk', 'kategori', 'berat_per_unit',
        'yield_rate', 'deskripsi',
    ];

    protected $casts = [
        'berat_per_unit' => 'decimal:2',
        'yield_rate' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(RecipeDetail::class);
    }

    public function finishedProducts()
    {
        return $this->hasMany(FinishedProduct::class, 'nama_produk', 'nama_produk');
    }

    /**
     * Hitung berapa unit produk yang bisa dibuat dari stok material saat ini
     */
    public function hitungMaksimumProduksi()
    {
        $maksimum = PHP_INT_MAX;

        foreach ($this->details as $detail) {
            $material = $detail->material;
            if ($detail->qty_dibutuhkan > 0) {
                // Konversi stok material ke gram
                $stokGram = $material->stok * 1000; // asumsi stok dalam kg
                $bisaDibuat = floor($stokGram / $detail->qty_dibutuhkan);
                $maksimum = min($maksimum, $bisaDibuat);
            }
        }

        return $maksimum === PHP_INT_MAX ? 0 : $maksimum;
    }

    /**
     * Hitung estimasi hari produksi berdasarkan rata-rata harian
     */
    public function estimasiHariProduksi()
    {
        $maksProduksi = $this->hitungMaksimumProduksi();
        $avgHarian = $this->finishedProducts()
            ->where('created_at', '>=', now()->subDays(7))
            ->count() / 7;

        if ($avgHarian <= 0) return null;
        return round($maksProduksi / $avgHarian);
    }
}