<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Produk Produksi (1 produk dengan recipe tetap)
        Schema::create('production_products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->string('kategori')->nullable();
            $table->decimal('cycle_time_standar', 8, 2); // detik per unit
            $table->decimal('target_per_shift', 10, 2)->default(0); // target unit per shift
            $table->decimal('waktu_shift_menit', 5, 2)->default(480); // 8 jam = 480 menit
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Detail Material per Produk
        Schema::create('production_product_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('qty_per_unit', 10, 4); // gram per unit
            $table->string('satuan')->default('gram');
            $table->timestamps();
        });

        // Production Run (per shift)
        Schema::create('production_runs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_run')->unique();
            $table->foreignId('production_product_id')->constrained()->onDelete('cascade');
            $table->enum('shift', ['1', '2', '3']);
            $table->date('tanggal');
            
            // Input dari operator
            $table->decimal('output_aktual', 10, 2)->default(0); // unit yang dihasilkan
            $table->decimal('downtime_menit', 8, 2)->default(0); // waktu mesin mati
            $table->decimal('cycle_time_aktual', 8, 2)->nullable(); // detik/unit aktual
            
            // Material yang dipakai
            $table->text('material_dipakai')->nullable(); // JSON: [{material_id, qty_aktual}]
            
            // Perhitungan otomatis
            $table->decimal('target_output', 10, 2)->default(0);
            $table->decimal('efisiensi', 5, 2)->default(0); // persen
            $table->decimal('material_standar_total', 12, 4)->default(0); // gram
            $table->decimal('material_aktual_total', 12, 4)->default(0); // gram
            $table->decimal('variance_material', 12, 4)->default(0); // gram (positif = boros)
            
            $table->text('catatan')->nullable();
            $table->foreignId('operator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Detail material per production run (untuk akurasi)
        Schema::create('production_run_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_run_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('qty_standar', 12, 4); // gram (output × qty_per_unit)
            $table->decimal('qty_aktual', 12, 4); // gram (yang benar-benar dipakai)
            $table->decimal('variance', 12, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_run_materials');
        Schema::dropIfExists('production_runs');
        Schema::dropIfExists('production_product_materials');
        Schema::dropIfExists('production_products');
    }
};