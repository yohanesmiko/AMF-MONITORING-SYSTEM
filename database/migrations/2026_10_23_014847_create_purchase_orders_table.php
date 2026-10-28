<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_po')->unique();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('qty', 12, 2);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('total_harga', 12, 2);
            $table->string('supplier');
            $table->date('tanggal_po');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->enum('status', ['draft', 'terkirim', 'diproses', 'selesai', 'dibatalkan'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};