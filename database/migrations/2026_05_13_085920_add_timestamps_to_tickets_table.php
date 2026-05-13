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
            $table->timestamp('started_at')->nullable()->after('status')->comment('Waktu IT support mulai mengerjakan tiket');
            $table->timestamp('resolved_at')->nullable()->after('started_at')->comment('Waktu IT support menyelesaikan tiket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'resolved_at']);
        });
    }
};
