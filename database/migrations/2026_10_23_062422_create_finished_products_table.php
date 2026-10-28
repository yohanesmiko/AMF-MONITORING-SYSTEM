<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finished_products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->decimal('stok', 12, 2)->default(0);
            $table->string('satuan')->default('unit');
            $table->decimal('harga_jual', 12, 2)->nullable();
            $table->string('lokasi')->nullable();
            $table->enum('status', ['tersedia', 'habis'])->default('tersedia');
            $table->timestamps();
        });

        // Log pergerakan stok (untuk audit & akurasi)
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi');
            $table->enum('tipe', ['material_masuk', 'material_keluar', 'produk_jadi']);
            $table->string('referensi_id')->nullable(); // ID PO/WIP
            $table->foreignId('material_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained('finished_products')->onDelete('set null');
            $table->decimal('qty', 12, 2);
            $table->string('satuan');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('finished_products');
    }
};