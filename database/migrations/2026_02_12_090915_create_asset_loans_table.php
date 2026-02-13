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
        Schema::create('asset_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('asset_id')->constrained('assets'); 
            $table->foreignId('admin_id')->nullable()->constrained('users'); 
            $table->dateTime('loan_date'); 
            $table->dateTime('due_date'); 
            $table->dateTime('return_date')->nullable(); 
            $table->enum('status', ['pending', 'active', 'returned', 'overdue', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_loans');
    }
};
