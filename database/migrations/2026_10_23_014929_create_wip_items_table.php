<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wip_items', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_wip')->unique();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('qty_input', 12, 2);
            $table->decimal('qty_output', 12, 2)->default(0);
            $table->decimal('qty_proses', 12, 2)->default(0);
            $table->string('mesin')->nullable();
            $table->string('operator')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['antrian', 'diproses', 'selesai', 'dibatalkan'])->default('antrian');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wip_items');
    }
};