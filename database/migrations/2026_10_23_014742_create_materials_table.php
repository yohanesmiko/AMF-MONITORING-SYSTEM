<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('kode_material')->unique();
            $table->string('nama_material');
            $table->enum('jenis', ['ct', 'masterbatch', 'lainnya'])->default('lainnya');
            $table->string('kategori')->nullable();
            $table->decimal('stok', 12, 2)->default(0);
            $table->string('satuan')->default('kg');
            $table->decimal('stok_minimum', 12, 2)->default(0);
            $table->decimal('harga_satuan', 12, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->string('lokasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['tersedia', 'menipis', 'habis'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};