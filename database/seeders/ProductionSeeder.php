<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductRecipe;
use App\Models\RecipeDetail;
use App\Models\FinishedProduct;
use App\Models\Material;
use App\Models\DailyUsage;
use Carbon\Carbon;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Recipe Produk
        $recipe1 = ProductRecipe::create([
            'kode_produk' => 'PROD-001',
            'nama_produk' => 'Kardus Box 500gr',
            'kategori' => 'Packaging',
            'berat_per_unit' => 500,
            'yield_rate' => 95.00,
            'deskripsi' => 'Kardus box ukuran standar 500gr',
        ]);

        // Material yang dibutuhkan per unit (dalam gram)
        RecipeDetail::create([
            'product_recipe_id' => $recipe1->id,
            'material_id' => 1, // CT
            'qty_dibutuhkan' => 450, // 450gr CT per unit
            'satuan' => 'gram',
        ]);

        RecipeDetail::create([
            'product_recipe_id' => $recipe1->id,
            'material_id' => 2, // Masterbatch
            'qty_dibutuhkan' => 50, // 50gr MB per unit
            'satuan' => 'gram',
        ]);

        // 2. Buat Produk Jadi
        FinishedProduct::create([
            'kode_produk' => 'FP-001',
            'nama_produk' => 'Kardus Box 500gr',
            'stok' => 100,
            'satuan' => 'unit',
            'harga_jual' => 15000,
            'lokasi' => 'Gudang Produk Jadi A',
            'status' => 'tersedia',
        ]);

        // 3. Buat data penggunaan harian 30 hari terakhir (untuk akurasi kalkulasi)
        $materials = Material::all();
        foreach ($materials as $material) {
            for ($i = 30; $i >= 0; $i--) {
                DailyUsage::create([
                    'material_id' => $material->id,
                    'qty_penggunaan' => rand(5, 20) + (rand(0, 100) / 100),
                    'tanggal' => Carbon::now()->subDays($i),
                    'keterangan' => 'Penggunaan produksi harian',
                    'user_id' => 1,
                ]);
            }
        }
    }
}