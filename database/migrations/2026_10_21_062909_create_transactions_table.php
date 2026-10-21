<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga', 12, 2)->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->string('keterangan')->nullable();
            $table->string('tujuan')->nullable(); // untuk barang keluar
            $table->date('tanggal');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};