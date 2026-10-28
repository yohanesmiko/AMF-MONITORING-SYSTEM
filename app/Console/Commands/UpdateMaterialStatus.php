<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Material;

class UpdateMaterialStatus extends Command
{
    protected $signature = 'material:update-status';
    protected $description = 'Update status semua material berdasarkan stok';

    public function handle()
    {
        $materials = Material::all();
        $updated = 0;

        foreach ($materials as $material) {
            $oldStatus = $material->status;
            
            if ($material->stok == 0) {
                $material->status = 'habis';
            } elseif ($material->stok <= $material->stok_minimum) {
                $material->status = 'menipis';
            } else {
                $material->status = 'tersedia';
            }

            if ($oldStatus !== $material->status) {
                $material->save();
                $updated++;
                $this->info("✅ {$material->nama_material}: {$oldStatus} → {$material->status}");
            }
        }

        $this->info("\n🎉 Selesai! {$updated} material diupdate.");
        return 0;
    }
}