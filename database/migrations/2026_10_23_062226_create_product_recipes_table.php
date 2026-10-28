<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_recipes', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->string('kategori')->nullable();
            $table->decimal('berat_per_unit', 10, 2); // gram
            $table->decimal('yield_rate', 5, 2)->default(95.00); // persentase
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel detail recipe (material yang dibutuhkan per produk)
        Schema::create('recipe_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('qty_dibutuhkan', 10, 2); // dalam gram
            $table->string('satuan')->default('gram');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_details');
        Schema::dropIfExists('product_recipes');
    }
};