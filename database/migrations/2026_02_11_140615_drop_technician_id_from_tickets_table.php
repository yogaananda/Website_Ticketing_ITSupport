<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Hapus Foreign Key dulu (jika ada), formatnya: nama_tabel_nama_kolom_foreign
            // Atau pakai array syntax biar aman:
            $table->dropForeign(['technician_id']); 
            
            // Baru hapus kolomnya
            $table->dropColumn('technician_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Kembalikan kolom jika rollback
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};