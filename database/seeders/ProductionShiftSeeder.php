<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionProduct;
use App\Models\Material;

class ProductionShiftSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada material CT dan Masterbatch
        $ct = Material::firstOrCreate(
            ['kode_material' => 'CT-001'],
            ['nama_material' => 'CT (Carton)', 'jenis' => 'ct', 'stok' => 500, 'satuan' => 'kg', 'stok_minimum' => 50, 'status' => 'tersedia']
        );
        
        $mb = Material::firstOrCreate(
            ['kode_material' => 'MB-001'],
            ['nama_material' => 'Masterbatch', 'jenis' => 'masterbatch', 'stok' => 100, 'satuan' => 'kg', 'stok_minimum' => 20, 'status' => 'tersedia']
        );

        // Buat produk
        $product = ProductionProduct::create([
            'kode_produk' => 'KARDUS-500',
            'nama_produk' => 'Kardus Box 500gr',
            'kategori' => 'Packaging',
            'cycle_time_standar' => 30, // 30 detik per unit
            'waktu_shift_menit' => 480, // 8 jam
            'target_per_shift' => 960, // (480*60)/30
            'deskripsi' => 'Kardus box standar 500gr',
        ]);

        // Recipe material
        $product->materials()->create([
            'material_id' => $ct->id,
            'qty_per_unit' => 450, // 450 gram CT per unit
            'satuan' => 'gram',
        ]);

        $product->materials()->create([
            'material_id' => $mb->id,
            'qty_per_unit' => 50, // 50 gram MB per unit
            'satuan' => 'gram',
        ]);
    }
}