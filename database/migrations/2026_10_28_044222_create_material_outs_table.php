<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_outs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_out')->unique();
            $table->date('tanggal');
            $table->time('jam_out');
            $table->enum('shift', ['1', '2', '3']);
            $table->string('nama_operator');
            $table->string('nama_produk');
            $table->string('nama_mesin')->nullable();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->string('jenis_material'); // ct, masterbatch, lainnya
            $table->string('warna')->nullable();
            $table->decimal('qty_out', 10, 2); // dalam kg
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_outs');
    }
};