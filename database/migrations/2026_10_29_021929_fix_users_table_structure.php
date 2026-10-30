<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom yang BELUM ADA saja
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan')->nullable()->after('role');
            }
            
            if (!Schema::hasColumn('users', 'shift')) {
                $table->string('shift')->nullable()->after('jabatan');
            }
            
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('shift');
            }
        });

        // Ubah tipe data kolom 'role' yang sudah ada menjadi ENUM
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'operator') DEFAULT 'operator'");
        } catch (\Exception $e) {
            // Jika ada data yang tidak cocok, abaikan error
            \Log::info('Role column already correct or has incompatible data');
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jabatan', 'shift', 'is_active']);
        });
    }
};